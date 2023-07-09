<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'quantity'
    ];
    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function orders(){
        return $this->belongsToMany(Order::class, 'order_details', 'product_item_id', 'order_id');
    }
}
