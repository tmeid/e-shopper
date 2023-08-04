@extends('dashboard')

@section('title')
Sửa sản phẩm con | E-shopper
@endsection

@section('sidebar')
@include('admin.sidebar')
@endsection

@section('content')
<div class="container">
    <h2>Sửa sản phẩm con</h2>
    <div class="row">
        <div class="col-12">
            <form action="" method="POST">
                @csrf
    
                <label for="sku">Sku</label>
                @error('sku')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <input class="form-control" type="text" id="sku" name="sku" value="{{ old('sku') ?? $sub_item->sku  }}">

                <label for="color">Màu</label>
                @error('color')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <input class="form-control" type="text" id="color" name="color" value="{{ old('color') ?? $sub_item->color  }}">

                <label for="size">Size</label>
                @error('size')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <input class="form-control" type="text" name="size" id="size" value="{{ old('size') ?? $sub_item->size  }}">

                <label for="quantity">Số lượng</label>
                @error('quantity')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <input class="form-control" type="text" id="quantity" placeholder="Nhập số lượng" name="quantity" value="{{ old('quantity') ?? $sub_item->quantity  }}">
                <br>
                <button type="submit" name="edit" class="btn btn-success">Sửa</button>
            </form>
        </div>
    </div>
</div>
@endsection