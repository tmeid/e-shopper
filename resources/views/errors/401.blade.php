@extends('errors.error')
@section('title')
     UNAUTHORIZED | E-Shopper 
@endsection 

@section('content')
    <p style="font-size: 20px; font-weight: bold;">Rất tiếc, bạn không có quyền truy cập trang này</p>
    <p>Click vào link sau để quay lại: <a href="{{ route('home') }}">E-shopper</a></p>
@endsection