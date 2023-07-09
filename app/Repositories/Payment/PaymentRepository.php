<?php 
namespace App\Repositories\Payment;

use App\Models\PaymentMethod;
use App\Repositories\BaseRepository;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface{
    public function getModel(){
        return PaymentMethod::class;
    }
}