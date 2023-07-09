<?php 
use Illuminate\Support\Facades\Auth;
function salePrice($discount, $price){
    return number_format((1 - $discount)*$price, 0, null, ',');
}

function countItemsCartEachUser($cartRepo, $cartItemRepo){
    if(Auth::check()){
        $user_id = Auth::user()->id;
        $user_in_cart = $cartRepo->findUserInCart(['user_id'=> $user_id]);
        if($user_in_cart){
            $cart_id = $user_in_cart->id;
            $items_order = $cartItemRepo->countItems($cart_id);
        }else{
            $items_order = 0;
        }
    }else{
        $items_order = 0;
    }
    return $items_order;
}