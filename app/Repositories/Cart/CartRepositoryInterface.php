<?php 
namespace App\Repositories\Cart;

use App\Repositories\RepositoryInterface;

interface CartRepositoryInterface extends RepositoryInterface{
    public function insertReturnId($data);
    public function findByKey($key, $value);
    public function getItemsPerUser($cart_id);
    public function findByKeys($condition);
}