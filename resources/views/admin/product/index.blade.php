@extends('dashboard')

@section('title')
Thêm danh mục | E-shopper
@endsection

@section('dashboard-type')
Admin Dashboard
@endsection

@section('sidebar')
@include('admin.sidebar')
@endsection

@section('content')

<h3>Danh sách sản phẩm</h3>
@if(session('msg'))
<p class="alert alert-danger">{{ session('msg') }}</p>
@endif
<a href="{{ route('admin.product.add') }}" class="btn btn-success mb-3">Thêm mới</a>
@if(session('msg'))
<p class="alert alert-{{ session('type') }}">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    {{ session('msg') }}
</p>
@endif
<div class="row">
    <form action="" class="col-lg-5 col-md-6 mb-3">
        <div class="input-group">
        <input name="search" type="text" class="form-control" placeholder="Tìm..." value="{{ request('search') }}">
            <div class="input-group-append">
                <button type="submit" name="searchName" class="input-group-text bg-transparent text-primary">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
</div>


<table class="table table-bordered" style="color:#000">
    <thead>
        <tr>
            <th>STT</th>
            <th>Tên</th>
            <th>Giá</th>
            <th>SL</th>
            <th>Qty Sold</th>
            <th>Discount</th>
            <th>Featured</th>
            <th colspan="4">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @if(count($products))
        @foreach($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->quantity }}</td>
            <td>{{ $product->qty_sold }}</td>
            <td>{{ $product->discount }}</td>
            <td>{{ $product->featured == 1 ? 'Yes' : 'No' }}</td>
            <td><a href="#">Sub items</a></td>
            <td><a href="{{ route('admin.product.show', ['id' => $product->id]) }}">Detail</a></td>
            <td><a href="{{ route('admin.product.showFormEdit', ['id' => $product->id ] ) }}" class="" style="font-size: 15px;"><i class="fa fa-edit"></i></a></td>
            <td><a href="{{ route('admin.product.delete', ['id' => $product->id ] ) }}" class="" style="font-size: 15px;"><i class="fa fa-trash-can"></i></a></td>
        


        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="2">Dữ liệu trống</td>
        </tr>
        @endif

    </tbody>
</table>
<div class="col-12 pb-1 d-flex justify-content-center pt-3">
    {{$products->links() }}
</div>
@endsection