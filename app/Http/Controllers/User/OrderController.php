<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\Order\OrderRepository;
use App\Repositories\OrderDetail\OrderDetailRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\ProductItem\ProductItemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductItem;

use function PHPUnit\Framework\isEmpty;

class OrderController extends Controller
{
    //
    protected $orderRepo;
    protected $orderDetailRepo;
    protected $productItemRepo;
    protected $productRepo;

    public function __construct
    (
        OrderRepository $orderRepo, 
        OrderDetailRepository $orderDetailRepo,
        ProductItemRepository $productItemRepo,
        ProductRepository $productRepo
    )
    {
       $this->orderRepo = $orderRepo; 
       $this->orderDetailRepo = $orderDetailRepo;
       $this->productItemRepo = $productItemRepo;
       $this->productRepo = $productRepo;
    }
    public function show(Request $request){
        $user_id = Auth::user()->id;
        $myOrderResult = $this->orderRepo->getAllOrdersPaginate([['user_id', $user_id], ['order_status_id', '<>', 6]], $request);
        $myOrder = $myOrderResult['orders'];
        $filter_by = $myOrderResult['filterBy'];
        return view('user.order.index')->with(['myOrder' => $myOrder, 'filter_by' => $filter_by]);
    }

    public function detailOrder($id){
        $user_id = Auth::user()->id;
        $order = $this->orderRepo->getOrder(['id' => $id, 'user_id' => $user_id]);
        if($order){
            return view('user.order.show')->with('order', $order);
        }
        abort('404');
        
    }

    // có thể huỷ đơn hàng nếu đang chờ xử lý
    public function cancel(Order $order){
        // logic: product/ product_item đã bị xoá mềm vẫn phải xuất hiện trong order để tránh mất đơn hàng
        // $order->prodItems đã bao gồm các product_item xoá mềm (xem model Order)
        // relation ship product trong model product_item đã bao gồm các product xoá mềm (relationshop noTrashedProduct thì k bao gồm product bị xoá mềm)
        // khi update cần update cả record xoá mềm ==> Model::withTrashed(), Model::withTrashed()->where(id)->update($data)
        $user_id = Auth::user()->id;
        $order_id = $order->id;
        // kiểm tra order tồn tại và có thuộc user 
        $checkAuthOrder = $this->orderRepo->getOrder(['id' => $order->id, 'user_id' => $user_id]);
        // trạng thái order = 1?
        if($checkAuthOrder && $order->order_status_id == 1){
            // update lại các qty từ bảng order_details ==> product và product_items
            // product: quantity, qty_sold 
            // produc_items: quantity
           
            // 1 order có nhiều product_items
            $product_items = $order->prodItems;
            if($product_items){
                foreach($product_items as $product_item){
                    //$product_item->pivot->quantity : là qty của từng order_detail tương ứng với từng product_item
                    $product_item_in_order_details_table = $product_item->pivot->quantity;
                    // $product_item->product: là product cha chứa product_item con
                    // orders n - n product_items
                    // prducts 1 - n product_items
                    $product = $product_item->product;
                    
                    $product_item_update_status = ProductItem::withTrashed()->where('id', $product_item->id)->update([
                        'quantity' => $product_item->quantity + $product_item_in_order_details_table
                    ]);
                    $product_update_status = Product::withTrashed()->where('id', $product->id)->update([
                        'quantity' => $product->quantity + $product_item_in_order_details_table,
                        'qty_sold' => $product->qty_sold - $product_item_in_order_details_table
                    ]);

                }
                // update order_status_id của order 
                if($product_item_update_status && $product_update_status){
                    $updats_status = $this->orderRepo->edit(['order_status_id' => 5], $order_id);
                    if($updats_status){
                        $msg = 'Huỷ đơn thành công';
                        $type = 'success';
                    }else{
                        $msg = 'Có lỗi xảy ra';
                        $type = 'danger';
                    }
                }else{
                    $msg = 'Có lỗi xảy ra';
                    $type = 'danger';
                }
                
            }else{
                $msg = 'Có lỗi xảy ra';
                $type = 'danger';
            }
            return redirect()->route('user.order.order')->with(['msg' => $msg, 'type' => $type]);
        }
        abort('404');
    }
}
