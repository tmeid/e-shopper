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

<h3>Quản lý sản phẩm</h3>

@if(session('msg'))
<p class="alert alert-{{ session('type') }}" style="width: 300px; margin: 0 auto;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    {{ session('msg') }}
</p>
@endif
<a href="{{ route('admin.product.add') }}" class="btn btn-success mb-3">Thêm mới</a>

<div class="row">
    <form action="" class="col-lg-5 col-md-6 mb-3">
        <div class="input-group">
            <input name="search" type="text" class="form-control" placeholder="Tìm..." value="{{ request('search') }}">
            <div class="input-group-append">
                <button type="submit" class="input-group-text bg-transparent text-primary">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<form action="" class="mb-3">
    <select class="form-control" name="sort_by" id="" onchange="this.form.submit()" style="width:initial">
        <option value="">Sắp xếp theo</option>
        <option value="price-asc" {{ request('sort_by') == 'price-asc' ? 'selected' : '' }}>Giá tăng dần</option>
        <option value="price-desc" {{ request('sort_by') == 'price-desc' ? 'selected' : '' }}>Giá giảm dần</option>
        <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Sản phẩm mới</option>
        <option value="name-asc" {{ request('sort_by') == 'name-asc' ? 'selected' : '' }}>Tên A-Z</option>
        <option value="name-desc" {{ request('sort_by') == 'name-desc' ? 'selected' : '' }}>Tên Z-A</option>
    </select>
</form>


<div style="overflow-x:auto;">
    <table class="table table-bordered" style="color:#000">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên</th>
                <th>Giá</th>
                <th>SL</th>
                <th>Đã bán</th>
                <th>Discount</th>
                <th>Featured</th>
                <th>Sub Items</th>
                <th>Chi tiết</th>
                <th>Sửa</th>
                <th>Xoá mềm</th>
                <th>Khôi phục</th>
                <th>Xoá hẳn</th>
            </tr>
        </thead>
        <tbody>
            @if(count($products))
            @foreach($products as $key=>$product)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->price, 0, null, '.') .' đ' }}</td>
                <td>{{ $product->quantity }}</td>
                <td>{{ $product->qty_sold }}</td>
                <td>{{ $product->discount }}</td>
                <td>{{ $product->featured == 1 ? 'Yes' : 'No' }}</td>
                <td>
                    @if(!$product->trashed())
                    <a href="{{ route('admin.product.showSubItems', ['product_id' => $product->id]) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                    @endif
                </td>
                <td><a href="{{ route('admin.product.show', ['id' => $product->id]) }}"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                <td>
                    @if(!$product->trashed())
                    <a href="{{ route('admin.product.showFormEdit', ['id' => $product->id ] ) }}" class="" style="font-size: 15px;"><i class="fa fa-edit"></i></a>
                    @endif
                </td>
                <td style="text-align: center;">
                        @if(!$product->trashed())
                        <a href="{{ route('admin.product.sortDelete', ['product' => $product]) }}" 
                            class="" style="font-size: 15px;"
                            onclick="return confirm('Bạn có chắc chắn tạm xoá?')"
                        >
                            <i class="fa fa-trash-can" style="color:green;"></i>
                        </a>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if($product->trashed())
                        <a href="{{ route('admin.product.restore', ['id' => $product->id]) }}" class="" style="font-size: 15px;"><i class="fa fa-undo"></i></a>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if($product->trashed())
                        <form action="{{ route('admin.product.forceDelete') }}" method="POST">
                            @csrf 
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <button style="border: none;" type="submit"><i class="fa fa-trash-can" style="color:red;"></i></button>
                        </form>
                        @endif
                    </td>



            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="2">Dữ liệu trống</td>
            </tr>
            @endif

        </tbody>
    </table>
</div>
<div class="col-12 pb-1 d-flex justify-content-center pt-3">
    {{$products->links() }}
</div>
@endsection