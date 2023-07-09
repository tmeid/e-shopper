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
<h3>Thêm danh mục</h3>
<form action="" method="POST">
    @if($errors->any())
    <p class="alert alert-danger text-center">Đã có lỗi xảy ra, vui lòng kiểm tra lại</p>
    @endif
    <div class="form-group">
        <label for="name">Tên</label>
        @error('name')
        <span class="text-danger">{{ $message }}</span>
        @enderror
        <input type="text" class="form-control" name="name" value="{{ old('name') }}" id="name" aria-describedby="emailHelp" placeholder="Tên...">
    </div>

    <button type="submit" class="btn btn-primary" name="submit">Thêm</button>
    @csrf
</form>

@endsection