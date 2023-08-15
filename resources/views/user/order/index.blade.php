@extends('dashboard')

@section('title')
Thêm danh mục | E-shopper
@endsection

@section('sidebar')
@include('user.sidebar')
@endsection

@section('content')
@if(session('msg'))
<p class="alert alert-{{ session('type') }}" style="width: 300px; margin: 0 auto 10px;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    {{ session('msg') }}
</p>
@endif
<form action="" class="mb-3">
    <select class="form-control" name="filter_by" id="" onchange="this.form.submit()" style="width:initial">
        <option value="">Bộ lọc</option>
        <option value="1" {{ request('filter_by') === '1' ? 'selected' : '' }}>Chờ xử lý</option>
        <option value="2" {{ request('filter_by') === '2' ? 'selected' : '' }}>Đang vận chuyển</option>
        <option value="3" {{ request('filter_by') === '3' ? 'selected' : '' }}>Đang giao</option>
        <option value="4" {{ request('filter_by') === '4' ? 'selected' : '' }}>Đã giao</option>
    </select>
</form>

@if(count($myOrder))
@foreach($myOrder as $order)
<div class="border p-4 mb-3 order">
    <p class="font-weight-bold">{{ $order->orderStatus->status }}</p>
    <hr>
    @foreach($order->prodItems as $item)

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

    @endforeach
    <div class="d-flex">
        @if($order->order_status_id == 4)
        <a href="{{ route('user.order.index', ['order' => $order]) }}" class="text-decoration-none" style="width: 50%; display: block;">Đánh giá</a>
        @elseif($order->order_status_id == 1)
        <a href="{{ route('user.order.cancel', ['order' => $order]) }}" class="text-decoration-none" style="width: 50%; display: block;"
            onclick="return confirm('Bạn có chắc chắn huỷ đơn?')"
        >
            Huỷ đơn
        </a>
        @else 
        <div style="width: 50%">&nbsp</div>
        @endif
        <div style="width: 50%" >
            <p class="text-right font-weight-bold">Thành tiền: {{ number_format($order->order_total, 0, null, '.') .' đ' }}</p>
            <p class="text-right"><a class="text-decoration-none" href="{{ route('user.order.show', ['id' => $order->id]) }}">Chi tiết</a></p>
        </div>
    </div>
</div>
@endforeach
@else
<p>Chưa có đơn hàng</p>
@endif
<div class="col-12 pb-1 d-flex justify-content-center pt-3">
    {{ $myOrder->links() }}
</div>
@endsection