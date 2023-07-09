<?php 
namespace App\Repositories\Brand;

use App\Models\Brand;
use App\Repositories\BaseRepository;
use App\Repositories\Brand\BrandRepoInterface;

class BrandRepo extends BaseRepository implements BrandRepoInterface{
    public function getModel(){
        return Brand::class;
    }
}