<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Category\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    protected $categoryRepo;
    public function __construct(CategoryRepository $categoryRepo)
    {
      $this->categoryRepo = $categoryRepo;  
    }
    public function index(){
        $categories = $this->categoryRepo->getCategories();
        return view('admin.category.index')->with('categories', $categories);
    }
}
