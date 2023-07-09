<?php 
namespace App\Repositories\CartItem;

use App\Repositories\RepositoryInterface;

interface CartItemRepositoryInterface extends RepositoryInterface{
    public function updateData($data, $id);
    public function countItems($cart_id);
    public function getItemsInSameCart($condtion);
}