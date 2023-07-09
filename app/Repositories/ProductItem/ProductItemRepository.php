<?php 
namespace App\Repositories\ProductItem;

use App\Models\ProductItem;
use App\Repositories\BaseRepository;
use App\Repositories\ProductItem\ProductItemRepositoryInterface;

class ProductItemRepository extends BaseRepository implements ProductItemRepositoryInterface{
    public function getModel(){
        return ProductItem::class;
    }

    public function getDistinctColor(){
        return $this->model->select('color')->distinct()->get();
    }

    public function productItemDetail($id, $color, $size){
        return $this->model->where([
                'product_id' => $id,
                'size' => $size,
                'color' => $color
            ])->first();
        
    }
}