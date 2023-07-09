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

    public function getItemsPerUser($cart_id){
        return $this->model->find($cart_id)->productItems;
    }

    public function findByKeys($condition){
        return $this->model->where($condition)->first();
    }
    
    
}