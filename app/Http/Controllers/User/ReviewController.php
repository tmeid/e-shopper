<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductItem;
use App\Repositories\Order\OrderRepository;
use App\Repositories\OrderDetail\OrderDetailRepository;
use App\Repositories\Review\ReviewRepository;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    //
    protected $reviewRepo;
    protected $orderRepo;
    protected $orderDetailRepo;

    public function __construct(
        OrderRepository $orderRepo, 
        OrderDetailRepository $orderDetailRepo,
        ReviewRepository $reviewRepo
    ){
        $this->orderRepo = $orderRepo;
        $this->orderDetailRepo = $orderDetailRepo;
        $this->reviewRepo = $reviewRepo;
    }
    public function index(Order $order){
        if($order->order_status_id == 4){
            $user_id = Auth::user()->id;
            
            $checkAuthOrder = $this->orderRepo->getOrders(['id' => $order->id, 'user_id' => $user_id]);
            if($checkAuthOrder && count($checkAuthOrder) > 0){
                return view('user.review.index')->with('order', $order);
            }else{
                abort('404');
            }           
        }else{
            abort('404');
        }
        
    }
    public function review(Order $order, ProductItem $productItem){
        $user_id = Auth::user()->id;
            
        $checkAuthOrder = $this->orderRepo->getOrder(['id' => $order->id, 'user_id' => $user_id]);
        $checkProductItemInOrder = $this->orderDetailRepo->getOrderDetail(['order_id' => $order->id, 'product_item_id' => $productItem->id]);
        if($checkAuthOrder &&  $checkProductItemInOrder){
            $order_detail_id = $this->orderDetailRepo->getOrderDetail(['order_id' => $order->id, 'product_item_id' => $productItem->id])->id;
            if(!isReviewed($order_detail_id)){
                return view('user.review.review')->with('item', $productItem);
            }else{
                return redirect()->route('user.order.index', ['order' => $order])->with(['msg' => 'Bạn đã đánh giá sản phẩm này', 'type' => 'danger']);
            }
            
        }
        abort('404');
    }
    public function postReview(Request $request, Order $order, ProductItem $productItem){
        $request->validate([
            'rating' => ['required', function($attribute, $value, $fail){
                $allow_values = [1, 2, 3, 4, 5];
                if(!in_array($value, $allow_values)){
                    $fail('Giá trị không hợp lệ');
                }
            }],
            'review' => 'required'
        ],[
            'required' => 'Bị trống'
        ]);
        $order_detail_id = $this->orderDetailRepo->getOrderDetail(['order_id' => $order->id, 'product_item_id' => $productItem->id])->id;
        $review = $this->reviewRepo->create([
            'product_id' => $productItem->product->id,
            'order_detail_id' => $order_detail_id,
            'user_id' => Auth::user()->id,
            'rating' => $request->rating,
            'message' => trim($request->review)
        ]);
        if($review){
            $msg = 'Cảm ơn bạn đã review sản phẩm';
            $type = 'success';
        }else{
            $msg = 'Đã có lỗi xảy ra, vui lòng thử lại';
            $type = 'danger';
        }
        return redirect()->route('user.order.index', ['order' => $order])->with(['msg' => $msg, 'type' => $type]);
    }
}
