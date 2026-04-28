<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 border-radius-xl position-sticky blur shadow-blur mt-4 left-auto top-1 z-index-sticky" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-2 px-3">
        <div class="d-flex flex-column justify-content-center">
            <h6 class="font-weight-bolder mb-0 text-dark" style="line-height: 1.2; font-size: 1.1rem;">@yield('page_title', 'Dashboard')</h6>
            <span class="text-xxs font-weight-bold text-secondary" style="display: block; margin-top: -1px;">
                @yield('page_description', 'Selamat datang di Sistem Manajemen Risiko')
            </span>
        </div>
        
        <div class="ms-md-auto d-flex align-items-center">
            <ul class="navbar-nav justify-content-end align-items-center">
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center me-3">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
                
                <li class="nav-item d-flex align-items-center me-3">
                    <span class="text-xl me-2">👋</span>
                    <span class="text-md font-weight-bold text-dark">Hi, {{ auth()->user()->unit->nama_unit ?? 'Umum' }}</span>
                </li>

                <li class="nav-item d-flex align-items-center ms-2 border-start ps-3 border-light">
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-icon-only mb-0 shadow-sm" style="background-color: #ff0000 !important; color: white !important; width: 34px; height: 34px;" data-bs-toggle="tooltip" title="Keluar Aplikasi">
                            <i class="fa fa-sign-out-alt text-sm"></i>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

