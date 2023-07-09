@extends('dashboard')

@section('title')
Thêm danh mục | E-shopper
@endsection

@section('sidebar')
@include('admin.sidebar')
@endsection

@section('content')

<h3>Danh sách các đơn hàng</h3>

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
            <td>{{ $order->order_total .' đ' }}</td>
            @if($order->order_status_id == 1)
            <td>Chờ xử lý</td>
            @elseif($order->order_status_id == 2)
            <td>Đang vận chuyển</td>
            @elseif($order->order_status_id == 3)
            <td>Đang giao</td>
            @elseif($order->order_status_id == 3)
            <td>Đã giao</td>
            else
            <td>Đã huỷ</td>
            @endif
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

        <!-- Button trigger modal -->




    </tbody>
</table>
@endsection