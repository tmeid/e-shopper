<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-text mx-3">E-shopper</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('user.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Quản lý</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOrder" aria-expanded="true" aria-controls="collapseOrder">
            <i class="fas fa-fw fa-cog"></i>
            <span>Đơn hàng</span>
        </a>
        <div id="collapseOrder" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('user.order.order')}}">Tất cả đơn</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUserInfo" aria-expanded="true" aria-controls="collapseUserInfo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Thông tin</span>
        </a>
        <div id="collapseUserInfo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('user.info.infoUser') }}">Xem</a>
                <a class="collapse-item" href="{{ route('user.showFormchangePass') }}">Đổi mật khẩu</a>
                <a class="collapse-item" href="{{ route('user.address.index') }}">Địa chỉ</a>
            </div>
        </div>
    </li>

</ul>
<!-- End of Sidebar -->