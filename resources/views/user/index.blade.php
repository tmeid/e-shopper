@extends('dashboard')

@section('title')
Thêm danh mục | E-shopper
@endsection


@section('sidebar')
@include('user.sidebar')
@endsection

@section('content')
@if(session('msg'))
<p class="alert alert-{{ session('type') }}">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    {{ session('msg') }}
</p>
@endif

<div class="container-fluid">
    <h2>ĐƠN HÀNG CỦA BẠN</h2>
    @if(count($myOrder))
        <p><span>1. Chờ xử lý: {{ $myOrder->where('order_status_id', 1)->count() }}</span></p>
        <p><span>2. Đang vận chuyển: {{ $myOrder->where('order_status_id', 2)->count() }}</span></p>
        <p><span>2. Đang giao: {{ $myOrder->where('order_status_id', 3)->count() }}</span></p>
        <p><span>3. Đã giao: {{ $myOrder->where('order_status_id', 4)->count() }}</span></p>
    @else 
    <p>Bạn chưa có đơn hàng nào</p>
    @endif
</div>

@endsection