@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Đăng nhập') }}</div>
                @if(session('msg'))
                    <p class="alert alert-{{ session('type') }}" style="text-align: center;">
                        {{ session('msg') }}
                    </p>
                @endif

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Mật khẩu') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Quên mật khẩu?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <p  class="text-center mt-3" style="font-size: 18px;">User: dthuy3319@gmail.com (pass: 123456789)</p>
    <p  class="text-center mt-3" style="font-size: 18px;">Admin: admin@gmail.com (pass: 123456789)</p>
    <ul class="text-center mt-3 " style="font-size: 18px; list-style-type: none;">
        <li>Thẻ test VNPAY</li>
        <li>Ngân hàng: NCB</li>
        <li>Số thẻ: 9704198526191432198</li>
        <li>Tên chủ thẻ: NGUYEN VAN A</li>
        <li>Ngày phát hành: 07/15</li>
        <li>Mật khẩu OTP: 123456</li>
    </ul>
</div>
@endsection
