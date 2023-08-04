<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductItem;
use App\Repositories\Product\ProductRepository;
use App\Repositories\ProductItem\ProductItemRepository;
use Illuminate\Validation\Rule;

class SubProductController extends Controller
{
    //
    protected $productItemRepo;
    protected $productRepo;

    public function __construct(ProductItemRepository $productItemRepo, ProductRepository $productRepo)
    {
        $this->productItemRepo = $productItemRepo;
        $this->productRepo = $productRepo;
    }
    public function showSubItems($id){
        $product = $this->productRepo->find($id);
        if($product){
            $sub_items = $product->productItems;
            return view('admin.product.sub.index')->with(['sub_items' => $sub_items, 'product' => $product]);
        }else{
            return redirect()->route('admin.product.list')->with(['msg' => 'Không tồn tại hoặc cần khôi phục để thực hiện', 'type' => 'danger']);
        }
        
    }

    public function addSubItem(Product $product){
        return view('admin.product.sub.add')->with('product', $product);
    }

    public function postAddSubItem(Product $product, Request $request){
        $request->validate([
            'sku' => 'required|unique:product_items,sku',
            'color' => ['required', Rule::unique('product_items')->where('product_id', $product->id)->where(function($query) use ($request){
                return $query->where([
                    'size' => $request->size,
                    'color' => $request->color
                ]);
            })],
            'size' => ['required', Rule::unique('product_items')->where('product_id', $product->id)->where(function($query) use ($request){
                return $query->where([
                    'size' => $request->size,
                    'color' => $request->color
                ]);
            })],
            'quantity' => ['required', function($attributes, $value, $fail){
                $reg = '/(?=.*[^0-9])/';
                if(preg_match($reg, $value)){
                    $fail('Chỉ nhập số');
                }
            }]
        ],[
            'required' => 'Không được để trống',
            'sku.unique' => 'sku đã tồn tại',
            'color.unique' => $product->name .' đã có màu- size này',
            'size.unique' => $product->name .' đã có màu- size này'
        ]);

        
        $subItemObj = $this->productItemRepo->create([
            'product_id' => $product->id,
            'sku' => $request->sku,
            'color' => $request->color,
            'size' => $request->size,
            'quantity' => $request->quantity
        ]);
       

        if($subItemObj){
            $msg = 'Thêm sản phẩm con thành công';
            $type = 'success';
        }else{
            $msg = 'Đã có lỗi xảy ra';
            $type = 'danger';
        }
        return redirect()->route('admin.product.showSubItems', ['product_id' => $product->id])->with(['msg' => $msg, 'type' => $type]);
    }

    public function showSubItem(Product $product, ProductItem $sub_item){
        return view('admin.product.sub.edit')->with('sub_item', $sub_item);
    }

    public function editSubItem(Product $product, ProductItem $sub_item, Request $request){
        $sub_item_id = $sub_item->id;
        $request->validate([
            'sku' => 'required|unique:product_items,sku,' .$sub_item_id,
            'color' => ['required', Rule::unique('product_items')->where('product_id', $product->id)->where(function($query) use ($request){
                return $query->where([
                    'size' => $request->size,
                    'color' => $request->color
                ]);
            })->ignore($sub_item_id)],
            'size' => ['required', Rule::unique('product_items')->where('product_id', $product->id)->where(function($query) use ($request){
                return $query->where([
                    'size' => $request->size,
                    'color' => $request->color
                ]);
            })->ignore($sub_item_id)],
            'quantity' => ['required', function($attributes, $value, $fail){
                $reg = '/(?=.*[^0-9])/';
                if(preg_match($reg, $value)){
                    $fail('Chỉ nhập số');
                }
            }]
        ],[
            'required' => 'Không được để trống',
            'sku.unique' => 'sku đã tồn tại',
            'color.unique' => $product->name .' đã có màu- size này',
            'size.unique' => $product->name .' đã có màu- size này'
        ]);

        $data = [];
        if($request->sku != $sub_item->sku){
            $data['sku'] = $request->sku;
        }
        if($request->size != $sub_item->size){
            $data['size'] = $request->size;
        }
        if($request->color != $sub_item->color){
            $data['color'] = $request->color;
        }
        if($request->quantity != $sub_item->quantity){
            $data['quantity'] = $request->quantity;
        }

        if(!empty($data)){
            $subItemObj = $this->productItemRepo->edit($data, $sub_item_id);
            if($subItemObj){
                $msg = 'Cập nhật thành công';
                $type = 'success';
            }else{
                $msg = 'Đã có lỗi xảy ra';
                $type = 'danger';
            }
        }else{
            $msg = 'Bạn chưa nhập gì';
            $type = 'danger';
        }
        return redirect()->route('admin.product.list')->with(['msg' => $msg, 'type' => $type]);
    }

    public function delete(Product $product, ProductItem $sub_item){
        $id = $sub_item->id;
        if($this->productItemRepo->find($id)){
            $deletedNum = $this->productItemRepo->delete($id);
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
        return redirect()->route('admin.product.showSubItems', ['product_id' => $product->id])->with(['msg' => $msg, 'type' => $type]);
       
       
    }

    public function restore(Product $product, $id){
        $trashedUser = $this->productItemRepo->getTrashed($id);
        if($trashedUser){
            $trashedUser->restore();
            $msg = 'Khôi phục thành công';
            $type = 'success';
        }else{
            $msg = 'Sản phẩm không tồn tại';
            $type = 'danger';
        }
        return redirect()->route('admin.product.showSubItems', ['product_id' => $product->id])->with(['msg' => $msg, 'type' => $type]);
    }

    public function forceDelete(Product $product, Request $request){
        $id = $request->id;
        $trashedUser = $this->productItemRepo->getTrashed($id);
        if($trashedUser){
            $trashedUser->forceDelete();
            $msg = 'Xoá thành công';
            $type = 'success';
        }else{
            $msg = 'Sản phẩm không tồn tại';
            $type = 'danger';
        }
        return redirect()->route('admin.product.showSubItems', ['product_id' => $product->id])->with(['msg' => $msg, 'type' => $type]);
    }

}
