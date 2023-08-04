<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Brand\BrandRepo;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Product\ProductRepository;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImg;

class ProductController extends Controller
{
    //
    protected $productRepo;
    protected $categoryRepo;
    protected $brandRepo;
    
    public function __construct(ProductRepository $productRepo, CategoryRepository $categoryRepo, BrandRepo $brandRepo)
    {
        $this->productRepo = $productRepo;
        $this->categoryRepo = $categoryRepo;
        $this->brandRepo = $brandRepo;
    }
    public function index(Request $request){
        $productsResult = $this->productRepo->productAdminPaginate($request);
        $products = $productsResult['products'];
        $sortBy = $productsResult['sort_by'];
        $search = $productsResult['search'];
        return view('admin.product.index')
            ->with([
                'products' => $products, 
                'sort_by' => $sortBy,
                'search' => $search
            ]);
    }

    public function show($id){
        $product = $this->productRepo->findWithTrashedProduct($id);
        return view('admin.product.show')->with('product', $product);
    }

    public function add(){
        $brands = $this->brandRepo->getAll();
        $categories = $this->categoryRepo->getAll();
        return view('admin.product.add')->with(['brands' => $brands, 'categories' => $categories]);
    }

    public function postAdd(Request $request){
        $request->validate([
            'name' => 'required',
            'brand_id' => 'required',
            'category_id' => 'required',
            'upload_image' => 'required',
            'description' => 'required',
            'content' => 'required',
            'quantity' => ['required', function($attributes, $value, $fail){
                $reg = '/(?=.*[^0-9])/';
                if(preg_match($reg, $value)){
                    $fail('Chỉ nhập số');
                }
            }],
            'price' => ['required', function($attributes, $value, $fail){
                $reg = '/(?=.*[^0-9])/';
                if(preg_match($reg, $value)){
                    $fail('Chỉ nhập số');
                }
            }],
            'discount' => ['required', function($attributes, $value, $fail){
                $reg = '/(?=.*[^.0-9])/';
                if(preg_match($reg, $value)){
                    $fail('Chỉ nhập số, số thập phân chỉ dùng dấu chấm');
                }else{
                    $value = (float)$value;
                    if($value < 0 || $value >= 1){
                        $fail('Discount không hợp lệ');
                    }
                }
                
            }],
        ],[
            'required' => 'Không được để trống'
        ]);

        if(empty($request->featured)){
            $featured = 0;
        }else{
            $featured = $request->featured;
        }

        $productObj = $this->productRepo->create([
            'name' => $request->name,
            'slug' => create_slug($request->name),
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'content' => $request->content,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'discount' => $request->discount,
            'featured' => $featured,
            'qty_sold' => 0
        ]);

        if($productObj){
            $product_id = $productObj->id;

            if($request->hasFile('upload_image')){
                $destinationPath = 'imgs/products';
                $imgs = $request->upload_image;

                foreach($imgs as $img){
                    $myimage = $img->getClientOriginalName();
                    $img->move(public_path($destinationPath), $myimage);               
                    ProductImg::create(['product_id' => $product_id, 'path' => $myimage]);
                }           
            }
            $msg = "Thêm sản phẩm thành công";
            $type = 'success';
        }else{
            $msg = "Đã có lỗi xảy ra";
            $type = 'danger';
        }
        return redirect()->route('admin.product.list')->with(['msg' => $msg, 'type' => $type]);
    }

    public function showFormEdit(Request $request, $id){
        $product = $this->productRepo->find($id);
        if($product){
            $brands = $this->brandRepo->getAll();
            $categories = $this->categoryRepo->getAll(); 
            return view('admin.product.edit')->with(['product' => $product, 'brands' => $brands, 'categories' => $categories]);
        }else{
            return redirect()->route('admin.product.list')->with(['msg' => 'Không tồn tại hoặc cần khôi phục để thực hiện', 'type' => 'danger']);
        }
        
    }

    public function edit(Request $request, $id){
        $request->validate([
            'name' => 'required',
            'brand_id' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'content' => 'required',
            'quantity' => ['required', function($attributes, $value, $fail){
                $reg = '/(?=.*[^0-9])/';
                if(preg_match($reg, $value)){
                    $fail('Chỉ nhập số');
                }
            }],
            'price' => ['required', function($attributes, $value, $fail){
                $reg = '/(?=.*[^0-9])/';
                if(preg_match($reg, $value)){
                    $fail('Chỉ nhập số');
                }
            }],
            'discount' => ['required', function($attributes, $value, $fail){
                $reg = '/(?=.*[^.0-9])/';
                if(preg_match($reg, $value)){
                    $fail('Chỉ nhập số, số thập phân chỉ chứa dấu chấm');
                }else{
                    $value = (float)$value;
                    if($value < 0 || $value >= 1){
                        $fail('Discount không hợp lệ');
                    }
                }
                
            }],
        ],[
            'required' => 'Không được để trống'
        ]);

        if(empty($request->featured)){
            $featured = 0;
        }else{
            $featured = $request->featured;
        }

        $status = $this->productRepo->edit([
            'name' => $request->name,
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'content' => $request->content,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'discount' => $request->discount,
            'featured' => $featured,
            'qty_sold' => 0
        ], $id);

        if($status){
            $msg = "Sửa sản phẩm thành công";
            $type = 'success';
        }else{
            $msg = "Đã có lỗi xảy ra";
            $type = 'danger';
        }
        return redirect()->route('admin.product.list')->with(['msg' => $msg, 'type' => $type]);

    }

    public function delete(Product $product){
        $id = $product->id;
        if($this->productRepo->find($id)){
            $deletedNum = $this->productRepo->delete($id);
            if($deletedNum){
                $msg = 'Xoá mềm thành công';
                $type = 'success';
            }else{
                $msg = 'Đã có lỗi xảy ra';
                $type = 'danger';
            }
        }else{
            abort('401');
        }
        return redirect()->route('admin.product.list')->with(['msg' => $msg, 'type' => $type]);
       
       
    }

    public function restore($id){
        $trashedUser = $this->productRepo->getTrashedProduct($id);
        if($trashedUser){
            $trashedUser->restore();
            $msg = 'Khôi phục thành công';
            $type = 'success';
        }else{
            $msg = 'Sản phẩm không tồn tại';
            $type = 'danger';
        }
        return redirect()->route('admin.product.list')->with(['msg' => $msg, 'type' => $type]);
    }

    public function forceDelete(Request $request){
        $id = $request->id;
        $trashedUser = $this->productRepo->getTrashedProduct($id);
        if($trashedUser){
            $trashedUser->forceDelete();
            $msg = 'Xoá thành công';
            $type = 'success';
        }else{
            $msg = 'Sản phẩm không tồn tại';
            $type = 'danger';
        }
        return redirect()->route('admin.product.list')->with(['msg' => $msg, 'type' => $type]);
    }
}
