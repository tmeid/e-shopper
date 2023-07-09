<?php 
namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface{
    public function getModel(){
        return User::class;
    }
    public function getAddresses($user_id){
        return $this->model->find($user_id)->addresses;
        
    }
}