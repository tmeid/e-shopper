<?php 
namespace App\Repositories;

abstract class BaseRepository implements RepositoryInterface{
    protected $model;

    public function __construct(){
      $this->model = app()->make($this->getModel());  
    }

    abstract function getModel();
    public function getAll(){
        return $this->model->all();
    }
    public function find($id){
        return $this->model->find($id);
    }
    public function create($data = []){
        return $this->model->create($data);
    }
    public function edit($data = [], $id){
        $item = $this->model->find($id);
        if($item){
            return $item->update($data);
        }
        return false;
    }
    public function delete($id){
        if($this->find($id)){
            return $this->model->destroy($id);
        }
        return false;
    }
}