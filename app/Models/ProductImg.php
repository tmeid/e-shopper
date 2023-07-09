<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductImg extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'path'];
    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
