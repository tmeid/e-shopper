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
                <p class="user-address"></p>

                @if(count($addresses) > 0)
                <a type="button" class="edit btn border-success mb-2" onclick="openLightbox(-100)">Cập nhật địa chỉ</a>

                <label for="select-address" class="d-block">Địa chỉ</label>
                @error('address')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <select name="address" id="select-address" class="form-control" aria-label="Default select example">
                    <option value="">Chọn địa chỉ</option>
                    @foreach($addresses as $address)
                    <option value="{{ $address->address }}">{{ $address->name .' (' .$address->phone .'), ' .$address->address }}</option>
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
                <label for="add-address">Hình thức thanh toán</label>
                @error('payment')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <select name="payment" id="" class="form-control" aria-label="Default select example">
                    <option value="">Chọn hình thức thanh toán</option>
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
<div class="wrap">
    <span>&times;</span>
    <div class="slideshow">
        <div class="forms">
            <form action="" method="POST" class="form form-ele">
                @csrf
                <h3>Thêm địa chỉ</h3>
                <label for="add-name">Tên</label>
                <span class="text-danger addName-error"></span>
                <input type="text" name="addName" id="add-name" class="form-control addName">

                <label for="add-phone">Số điện thoại</label>
                <span class="text-danger addPhone-error"></span>
                <input type="text" name="addPhone" id="add-phone" class="form-control addPhone">

                <label for="add-address">Địa chỉ</label>
                <span class="text-danger addAddress-error"></span>
                <input type="text" name="addAddress" id="add-address" class="form-control addAddress">

                <hr>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn" onclick="back(-100)">Quay lại</button>
                    <button type="submit" class="create-modal btn btn-primary">Thêm</button>
                </div>
            </form>

            <form action="" method="POST" class="form-ele form">
                @csrf
                <h3>Địa chỉ của tôi</h3>
                <button type="button" class="btn btn-success mb-2" onclick="back(0)">Thêm địa chỉ</button>
                @if(count($addresses))
                @foreach($addresses as $address)
                <div class="col-12">
                    <p>
                        <span class="name-{{ $address->id }}">{{ $address->name }}</span> | <span class="phone-{{ $address->id }}">{{ $address->phone }} </span>
                    </p>
                    <p class="address-{{ $address->id }}">{{ $address->address }}</p>
                    <p>
                        <button type="button" class="edit btn border-success edit{{ $address->id }}" id="{{ $address->id }}" onclick="back(-200), fillUpdateForm(this)">Cập nhật</button>
                    </p>
                    <hr>
                </div>
                @endforeach
                @endif
            </form>

            <form action="" method="POST" class="form form-ele form-update">
                @csrf
                <h3>Chỉnh sửa</h3>
                @csrf
                <input type="hidden" name="id" value="" class="id">
                <label for="name">Tên</label>
                <span class="text-danger name-error"></span>
                <input type="text" name="name" id="name" class="form-control name">

                <label for="phone">Số điện thoại</label>
                <span class="text-danger phone-error"></span>
                <input type="text" name="phone" id="phone" class="form-control phone">

                <label for="address">Địa chỉ</label>
                <span class="text-danger address-error"></span>
                <input type="text" name="address" id="address" class="form-control address">

                <hr>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn" onclick="back(-100)">Quay lại</button>
                    <button type="submit" class="edit-modal btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    let forms = document.querySelector('.forms'),
        wrap = document.querySelector('.wrap'),
        slideshow = document.querySelector('.slideshow'),
        inputs = document.querySelectorAll('.form-update input');

    function back(pos){
        forms.style.left = pos + "%";
    }

    // open lightbox
    function openLightbox(pos) {
        document.body.style.overflow = 'hidden';

        // lock backgroud when open lightbox
        wrap.style.display = 'block';

        back(pos);
    }

    // stop Propagation: click outside close the lightbox
    slideshow.onclick = function(event) {
        event.stopPropagation();
    }
    wrap.onclick = function() {
        wrap.style.display = 'none';

        // body can scroll now 
        document.body.style.overflow = 'auto';
    }

    // get data to update form 
    function fillUpdateForm(ele){
        let address_id = ele.getAttribute("id"),
            name = document.querySelector('.name-' + address_id).innerText;
            phone = document.querySelector('.phone-' + address_id).innerText,
            address = document.querySelector('.address-' + address_id).innerText,
         
        // fill data
        
        document.querySelector('.form-update .id').value = address_id;
        document.querySelector('.form-update .name').value = name;
        document.querySelector('.form-update .phone').value = phone;
        document.querySelector('.form-update .address').value = address;

    }
</script>

<script>
     $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.edit-modal').on('click', function(e) {
            e.preventDefault();
            let id = $('.form-update .id').val();

            $.ajax({
                method: 'POST',
                url: '/user/address/' + id,
                dataType: 'json',
                data: {
                    name: $('.form-update .name').val(),
                    phone: $('.form-update .phone').val(),
                    address: $('.form-update .address').val()
                },
                beforeSend: function(){
                    $('.name-error').text('');
                    $('.phone-error').text('');
                    $('.address-error').text('');
                },
                success: function(response) {
                    if (response.status == 404) {
                        alert('Không thể update thông tin không tồn tại');
                    } else if (response.status == 304) {
                        alert('Có lỗi xảy ra, vui lòng thử lại');
                    } else if (response.status == 200) {
                        location.reload();
                    }
                },
                error: function(error) {
                    let respJson = error.responseJSON.errors;
                    if (Object.keys(respJson).length > 0) {
                        for (let key in respJson) {
                            $('.form-update .' + key + '-error').text(respJson[key][0]);
                        }
                    }
                }
            })
        });

        $('.create-modal').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                method: 'POST',
                url: '/user/address/add',
                dataType: 'json',
                data: {
                    addName: $('.addName').val(),
                    addPhone: $('.addPhone').val(),
                    addAddress: $('.addAddress').val()
                },
                beforeSend: function(){
                    $('.addName-error').text('');
                    $('.addPhone-error').text('');
                    $('.addAddress-error').text('');
                },
                success: function(response) {
                    if (response.status == 304) {
                        alert('Có lỗi xảy ra, vui lòng thử lại');
                    } else if (response.status == 200) {
                        location.reload();
                    }

                },
                error: function(error) {
                    let respJson = error.responseJSON.errors;
                    if (Object.keys(respJson).length > 0) {
                        for (let key in respJson) {
                            $('.' + key + '-error').text(respJson[key][0]);
                        }
                    }
                    
                }
            })
        });
    });
</script>
@endsection