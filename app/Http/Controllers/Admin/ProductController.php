<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Brand\BrandRepo;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Product\ProductRepository;
use Illuminate\Http\Request;

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
        $productsResult = $this->productRepo->productPaginate($request);
        $products = $productsResult['products'];
        return view('admin.product.index')->with('products', $products);
    }

    public function show($id){
        $product = $this->productRepo->find($id);
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
                $reg = '/(?=.*[^.,0-9])/';
                if(preg_match($reg, $value)){
                    $fail('Chỉ nhập số');
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

        $status = $this->productRepo->create([
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
        ]);
        if($status){
            $msg = "Thêm sản phẩm thành công";
        }else{
            $msg = "Đã có lỗi xảy ra";
        }
        return redirect()->route('admin.product.list')->with('msg', $msg);
    }

    public function showFormEdit(Request $request, $id){
        $brands = $this->brandRepo->getAll();
        $categories = $this->categoryRepo->getAll();
        $product = $this->productRepo->find($id);
        return view('admin.product.edit')->with(['product' => $product, 'brands' => $brands, 'categories' => $categories]);
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
                $reg = '/(?=.*[^.,0-9])/';
                if(preg_match($reg, $value)){
                    $fail('Chỉ nhập số');
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
        }else{
            $msg = "Đã có lỗi xảy ra";
        }
        return redirect()->route('admin.product.list')->with('msg', $msg);

    }

    public function delete($id){
        if($this->productRepo->delete($id)){
            $msg = 'Xoá thành công';
        }else{
            $msg = 'Đã có lỗi xảy ra';
        }
        return redirect()->route('admin.product.list')->with('msg', $msg);
    }
}
