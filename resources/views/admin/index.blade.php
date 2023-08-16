@extends('dashboard')

@section('title')
Dashboard | E-shopper
@endsection

@section('dashboard-type')
Admin Dashboard
@endsection

@section('sidebar')
@include('admin.sidebar')
@endsection

@section('content')


<div class="container-fluid">
    <h2 class="mb-3">QUẢN LÝ ĐƠN HÀNG</h2>
    @if(count($orders))
        <p><span>1. Chờ xử lý: {{ $orders->where('order_status_id', 1)->count() }}</span></p>
        <p><span>2. Đang vận chuyển: {{ $orders->where('order_status_id', 2)->count() }}</span></p>
        <p><span>2. Đang giao: {{ $orders->where('order_status_id', 3)->count() }}</span></p>
        <p><span>3. Đã giao: {{ $orders->where('order_status_id', 4)->count() }}</span></p>
        <p><span>3. Đơn huỷ: {{ $orders->where('order_status_id', 5)->count() }}</span></p>
        <p><span>3. Lỗi: {{ $orders->where('order_status_id', 6)->count() }}</span></p>
    @else 
    <p>Khách chưa mua đơn nào</p>
    @endif
</div>


@endsection