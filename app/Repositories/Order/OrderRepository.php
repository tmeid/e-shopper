<?php 
namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\BaseRepository;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface{
    public function getModel()
    {
        return Order::class;
    }
    public function getAllOrders($condition){
        return $this->model->where($condition)->get();
    }

    public function countEachOrder($condition){
        return $this->model->where($condition)->count();
    }
}