@extends('dashboard')

@section('title')
Sửa người dùng | E-shopper
@endsection

@section('sidebar')
@include('admin.sidebar')
@endsection

@section('content')
<div class="container">
    <h2>Sửa người dùng</h2>
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

                <label for="role">Quyền</label>
                @error('role')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <select name="role" id="role" class="form-control">
                    <option value="0" {{ (old('role') === 0 || $user->role === 0) ? 'selected' : '' }}>User</option>
                    <option value="1" {{ (old('role') === 1 || $user->role === 1) ? 'selected' : '' }}>Admin</option>
                </select>

                <br>
                <button type="submit" name="edit" class="btn btn-success">Sửa</button>
            </form>
        </div>
    </div>
</div>
@endsection