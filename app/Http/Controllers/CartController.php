<?php

namespace App\Http\Controllers;

use App\Repositories\Cart\CartRepository;
use App\Repositories\CartItem\CartItemRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\ProductItem\ProductItemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Ui\Presets\React;

class CartController extends Controller
{
    //
    protected $productItemRepo;
    protected $cartRepository;
    protected $cartItemRepo;
    protected $categoryRepo;

    public function __construct(
        ProductItemRepository $productItemRepo,
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepo,
        CategoryRepository $categoryRepo
    ) {
        $this->productItemRepo = $productItemRepo;
        $this->cartRepository = $cartRepository;
        $this->cartItemRepo = $cartItemRepo;
        $this->categoryRepo = $categoryRepo;
    }

    public function index()
    {
        $categories = $this->categoryRepo->getAll();

        // count the number of items per user in cart_items table
        $total_items_order =  countItemsCartEachUser($this->cartRepository, $this->cartItemRepo);

        $user_id = Auth::user()->id;
        $cart_info = $this->cartRepository->findByKey('user_id', $user_id);

        if (!empty($cart_info->id)) {
            $cart_id = $cart_info->id;
            $totalPrice = 0;

            $items_order = $this->cartRepository->getItemsPerUser($cart_id);

            foreach ($items_order as $item_order) {
                if ($item_order->noTrashedProduct->discount > 0) {
                    $price = (1 - $item_order->noTrashedProduct->discount) * ($item_order->noTrashedProduct->price) * ($item_order->pivot->quantity);
                } else {
                    $price = ($item_order->noTrashedProduct->price) * ($item_order->pivot->quantity);
                }
                $totalPrice += $price;
            }
            return view('layouts.cart')->with([
                'categories' => $categories,
                'total_items_order' => $total_items_order,
                'items_order' => $items_order,
                'totalPrice' => $totalPrice
            ]);
        }


        // save items_order to session

        return view('layouts.cart')->with([
            'categories' => $categories,
            'total_items_order' => $total_items_order,
            'items_order' => null,
            'totalPrice' => null
        ]);
    }
    public function add(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::check()) {
                $validator = Validator::make($request->all(), [
                    'size' => 'required',
                    'color' => 'required'
                ], [
                    'size.required' => 'Chưa chọn size. ',
                    'color.required' => 'Chưa chọn màu. '
                ]);

                if ($validator->fails()) {
                    return response()->json(['status' => 'validate fails', 'errors' => $validator->errors()->toArray()]);
                } else {
                    // input: user_id, 
                    // quantity
                    // (size, color) => find out id of product_item_id (product_item_id != product_id, 1 product_id has many product_item_id)
                    // insert user to carts, cart_items
                    $user_id = Auth::user()->id;
                    $product_id = $request->product_id;
                    $color = $request->color;
                    $size = $request->size;
                    $quantity = (int)$request->quantity;

                    // double check quatity of item (size, color)
                    $productItemQtyStock = $this->productItemRepo->productItemDetail($product_id, $color, $size);

                    if ($productItemQtyStock && $productItemQtyStock->quantity > 0 && $productItemQtyStock->quantity >= $quantity) {
                        if ($this->cartRepository->findByKey('user_id', $user_id)) {
                            $cart_id = $this->cartRepository->findByKey('user_id', $user_id)->id;
                        } else {
                            $cart_id = $this->cartRepository->insertReturnId(['user_id' => $user_id]);
                        }

                        // find record item exist in cart_items table: 
                        // if exist: update quantity
                        $product_item_id = $productItemQtyStock->id;
                        $cart_item = $this->cartItemRepo->findRecord(['cart_id' => $cart_id, 'product_item_id' => $product_item_id]);

                        $flag_items_cart_increase = false;
                        if ($cart_item) {
                            $cart_item_id = $cart_item->id;
                            $cart_item_quantity = $cart_item->quantity + $quantity;
                            $this->cartItemRepo->updateData(['quantity' => $cart_item_quantity], $cart_item_id);
                        } else {
                            $this->cartItemRepo->create(['cart_id' => $cart_id, 'product_item_id' => $product_item_id, 'quantity' => $quantity]);
                            $flag_items_cart_increase = true;
                        }


                        return response()->json([
                            'status' => 'success',
                            'msg' => 'Thêm vào giỏ hàng thành công',
                            'flag_increase' => $flag_items_cart_increase
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'invalid-credentials'
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'status' => 'unauthorized',
                    'redirect_uri' => route('login')
                ]);
            }
        }
    }
    public function update(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::check()) {
                $user_id = Auth::user()->id;
                $cart_items_id = $request->cart_items_id;
                $prod_qty = $request->qty;

                if ($this->cartRepository->findByKey('user_id', $user_id)) {
                    $cart_id = $this->cartRepository->findByKey('user_id', $user_id)->id;
                    if ($this->cartItemRepo->findRecord(['cart_id' => $cart_id, 'id' => $cart_items_id])) {
                        $this->cartItemRepo->edit(['quantity' => $prod_qty], $cart_items_id);
                        return response()->json(['status' => 'update qty successfully']);
                    }
                    return response()->json(['status' => 'that bai']);
                }
                return response()->json(['status' => 'unauthorized']);
            }
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::check()) {
                $user_id = Auth::user()->id;
                $cart_items_id = $request->cart_items_id;

                if ($this->cartRepository->findByKey('user_id', $user_id)) {
                    $cart_id = $this->cartRepository->findByKey('user_id', $user_id)->id;
                    if ($this->cartItemRepo->findRecord(['cart_id' => $cart_id, 'id' => $cart_items_id])) {
                        $this->cartItemRepo->delete($cart_items_id);
                        return response()->json(['status' => 'success']);
                    }
                    return response()->json(['status' => 'delete fail']);
                }
                return response()->json(['status' => 'unauthorized']);
            }
        }
    }
}
