@extends('dashboard')

@section('title')
Thay đổi mật khẩu | E-shopper
@endsection


@section('sidebar')
@include('user.sidebar')
@endsection

@section('content')

<h3>Thay đổi mật khẩu</h3>
<hr>

@if(session('msg'))
<p class="alert alert-{{ session('type') }}">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    {{ session('msg') }}
</p>
@endif

<div class="container-fluid">
    <form action="" method="POST" style="max-width: 400px;">
        @csrf
        <label for="old-pass">Mật khẩu cũ</label>
        @error('old-pass')
        <span class="text-danger">{{ $message }}</span>
        @enderror
        <input class="form-control" type="password" id="old-pass" name="old-pass" value="">

        <label for="old-pass">Mật khẩu mới</label>
        @error('newPass')
        <span class="text-danger">{{ $message }}</span>
        @enderror
        <input class="form-control" type="password" id="new-pass" name="newPass" value="">

        <label for="re-new-pass">Nhập lại mật khẩu mới</label>
        @error('reNewPass')
        <span class="text-danger">{{ $message }}</span>
        @enderror
        <input class="form-control" type="password" id="re-new-pass" name="reNewPass" value="">

        <button type="submit" class="btn btn-success mt-2">Đổi</button>

    </form>
</div>

@endsection