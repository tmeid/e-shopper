<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    protected $orderRepo;

    public function __construct(OrderRepository $orderRepo)
    {
       $this->orderRepo = $orderRepo; 
    }
    public function index(){
        $user_id = Auth::user()->id;
        $myOrder = $this->orderRepo->getAllOrders(['user_id' => $user_id]);
        return view('user.index')->with('myOrder', $myOrder);
    }

    public function show(){
        $user_id = Auth::user()->id;
        $myOrder = $this->orderRepo->getAllOrders(['user_id' => $user_id]);
        return view('user.order.index')->with('myOrder', $myOrder);
    }

    public function detailOrder($id){
        $order = $this->orderRepo->find($id);
        return view('user.order.show')->with('order', $order);
    }
}
