@extends('dashboard')

@section('title')
Thêm danh mục | E-shopper
@endsection

@section('sidebar')
@include('user.sidebar')
@endsection

@section('content')

<h3>Danh sách các đơn hàng</h3>
<div style="overflow-x:auto;">
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
                <td>{{ number_format($order->order_total, 0, null, '.') .' đ' }}</td>
                <td>{{ $order->orderStatus->status }}</td>
                <td><a href="{{ route('user.order.show', ['id' => $order->id]) }}">Chi tiết</a></td>

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
    {{ $myOrder->links() }}
</div>
@endsection