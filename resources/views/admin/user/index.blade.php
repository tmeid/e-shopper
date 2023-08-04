@extends('dashboard')

@section('title')
Người dùng | E-shopper
@endsection

@section('dashboard-type')
Admin Dashboard
@endsection

@section('sidebar')
@include('admin.sidebar')
@endsection

@section('content')

<h3 class="mb-4">Quản lý người dùng</h3>

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
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Sửa</th>
                <th>Xoá mềm</th>
                <th>Khôi phục</th>
                <th>Xoá hẳn</th>
            </tr>
        </thead>
        <tbody>
            @if(count($users))
            @foreach($users as $key=>$user)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->role == 1 ? 'Admin' : 'User'}}</td>
                <td style="text-align: center;">
                    @if(!$user->trashed())
                    <a href="{{ route('admin.user.showFormEdit', ['user' => $user]) }}" class="" style="font-size: 15px;"><i class="fa fa-edit"></i></a>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if(!$user->trashed())
                    <a href="{{ route('admin.user.sortDelete', ['user' => $user]) }}" class="" style="font-size: 15px;" onclick="return confirm('Bạn có chắc chắn tạm xoá?')">
                        <i class="fa fa-trash-can" style="color:green;"></i>
                    </a>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if($user->trashed())
                    <a href="{{ route('admin.user.restore', ['id' => $user->id]) }}" class="" style="font-size: 15px;"><i class="fa fa-undo"></i></a>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if($user->trashed())
                    <form action="{{ route('admin.user.forceDelete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" value="{{ $user->id }}">
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
    {{ $users->links() }}
</div>
@endsection