<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductItem;
use App\Models\ProductImg;
use App\Models\Order;
use App\Models\ProductComment;

class Product extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name', 'brand_id', 'category_id', 'description', 'content', 'quantity', 'featured', 'qty_sold', 'price', 'discount'];
  

    public function brand(){
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function productComments(){
        return $this->hasMany(ProductComment::class, 'product_id', 'id');
    }
    public function productImgs(){
        return $this->hasMany(ProductImg::class, 'product_id', 'id');
    }

    public function orders(){
        return $this->belongsToMany(Order::class, 'order_details', 'product_id', 'order_id');
    }
    public function productItems(){
        return $this->hasMany(ProductItem::class, 'product_id', 'id');
    }
}
