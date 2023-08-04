<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Repositories\Cart\CartRepository;
use App\Repositories\CartItem\CartItemRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\ProductComment\ProductCommentRepository;
use App\Repositories\ProductImg\ProductImgRepository;
use App\Repositories\ProductItem\ProductItemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    //
    protected $productRepository;
    protected $productCommentRepository;
    protected $categoryRepo;
    protected $productItemRepo;
    protected $cartItemRepo;
    protected $cartRepo;
    protected $productImgrepo;

    public function __construct(
        ProductRepository $productRepo,
        ProductCommentRepository $productCommentRepo,
        CategoryRepository $categoryRepo,
        ProductItemRepository $productItemRepo,
        CartItemRepository $cartItemRepo,
        CartRepository $cartRepo,
        ProductImgRepository $productImgRepo
    ) {
        $this->productRepository = $productRepo;
        $this->productCommentRepository = $productCommentRepo;
        $this->categoryRepo = $categoryRepo;
        $this->productItemRepo = $productItemRepo;
        $this->cartRepo = $cartRepo;
        $this->cartItemRepo = $cartItemRepo;
        $this->productImgrepo = $productImgRepo;
    }

    public function index($slug)
    {
        $id = $this->productRepository->getId(['slug' => $slug]);
        
        $categories = $this->categoryRepo->getAll();
        $product = $this->productRepository->find($id);
        
        if($product){
            $product_item = $product->productItems;
        }else{
            return view('errors.404');
        }
        
        $productImgs = $this->productImgrepo->getAll();

        // count the number of cart items
        $total_items_order =  countItemsCartEachUser($this->cartRepo, $this->cartItemRepo);
        
        $sizes = $this->productRepository->getVariationItems($id, 'size');
        $colors = $this->productRepository->getVariationItems($id, 'color');
        $comments = $this->productRepository->commentsInfo($id);

        $relatedProducts = $this->productRepository->relatedProducts($id, $product, 4);

        return view('layouts.product')->with([
            'product' =>  $product,
            'product_item' => $product_item,
            'sizes' => $sizes,
            'colors' => $colors,
            'comments' => $comments,
            'categories' => $categories,
            'relatedProducts' => $relatedProducts,
            'total_items_order' => $total_items_order,
            'productImgs' => $productImgs
        ]);
    }

    public function comment(Request $request, $id)
    {
        if (!empty($request->id)) {
            $product_id = $request->id;

            if ($product_id == $id) {
                $request->validate([
                    'name' => 'required',
                    'email' => 'email',
                    'comment' => 'required'
                ]);
                $data = [
                    'product_id' =>  $product_id,
                    'email' => $request->email,
                    'name' => $request->name,
                    'comment' => $request->comment
                ];
                $this->productCommentRepository->create($data);
                return redirect()->back();
                // return redirect()->route('product.detail', ['id' => $id]);
            }
        }
    }

    public function getProductItemDetail($id, Request $request)
    {
        if($request->ajax()){
            if($request->color && $request->size && $id){
                $product_detail =  $this->productItemRepo->productItemDetail($id, $request->color, $request->size);
    
                if($product_detail){
                    return response()->json(['status' => true, 'product_detail' => $product_detail ]);
                }
                return response()->json(['status' => false, 'msg' => 'ở giữa']);
            }
            return response()->json(['status' => false, 'msg' => 'ở ngoài']);
        }
        return back();
        
    }
}
