<?php 
namespace App\Repositories\Category;

use App\Repositories\RepositoryInterface;

interface CategoryRepositoryInterface extends RepositoryInterface{
    public function limit($limit);
    public function getValuesByKey($data, $key);
    public function getId($data);
}