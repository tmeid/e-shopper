@extends('dashboard')

@section('title')
Đơn hàng | E-shopper
@endsection

@section('sidebar')
@include('user.sidebar')
@endsection
@section('css')

@endsection

@section('content')
@if(session('msg'))
<p class="alert alert-{{ session('type') }}" style="width: 300px; margin: 0 auto 10px;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    {{ session('msg') }}
</p>
@endif
@foreach($order->prodItems as $item)
<div class="border p-4 mb-3 order">
    <div class="d-flex align-items-center">
        <div class="d-flex item-info" style="width: 70%">
            <div class="d-flex align-items-center mr-3 item-info-left" style="width: 100px;">
                <a href="{{ route('product.detail', ['product' => $item->product->slug]) }}"><img src="{{ asset('imgs/products/' .$item->product->productImgs->first()->path) }}" alt="{{ $item->product->name }}" style="max-width: 100%; width:100%; display: block;"></a>
            </div>
            <div class="item-info-right">
                <p><a class="text-decoration-none" href="{{ route('product.detail', ['product' => $item->product->slug]) }}">{{ $item->product->name }}</a></p>
                <p class="item-detailt">Phân loại: {{ $item->size }}, {{ $item->color }} </p>
                <p class="item-quantity">x{{ $item->pivot->quantity }}</p>
                <p class="mobile-view">{{ $item->pivot->quantity }} sản phẩm | {{ number_format($item->pivot->price, 0, null, '.') .' đ' }}</p>
            </div>
        </div>
        <div style="width: 30%" class="price">
            <p class="text-right">
                @if($item->product->discount)
                <del class="font-weight-light text-muted">{{ number_format($item->product->price, 0, null, '.') . ' đ' }}</del>
                @endif
                {{ number_format($item->pivot->price, 0, null, '.') .' đ' }}
            </p>
        </div>
    </div>
    <hr>
    
    @if(!isReviewed($item->pivot->id))
    <a href="{{ route('user.order.review', ['order' => $order, 'productItem' => $item]) }}" class="text-decoration">Đánh giá sản phẩm này</a>
    @else
    <p>Đã được đánh giá</p>
    @endif
</div>
@endforeach

@endsection