<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImg;
use App\Repositories\Product\ProductRepository;
use App\Repositories\ProductImg\ProductImgRepository;
use Illuminate\Http\Request;

class ProductImgController extends Controller
{
    //
    protected $productRepo;
    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
        
    }
    public function index($product_id){
        $productImgs = $this->productRepo->find($product_id)->productImgs;
        return view('admin.product.img.index')->with(['productImgs' => $productImgs, 'product_id' => $product_id]);
    }

    public function upload(Request $request, $product_id){
        if($request->hasFile('upload_image')){
            $destinationPath = 'imgs/products';
            $myimage = $request->upload_image->getClientOriginalName();
            $request->upload_image->move(public_path($destinationPath), $myimage);
            
    
            ProductImg::create(['product_id' => $product_id, 'path' => $myimage]);
        }
        return redirect()->back();
    }
    public function delete($product_id, $product_img_id){
        $file_name = ProductImg::find($product_img_id)->path;
        if($file_name != ''){
            unlink('imgs/products/' .$file_name);
        }
        ProductImg::find($product_img_id)->delete();
        return redirect()->back();
    }
}
