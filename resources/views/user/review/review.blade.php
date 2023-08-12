@extends('dashboard')

@section('title')
Đánh giá sản phẩm | E-shopper
@endsection

@section('sidebar')
@include('user.sidebar')
@endsection
@section('css')
<style>
    /* Rating */

    .rate {
        float: left;
        height: 46px;
    }

    .rate:not(:checked)>input {
        display: none;
    }

    .rate:not(:checked)>label {
        float: right;
        width: 1em;
        overflow: hidden;
        white-space: nowrap;
        cursor: pointer;
        font-size: 30px;
        color: #ccc;
    }

    .rate:not(:checked)>label:before {
        content: '★ ';
    }

    .rate>input:checked~label {
        color: #ffc700;
    }

    .rate:not(:checked)>label:hover,
    .rate:not(:checked)>label:hover~label {
        color: #deb217;
    }

    .rate>input:checked+label:hover,
    .rate>input:checked+label:hover~label,
    .rate>input:checked~label:hover,
    .rate>input:checked~label:hover~label,
    .rate>label:hover~input:checked~label {
        color: #c59b08;
    }
    .rating-error{
        display: inline-block;
        height: 46px;
        line-height: 46px;
    }
</style>
@endsection

@section('content')

<div class="border p-4 order">
    <div class="d-flex align-items-center mb-4">
        <div class="d-flex item-info" style="width: 70%">
            <div class="d-flex align-items-center mr-3 item-info-left" style="width: 100px;">
                <a href="{{ route('product.detail', ['product' => $item->product->slug]) }}"><img src="{{ asset('imgs/products/' .$item->product->productImgs->first()->path) }}" alt="{{ $item->product->name }}" style="max-width: 100%; width:100%; display: block;"></a>
            </div>
            <div class="item-info-right">
                <p><a class="text-decoration-none" href="{{ route('product.detail', ['product' => $item->product->slug]) }}">{{ $item->product->name }}</a></p>
                <p>Phân loại: {{ $item->size }}, {{ $item->color }} </p>
            </div>
        </div>
    </div>
    <form action="" method="POST">
        @csrf
        <h5 class="mb-3 text-uppercase">Đánh giá sản phẩm</h5>
        
        @error('rating')
        <span class="text-danger rating-error">{{ $message }}</span>
        @enderror
        <div class="rate">
            <input type="radio" id="star5" name="rating" value="5" />
            <label for="star5" title="text">5 stars</label>
            <input type="radio" id="star4" name="rating" value="4" />
            <label for="star4" title="text">4 stars</label>
            <input type="radio" id="star3" name="rating" value="3" />
            <label for="star3" title="text">3 stars</label>
            <input type="radio" id="star2" name="rating" value="2" />
            <label for="star2" title="text">2 stars</label>
            <input type="radio" id="star1" name="rating" value="1" />
            <label for="star1" title="text">1 star</label>
        </div>

        <textarea cols="30" rows="10" class="form-control mb-2" placeholder="Đánh giá" name="review"></textarea>
        @error('review')
        <span class="text-danger d-block">{{ $message }}</span>
        @enderror
        <button class="btn btn-success" type="submit">Gửi</button>
    </form>
</div>
@endsection