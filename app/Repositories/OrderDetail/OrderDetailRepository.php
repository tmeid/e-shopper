<?php 
namespace App\Repositories\OrderDetail;

use App\Models\OrderDetail;
use App\Repositories\BaseRepository;

class OrderDetailRepository extends BaseRepository implements OrderDetailRepositoryInterface{
    public function getModel()
    {
        return OrderDetail::class;
    }
    public function getOrderDetail($condition){
        return $this->model->where($condition)->first();
    }
    public function getOrderDetails($condition){
        return $this->model->where($condition)->get();
    }
}