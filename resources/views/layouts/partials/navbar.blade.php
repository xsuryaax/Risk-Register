<nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 mx-md-4 border-radius-xl position-sticky blur shadow-blur mt-3 mt-md-4 left-auto top-1 z-index-sticky" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <!-- Brand & Toggle Section -->
        <div class="d-flex align-items-center">
            <!-- Desktop Hamburger Toggle -->
            <div class="d-none d-xl-block navbar-toggle-container pe-3">
                <div class="sidenav-toggler btn-sidebar-toggle" title="Toggle Sidebar">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>

            <!-- Hamburger Menu on the Left (Mobile Only) -->
            <div class="d-xl-none pe-3 py-1">
                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </a>
            </div>

            <!-- Page Title & Description -->
            <div class="d-flex flex-column justify-content-center">
                <h6 class="font-weight-bolder mb-0 text-dark header-title">@yield('page_title', 'Dashboard')</h6>
                <p class="text-xxs font-weight-bold text-secondary mb-0 header-subtitle d-none d-sm-block">
                    @yield('page_description', 'Sistem Manajemen Risiko')
                </p>
            </div>
        </div>
        
        <!-- User Actions Section -->
        <div class="ms-auto d-flex align-items-center">
            <ul class="navbar-nav justify-content-end align-items-center flex-row">
                <!-- User Profile -->
                <li class="nav-item d-flex align-items-center me-3">
                    <span class="text-xl me-2">👋</span>
                    <span class="text-md font-weight-bold text-dark">Hi, {{ auth()->user()->unit->nama_unit ?? 'Umum' }}</span>
                </li>

                <!-- Logout Button -->
                <li class="nav-item d-flex align-items-center border-start ps-2 ps-md-3 border-light">
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-icon-only mb-0 shadow-sm transition-all" style="background-color: #f5365c !important; color: white !important; width: 32px; height: 32px;" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Keluar">
                            <i class="fa fa-sign-out-alt text-xs"></i>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .header-title {
        line-height: 1.1; 
        font-size: 1.1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
    }
    
    @media (min-width: 576px) {
        .header-title { max-width: 300px; font-size: 1.25rem; }
    }

    @media (max-width: 575.98px) {
        .header-title { font-size: 0.95rem; }
        .mx-3 { margin-left: 10px !important; margin-right: 10px !important; }
        .navbar-main { padding-left: 5px !important; padding-right: 5px !important; }
    }

    .transition-all {
        transition: all 0.2s ease-in-out;
    }
    
    .btn-icon-only:hover {
        transform: scale(1.1);
        opacity: 0.9;
    }
</style>
