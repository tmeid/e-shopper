@extends('dashboard')

@section('title')
Sản phẩm con | E-shopper
@endsection

@section('dashboard-type')
Admin Dashboard
@endsection

@section('sidebar')
@include('admin.sidebar')
@endsection

@section('content')

<h3>Danh sách sản phẩm con</h3>
@if(session('msg'))
<p class="alert alert-{{ session('type') }}">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    {{ session('msg') }}
</p>
@endif
<a href="{{ route('admin.product.addSubItem', ['product' => $product]) }}" class="btn btn-success mb-3">Thêm mới</a>
<div style="overflow-x:auto;">
    <table class="table table-bordered" style="color:#000">
        <thead>
            <tr>
                <th>STT</th>
                <th>Sku</th>
                <th>Màu</th>
                <th>Size</th>
                <th>Số lượng</th>
                <th>Sửa</th>
                <th>Xoá mềm</th>
                <th>Khôi phục</th>
            </tr>
        </thead>
        <tbody>
            @if(count($sub_items))
            @foreach($sub_items as $key=>$sub_item)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $sub_item->sku }}</td>
                <td>{{ $sub_item->color }}</td>
                <td>{{ $sub_item->size }}</td>
                <td>{{ $sub_item->quantity }}</td>
                <td>
                    @if(!$sub_item->trashed())
                    <a href="{{ route('admin.product.showSubItem', ['product' => $product, 'sub_item' => $sub_item]) }}" class="" style="font-size: 15px;"><i class="fa fa-edit"></i></a>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if(!$sub_item->trashed())
                    <a href="{{ route('admin.product.sortDeleteSub', ['product' => $product, 'sub_item' => $sub_item]) }}" class="" style="font-size: 15px;" onclick="return confirm('Bạn có chắc chắn tạm xoá?')">
                        <i class="fa fa-trash-can" style="color:green;"></i>
                    </a>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if($sub_item->trashed())
                    <a href="{{ route('admin.product.restoreSub', ['product' => $product, 'id' => $sub_item->id]) }}" class="" style="font-size: 15px;"><i class="fa fa-undo"></i></a>
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
@endsection