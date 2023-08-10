<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Address extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['name', 'address', 'phone', 'user_id'];

    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function orders(){
        return $this->hasMany(Order::class, 'address_id', 'id')->withPivot('is_default');
    }


}
