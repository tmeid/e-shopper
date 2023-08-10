@extends('dashboard')

@section('title')
Chi tiết đơn hàng | E-shopper
@endsection

@section('sidebar')
@include('user.sidebar')
@endsection

@section('dashboard-type')
Chi tiết đơn hàng
@endsection


@section('content')

<div class="row">
    <div class="col-md-12" style="color:black">
        <div class="main-card mb-3 card">
            <div class="card-body display_data">


                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">id:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ $order->id }}</p>
                    </div>
                </div>


                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Sản phẩm:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">

                        @foreach($order->prodItems as $prodItem)
                        <p>
                        {{ $prodItem->noTrashedProduct->name .' (size ' .$prodItem->size .', màu ' .$prodItem->color  .')' .': ' .$prodItem->pivot->price .' x ' .$prodItem->pivot->quantity .' cái'  }}
                        </p>
                        @endforeach

                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Tổng tiền:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ number_format($order->order_total, 0, null, '.') .' đ' }}</p>
                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Người đặt hàng:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ $order->user->name }}</p>
                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Địa chỉ:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ $order->address }}</p>
                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Số điện thoại:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ $order->user->phone }}</p>
                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Hình thức thanh toán:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ $order->paymentMethod->name }}</p>
                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Ngày đặt hàng:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ date('d-m-Y H:i:s', strtotime($order->created_at)) }}</p>
                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Trạng thái đơn hàng:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ $order->orderStatus->status }}</p>
                    </div>
                </div>



             


            </div>
        </div>
    </div>
</div>
@endsection