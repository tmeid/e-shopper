@extends('dashboard')

@section('title')
Thông tin cá nhân | E-shopper
@endsection

@section('sidebar')
@include('user.sidebar')
@endsection

@section('content')
<div class="container">
    <h2>Thông tin cá nhân</h2>
    <div class="row">
        <div class="col-12">
            <form action="" method="POST">
                @csrf

                <label for="name">Tên</label>
                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <input class="form-control" type="text" id="name" name="name" value="{{ old('name') ?? $user->name  }}">

                <label for="email">Email</label>
                @error('email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <input class="form-control" type="text" id="email" name="email" value="{{ old('email') ?? $user->email }}">

                <label for="phone">Số điện thoại</label>
                @error('phone')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <input class="form-control" type="text" name="phone" id="phone" value="{{ old('phone') ?? $user->phone }}">

                <br>
                <button type="submit" name="edit" class="btn btn-success">Sửa</button>
            </form>
        </div>
    </div>
</div>
@endsection