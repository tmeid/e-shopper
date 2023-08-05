<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
    public function index(Request $request){
        $categories = $this->categoryRepo->getCategories($request);
        return view('admin.category.index')->with('categories', $categories);
    }

    public function add(){
      return view('admin.category.add');
    }
    public function postAdd(Request $request){
      $request->validate([
        'name' => 'required|unique:categories'
      ], [
        'required' => 'Bị trống',
        'unique' => 'Danh mục đã tồn tại'
      ]);

      $category_name = trim($request->name);
      $categoryObj = $this->categoryRepo->create([
        'name' => $category_name,
        'slug' => create_slug($category_name)
      ]);
      if($categoryObj){
        $msg = 'Thêm danh mục thành công';
        $type = 'success';
      }else{
        $msg = 'Đã có lỗi xảy ra';
        $type = 'danger';
      }
      return redirect()->route('admin.category.list')->with(['msg' => $msg, 'type' => $type]);
    }

    public function edit(Category $category){
      return view('admin.category.edit')->with('category', $category);
    }

    public function postEdit(Request $request, Category $category){
      $request->validate([
        'name' => 'required|unique:categories,name,' .$category->id
      ], [
        'required' => 'Bị trống',
        'unique' => 'Danh mục đã tồn tại'
      ]);

      if($request->name != $category->name){
        $category_name = trim($request->name);
        $status = $this->categoryRepo->edit([
          'name' => $request->name,
          'slug' => create_slug($category_name)
          ], $category->id);
        if($status){
          $msg = 'Sửa danh mục thành công';
          $type = 'success';
        }else{
          $msg = 'Có lỗi xảy ra';
          $type = 'danger';
        }
      }else{
        $msg = 'Bạn chưa nhập gì';
        $type = 'danger';
      }
      return redirect()->route('admin.category.list')->with(['msg' => $msg, 'type' => $type]);
    }

    public function delete(Request $request){
      $id = $request->id;
      $category = $this->categoryRepo->find($id);
      if($category){
        $deletedNum = $this->categoryRepo->delete($id);
        if($deletedNum){
          $msg = 'Xoá thành công';
          $type = 'success';
        }else{
          $msg = 'Có lỗi xảy ra';
          $type = 'danger';
        }
      }else{
        $msg = 'Danh mục không tồn tại';
        $type = 'danger';
      }
      
      return redirect()->route('admin.category.list')->with(['msg' => $msg, 'type' => $type]);
    }

}
