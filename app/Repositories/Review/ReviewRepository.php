<?php 
namespace App\Repositories\Review;

use App\Models\Review;
use App\Repositories\BaseRepository;

class ReviewRepository extends BaseRepository implements ReviewRepositoryInterface{
    public function getModel(){
        return Review::class;
    }
    

}