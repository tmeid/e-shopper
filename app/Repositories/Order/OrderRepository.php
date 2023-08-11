<?php 
namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface{
    public function getModel()
    {
        return Order::class;
    }
    public function getAllOrders($condition){
        return $this->model->where($condition)->orderBy('created_at', 'desc')->paginate(5);
    }

    public function countEachOrder($condition){
        return $this->model->where($condition)->count();
    }
    public function orderPaginate($request){

        // filter key hold data
        $search = null;
        $filterBy = null;
        $perPage = 10;

        if(!empty($request->search)){
            // validate $search later
            $search = $request->search;
            $search_pattern = str_replace('.', '', $search);
            $search_pattern =  str_replace(' ', '%', $search_pattern);

            $ordersQuery = $this->model->where(function($query) use ($search_pattern){
                $query->where('order_total', 'like', $search_pattern);
                $query->orWhere('id', $search_pattern);

            });
        }else{
            $ordersQuery = $this->model;
        }

        if(!empty($request->filter_by)){
            $filter_by = trim($request->filter_by);
            $filterBy = $filter_by;
            $query = $ordersQuery->where('order_status_id', $filter_by);
        }else{
            $query = $ordersQuery;
        }

        // sort by 
        $query = $query->orderBy('created_at', 'DESC');
        
        return [
                'orders' => $query->paginate($perPage)->withQueryString(),
                'search' => $search,
                'filterBy' => $filterBy
            ];
        
    }
}