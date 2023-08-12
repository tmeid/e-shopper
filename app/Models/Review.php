<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDetail;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id', 
        'order_detail_id', 
        'user_id',
        'rating',
        'message'
    ];

    public function orderDetail(){
        return $this->belongsTo(OrderDetail::class, 'order_detail_id', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
