<?php 
namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\BaseRepository;
use App\Repositories\Category\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface{
    public function getModel(){
        return Category::class;
    }

    public function limit($limit){
        return $this->model->limit($limit)->get();
    }
    public function getValuesByKey($data, $key){
        return array_column(($data)->toArray(), $key);
    }

    public function getCategoriesWithCountProduct($relation){
        return $this->model::withCount($relation)->get();
    }

    public function getId($data){
       $category = $this->model->where($data)->first();
       if($category){
            return $category->id;
       }
       return null;
    }
    public function getCategories(){
        return $this->model->get();
    }
    
}