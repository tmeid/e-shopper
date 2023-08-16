@extends('dashboard')

@section('title')
Đánh giá | E-shopper
@endsection

@section('dashboard-type')
Admin Dashboard
@endsection

@section('sidebar')
@include('admin.sidebar')
@endsection

@section('content')

<h3 class="mb-4">Quản lý review</h3>

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
    <select class="form-control" name="filter" id="" onchange="this.form.submit()" style="width:initial">
        <option value="">Lọc</option>
        <option value="1" {{ request('filter') == 1 ? 'selected' : '' }}>1 sao</option>
        <option value="2" {{ request('filter') == 2 ? 'selected' : '' }}>2 sao</option>
        <option value="3" {{ request('filter') == 3 ? 'selected' : '' }}>3 sao</option>
        <option value="4" {{ request('filter') == 4 ? 'selected' : '' }}>4 sao</option>
        <option value="5" {{ request('filter') == 5 ? 'selected' : '' }}>5 sao</option>
    </select>
</form>
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
                <th>User id</th>
                <th>Tên sản phẩm</th>
                <th>Rate</th>
                <th>Review</th>
            </tr>
        </thead>
        <tbody>
            @if(count($reviews))
            @foreach($reviews as $key=>$review)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $review->user_id }}</td>
                <td>
                    <a class="text-decoration" href="{{ route('product.detail', ['product' => $review->product->slug ]) }}">{{ $review->product->name }}</a>
                </td>
                <td>{{ $review->rating }}</td>
                <td>{{  $review->message }}</td>
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
    {{ $reviews->links() }}
</div>
@endsection