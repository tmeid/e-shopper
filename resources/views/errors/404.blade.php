@extends('errors.error')
@section('title')
    NOT FOUND | E-Shopper 
@endsection 

@section('content')
    <p style="font-size: 20px; font-weight: bold;">Rất tiếc, trang bạn tìm không tồn tại</p>
    <p>Click vào link sau để quay lại: <a href="{{ route('home') }}">E-shopper</a></p>
@endsection