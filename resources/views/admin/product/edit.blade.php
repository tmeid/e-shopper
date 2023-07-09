@extends('dashboard')

@section('title')
Sửa sản phẩm | E-shopper
@endsection

@section('sidebar')
@include('admin.sidebar')
@endsection

@section('content')
<div class="container">
    <h2>Thêm sản phẩm</h2>
    <div class="row">
        <div class="col-12">
            <form action="" method="POST">
                @csrf

                <label for="name">Tên</label>
                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <input class="form-control" type="text" id="name" placeholder="Nhập tên..." name="name" value="{{ $product->name ?? old('name') }}">

                <label for="brand">Thương hiệu</label>
                @error('brand_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <select name="brand_id" id="brand" class="form-control">
                    <option value="">Chọn thương hiệu</option>
                    @if(!empty($brands))
                    @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ ($product->brand_id == $brand->id || old('brand_id') == $brand->id) ? 'selected' : '' }}>{{ ucfirst($brand->name) }}</option>
                    @endforeach
                    @endif
                </select>


                <label for="category">Danh mục</label>
                @error('category_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <select name="category_id" id="category" class="form-control">
                    <option value="">Chọn nhóm</option>
                    @if(!empty($categories))
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ ($product->category_id == $category->id || old('category_id') == $category->id) ? 'selected' : '' }}>{{ ucfirst($category->name) }}</option>
                    @endforeach
                    @endif
                </select>

                <label for="description">Mô tả</label>
                @error('description')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <textarea name="description" id="description" cols="30" rows="10" class="form-control">{{ $product->description ?? old('description')}}</textarea>

                <label for="content">Chi tiết</label>
                @error('content')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <textarea name="content" id="content" cols="30" rows="10" class="form-control">{{ $product->content ?? old('content')}}</textarea>

                <label for="quantity">Số lượng</label>
                @error('quantity')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <input class="form-control" type="text" id="quantity" placeholder="Nhập số lượng" name="quantity" value="{{ $product->quantity ?? old('quantity') }}">
                
                <label for="price">Giá</label>
                @error('price')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <input class="form-control" type="text" id="price" placeholder="Nhập giá" name="price" value="{{ $product->price ?? old('price') }}">

                <label for="discount">Giảm giá</label>
                @error('discount')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <input class="form-control" type="text" id="discount" placeholder="Discount" name="discount" value="{{ $product->discount ?? old('discount') }}">
                
                <input type="checkbox" name="featured" value="1" id="featured" {{ $product->featured == 1 ? 'checked' : '' }}>
                <label for="featured">Featured</label>
                <br>

                <button type="submit" name="edit" class="btn btn-success">Sửa</button>
            </form>
        </div>
    </div>
</div>
@endsection