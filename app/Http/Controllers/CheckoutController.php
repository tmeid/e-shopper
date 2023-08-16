<?php

namespace App\Http\Controllers;

use App\Repositories\Address\AddressRepository;
use Illuminate\Http\Request;
use App\Repositories\Cart\CartRepository;
use App\Repositories\CartItem\CartItemRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\OrderDetail\OrderDetailRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\ProductItem\ProductItemRepository;
use App\Repositories\User\UserRepository;
use App\Utilities\VNPay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Ui\Presets\React;

class CheckoutController extends Controller
{
    //
    protected $productItemRepo;
    protected $cartRepository;
    protected $cartItemRepo;
    protected $categoryRepo;
    protected $userRepo;
    protected $paymentRepo;
    protected $addressRepo;
    protected $orderRepo;
    protected $orderDetailRepo;
    protected $productRepo;

    public function __construct(
        ProductItemRepository $productItemRepo,
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepo,
        CategoryRepository $categoryRepo,
        UserRepository $userRepo,
        PaymentRepository $paymentRepo,
        AddressRepository $addressRepo,
        OrderRepository $orderRepo,
        OrderDetailRepository $orderDetailRepo,
        ProductRepository $productRepo
    ) {
        $this->productItemRepo = $productItemRepo;
        $this->cartRepository = $cartRepository;
        $this->cartItemRepo = $cartItemRepo;
        $this->categoryRepo = $categoryRepo;
        $this->userRepo = $userRepo;
        $this->paymentRepo = $paymentRepo;
        $this->addressRepo = $addressRepo;
        $this->orderRepo = $orderRepo;
        $this->orderDetailRepo = $orderDetailRepo;
        $this->productRepo = $productRepo;
    }
    //
    public function index()
    {
        $categories = $this->categoryRepo->getCategoriesAtLeastOneProduct();

        // count the number of items (with trashed) per user in cart_items table
        $total_items_order =  countItemsCartEachUser($this->cartRepository, $this->cartItemRepo);

        $user_id = Auth::user()->id;
        $addresses = $this->userRepo->getAddresses($user_id);
        $cart_info = $this->cartRepository->findByKey('user_id', $user_id);

        if ($cart_info) {
            $cart_id = $cart_info->id;
            $totalPrice = 0;
            $payments = $this->paymentRepo->getAll();

            $items_order = $this->cartRepository->getItemsWithTrashedPerUser($cart_id);

            foreach ($items_order as $item_order) {
                if($item_order->quantity >= 1 && !$item_order->trashed()){
                    $product = $item_order->noTrashedProduct;
                    if($product){
                        if ($product->discount > 0) {
                            $price = (1 - $product->discount) * ($product->price) * ($item_order->pivot->quantity);
                        } else {
                            $price = ($product->price) * ($item_order->pivot->quantity);
                        }
                        $totalPrice += $price;
                    }
                }
            }

            return view('layouts.checkout')->with([
                'categories' => $categories,
                'total_items_order' => $total_items_order,
                'items_order' => $items_order,
                'totalPrice' => $totalPrice,
                'addresses' => $addresses,
                'payments' => $payments,
                'cart_id' => $cart_id
            ]);
        }
        return redirect()->route('home');
    }

    public function process(Request $request)
    {

        $request->validate([
            'address' => ['required', function ($attribute, $value, $fail) {
                $address = $this->addressRepo->find($value);
                if (!$address) {
                    $fail("Địa chỉ không tồn tại");
                }
            }],
            'payment' => 'required',
            'cart_id' => [function($attribute, $value, $fail){
                $cart_id = $value;
                $cart_info = $this->cartRepository->findByKey('user_id', Auth::user()->id);
                if($cart_id == $cart_info->id){
                    $cart_items = $this->cartRepository->getItemsWithTrashedPerUser($cart_id);            
                    if(count($cart_items)){
                        foreach($cart_items as $cart_item){
                            $product = $cart_item->product;
                            if($product->trashed()){
                                $fail('Vui lòng xoá sản phẩm ngừng kinh doanh');
                                break;
                            }elseif($cart_item->quantity <= 0 || $cart_item->trashed()){
                                $fail('Vui lòng xoá sản phẩm hết hàng');
                                break;
                            }elseif($cart_item->pivot->quantity >  $cart_item->quantity){
                                $fail('Vượt quá số lượng có sẵn');
                                break;
                            }
                        }
                    }else{
                        $fail('Opps, Lỗi');
                    }
                }else{
                    $fail('Opps, cart_id k hợp lệ');
                }
                
                
            }]
        ], [
            'address.required' => 'Chưa nhập địa chỉ',
            'payment.required' => 'Chưa chọn hình thức thanh toán'
        ]);

        $address_id = $request->address;
        $payment_id = $request->payment;
        $order_total = $request->order_total;
        $cart_id = $request->cart_id;
        $user_id = Auth::user()->id;

        if (!empty($order_total)) {
            // payment later         
            $addressRecord = $this->addressRepo->find($address_id);

            // lưu thông tin order
            $inserOrder = $this->orderRepo->create([
                'user_id' => Auth::user()->id,
                'name' => $addressRecord->name,
                'address' => $addressRecord->address,
                'phone' => $addressRecord->phone,
                'payment_method_id' => $payment_id,
                'order_total' => $order_total,
                'order_status_id' => 1
            ]);
            if ($inserOrder) {
                $inserOrderId = $inserOrder->id;
                // lưu thông tin vào bảng order_details
                $cart_items = $this->cartItemRepo->getItemsInSameCart(['cart_id' => $cart_id]);
                if (count($cart_items) > 0) {

                    foreach ($cart_items as $cart_item) {
                        $productId = $this->productItemRepo->find($cart_item->product_item_id)->product_id;
                        $product = $this->productRepo->find($productId);

                        if ($product->discount > 0) {
                            $final_price = (1 - $product->discount) * ($product->price);
                        } else {
                            $final_price = $product->price;
                        }

                        $this->orderDetailRepo->create([
                            'order_id' =>  $inserOrderId,
                            'product_item_id' => $cart_item->product_item_id,
                            'quantity' => $cart_item->quantity,
                            'price' => $final_price
                        ]);

                        // qty còn lại trong product_items
                        $product_item = $this->productItemRepo->find($cart_item->product_item_id);
                        $product_item->update(['quantity' => $product_item->quantity - $cart_item->quantity]);

                        // update số lượng sản phẩm sold, quantity còn lại trong bảng product
                        // $productId = $this->productItemRepo->find($cart_item->product_item_id)->product_id;
                        // $product = $this->productRepo->find($productId);
                        $product->update(['qty_sold' => $product->qty_sold + $cart_item->quantity]);
                        $product->update(['quantity' => $product->quantity - $cart_item->quantity]);
                    }
                    // payment later
                    if ($payment_id == 1) {
                        // xoá thông tin trong bảng cart_items
                        $deleteCartItemStatus = $this->cartItemRepo->deleteItems(['cart_id' => $cart_id]);
                        if ($deleteCartItemStatus) {
                            // xoá thông tin trong bảng cart
                            $this->cartRepository->delete(['id' => $cart_id]);
                            $msg = 'Giao dịch thành công';
                            $type = 'success';
                            return redirect()->route('user.order.order')->with(['msg' => $msg, 'type' => $type]);
                        } else {
                            $msg = 'Giao dịch thành công mặc dù chưa xoá ở các bảng cart';
                            $type = 'success';
                        }
                    } elseif ($payment_id == 2) {
                        // online payment
                        //1. Lấy url thanh toán VNpay
                        $data_url = VNPay::vnpay_create_payment([
                            // id của đơn hàng
                            'vnp_TxnRef' => $inserOrderId,
                            // tổng bill
                            'vnp_Amount' => $order_total,

                        ]);
                        // 2. Chuyển hướng đến url lấy được
                        return redirect()->to($data_url);
                    }
                } else {
                    // thay đổi order_status_id sang trạng thái lỗi
                    $this->orderRepo->edit([
                        'order_status_id' => 6
                    ], $inserOrderId);
                    $msg = 'Lưu thông tin vào bảng order_details bị lỗi';
                    $type = 'danger';
                }
            } else {
                $msg = 'Đã có lỗi xảy ra, vui lòng thử lại';
                $type = 'success';
            }
            return redirect()->route('checkout.index')->with(['msg' => $msg, 'type' => $type]);
        }
    }
    public function vnPayCheck(Request $request)
    {
        // 1. Lấy data từ URL (do VNpay trả về qua $vnp_returnUrl)
        // vào file vnpay_return để xem các key
        $vnp_ResponseCode = $request->vnp_ResponseCode; // 00 là thành công
        $vnp_TxnRef = $request->vnp_TxnRef;
        $vnp_Amount = $request->vnp_Amount;

        // 2. Kiểm tra data, xem kết quả giao dịch trả về có hợp lệ không
        if (!empty($vnp_ResponseCode)) {
            if ($vnp_ResponseCode == '00') {
                // xoá thông tin trong bảng cart_items
                // mỗi user chỉ có duy nhất 1 record trong bảng cart
                $cart_id = $this->cartRepository->findByKey('user_id', Auth::user()->id)->id;
                $deleteCartItemStatus = $this->cartItemRepo->deleteItems(['cart_id' => $cart_id]);
                if ($deleteCartItemStatus) {
                    // xoá thông tin trong bảng cart
                    $this->cartRepository->delete(['id' => $cart_id]);
                    $msg = 'Giao dịch thành công';
                    $type = 'success';
                } else {
                    $msg = 'Giao dịch thành công mặc dù chưa xoá được ở các bảng cart';
                    $type = 'success';
                }
                return redirect()->route('user.order.order')->with(['msg' => $msg, 'type' => $type]);
            } else {
                // order_status // giao dịch online thất bại
                $this->orderRepo->edit([
                    'order_status_id' => 6
                ], $vnp_TxnRef);
                return redirect()->route('user.order.order')->with(['msg' => 'Giao dịch thất bại', 'type' => 'danger']);
            }
        }
    }
}
