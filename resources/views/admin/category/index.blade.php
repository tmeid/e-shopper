@extends('dashboard')

@section('title')
Thêm danh mục | E-shopper
@endsection

@section('dashboard-type')
Admin Dashboard
@endsection

@section('sidebar')
@include('admin.sidebar')
@endsection

@section('content')


<div class="container-fluid">
    <h2>QUẢN LÝ ĐƠN HÀNG</h2>
    @if(count($orders))
        <p><span>1. Chờ xử lý: {{ $orders->where('order_status_id', 1)->count() }}</span></p>
        <p><span>2. Đang vận chuyển: {{ $orders->where('order_status_id', 2)->count() }}</span></p>
        <p><span>2. Đang giao: {{ $orders->where('order_status_id', 3)->count() }}</span></p>
        <p><span>3. Đã giao: {{ $orders->where('order_status_id', 4)->count() }}</span></p>
    @else 
    <p>Khách chưa mua đơn nào</p>
    @endif
</div>


@endsection