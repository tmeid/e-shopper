<?php

namespace App\Http\Controllers;

use App\Repositories\Cart\CartRepository;
use App\Repositories\CartItem\CartItemRepository;
use App\Repositories\Category\CategoryRepository;
use Illuminate\Http\Request;
use App\Repositories\Product\ProductRepository;
use App\Repositories\ProductImg\ProductImgRepository;

class IndexController extends Controller
{
    //
    protected $productRepo;
    protected $categoryRepo;
    protected $cartRepo;
    protected $cartItemRepo;
    protected $productImgRepo;

    public function __construct
    (
        ProductRepository $productRepo, 
        CategoryRepository $categoryRepo,
        CartRepository $cartRepo,
        CartItemRepository $cartItemRepo,
        ProductImgRepository $productImgRepo
    )
    {   
        $this->productRepo = $productRepo;
        $this->categoryRepo = $categoryRepo;
        $this->cartRepo = $cartRepo;
        $this->cartItemRepo = $cartItemRepo;
        $this->productImgRepo = $productImgRepo;
    }

    public function index(){
        $featuredProducts = $this->featuredProducts();
        $categories = $this->categoryRepo->getAll();
        $categoriesWithCountProduct = $this->getCategories();
        $productImgs = $this->productImgRepo->getAll();
        $featuredProds = $this->productRepo->featuredProd();

        // count the number of cart items
        $total_items_order =  countItemsCartEachUser($this->cartRepo, $this->cartItemRepo);

        return view('layouts.home')->with([
            'featuredProducts' => $featuredProducts,
            'categoriesWithCountProduct' => $categoriesWithCountProduct,
            'categories' => $categories,
            'total_items_order' => $total_items_order,
            'productImgs' => $productImgs,
            'featuredProds' => $featuredProds
        ]);
    }

    
    public function featuredProducts(){
        // get the first 3 category id
        $total_categories = 3;
        $categories = $this->categoryRepo->limit($total_categories);
        $category_ids = $this->categoryRepo->getValuesByKey($categories, 'id');
        
        $featuredProducts = [];
        if(count($category_ids) > 0){
            foreach($category_ids as $id){
                $featuredProducts[] = $this->productRepo->getFeaturedProduct($id, 4);
            }
            return $featuredProducts;
        }
        

    }

    public function getCategories(){
        return $this->categoryRepo->getCategoriesWithCountProduct('products');
    }
}
