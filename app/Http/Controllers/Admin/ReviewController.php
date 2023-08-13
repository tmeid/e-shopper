<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Review\ReviewRepository;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    //
    protected $reviewRepo;
    public function __construct(ReviewRepository $reviewRepo)
    {
        $this->reviewRepo = $reviewRepo;
    }
    public function index(Request $request){
        $reviewsResult = $this->reviewRepo->getReviews($request);
        $reviews = $reviewsResult['reviews'];
        $search = $reviewsResult['search'];
        $filter = $reviewsResult['filter'];
        return view('admin.review.index')->with(['reviews' => $reviews, 'search' => $search, 'filter' => $filter]);
    }
}
