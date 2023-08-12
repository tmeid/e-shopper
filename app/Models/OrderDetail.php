<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Review;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_item_id', 'quantity', 'price'];
    public function review(){
        return $this->hasOne(Review::class, 'order_detail_id', 'id');
    }
    
}
