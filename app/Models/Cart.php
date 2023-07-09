<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductItem;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = ['user_id'];

    public function productItems(){
        return $this->belongsToMany(ProductItem::class, 'cart_items', 'cart_id', 'product_item_id')
                    ->withPivot('quantity')->withPivot('id');
    }

}
