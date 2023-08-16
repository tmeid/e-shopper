<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Brand\BrandRepo;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    //
    protected $brandRepo;
    public function __construct(BrandRepo $brandRepo)
    {
        $this->brandRepo = $brandRepo;
    }
    public function index(Request $request){
        $brands = $this->brandRepo->getBrands($request);
        return view('admin.brand.index')->with('brands', $brands);
    }

    public function add(){
        return view('admin.brand.add');
      }
      public function postAdd(Request $request){
        $request->validate([
          'name' => 'required|unique:brands'
        ], [
          'required' => 'Bị trống',
          'unique' => 'Danh mục đã tồn tại'
        ]);
  
        $brand_name = trim($request->name);
        $brandObj = $this->brandRepo->create([
          'name' => $brand_name,
          'slug' => create_slug($brand_name)
        ]);
        if($brandObj){
          $msg = 'Thêm thương hiệu thành công';
          $type = 'success';
        }else{
          $msg = 'Đã có lỗi xảy ra';
          $type = 'danger';
        }
        return redirect()->route('admin.brand.list')->with(['msg' => $msg, 'type' => $type]);
      }
  
      public function edit(Brand $brand){
        return view('admin.brand.edit')->with('brand', $brand);
      }
  
      public function postEdit(Request $request, Brand $brand){
        $request->validate([
          'name' => 'required|unique:categories,name,' .$brand->id
        ], [
          'required' => 'Bị trống',
          'unique' => 'Danh mục đã tồn tại'
        ]);
  
        if($request->name != $brand->name){
          $brand_name = trim($request->name);
          $status = $this->brandRepo->edit([
            'name' => $brand_name,
            'slug' => create_slug($brand_name)
            ], $brand->id);
          if($status){
            $msg = 'Sửa thương hiệu thành công';
            $type = 'success';
          }else{
            $msg = 'Có lỗi xảy ra';
            $type = 'danger';
          }
        }else{
          $msg = 'Bạn chưa nhập gì';
          $type = 'danger';
        }
        return redirect()->route('admin.brand.list')->with(['msg' => $msg, 'type' => $type]);
      }
  
      public function delete(Request $request){
        $id = $request->id;
        $brand = $this->brandRepo->find($id);
        if($brand){
          $deletedNum = $this->brandRepo->delete($id);
          if($deletedNum){
            $msg = 'Xoá thành công';
            $type = 'success';
          }else{
            $msg = 'Có lỗi xảy ra';
            $type = 'danger';
          }
        }else{
          $msg = 'Thương hiệu không tồn tại';
          $type = 'danger';
        }
        
        return redirect()->route('admin.brand.list')->with(['msg' => $msg, 'type' => $type]);
      }
}
