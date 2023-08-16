<?php 
namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\BaseRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

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
    public function getLimitCategoriesWithCountProduct($relation, $limit){
        return $this->model->has('products')->withCount($relation)->limit($limit)->get();
    }

    public function getId($data){
       $category = $this->model->where($data)->first();
       if($category){
            return $category->id;
       }
       return null;
    }
    public function getCategories($request){
        $search = null;
        if(!empty($request->search)){
            $search = trim($request->search);
            $search_pattern = str_replace(' ', '%', $search);
            $query = $this->model->where('name', 'like', "%$search_pattern%");
        }else{
            $query = $this->model;
        }
        // sort 
        $query = $query->orderBy('created_at', 'DESC');
        return $query->get();
    }

    public function getCategoriesAtLeastOneProduct(){
        return $this->model->has('products')->get();
    }
    
}