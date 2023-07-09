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
                    <label for="" class="col-md-3 text-md-right col-form-label">id</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ $order->id }}</p>
                    </div>
                </div>


                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Sản phẩm:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">

                        @foreach($order->prodItems as $prodItem)
                        <p>
                        {{ $prodItem->product->name .': ' .$prodItem->pivot->price .' x ' .$prodItem->pivot->quantity  }}
                        </p>
                        @endforeach

                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Tổng tiền</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ $order->order_total .' đ' }}</p>
                    </div>
                </div>



             


            </div>
        </div>
    </div>
</div>
@endsection