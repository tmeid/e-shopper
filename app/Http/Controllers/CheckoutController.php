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
        $categories = $this->categoryRepo->getAll();

        // count the number of items per user in cart_items table
        $total_items_order =  countItemsCartEachUser($this->cartRepository, $this->cartItemRepo);

        $user_id = Auth::user()->id;
        $addresses = $this->userRepo->getAddresses($user_id);
        $cart_info = $this->cartRepository->findByKey('user_id', $user_id);
        $cart_id = $cart_info->id;
        $totalPrice = 0;
        $payments = $this->paymentRepo->getAll();

        $items_order = $this->cartRepository->getItemsPerUser($cart_id);

        foreach ($items_order as $item_order) {
            if ($item_order->noTrashedProduct->discount > 0) {
                $price = (1 - $item_order->noTrashedProduct->discount) * ($item_order->noTrashedProduct->price) * ($item_order->pivot->quantity);
            } else {
                $price = ($item_order->noTrashedProduct->price) * ($item_order->pivot->quantity);
            }
            $totalPrice += $price;
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

    public function process(Request $request)
    {
        $request->validate([
            'address' => 'required',
            'payment' => 'required'
        ], [
            'address.required' => 'Chưa nhập địa chỉ',
            'payment.required' => 'Chưa chọn hình thức thanh toán'
        ]);

        // Lưu thông tin địa chỉ xuống bảng addresses nếu địa chỉ chưa tồn tại --> lấy id để insert vào bảng orders
        $address = $request->address;
        $payment_id = $request->payment;
        $order_total = $request->order_total;
        $cart_id = $request->cart_id;
        $user_id = Auth::user()->id;

        if (!empty($order_total)) {
            $insertAddress = $this->addressRepo->findOrInsert(['address' => $address, 'user_id' => $user_id]);

            if ($insertAddress) {
                // lưu thông tin order
                $inserOrder = $this->orderRepo->create([
                    'user_id' => Auth::user()->id,
                    'address' => $address,
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

                        // xoá thông tin trong bảng cart_items
                        $deleteCartItemStatus = $this->cartItemRepo->deleteItems(['cart_id' => $cart_id]);
                        if ($deleteCartItemStatus) {
                            $msg = 'Giao dịch thành công';
                        } else {
                            $msg = 'Giao dịch thất bại';
                        }
                    } else {
                        $msg = 'Lưu thông tin vào bảng order_details bị lỗi';
                    }
                } else {
                    $msg = 'Lưu thông tin đơn hàng bị lỗi';
                }

                // xoá thông tin cart
            } else {
                $msg = 'Có lỗi khi lưu địa chỉ, vui lòng thử lại';
            }
        } else {
            $msg = 'Giỏ hảng trống';
        }
        return redirect()->route('checkout.index')->with('msg', $msg);
    }
}
