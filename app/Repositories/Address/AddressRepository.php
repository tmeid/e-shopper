<?php 
namespace App\Repositories\Address;

use App\Models\Address;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

class AddressRepository extends BaseRepository implements RepositoryInterface{
    public function getModel(){
        return Address::class;
    }

    public function findId($condition){
        if($this->model->where($condition)->first()){
            return $this->model->where($condition)->first()->id;
        }
        return false;
    }
    public function findOrInsert($data){
        return $this->model->firstOrCreate($data);
    }
    public function getAddress(){
        return $this->model->get();
    }
}