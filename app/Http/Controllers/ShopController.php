<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\Cart\CartRepository;
use App\Repositories\CartItem\CartItemRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\ProductItem\ProductItemRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class ShopController extends Controller
{
    //
    protected $productRepo;
    protected $categoryRepo;
    protected $productItemRepo;
    protected $cartRepo;
    protected $cartItemRepo;

    public function __construct(
        ProductRepository $productRepo, 
        CategoryRepository $categoryRepo, 
        ProductItemRepository $productItemRepo,
        CartRepository $cartRepo,
        CartItemRepository $cartItemRepo
    ){        
        $this->productRepo = $productRepo;
        $this->categoryRepo = $categoryRepo;
        $this->productItemRepo = $productItemRepo;
        $this->cartRepo = $cartRepo;
        $this->cartItemRepo = $cartItemRepo;

    }

    public function index(Request $request, $slug = null){
        $id = null;
        if(!empty($slug)){
            $id = $this->categoryRepo->getId(['slug' => $slug]);
        }
        $categories = $this->categoryRepo->getAll();
        $colors = $this->productItemRepo->getDistinctColor();

        $productsResult = $this->productRepo->productPaginate($request, $id);

        // count the number of cart items
        $total_items_order =  countItemsCartEachUser($this->cartRepo, $this->cartItemRepo);

        $products = $productsResult['products'];
        $search = $productsResult['search'];
        $categoryFilter = $productsResult['categoryFilter'];
        $sizeFilter = $productsResult['sizeFilter'];
        $colorFilter = $productsResult['colorFilter'];
        $minPrice = $productsResult['minPrice'];
        $maxPrice = $productsResult['maxPrice'];
        
        return view('layouts.products')->with([
            'products' => $products,
            'total_items_order' =>  $total_items_order,
            'colors' => $colors,
            'search' => $search,
            'categoryFilter' =>  $categoryFilter,
            'categories' => $categories,
            'sizeFilter' => $sizeFilter,
            'colorFilter' => $colorFilter,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice
        ]);
    }

}
