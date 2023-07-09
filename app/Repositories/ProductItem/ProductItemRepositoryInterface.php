<?php 
namespace App\Repositories\ProductItem;

use App\Repositories\RepositoryInterface;

interface ProductItemRepositoryInterface extends RepositoryInterface{
    public function getDistinctColor();
    public function productItemDetail($id, $color, $size);

}

