@extends('dashboard')

@section('title')
Thêm danh mục | E-shopper
@endsection

@section('sidebar')
@include('user.sidebar')
@endsection

@section('content')

@if(count($myOrder))
@foreach($myOrder as $order)
<div class="border p-4 mb-3 order">
    <p class="font-weight-bold">{{ $order->orderStatus->status }}</p>
    <hr>
    @foreach($order->prodItems as $item)

    <div class="d-flex align-items-center">
        <div class="d-flex item-info" style="width: 70%">
            <div class="d-flex align-items-center mr-3 item-info-left" style="width: 100px;">
                <a href="{{ route('product.detail', ['product' => $item->noTrashedProduct->slug]) }}"><img src="{{ asset('imgs/products/' .$item->noTrashedProduct->productImgs->first()->path) }}" alt="{{ $item->noTrashedProduct->name }}" style="max-width: 100%; width:100%; display: block;"></a>
            </div>
            <div class="item-info-right">
                <p><a class="text-decoration-none" href="{{ route('product.detail', ['product' => $item->noTrashedProduct->slug]) }}">{{ $item->noTrashedProduct->name }}</a></p>
                <p class="item-detailt">Phân loại: {{ $item->size }}, {{ $item->color }} </p>
                <p class="item-quantity">x{{ $item->pivot->quantity }}</p>
                <p class="mobile-view">{{ $item->pivot->quantity }} sản phẩm | {{ number_format($item->pivot->price, 0, null, '.') .' đ' }}</p>
            </div>
        </div>
        <div style="width: 30%" class="price">
            <p class="text-right">
                @if($item->noTrashedProduct->discount)
                <del class="font-weight-light text-muted">{{ number_format($item->noTrashedProduct->price, 0, null, '.') . ' đ' }}</del> 
                @endif
                {{ number_format($item->pivot->price, 0, null, '.') .' đ' }}
            </p>
        </div>
    </div>
    <hr>

    @endforeach
    <div class="d-flex">
        <a href="" class="text-decoration-none" style="width: 50%; display: block;">Đánh giá</a>
        <div style="width: 50%" >
            <p class="text-right font-weight-bold">Thành tiền: {{ number_format($order->order_total, 0, null, '.') .' đ' }}</p>
            <p class="text-right"><a class="text-decoration-none" href="{{ route('user.order.show', ['id' => $order->id]) }}">Chi tiết</a></p>
        </div>
    </div>
</div>
@endforeach
@endif
<div class="col-12 pb-1 d-flex justify-content-center pt-3">
    {{ $myOrder->links() }}
</div>
@endsection