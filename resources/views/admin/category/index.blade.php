@extends('dashboard')

@section('title')
Danh mục | E-shopper
@endsection

@section('sidebar')
@include('admin.sidebar')
@endsection

@section('content')

<h3>Quản lý danh mục</h3>
<a href="{{ route('admin.category.add') }}" class="btn btn-success mb-3">Thêm mới</a>

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
@if(session('msg'))
<p class="alert alert-{{ session('type') }}" style="width: 300px; margin: 0 auto 10px;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    {{ session('msg') }}
</p>
@endif

<div style="overflow-x:auto;">
    <table class="table table-bordered" style="color:#000">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên</th>
                <th>Sửa</th>
                <th>Xoá</th>
            </tr>
        </thead>
        <tbody>
            @if(count($categories))
            @foreach($categories as $index=>$category)
            <tr>
                <td>{{ ++$index }}</td>
                <td>{{ $category->name }}</td>
                <td>
                    <a href="{{ route('admin.category.showFormEdit', ['category' => $category]) }}" class="" style="font-size: 15px;"><i class="fa fa-edit"></i></a>
                </td>
                <td>
                    <form action="{{ route('admin.category.delete') }}" method="POST">
                        @csrf 
                        @method('DELETE')
                        <input type="hidden" name="id" value="{{ $category->id }}">
                        <button style="border: none;" type="submit"><i class="fa fa-trash-can" style="color:red;"></i></button>
                    </form>
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
  
</div>
@endsection