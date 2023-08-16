<?php 
namespace App\Repositories\Cart;

use App\Repositories\BaseRepository;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Models\Cart;

class CartRepository extends BaseRepository implements CartRepositoryInterface{
    public function getModel(){
        return Cart::class;
    }
    public function insertReturnId($data){
        $cart = $this->model->create($data);
        return $cart->id;
    }
    public function findByKey($key, $value){
        return $this->model->where($key, $value)->first();
    }
    public function findUserInCart($data){
        return $this->model->where($data)->first();
    }
    // lẩy ra các items trong cart của 1 user nhưng đã subquery với bảng product_items
    public function getItemsPerUser($cart_id){
        return $this->model->find($cart_id)->productItems;
    }

    public function getItemsWithTrashedPerUser($cart_id){
        return $this->model->find($cart_id)->productItemsWithTrashed;
    }

    public function findByKeys($condition){
        return $this->model->where($condition)->first();
    }
    
    
}