<?php 
namespace App\Repositories\Product;

use App\Repositories\RepositoryInterface;

interface ProductRepositoryInterface extends RepositoryInterface{
    public function getVariationItems($product_id, $variation);
    public function commentsInfo($product_id);
    // product detail page
    public function relatedProducts($product_id, $product, $limit);

    // home
    // featured product 
    public function getFeaturedProduct($category_id, $limit);

    // products: paginate 
    public function productPaginate($request, $category_id);
}