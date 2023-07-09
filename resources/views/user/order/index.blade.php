@extends('dashboard')

@section('title')
Thêm danh mục | E-shopper
@endsection

@section('sidebar')
@include('user.sidebar')
@endsection

@section('content')

<h3>Danh sách các đơn hàng</h3>

<table class="table table-bordered" style="color:#000">
    <thead>
        <tr>
            <th>#id</th>
            <th>Tổng bill</th>
            <th>Trạng thái</th>
            <th>Xem</th>
        </tr>
    </thead>
    <tbody>
        @if(count($myOrder))
        @foreach($myOrder as $order)
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
            <td><a href="{{ route('user.order.show', ['id' => $order->id]) }}">Detail</a></td>

        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="2">Dữ liệu trống</td>
        </tr>
        @endif

    </tbody>
</table>
@endsection