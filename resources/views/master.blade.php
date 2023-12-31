<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="E-shopper" name="keywords">
    <meta content="E-commerce" name="description">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('imgs/logo/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('imgs/logo/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('imgs/favicon-16x16.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

   

    <!-- Google Web Fonts -->
    <link href="{{ asset('vendor/css/fonts.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="{{ asset('vendor/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/all.min.css') }}" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('vendor/css/owlcarousel.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('vendor/css/style.css') }}" rel="stylesheet">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('vendor/js/jquery.min.js') }}"></script>

    @yield('css')
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row align-items-center py-3 px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a href="" class="text-decoration-none">
                    <h1 class="m-0 display-5 font-weight-semi-bold"><a class="text-decoration-none" href="{{ route('home') }}"><span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper</a></h1>
                </a>
            </div>
            <div class="col-lg-6 col-8 text-left">
                <form action="{{ route('shop.products') }}">
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
           
            <div class="col-lg-3 col-4 text-right">
                <a href="{{ route('cart') }}" class="btn border">
                    <i class="fas fa-shopping-cart text-primary"></i>
                    <span class="badge">{{ $total_items_order }}</span>
                </a>
            </div>
           
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid mb-3">
        <div class="row border-top px-xl-5">
            <div class="col-lg">
                <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                    <a href="{{ route('home') }}" class="text-decoration-none d-block d-lg-none">
                        <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper</h1>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="{{ route('home') }}" class="nav-item nav-link active">Trang chủ</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Danh mục</a>
                                <div class="dropdown-menu rounded-0 m-0">
                                    @if(count($categories))
                                    @foreach($categories as $category)
                                        <a href="{{ route('shop.category', ['category' => $category->slug]) }}" class="dropdown-item">{{ $category->name }}</a>
                                    @endforeach
                                    @endif
                                   
                                </div>
                            </div>

                        </div>
                        <div class="navbar-nav ml-auto py-0">
                            @guest
                            @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Đăng nhập') }}</a>
                            </li>
                            @endif

                            @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Đăng ký') }}</a>
                            </li>
                            @endif
                            @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu custom-dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ Auth::user()->role === 1 ? route('admin.index') : route('user.index') }}">
                                        {{ __('Tài khoản') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            @endguest
                        </div>
                    </div>
                </nav>

                @yield('banner')
            </div>
        </div>
    </div>
    <!-- Navbar End -->

    @yield('content')

    <!-- Footer Start -->
    <footer class="container-fluid bg-secondary text-dark mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                
                <h1 class="mb-4 display-5 font-weight-semi-bold"><a href="{{ route('home') }}" class="text-dark text-decoration-none link-hover"><span class="text-primary font-weight-bold border border-white px-3 mr-1">E</span>Shopper</a></h1>
                
                <p>Chúng tôi luôn hướng đến sự tự tin, sang trọng và thanh lịch trong cuộc sống</p>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>123 Nguyễn Lương Bằng, Đà Nẵng</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>eshopper.dn@gmail.com</p>
                <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>+012 345 67890</p>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">Links</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-dark mb-2 text-decoration-none link-hover" href="{{ route('home') }}"><i class="fa fa-angle-right mr-2"></i>Trang chủ</a>
                            <a class="text-dark mb-2 text-decoration-none link-hover" href="{{ route('shop.products') }}"><i class="fa fa-angle-right mr-2"></i>Shop now</a>
                        </div>
                    </div>               
                </div>
            </div>
        </div>
        <div class="row border-top border-light mx-xl-5 py-4">
            <div class="col px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-dark">
                    &copy; <a class="text-dark font-weight-semi-bold text-decoration-none link-hover" href="{{ route('home') }}">E-Shopper</a>. All Rights Reserved. Designed
                    by <a class="text-dark font-weight-semi-bold text-decoration-none link-hover" href="https://htmlcodex.com">HTML Codex. </a>
                    Customized by Diem Thuy Huynh
                </p>
            </div>
        </div>
    </footer>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    @yield('js')
    
    <script src="{{ asset('vendor/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/js/easing.min.js') }} "></script>
    <script src="{{ asset('vendor/js/owl.carousel.min.js') }}"></script>
</body>

</html>