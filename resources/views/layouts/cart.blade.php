@extends('master')

@section('title')
Giỏ hàng | E-Shopper
@endsection

@section('content')
<!-- Cart Start -->
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col table-responsive mb-5">
            <table class="table table-bordered text-center mb-0" style="color:#000">
                <thead class="bg-secondary text-dark">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Xoá</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @if( $items_order && count($items_order))
                    @foreach($items_order as $item_order)
                    <tr class="product_data">
                        @php 
                            $product = $item_order->product;
                        @endphp
                        <input type="hidden" class="qty-stock-{{$item_order->id}}" value="{{ $item_order->quantity }}">
                        <input type="hidden" class="cart_items_id" value="{{ $item_order->pivot->id }}">
                        <input type="hidden" class="cart_id" value="{{ $cart_id}}">
                        <td class="align-items-start d-flex flex-wrap">
                            <a href="{{ route('product.detail', ['product' => $product->slug]) }}"><img src="{{ asset('imgs/products/' .$product->productImgs->first()->path) }}" alt="{{ $product->name }}" style="width: 50px; display: block;"></a>
                            @if($product->trashed())
                            <span class="mt-1 ml-2"><del><a style="color:#000" class="text-decoration-none" href="{{ route('product.detail', ['product' => $product->slug ])}}">{{ $product->name }} ({{ 'Size: ' .$item_order->size .', màu: ' .$item_order->color }})</a></del>
                            <span class="text-danger">Ngừng kinh doanh</span></span>
                            @elseif($item_order->quantity <= 0 || $item_order->trashed())
                            <span class="mt-1 ml-2"><del><a style="color:#000" class="text-decoration-none" href="{{ route('product.detail', ['product' => $product->slug ])}}">{{ $product->name }} ({{ 'Size: ' .$item_order->size .', màu: ' .$item_order->color }})</a></del>
                            <span class="text-danger">Hết hàng</span></span>
                            @elseif($item_order->pivot->quantity >  $item_order->quantity)
                            <span class="mt-1 ml-2"><a style="color:#000" class="text-decoration-none" href="{{ route('product.detail', ['product' => $product->slug ])}}">{{ $product->name }} ({{ 'Size: ' .$item_order->size .', màu: ' .$item_order->color }})</a>
                            <span class="text-danger">Chỉ còn: {{ $item_order->quantity }} sp</span></span>
                            @else
                            <span class="mt-1 ml-2"><a style="color:#000" class="text-decoration-none" href="{{ route('product.detail', ['product' => $product->slug ])}}">{{ $product->name }} ({{ 'Size: ' .$item_order->size .', màu: ' .$item_order->color }})</a></span>                          
                            @endif
                        </td>
                        <td class="align-middle">
                            @if($product->discount > 0)
                            <del style="font-size: 0.95rem" class="font-weight-light text-muted">₫{{ number_format(($product->price), 0, null, '.')}}</del>
                            <span class="ml-2 inline-block price-{{$item_order->id}}">₫{{ number_format((1- $product->discount)*($product->price), 0, null, '.')}}</span>
                            @else
                            <span class="price-{{$item_order->id}}">₫{{ number_format(($product->price), 0, null, '.') }}</span>
                            @endif

                        </td>
                        <td class="align-middle">
                            @if($item_order->quantity >= 1)
                            <div class="input-group quantity mx-auto" style="width: 100px;">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-primary btn-minus decrease-btn change-qty" onclick="decrease('.qty-btn-{{$item_order->id }}'),
                                        cash('.price-{{$item_order->id}}', '.qty-btn-{{$item_order->id }}', '.cash-{{$item_order->id }}')">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <input type="text" id="{{$item_order->id }}" class="form-control form-control-sm bg-secondary text-center change-qty-input quantity-input qty-btn-{{$item_order->id }}" value="{{ $item_order->pivot->quantity }}" onkeypress="return numberOnly(event)" onkeyup="getQtyKeyUp('.qty-btn-{{$item_order->id }}', '.qty-stock-{{$item_order->id}}')">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-primary btn-plus  increase-btn change-qty" onclick="increase('.qty-btn-{{$item_order->id }}', '.qty-stock-{{$item_order->id}}'), 
                                            cash('.price-{{$item_order->id}}', '.qty-btn-{{$item_order->id }}', '.cash-{{$item_order->id }}')">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            @else
                            <div class="input-group quantity mx-auto" style="width: 100px;">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-primary btn-minus decrease-btn change-qty" disabled>
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <input type="text" id="{{$item_order->id }}" class="form-control form-control-sm bg-secondary text-center change-qty-input quantity-input qty-btn-{{$item_order->id }}" value="0" disabled>
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-primary btn-plus  increase-btn change-qty" disabled>
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            @endif
                        </td>
                        <td class="align-middle cash-{{ $item_order->id }} subtotal">
                            @if($item_order->quantity <= 0 || $item_order->trashed() || $product->trashed())
                                ₫0
                            @elseif($product->discount > 0)
                            ₫{{ number_format((1- $product->discount)*($product->price)*($item_order->pivot->quantity), 0, null, '.')}}
                            @else
                            ₫{{ number_format(($product->price)*($item_order->pivot->quantity), 0, null, '.') }}
                            @endif
                        </td>
                        <td class="align-middle"><button class="btn btn-sm btn-primary delete-btn"><i class="fa fa-times"></i></button></td>
                    </tr>
                    @endforeach
                    @else
                    <p>Giỏ hàng trống</p>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
    @if( $items_order && count($items_order))
    <div class="row px-xl-5">
        <div class="col-lg-4">
            <div class="card border-secondary mb-5">
                <div class="card-header bg-secondary border-0">
                    <h4 class="font-weight-semi-bold m-0">Tổng thanh toán</h4>
                </div>
                <div class="card-footer border-secondary bg-transparent">
                    <div class="d-flex justify-content-between mt-2">
                        <h5 class="font-weight-bold">Tổng</h5>
                        <h5 class="font-weight-bold total">₫{{ number_format($totalPrice, 0, null, '.') }}</h5>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-block btn-primary my-3 py-3 checkout">Mua hàng</a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<!-- Cart End -->
@endsection

@section('js')
<script>
    function numberOnly(event) {
        let charCode = (event.which) ? event.which : event.keyCode;

        if ((charCode >= 32 && charCode <= 47) || charCode >= 58) {
            return false;
        }
        return true;
    }

    function getQtyKeyUp($qtyBtnClass, $qtyStockClass) {
        let qtyInput = document.querySelector($qtyBtnClass),
            qtyInputValue = parseInt(qtyInput.value, 10);

        qtyStock = document.querySelector($qtyStockClass),
            maxQty = parseInt(qtyStock.value, 10);

        if (qtyInputValue > maxQty) {
            qtyInput.value = maxQty;
        } else if (qtyInputValue < 1) {
            qtyInput.value = 1;
        }
    }


    function decrease($qtyBtnClass) {
        let qtyBtn = document.querySelector($qtyBtnClass);
        let qtyBtnValue = qtyBtn.value;

        let current_qty = +qtyBtnValue - 1;
        if (current_qty <= 0) {
            current_qty = 1;
        }
        qtyBtn.value = current_qty;
    }

    function increase($qtyBtnClass, $qtyStockClass) {
        let qtyBtn = document.querySelector($qtyBtnClass),
            maxQtyStock = document.querySelector($qtyStockClass),

            qtyBtnValue = +qtyBtn.value,
            currentQty = qtyBtnValue + 1,
            maxQtyStockValue = +maxQtyStock.value;

        if (currentQty > maxQtyStockValue) {
            currentQty = maxQtyStockValue;
        }
        qtyBtn.value = currentQty;
    }

    function cash(priceClass, qtyClass, cashClass) {
        let priceItem = document.querySelector(priceClass),
            qtyInput = document.querySelector(qtyClass),
            cashItem = document.querySelector(cashClass),

            priceItemValue = priceItem.textContent,
            // remove non-digit from price string
            priceItemInt = +priceItemValue.replace(/[^0-9]/g, ''),
            cashes = document.querySelectorAll('.subtotal'),
            total = 0,
            totalItem = document.querySelector('.total');
        qtyInputValue = qtyInput.value;

        cashItem.textContent = '₫' + (priceItemInt * qtyInputValue).toLocaleString("da-DK");
        cashes.forEach((cash) => {
            let cash_value = cash.textContent,
                cash_value_int = +cash_value.replace(/[^0-9]/g, '');
            total += cash_value_int;
        });
        totalItem.textContent = '₫' + (total).toLocaleString("da-DK");
    }

    // Enter the quantity in the input element ==> update price of its product
    let qtyInputs = document.querySelectorAll('.quantity-input');

    qtyInputs.forEach((qtyInput) => {
        qtyInput.addEventListener('click', function(e) {
            // prevent event spreading to body
            e.stopPropagation();
            let currentProductId = this.id;

            document.body.addEventListener('click', function(event) {
                cash('.price-' + currentProductId, '.qty-btn-' + currentProductId, '.cash-' + currentProductId);

            });
        });
    });

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.change-qty').click(function(e) {
            e.preventDefault();
            let cart_items_id = $(this).closest('.product_data').find('.cart_items_id').val();
            let qty = $(this).closest('.product_data').find('.quantity-input').val();

            $.ajax({
                method: 'POST',
                url: '/update-cart',
                data: {
                    cart_items_id: cart_items_id,
                    qty: qty
                },
                success: function(response) {

                }
            });
        });

        $('.change-qty-input').click(function(e) {
            e.preventDefault();
            let cart_items_id = $(this).closest('.product_data').find('.cart_items_id').val();
            let qty_item = $(this);

            $('body').click(function(event) {
                event.preventDefault();
                let qty = qty_item.val();
                $.ajax({
                    method: 'POST',
                    url: '/update-cart',
                    data: {
                        cart_items_id: cart_items_id,
                        qty: qty
                    },
                    success: function(response) {

                    }
                });
            });
        });

        $('.delete-btn').click(function(e) {
            e.preventDefault();
            let cart_items_id = $(this).closest('.product_data').find('.cart_items_id').val();

            $.ajax({
                method: 'POST',
                url: '/delete-cart-item',
                data: {
                    cart_items_id: cart_items_id
                },
                success: function(response) {
                    if (response.status) {
                        window.location.reload();
                    }
                }
            });

        });

        $('.checkout').click(function(e){
            e.preventDefault();
            let cart_id = $('.cart_id').val();
            $.ajax({
                method: 'POST',
                url: '/pre-checkout',
                data: {
                    cart_id: cart_id
                },
                success: function(response){
                    if(response.status == 'success'){
                        window.location.href = e.target.href;
                    }else if(response.status == 'sold_out'){
                        alert('Xin vui lòng xoá những sản phẩm hết hàng để đặt hàng');
                    }else if(response.status == 'exceed_qty'){
                        alert('Sản phẩm vượt quá số lượng còn lại trong kho');
                    }else if(response.status == 'empty'){
                        alert('Giỏ hàng trống');
                    }else if(response.status = 'out_of_business'){
                        alert('Xin vui lòng xoá những sản phẩm ngừng kinh doanh để đặt hàng');
                    }
                    
                }
            })
        });
    });

</script>

@endsection