@extends('master')

@section('title')
{{ $product->name }} | E-shopper
@endsection

@section('content')
<!-- Shop Detail Start -->

<div class="container py-5">
    <div class="row px-xl-5">
        <div class="col-lg-5 pb-5">
            <div id="product-carousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner border">
                    @php
                    $prodImgs = $productImgs->where('product_id', $product->id);
                    @endphp
                    <!-- @foreach($prodImgs as $prodImg) -->
                    <div class="carousel-item active">
                        <img class="w-100 h-100" src="{{ asset('imgs/products/' .$prodImgs->first()->path) }}" alt="{{ $prodImg->product->name }}">
                    </div>
                    <!-- @endforeach -->
                </div>
                <!-- <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                    <i class="fa fa-2x fa-angle-left text-dark"></i>
                </a>
                <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                    <i class="fa fa-2x fa-angle-right text-dark"></i>
                </a> -->
            </div>
        </div>

        <div class="col-lg-7 pb-3">
            <h3 class="font-weight-semi-bold">{{ $product->name }}</h3>

            @if($product->discount != null)
            <h3 class="font-weight-semi-bold mb-4">{{ salePrice($product->discount, $product->price) }}đ
                <del style="font-size: 1.4rem" class="font-weight-light text-muted">{{ number_format($product->price, 0, null, ',') }}đ</del>
            </h3>
            @else
            <h3 class="font-weight-semi-bold mb-4">{{ number_format($product->price, 0, null, ',') }} đ</h3>
            @endif

            <p class="mb-4">{{ $product->description }}</p>
            <div class="variation">
                <div class="d-flex mb-3 item-1">
                    <p class="text-dark font-weight-medium mb-0 mr-3">Size:</p>
                    <form>
                        <input type="hidden" class="product_id" name="product_id" value="{{ $product->id }}">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input size" id="size-1" name="size" value="xs" <?php echo !in_array('XS', $sizes) ? 'disabled' : '' ?>>
                            <label class="custom-control-label" for="size-1">XS</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input size" id="size-2" name="size" value="s" <?php echo !in_array('S', $sizes) ? 'disabled' : '' ?>>
                            <label class="custom-control-label" for="size-2">S</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input size" id="size-3" name="size" value="m" <?php echo !in_array('M', $sizes) ? 'disabled' : '' ?>>
                            <label class="custom-control-label" for="size-3">M</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input size" id="size-4" name="size" value="l" <?php echo !in_array('L', $sizes) ? 'disabled' : '' ?>>
                            <label class="custom-control-label" for="size-4">L</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input size" id="size-5" name="size" value="xl" <?php echo !in_array('XL', $sizes) ? 'disabled' : '' ?>>
                            <label class="custom-control-label" for="size-5">XL</label>
                        </div>
                    </form>
                </div>

                <div class="d-flex mb-4 item-2">
                    <p class="text-dark font-weight-medium mb-0 mr-3">Màu:</p>
                    <form>
                        @if(count($colors))
                        @foreach($colors as $color)
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input color" id="color-{{ $color }}" name="color" value="{{ $color }}">
                            <label class="custom-control-label" for="color-{{ $color }}">{{ $color }}</label>
                        </div>
                        @endforeach
                        @endif
                    </form>
                </div>
            </div>
            <div class="d-flex align-items-center child-1">
                <div class="align-items-center mb-4 pt-2">
                    <div class="input-group quantity mr-3" style="width: 130px;">
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-minus quantity-btn decrease-btn">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control bg-secondary text-center quantity-btn qty-btn" value="1" onkeypress="return numberOnly(event)" onkeyup="getQtyKeyUp()">
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-plus quantity-btn increase-btn">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>

                    </div>
                </div>
                <span><span class="qty-stock">{{ $product->quantity }}</span> sản phẩm có sẵn</span>
            </div>
            <button class="btn btn-primary px-3 add-to-cart p-2 add-to-cart-btn"><i class="fa fa-shopping-cart mr-1"></i> Thêm vào giỏ hàng</button>
            <div style="vertical-align: topl; min-height: 60px" class="mt-2">
                <span class="size_error error_text" style="color:red;">&nbsp;</span>
                <span class="color_error error_text" style="color:red;">&nbsp;</span>
            </div>
        </div>
    </div>
    <div class="row px-xl-5">
        <div class="col">
            <div class="nav nav-tabs justify-content-center border-secondary mb-4">
                <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-1">Thông tin</a>
                <a class="nav-item nav-link active" data-toggle="tab" href="#tab-pane-2">Review ({{ count($reviews )}})</a>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade" id="tab-pane-1">
                    <h4 class="mb-3">Thông tin sản phẩm</h4>
                    <p>{{ $product->content }}</p>

                </div>
                <div class="tab-pane fade show active" id="tab-pane-2">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="mb-4">{{ count($reviews )}} Review</h4>
                            @if(count($reviews) > 0)
                            @foreach($reviews as $review)
                            <div class="media mb-4">
                                <img src="{{ asset('imgs/avt.jpg' )}}" alt="Image" class="img-fluid mr-3 mt-1" style="width: 45px;">
                                <div class="media-body">
                                    <h6>{{ $review->user->name }}<small> <i>{{ date('d-m-Y', strtotime($review->created_at)) }}</i></small></h6>
                                    @php
                                        $product_item_id = $review->orderDetail->product_item_id;
                                        $product_item = $review->product->productItems()->find($product_item_id);
                                    @endphp
                                    <p><small>Phân loại: {{ $product_item->size}}, {{ $product_item->color}}</small></p>
                                    <p>
                                    <div class="rating">
                                        @php 
                                            $rating = $review->rating;
                                        @endphp
                                        @for($i = 1; $i <= $rating; $i++)
                                            <span class="fa fa-star star-checked"></span>
                                        @endfor
                                        @if($rating < 5)
                                            @for($j = 1; $j <= 5 - $rating; $j++)
                                                <span class="fa fa-star star-not-checked"></span>
                                            @endfor
                                        @endif
                                    </div>
                                    </p>
                                    <p>{{ $review->message }}</p>
                                </div>
                            </div>
                            @endforeach
                            <div class="col-12 pb-1 d-flex justify-content-center pt-3">
                                {{ $reviews->links() }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Shop Detail End -->


<!-- Products Start -->
<div class="container-fluid py-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Có thể bạn thích</span></h2>
    </div>
    <div class="row px-xl-5">
        @if(count($relatedProducts))
        @foreach($relatedProducts as $relatedProduct)
        <div class="col-lg-3 col-md-6 col-sm-12 card product-item border-0">
            <div class="product-img overflow-hidden bg-transparent border p-0">
                <a class="text-decoration-none" href="{{ route('product.detail', ['product' => $relatedProduct->slug]) }}">
                    <img class="img-fluid w-100" src="{{ asset('imgs/products/' .$relatedProduct->productImgs->first()->path) }}" alt="">
                </a>

            </div>
            <div class="border text-center p-0 pt-4 pb-3 mb-3">
                <h6 class="text-truncate mb-3"><a class="text-decoration-none" href="{{ route('product.detail', ['product' => $relatedProduct->slug]) }}">{{ $relatedProduct->name }}</a></h6>
                <div class="d-flex justify-content-center">
                    @if(!empty($relatedProduct->discount))
                    <h6>{{ salePrice($relatedProduct->discount, $relatedProduct->price) }}đ</h6>
                    <h6 class="text-muted ml-2"><del>{{ number_format($relatedProduct->price, 0, null, ',') }}đ</del></h6>
                    @else
                    <h6>{{ number_format($relatedProduct->price, 0, null, ',') }}đ</h6>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        @else
        <p>Chưa có sản phẩm liên quan</p>
        @endif
    </div>
</div>
</div>
<!-- Products End -->

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

    function getQtyKeyUp() {
        let qtyInput = document.querySelector('.qty-btn'),
            qtyInputValue = parseInt(qtyInput.value, 10);

        qtyStock = document.querySelector('.qty-stock'),
            maxQty = parseInt(qtyStock.textContent, 10);

        if (qtyInputValue > maxQty) {
            qtyInput.value = maxQty;
        } else if (qtyInputValue < 1) {
            qtyInput.value = 1;
        }
    }

    $(document).ready(function() {
        // increase, decrease-btn btn 
        $('.decrease-btn').on('click', function() {
            let current_qty = parseInt($('.qty-btn').val(), 10) - 1;
            if (current_qty <= 0) {
                current_qty = 1;
            }
            $('.qty-btn').val(current_qty);
        });

        $('.increase-btn').on('click', function() {
            let current_qty = parseInt($('.qty-btn').val(), 10) + 1,
                maxQtyStock = parseInt($('.qty-stock').text(), 10);

            if (current_qty > maxQtyStock) {
                current_qty = maxQtyStock;
            }
            $('.qty-btn').val(current_qty);
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".variation input").on('click', function(e) {
            if ($("input[name='size']:checked").val() && $("input[name='color']:checked").val()) {
                // console.log(e.target);
                let product_id = $('.product_id').val();
                $.ajax({
                    method: 'GET',
                    url: '/pre-cart/' + product_id,
                    data: {
                        size: $("input[name='size']:checked").val(),
                        color: $("input[name='color']:checked").val()

                    },
                    beforeSend: function() {
                        $('.add-to-cart-btn').prop('disabled', false);
                        $('.quantity-btn').prop('disabled', false);
                        $('.qty-stock').text('');
                        $('.qty-btn').val(1);
                    },
                    success: function(reponse) {
                        if (reponse.status) {
                            if (reponse.product_detail.quantity) {
                                $('.qty-stock').text(reponse.product_detail.quantity);

                                // 
                            } else {
                                // sold out product: quantity = 0
                                $('.qty-stock').text('0');
                                $('.add-to-cart-btn').prop('disabled', true);
                                $('.quantity-btn').prop('disabled', true);
                                console.log(reponse.msg);
                            }

                        } else {
                            // product doesnt exist
                            $('.qty-stock').text('0');
                            $('.add-to-cart-btn').prop('disabled', true);
                            $('.quantity-btn').prop('disabled', true);
                            console.log(reponse.msg);
                        }
                    }
                })

            }

        });

        $('.add-to-cart').click(function(e) {

            e.preventDefault();

            $.ajax({
                method: 'POST',
                url: '/add-to-cart',
                data: {
                    size: $("input[name='size']:checked").val(),
                    color: $("input[name='color']:checked").val(),
                    quantity: $(".qty-btn").val(),
                    product_id: $(".product_id").val()
                },
                beforeSend: function() {
                    $(document).find('span.error_text').text('')
                },
                success: function(response) {
                    if (response.status == 'validate fails') {
                        $.each(response.errors, function(key, value) {
                            $('span.' + key + '_error').text(value[0]);
                        });
                    } else if (response.status == 'unauthorized') {
                        window.location.href = response.redirect_uri;
                    } else if (response.status == 'success') {
                        // update total item in cart
                        if (response.flag_increase) {
                            $items = +$('.badge').text() + 1;
                            $('.badge').text($items);
                        }

                        alert(response.msg);
                    } else {
                        alert(response.status);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    if (xhr.status == 401) {
                        alert(xhr.responseText)
                    }

                }
            });
        });
    });
</script>

@endsection