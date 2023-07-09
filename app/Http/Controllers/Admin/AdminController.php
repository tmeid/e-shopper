<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepository;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    protected $orderRepo;
    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }
    public function index(){
        $orders = $this->orderRepo->getAll();
        return view('admin.category.index')->with('orders', $orders);
    }
}
