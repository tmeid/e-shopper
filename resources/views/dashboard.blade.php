<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/all.min.css') }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{  asset('vendor/css/sb-admin-2.min.css') }}" rel="stylesheet">

</head>

<body id="page-top" style="color: #000;">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @yield('sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    
                    @yield('content')
                    
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; E-shopper</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Bạn có chắc chắn muốn đăng xuất?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Nhấn vào Logout để thoát phiên làm việc</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Huỷ</button>
                        <a class="btn btn-primary" href="{{ route('logout') }}" onclick="event.preventDefault(); 
                        document.querySelector('.logoutForm').submit() ">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <form class="logoutForm" action="{{ route('logout') }}" method="POST">
            @csrf
        </form>

        <!-- Bootstrap core JavaScript-->
        <!-- JavaScript Libraries -->
        <script src="{{ asset('vendor/js/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/js/bootstrap.min.js') }}"></script>

        <script src="{{ asset('admins') }}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="{{ asset('vendor/js/easing.min.js') }} "></script>

        <!-- Custom scripts for all pages-->
        <script src="{{ asset('vendor/js/sb-admin-2.min.js') }}"></script>


</body>

</html>