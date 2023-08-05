<?php 
namespace App\Repositories\Brand;

use App\Models\Brand;
use App\Repositories\BaseRepository;
use App\Repositories\Brand\BrandRepoInterface;

class BrandRepo extends BaseRepository implements BrandRepoInterface{
    public function getModel(){
        return Brand::class;
    }
    public function getBrands($request){
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
}