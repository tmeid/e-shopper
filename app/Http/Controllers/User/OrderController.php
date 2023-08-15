<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class OrderController extends Controller
{
    //
    protected $orderRepo;

    public function __construct(OrderRepository $orderRepo)
    {
       $this->orderRepo = $orderRepo; 
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
}
