@extends('dashboard')

@section('title')
Thêm sản phẩm con | E-shopper
@endsection

@section('sidebar')
@include('admin.sidebar')
@endsection

@section('content')
<div class="container">
    <h2>Thêm sản phẩm con</h2>
    <div class="row">
        <div class="col-12">
            <form action="" method="POST">
                @csrf

                <label for="sku">Sku</label>
                @error('sku')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <input class="form-control" type="text" id="sku" name="sku" value="{{ old('sku')  }}">

                <label for="color">Màu</label>
                @error('color')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <input class="form-control" type="text" id="color" name="color" value="{{ old('color') }}">

                <label for="size">Size</label>
                @error('size')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <input class="form-control" type="text" name="size" id="size" value="{{ old('size') }}">

                <label for="quantity">Số lượng</label>
                @error('quantity')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <input class="form-control" type="text" id="quantity" placeholder="Nhập số lượng" name="quantity" value="{{ old('quantity') }}">
                <br>
                <button type="submit" name="edit" class="btn btn-success">Thêm</button>
            </form>
        </div>
    </div>
</div>
@endsection