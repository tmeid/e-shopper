@extends('master')

@section('title')
Thanh toán | E-Shopper
@endsection

@section('content')
<!-- Cart Start -->
<div class="container-fluid pt-2">
    <div class="row px-xl-5">

        <div class="col table-responsive mb-5 text-center">
            <h2 class="text-center mb-3"> THANH TOÁN</h2>
            @if(session('msg'))
            <span style="margin: 0 auto;" class="alert alert-danger inline-block">{{ session('msg') }}</span>

            @endif
            <table class="table table-bordered text-center mb-0" style="color:#000">
                <thead class="bg-secondary text-dark">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @if(count($items_order))
                    @foreach($items_order as $item_order)
                    <tr class="product_data">
                        <input type="hidden" class="qty-stock-{{$item_order->id}}" value="{{ $item_order->quantity }}">
                        <input type="hidden" class="cart_items_id" value="{{ $item_order->pivot->id }}">
                        <td class="align-items-start d-flex flex-wrap">
                            <img src="{{ asset('imgs/products/' .$item_order->noTrashedProduct->productImgs->first()->path) }}" alt="{{ $item_order->noTrashedProduct->name }}" style="width: 50px; display: block;">
                            <span class="mt-1 ml-2"><a style="color:#000" class="text-decoration-none" href="{{ route('product.detail', ['product' => $item_order->noTrashedProduct->id ])}}">{{ $item_order->noTrashedProduct->name }} ({{ 'Size: ' .$item_order->size .', màu: ' .$item_order->color }})</a></span>
                        </td>
                        <td class="align-middle">
                            @if($item_order->noTrashedProduct->discount > 0)
                            <span class="ml-2 inline-block price-{{$item_order->id}}">₫{{ number_format((1- $item_order->noTrashedProduct->discount)*($item_order->noTrashedProduct->price), 0, null, '.')}}</span>
                            @else
                            <span class="price-{{$item_order->id}}">₫{{ number_format(($item_order->noTrashedProduct->price), 0, null, '.') }}</span>
                            @endif

                        </td>
                        <td class="align-middle">
                            <div class="input-group quantity mx-auto" style="width: 100px;">
                                <input type="text" id="{{$item_order->id }}" class="form-control form-control-sm bg-secondary text-center" value="{{ $item_order->pivot->quantity }}">
                            </div>
                        </td>
                        <td class="align-middle cash-{{ $item_order->id }} subtotal">
                            @if($item_order->noTrashedProduct->discount > 0)
                            ₫{{ number_format((1- $item_order->noTrashedProduct->discount)*($item_order->noTrashedProduct->price)*($item_order->pivot->quantity), 0, null, '.')}}
                            @else
                            ₫{{ number_format(($item_order->noTrashedProduct->price)*($item_order->pivot->quantity), 0, null, '.') }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>

    </div>
    <div class="row px-xl-5">
        <div class="col-lg-7">
            <form action="" method="POST" class="checkout-info">
                @csrf
                <input type="hidden" name="order_total" value="{{ $totalPrice }}">
                <input type="hidden" name="cart_id" value="{{ $cart_id }}">
                <h5><i class="fas fa-map-marker text-primary mr-2"></i>Địa chỉ nhận hàng</h5>
                <p style="color:#000">{{ Auth::user()->name }}, {{ Auth::user()->phone }}</p>

                @if(count($addresses) > 0)
                    
                    <label for="select-address">Chọn địa chỉ</label>
                    @error('address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <select name="address" id="select-address" class="form-control" aria-label="Default select example">
                        @foreach($addresses as $address)
                        <option value="{{ $address->address }}">{{ $address->address }}</option>
                        @endforeach
                    </select>
                @else
                    <label for="add-address">Thêm địa chỉ</label>
                    @error('address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <input type="text" placeholder="Nhập địa chỉ" name="address" class="form-control" id="add-address">
                @endif
                    <br>
                @if(count($payments) > 0)
                    <label for="add-address">Chọn địa chỉ</label>
                    @error('payment')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <select name="payment" id="" class="form-control" aria-label="Default select example">
                        <option value="">Hình thức thanh toán</option>
                        @foreach($payments as $payment)
                        <option value="{{ $payment->id }}">{{ $payment->name }}</option>
                        @endforeach
                    </select>
                @endif

            </form>

        </div>
        <div class="col-lg-5">
            <div class="card border-secondary mb-5">
                <div class="card-header bg-secondary border-0">
                    <h4 class="font-weight-semi-bold m-0">Tổng thanh toán</h4>
                </div>
                <div class="card-footer border-secondary bg-transparent">
                    <div class="d-flex justify-content-between mt-2">
                        <h5 class="font-weight-bold">Phí ship</h5>
                        <h5 class="font-weight-bold total">₫0</h5>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <h5 class="font-weight-bold">Tổng</h5>
                        <h5 class="font-weight-bold total">₫{{ number_format($totalPrice, 0, null, '.') }}</h5>
                    </div>
                    <a href="" class="btn btn-block btn-primary my-3 py-3" onclick="event.preventDefault();
                        document.querySelector('.checkout-info').submit();">Đặt hàng</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cart End -->
@endsection
