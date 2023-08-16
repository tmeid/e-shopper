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
        $num_featured_category = 3;
        $categories = $this->categoryRepo->getAll();
        $categoriesWithCountProduct = $this->categoryRepo->getLimitCategoriesWithCountProduct('products', $num_featured_category);
        $productImgs = $this->productImgRepo->getAll();
        $featuredProds = $this->productRepo->getFeaturedProduct(8);

        // count the number of cart items
        $total_items_order =  countItemsCartEachUser($this->cartRepo, $this->cartItemRepo);

        return view('layouts.home')->with([
            'categoriesWithCountProduct' => $categoriesWithCountProduct,
            'categories' => $categories,
            'total_items_order' => $total_items_order,
            'productImgs' => $productImgs,
            'featuredProds' => $featuredProds
        ]);
    }

}
