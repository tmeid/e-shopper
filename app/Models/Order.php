<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentMethod;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\Address;
use App\Models\ProductItem;
use App\Models\User;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'payment_method_id',
        'order_total',
        'order_status_id'
    ];
    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }

    public function orderStatus(){
        return $this->belongsTo(OrderStatus::class, 'order_status_id', 'id');
    }

    // public function productItems(){
    //     return $this->belongsToMany(Product::class, 'order_details', 'order_id', 'product_id');
    // }

    public function address(){
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }

    public function orderDetails(){
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function prodItems(){
        return $this->belongsToMany(ProductItem::class, 'order_details', 'order_id', 'product_item_id')->withPivot('price', 'quantity', 'id')->withTrashed();
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    
}
