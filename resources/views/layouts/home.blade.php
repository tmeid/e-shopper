@extends('master')

@section('title')
E-Shopper | Trang chủ
@endsection

@section('banner')
<div id="header-carousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active" style="height: 410px;">
            <img class="img-fluid" src="{{ asset('vendor/imgs/carousel-1.jpg') }}" alt="Image">
            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                <div class="p-3" style="max-width: 700px;">
                    <h4 class="text-light text-uppercase font-weight-medium mb-3">Thời trang hiện đại</h4>
                    <h3 class="display-4 text-white font-weight-semi-bold mb-4">Giá cả hợp lý</h3>
                    <a href="{{ route('shop.products') }}" class="btn btn-light py-2 px-3">Shop Now</a>
                </div>
            </div>
        </div>
        <div class="carousel-item" style="height: 410px;">
            <img class="img-fluid" src="{{ asset('vendor/imgs/carousel-1.jpg') }}" alt="Image">
            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                <div class="p-3" style="max-width: 700px;">
                    <h4 class="text-light text-uppercase font-weight-medium mb-3">Thời trang hiện đại</h4>
                    <h3 class="display-4 text-white font-weight-semi-bold mb-4">Giá cả hợp lý</h3>
                    <a href="{{ route('shop.products') }}" class="btn btn-light py-2 px-3">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
        <div class="btn btn-dark" style="width: 45px; height: 45px;">
            <span class="carousel-control-prev-icon mb-n2"></span>
        </div>
    </a>
    <a class="carousel-control-next" href="#header-carousel" data-slide="next">
        <div class="btn btn-dark" style="width: 45px; height: 45px;">
            <span class="carousel-control-next-icon mb-n2"></span>
        </div>
    </a>
</div>
@endsection

@section('content')
<!-- Featured Start -->
<div class="container-fluid pt-5">
    <div class="row px-xl-5 pb-3">
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                <h5 class="font-weight-semi-bold m-0">Mặc là đẹp</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                <h5 class="font-weight-semi-bold m-0">Giao hàng nhanh</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                <h1 class="fas fa-exchange-alt text-primary m-0 mr-3"></h1>
                <h5 class="font-weight-semi-bold m-0">Giá hợp lý</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                <h5 class="font-weight-semi-bold m-0">Hỗ trợ 24/7</h5>
            </div>
        </div>
    </div>
</div>
<!-- Featured End -->


<!-- Categories Start -->
<div class="container-fluid pt-5">
    <div class="row px-xl-5 pb-3">
        @if(count($categoriesWithCountProduct))
        @foreach($categoriesWithCountProduct as $category)
        <div class="col-lg-4 col-md-6 pb-1">
            <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                <p class="text-right">{{ $category->products_count }} sản phẩm</p>
                <a class="text-decoration-none" href="{{ route('shop.category', ['id' => $category->id ]) }}" class="cat-img position-relative overflow-hidden mb-3">
                    <img class="img-fluid" src="{{ asset('imgs/products/' .$productImgs->where('product_id', $category->products->first()->id)->first()->path )}}" alt="">
                </a>
                <h5 class="p-3 text-center"><a class="text-decoration-none font-weight-semi-bold m-0" style="color:#1C1C1C;" href="{{ route('category', ['id' => $category->id ]) }}">{{ $category->name }}</a></h5>
            </div>
        </div>
        @endforeach
        @endif
      
    </div>
</div>
<!-- Categories End -->



<!-- Products Start -->
<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Sản phẩm nổi bật</span></h2>
    </div>
    <div class="row px-xl-5 pb-3">
    @if(count($featuredProds) > 0)
        @foreach($featuredProds as $featuredProduct)
            <div class="col-lg-3 col-md-6 col-sm-12 card product-item border-0">
                <div class="product-img overflow-hidden bg-transparent border p-0">
                    <a class="text-decoration-none" href="{{ route('product.detail', ['product' => $featuredProduct]) }}">
                        <img class="img-fluid w-100" src="{{ asset('imgs/products/' .$featuredProduct->productImgs->first()->path) }}" alt="">
                    </a>
                    
                </div>
                <div class="border text-center p-0 pt-4 pb-3 mb-3">
                    <h6 class="text-truncate mb-3"><a class="text-decoration-none" style="color:#1C1C1C;" href="{{ route('product.detail', ['product' => $featuredProduct]) }}">{{ $featuredProduct->name }}</a></h6>
                    <div class="d-flex justify-content-center">
                        @if(!empty($featuredProduct->discount))
                        <h6>{{ salePrice($featuredProduct->discount, $featuredProduct->price) }}đ</h6>
                        <h6 class="text-muted ml-2"><del>{{ number_format($featuredProduct->price, 0, null, ',') }}đ</del></h6>
                        @else
                        <h6>{{ number_format($featuredProduct->price, 0, null, ',') }}đ</h6>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
        @else
        <p>Quý khách thông cảm, chưa có sản phẩm nổi bật</p>
        @endif
        
    </div>
</div>
<!-- Products End -->



<!-- Vendor End -->
@endsection