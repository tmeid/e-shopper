@extends('dashboard')

@section('title')
Thêm danh mục | E-shopper
@endsection

@section('sidebar')
@include('admin.sidebar')
@endsection

@section('content')

<h3>Quản lý các đơn hàng</h3>

<div class="row">
    <form action="" class="col-lg-5 col-md-6 mb-3">
        <div class="input-group">
            <input name="search" type="text" class="form-control" placeholder="Tìm..." value="{{ request('search') }}">
            <div class="input-group-append">
                <button type="submit" class="input-group-text bg-transparent text-primary">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<form action="" class="mb-3">
    <select class="form-control" name="filter_by" id="" onchange="this.form.submit()" style="width:initial">
        <option value="">Bộ lọc</option>
        <option value="1" {{ request('filter_by') === '1' ? 'selected' : '' }}>Chờ xử lý</option>
        <option value="2" {{ request('filter_by') === '2' ? 'selected' : '' }}>Đang vận chuyển</option>
        <option value="3" {{ request('filter_by') === '3' ? 'selected' : '' }}>Đang giao</option>
        <option value="4" {{ request('filter_by') === '4' ? 'selected' : '' }}>Đã giao</option>
        <option value="5" {{ request('filter_by') === '5' ? 'selected' : '' }}>Đã huỷ</option>
    </select>
</form>
<div style="overflow-x:auto;">
    <table class="table table-bordered" style="color:#000">
        <thead>
            <tr>
                <th>#id</th>
                <th>Tổng bill</th>
                <th>Trạng thái</th>
                <th>Đổi trạng thái</th>
                <th>Xem</th>
            </tr>
        </thead>
        <tbody>
            @if(count($orders))
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ number_format($order->order_total, 0, null, '.') .' đ' }}</td>
                <td>{{ $order->orderStatus->status }}</td>
                <td>
                    <form action="{{ route('admin.order.changeStatus', ['id' => $order->id] )}}" method="POST">
                        @csrf

                        <select name="order_status_id" id="">
                            <option value="1" {{$order->order_status_id == 1 ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="2" {{$order->order_status_id == 2 ? 'selected' : '' }}>Đang vận chuyển</option>
                            <option value="3" {{$order->order_status_id == 3 ? 'selected' : '' }}>Đang giao</option>
                            <option value="4" {{$order->order_status_id == 4 ? 'selected' : '' }}>Đã giao</option>
                            <option value="5" {{$order->order_status_id == 5 ? 'selected' : ''  }}>Đã huỷ</option>
                        </select>
                        <input type="submit" name="submit">

                </td>
                <td><a href="{{ route('admin.order.detail', ['id' => $order->id])}}">Chi tiết</a></td>

            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="2">Dữ liệu trống</td>
            </tr>
            @endif

        </tbody>
    </table>
</div>
<div class="col-12 pb-1 d-flex justify-content-center pt-3">
    {{$orders->links() }}
</div>
@endsection