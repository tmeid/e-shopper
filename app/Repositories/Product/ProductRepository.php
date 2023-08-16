<?php 
namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\BaseRepository;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface{
    public function getModel(){
        return Product::class;
    }

    public function getId($data){
        $product = $this->model->where($data)->first();
        if($product){
             return $product->id;
        }
        return null;
    }
    
    public function getVariationItems($product_id, $variation){
        $productItems = $this->model->find($product_id)->productItems;

        $result = [];
        foreach($productItems as $item){
            if(!in_array($item->$variation, $result)){
                $result[] = $item->$variation;
            }
        }
        return $result;
    }
    public function featuredProd(){
        return $this->model->where('featured', 1)->get();
    }

    public function commentsInfo($product_id){
        $comments = $this->model->find($product_id)->productComments;
        return $comments;
    }
    public function reviewsInfo($product_id){
        return $this->model->find($product_id)->reviews()->paginate(10);
    }

    public function relatedProducts($product_id, $product, $limit){
        return $this->model->where('category_id', $product->category_id)
            ->where('id', '!=', $product_id)->limit($limit)->get();
        
    }

    public function getFeaturedProduct($limit){
        return $this->model->where('featured', 1)->limit($limit)->get();
    }

    // use in soft delete: restore and force delete 
    public function getTrashedProduct($id){
        return $this->model->onlyTrashed()->find($id);
    }
    public function findWithTrashedProduct($id){
        return $this->model->withTrashed()->find($id);
    }

    public function productPaginate($request, $category_id = null){
        // filter key hold data
        $search = null;
        $categoryFilter = null;
        $sizeFilter = null;
        $colorFilter = null;
        $minPrice = null;
        $maxPrice = null;

        if(!empty($request->show) && filter_var($request->show, FILTER_VALIDATE_INT)){
            $perPage = trim($request->show);
        }else{
            $perPage = 8;
        }
        if(!empty($request->sort_by)){
            $sort_by = trim($request->sort_by);
        }else{
            $sort_by = 'latest';
        }

        if(!empty($request->search)){
            // validate $search later
            $search = trim($request->search);
            $search_pattern =  str_replace(' ', '%', $request->search);

            $productsQuery = $this->model->where('name', 'like', "%$search_pattern%");
        }else{
            $productsQuery = $this->model;
        }

        // category 
        if($category_id != null){
            $productsQuery = $productsQuery->where('category_id', $category_id);
        }

        // filter 
        // category 
        if(isset($request->category) && is_array($request->category) && count($request->category) > 0){
            $categoryFilter = $request->category;
            $productsQuery = $productsQuery->whereIn('category_id',  $categoryFilter);
        }

        // filter 
        // size 
        if(isset($request->size) && is_array($request->size) && count($request->size) > 0){
            $sizeFilter = $request->size;
            // $productsQuery = $productsQuery->productItems()->whereIn('size', $sizeFilter); 
            $productsQuery = $productsQuery->whereHas('productItems', function($query) use($sizeFilter){
                return $query->whereIn('size', $sizeFilter);
            });
        }

        // filter 
        // color 
        if(isset($request->color) && is_array($request->color) && count($request->color) > 0){
            $colorFilter = $request->color;
            // $productsQuery = $productsQuery->productItems()->whereIn('size', $sizeFilter); 
            $productsQuery = $productsQuery->whereHas('productItems', function($query) use($colorFilter){
                return $query->whereIn('color', $colorFilter);
            });
        }

        // filter 
        // price 
        if(!empty($request->minPrice) || !empty($request->maxPrice)){
            if(!empty($request->minPrice) && !empty($request->maxPrice)){
                $minPrice = $request->minPrice;
                $maxPrice = $request->maxPrice;
                $productsQuery = $productsQuery->where(DB::raw("IF(discount > 0, (1 - discount)*price, price)"), '>=', $minPrice)
                                                ->where(DB::raw("IF(discount > 0, (1 - discount)*price, price)"), '<=', $maxPrice);
            }elseif(!empty($request->minPrice)){
                $minPrice = $request->minPrice;
                $productsQuery = $productsQuery->where(DB::raw("IF(discount > 0, (1 - discount)*price, price)"), '>=', $minPrice);
            }else{
                $maxPrice = $request->maxPrice;
                $productsQuery = $productsQuery->where(DB::raw("IF(discount > 0, (1 - discount)*price, price)"), '<=', $maxPrice);
            }
        }
        
        // sort
        switch($sort_by){
            case 'latest':
                $query = $productsQuery->orderBy('created_at', 'desc');
                break;
            case 'price-asc':
                $query = $productsQuery->orderBy(DB::raw("IF(discount > 0, (1 - discount)*price, price)"), 'asc');
                break;
            case 'price-desc':
                $query = $productsQuery->orderBy(DB::raw("IF(discount > 0, (1 - discount)*price, price)"), 'desc');
                break;
            case 'name-asc':
                $query = $productsQuery->orderBy('name', 'asc');
                break;
            case 'name-desc':
                $query = $productsQuery->orderBy('name', 'desc');
                break;
            default:
                $query = $productsQuery->orderBy('id', 'asc');
        }
        
        return [
                'products' => $query->paginate($perPage)->withQueryString(),
                'search' => $search,
                'categoryFilter' => $categoryFilter,
                'sizeFilter' => $sizeFilter,
                'colorFilter' => $colorFilter,
                'minPrice' => $minPrice,
                'maxPrice' => $maxPrice
            ];
        
    }

    public function productAdminPaginate($request){
        // filter key hold data
        $search = null;
        $perPage = 10;

        if(!empty($request->search)){
            // validate $search later
            $search = trim($request->search);
            $search_pattern =  str_replace(' ', '%', $search);

            $productsQuery = $this->model->where(function($query) use ($search_pattern){
                $query->where('name', 'like', "%$search_pattern%");
                $query->orWhere('price', 'like', "%$search_pattern%");
                $query->orWhere('qty_sold', 'like', "%$search_pattern%");
                $query->orWhere('discount', 'like', "%$search_pattern%");

            });
        }else{
            $productsQuery = $this->model;
        }


        // sort by 
        if(!empty($request->sort_by)){
            $sort_by = trim($request->sort_by);
        }else{
            $sort_by = 'latest';
        }
        // sort
        switch($sort_by){
            case 'latest':
                $query = $productsQuery->orderBy('created_at', 'desc');
                break;
            case 'price-asc':
                $query = $productsQuery->orderBy('price', 'asc');
                break;
            case 'price-desc':
                $query = $productsQuery->orderBy('price', 'desc');
                break;
            case 'name-asc':
                $query = $productsQuery->orderBy('name', 'asc');
                break;
            case 'name-desc':
                $query = $productsQuery->orderBy('name', 'desc');
                break;
            default:
                $query = $productsQuery->orderBy('id', 'asc');
        }
        
        return [
                'products' => $query->withTrashed()->paginate($perPage)->withQueryString(),
                'search' => $search,
                'sort_by' => $sort_by
            ];
        
    }

}