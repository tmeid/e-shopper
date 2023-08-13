<?php

namespace App\Repositories\Review;

use App\Models\Review;
use App\Repositories\BaseRepository;

class ReviewRepository extends BaseRepository implements ReviewRepositoryInterface
{
    public function getModel()
    {
        return Review::class;
    }
    public function getReviews($request)
    {
        // filter key hold data
        $search = null;
        $filter = null;
        $perPage = 10;

        if (!empty($request->search)) {
            // validate $search later
            $search = $request->search;
            $search_pattern = str_replace('.', '', $search);
            $search_pattern =  str_replace(' ', '%', $search_pattern);

            $ordersQuery = $this->model->where(function ($query) use ($search_pattern) {
                $query->where('user_id', $search_pattern);
                $query->orWhere('rating', $search_pattern);
                $query->orWhere('message', 'like', "%$search_pattern%");
            })->orWhereHas('product', function($query) use ($search_pattern){
                $query->where('name', 'like', "%$search_pattern%");
            });
        } else {
            $ordersQuery = $this->model;
        }

        if (!empty($request->filter)) {
            $filter = trim($request->filter);
            $filter = $filter;
            $query = $ordersQuery->where('rating', $filter);
        } else {
            $query = $ordersQuery;
        }

        // sort by 
        $query = $query->orderBy('created_at', 'DESC');

        return [
            'reviews' => $query->paginate($perPage)->withQueryString(),
            'search' => $search,
            'filter' => $filter
        ];
    }
}
