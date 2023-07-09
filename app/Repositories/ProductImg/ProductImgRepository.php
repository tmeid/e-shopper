<?php 
namespace App\Repositories\ProductImg;

use App\Models\ProductImg;
use App\Repositories\BaseRepository;
use App\Repositories\ProductImg\ProductImgRepositoryInterface;

class ProductImgRepository extends BaseRepository implements ProductImgRepositoryInterface{
    public function getModel(){
        return ProductImg::class;
    }
}