@extends('dashboard')

@section('title')
Chi tiết sản phẩm | E-shopper
@endsection

@section('dashboard-type')
Chi tiết sản phẩm
@endsection

@section('sidebar')
@include('admin.sidebar')
@endsection

@section('content')

<div class="row">
    <div class="col-md-12" style="color:black">
        <div class="main-card mb-3 card">
            <div class="card-body display_data">
                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Quản lý ảnh:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p><a href="{{ route('admin.product.productImg', ['product_id' => $product->id ] )}}">Qly ảnh</a></p>
                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Quản lý sản phẩm con:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p><a href="{{ route('admin.product.showSubItems', ['product_id' => $product->id]) }}">Qly sản phẩm con</a></p>
                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Tên:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ $product->name}}</p>
                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Category:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ $product->category->name}}</p>
                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Thương hiệu:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ $product->brand->name}}</p>
                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Mô tả:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ $product->description}}</p>
                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Chi tiết:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ $product->content}}</p>
                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Sản phẩm con:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">

                        @foreach($product->productItems as $productItem)
                        <p>
                        {{ $productItem->sku }}
                        {{ '.Size: ' .$productItem->size}}
                        {{ '.Màu: ' .$productItem->color }}
                        {{ '.Số lượng: ' .$productItem->quantity }}
                        </p>
                        @endforeach

                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Số lượng:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ $product->quantity}}</p>
                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Đã bán:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ $product->qty_sold}}</p>
                    </div>
                </div>

                <div class="position-relative row">
                    <label for="" class="col-md-3 text-md-right col-form-label">Sản phẩm nổi bật:</label>
                    <div class="col-md-9 col-xl-8 col-form-label">
                        <p>{{ $product->featured ? 'Yes' : 'No'}}</p>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
@endsection