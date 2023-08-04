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
        $product = $this->productRepo->find($product_id);
        if($product){
            $productImgs = $product->productImgs;
            return view('admin.product.img.index')->with(['productImgs' => $productImgs, 'product_id' => $product_id]);
        }else{
            return redirect()->route('admin.product.list')->with(['msg' => 'Không tồn tại hoặc cần khôi phục để thực hiện', 'type' => 'danger']);
        }
        
    }

    public function upload(Request $request, $product_id){
        if($request->hasFile('upload_image')){
            $destinationPath = 'imgs/products';
            $imgs = $request->upload_image;
            
            foreach($imgs as $img){
                $myimage = $img->getClientOriginalName();
                $img->move(public_path($destinationPath), $myimage);               
                ProductImg::create(['product_id' => $product_id, 'path' => $myimage]);
            }           
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
