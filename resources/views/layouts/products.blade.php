@extends('master')

@section('title')
Tất cả sản phẩm | E-shopper
@endsection

@section('content')
<!-- Shop Start -->
<div class="container-fluid">
    <div class="row px-xl-5">
        <!-- Shop Sidebar Start -->
        <div class="col-lg-2 col-md-12">
            <!-- Price Start -->
            <div class="row">
                <div class=" mb-4 pb-4 col-lg-12 col-md-3 col-sm-6">
                    <p>Bộ lọc tìm kiếm</p>
                    <h5 class="font-weight-semi-bold mb-4">Khoảng giá</h5>
                    <form>
                        
                        @if(!empty($search))
                        <input type="hidden" name="search" value="{{ $search }}">
                        @endif

                        @if(!empty($sizeFilter))
                        @foreach($sizeFilter as $size)
                        <input type="hidden" name="size[]" value="{{ $size }}">
                        @endforeach
                        @endif

                        @if(!empty($colorFilter))
                        @foreach($colorFilter as $color)

                        @if(!empty($categoryFilter))
                        @foreach($categoryFilter as $category)
                        <input type="hidden" name="category[]" value="{{ $category }}">
                        @endforeach
                        @endif

                        <input type="hidden" name="color[]" value="{{ $color }}">
                        @endforeach
                        @endif
                        <div class="d-flex justify-content-between mb-3">
                            <input class="range-price" maxlength="13" name="minPrice" 
                                style="width: 5rem" type="text" placeholder="₫ TỪ" 
                                onkeypress="return numberOnly(event)"
                                onpaste="return false"
                                value="{{ !empty(request('minPrice')) ? request('minPrice') : '' }}"
                            >
                            <input class="range-price" maxlength="13" name="maxPrice" 
                                style="width: 5rem" type="text" placeholder="₫ ĐẾN" 
                                onkeypress="return numberOnly(event)"
                                onpaste="return false"
                                value="{{ !empty(request('maxPrice')) ? request('maxPrice') : '' }}"
                            >
                        </div>
                        <button style="width:100%; background-color: #D19C97" class="btn d-block btn-success">Áp dụng</button>
                    </form>
                </div>
                <!-- Price End -->

                <!-- Category Start -->
                @if(\Request::route()->getName() != 'shop.category')
                <div class=" mb-4 col-lg-12 col-md-3 col-sm-6">
                    <h5 class="font-weight-semi-bold mb-4">Theo danh mục</h5>
                    <form>
                        @if(!empty($search))
                        <input type="hidden" name="search" value="{{ $search }}">
                        @endif

                        @if(!empty($sizeFilter))
                        @foreach($sizeFilter as $size)
                        <input type="hidden" name="size[]" value="{{ $size }}">
                        @endforeach
                        @endif

                        @if(!empty($colorFilter))
                        @foreach($colorFilter as $color)
                        <input type="hidden" name="color[]" value="{{ $color }}">
                        @endforeach
                        @endif

                        @if(!empty($minPrice))
                        <input type="hidden" name="minPrice" value="{{ $minPrice }}">
                        @endif

                        @if(!empty($maxPrice))
                        <input type="hidden" name="maxPrice" value="{{ $maxPrice }}">
                        @endif

                        @if(isset($categories) && count($categories) > 0)
                        @foreach($categories as $category)
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" onchange="this.form.submit()" name="category[]" class="custom-control-input" id="{{ $category->id }}" value="{{ $category->id }}" {{ request('category') && in_array($category->id, request('category')) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="{{ $category->id }}">{{ $category->name }}</label>
                        </div>
                        @endforeach
                        @endif
                    </form>
                </div>
                @endif
                <!-- Category End -->

                <!-- Size Start -->
                <div class=" mb-4 col-lg-12 col-md-3 col-sm-6">
                    <h5 class="font-weight-semi-bold mb-4">Theo size</h5>
                    <form>
                        @if(!empty($search))
                        <input type="hidden" name="search" value="{{ $search }}">
                        @endif

                        @if(!empty($categoryFilter))
                        @foreach($categoryFilter as $category)
                        <input type="hidden" name="category[]" value="{{ $category }}">
                        @endforeach
                        @endif

                        @if(!empty($colorFilter))
                        @foreach($colorFilter as $color)
                        <input type="hidden" name="color[]" value="{{ $color }}">
                        @endforeach
                        @endif

                        @if(!empty($minPrice))
                        <input type="hidden" name="minPrice" value="{{ $minPrice }}">
                        @endif

                        @if(!empty($maxPrice))
                        <input type="hidden" name="maxPrice" value="{{ $maxPrice }}">
                        @endif

                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input name="size[]" value="XS" {{ request('size') && in_array('XS', request('size')) ? 'checked' : '' }} type="checkbox" class="custom-control-input" id="size-1" onchange="this.form.submit()">
                            <label class="custom-control-label" for="size-1">XS</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input name="size[]" value="S" {{ request('size') && in_array('S', request('size')) ? 'checked' : '' }} type="checkbox" class="custom-control-input" id="size-2" onchange="this.form.submit()">
                            <label class="custom-control-label" for="size-2">S</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input name="size[]" value="M" {{ request('size') && in_array('M', request('size')) ? 'checked' : '' }} type="checkbox" class="custom-control-input" id="size-3" onchange="this.form.submit()">
                            <label class="custom-control-label" for="size-3">M</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input name="size[]" value="L" {{ request('size') && in_array('L', request('size')) ? 'checked' : '' }} type="checkbox" class="custom-control-input" id="size-4" onchange="this.form.submit()">
                            <label class="custom-control-label" for="size-4">L</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between">
                            <input name="size[]" value="XL" {{ request('size') && in_array('XL', request('size')) ? 'checked' : '' }} type="checkbox" class="custom-control-input" id="size-5" onchange="this.form.submit()">
                            <label class="custom-control-label" for="size-5">XL</label>
                        </div>
                    </form>
                </div>
                <!-- Size End -->

                <!-- Color Start -->
                <div class=" mb-4 pb-4 col-lg-12 col-md-3 col-sm-6">
                    <h5 class="font-weight-semi-bold mb-4">Theo màu</h5>
                    <form>
                        @if(!empty($search))
                        <input type="hidden" name="search" value="{{ $search }}">
                        @endif

                        @if(!empty($categoryFilter))
                        @foreach($categoryFilter as $category)
                        <input type="hidden" name="category[]" value="{{ $category }}">
                        @endforeach
                        @endif

                        @if(!empty($sizeFilter))
                        @foreach($sizeFilter as $size)
                        <input type="hidden" name="size[]" value="{{ $size }}">
                        @endforeach
                        @endif

                        @if(!empty($minPrice))
                        <input type="hidden" name="minPrice" value="{{ $minPrice }}">
                        @endif

                        @if(!empty($maxPrice))
                        <input type="hidden" name="maxPrice" value="{{ $maxPrice }}">
                        @endif

                        @if(count($colors))
                        @foreach($colors as $color)
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input name="color[]" value="{{ $color->color }}" {{ request('color') && in_array( $color->color, request('color')) ? 'checked' : '' }} onchange="this.form.submit()" type="checkbox" class="custom-control-input" id="{{ $color->color }}">
                            <label class="custom-control-label" for="{{ $color->color }}">{{ $color->color }}</label>
                        </div>
                        @endforeach
                        @endif
                    </form>
                </div>
                <!-- Color End -->


            </div>
        </div>
        <!-- Shop Sidebar End -->


        <!-- Shop Product Start -->
        <div class="col-lg-10 col-md-12">
            <div class="row pb-3">
                <div class="col-12 pb-1">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <form action="" class="d-flex">
                                @if(!empty($search))
                                <input type="hidden" name="search" value="{{ $search }}">
                                @endif

                                @if(!empty($categoryFilter))
                                @foreach($categoryFilter as $category)
                                <input type="hidden" name="category[]" value="{{ $category }}">
                                @endforeach
                                @endif

                                @if(!empty($sizeFilter))
                                @foreach($sizeFilter as $size)
                                <input type="hidden" name="size[]" value="{{ $size }}">
                                @endforeach
                                @endif

                                @if(!empty($colorFilter))
                                @foreach($colorFilter as $color)
                                <input type="hidden" name="color[]" value="{{ $color }}">
                                @endforeach
                                @endif

                                @if(!empty($minPrice))
                                <input type="hidden" name="minPrice" value="{{ $minPrice }}">
                                @endif

                                @if(!empty($maxPrice))
                                <input type="hidden" name="maxPrice" value="{{ $maxPrice }}">
                                @endif

                                <select class="form-control" name="sort_by" id="" onchange="this.form.submit()" style="width:initial">
                                    <option value="">Sắp xếp theo</option>
                                    <option value="price-asc" {{ request('sort_by') == 'price-asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                    <option value="price-desc" {{ request('sort_by') == 'price-desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                    <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Sản phẩm mới</option>
                                    <option value="name-asc" {{ request('sort_by') == 'name-asc' ? 'selected' : '' }}>Tên A-Z</option>
                                    <option value="name-desc" {{ request('sort_by') == 'name-desc' ? 'selected' : '' }}>Tên Z-A</option>
                                </select>

                                <select class="form-control ml-3" name="show" id="" onchange="this.form.submit()">
                                    <option value="">Hiển thị</option>
                                    <option value="3" {{ request('show') == 3 ? 'selected' : '' }}>3 sản phẩm</option>
                                    <option value="5" {{ request('show') == 5 ? 'selected' : '' }}>5 sản phẩm</option>
                                    <option value="10" {{ request('show') == 10 ? 'selected' : '' }}>10 sản phẩm</option>
                                </select>
                            </form>
                        </div>



                    </div>
                </div>
                @if(count($products))
                @foreach($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                    <div class="product-img overflow-hidden bg-transparent border p-0">
                        <a class="text-decoration-none" href="{{ route('product.detail', ['product' => $product->slug] )}}">
                            <img class="img-fluid w-100" src="{{ asset('imgs/products/' .$product->productImgs->first()->path) }}" alt="{{ $product->name}}">
                        </a>
                    </div>
                    <div class="border text-center p-0 pt-4 pb-3 mb-3">
                        <h6 class="text-truncate mb-3"><a class="text-decoration-none" style="color:#1C1C1C;" href="{{ route('product.detail', ['product' => $product->slug] )}}">{{ $product->name }}</a></h6>
                        <div class="d-flex justify-content-center">
                            @if($product->discount != null)
                            <h6>{{ salePrice($product->discount, $product->price) }}đ
                                <del class="font-weight-light text-muted">{{ number_format($product->price, 0, null, ',') }}đ</del>
                            </h6>
                            @else
                            <h6>{{ number_format($product->price, 0, null, ',') }}đ</h6>
                            @endif
                        </div>
                        <p>Đã bán: {{ $product->qty_sold ?? 0 }}</p>
                    </div>
                </div>
                @endforeach
                @else
                <p>0 có kết quả</p>
                @endif

                <div class="col-12 pb-1 d-flex justify-content-center pt-3">
                    {{$products->links() }}
                </div>
            </div>
        </div>
        <!-- Shop Product End -->
    </div>
</div>
<!-- Shop End -->



@endsection

@section('js')
<script>
    // let priceFields = document.querySelectorAll('.range-price');
    // priceFields.forEach((priceField) => {
    //     priceField.addEventListener('keypress', function(event){
    //         let charCode = (event.which) ? event.which : event.keyCode;
    //         if((charCode >= 32 && charCode <= 47) || charCode >= 58){
    //             return false;
    //         }

    //         return true;
    //     });
    // });

    function numberOnly(event) {
        let charCode = (event.which) ? event.which : event.keyCode;
        if ((charCode >= 32 && charCode <= 47) || charCode >= 58) {
            return false;
        }

        return true;

    }
</script>


@endsection