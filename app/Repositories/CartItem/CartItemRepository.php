<?php 
namespace App\Repositories\CartItem;

use App\Models\CartItem;
use App\Repositories\BaseRepository;

class CartItemRepository extends BaseRepository implements CartItemRepositoryInterface{
    public function getModel(){
        return CartItem::class;
    }
    public function findRecord($data){
        return $this->model->where($data)->first();
    }
    public function updateData($data, $id){
        return $this->edit($data, $id);
    }
    public function countItems($cart_id){
        return $this->model->where('cart_id', $cart_id)->count();
    }
    public function getItemsInSameCart($condtion){
        return $this->model->where($condtion)->get();
    }
    public function deleteItems($condtion){
        return $this->model->where($condtion)->delete();
    }

    
}