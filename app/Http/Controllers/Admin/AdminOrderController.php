<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepository;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    //
    protected $orderRepo;
    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function index(){
        $orders = $this->orderRepo->getAll();
        return view('admin.order.index')->with('orders', $orders);
    }

    public function detail($id){
        $order = $this->orderRepo->find($id);
        return view('admin.order.detail')->with('order', $order);
    }

    public function changeStatus(Request $request, $id){
        $status = $request->order_status_id;
        $this->orderRepo->edit(['order_status_id' => $status], $id);
        return redirect()->back();
    }
}
