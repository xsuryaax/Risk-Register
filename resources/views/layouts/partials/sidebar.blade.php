<aside class="sidenav navbar navbar-vertical navbar-expand-xs my-3 fixed-start ms-3 bg-transparent" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('img/favicon_azra.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-2 font-weight-bolder" style="color: #007774;">Risk Register</span>
        </a>


    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto h-auto" id="sidenav-collapse-main">

        <ul class="navbar-nav" style="padding-bottom: 50px;">

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">MENU UTAMA</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-home text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:;">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-search text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Identifikasi Resiko</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:;">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-chart-pie text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Analisis Resiko</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:;">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-balance-scale text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Analisis Kecukupan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:;">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-list text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Daftar Lengkap</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:;">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-check-square text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Evaluasi Resiko</span>
                </a>
            </li>



            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">MASTER DATA</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:;">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-database text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Master Resiko</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">PENGATURAN</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('master/hak-akses*') ? 'active' : '' }}"
                    href="{{ route('hak-akses.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-lock text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Hak Akses</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('master/users*') ? 'active' : '' }}"
                    href="{{ route('users.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-users text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajemen User</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('master/roles*') ? 'active' : '' }}"
                    href="{{ route('roles.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-user-tag text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajemen Role</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('master/units*') ? 'active' : '' }}"
                    href="{{ route('units.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-building text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajemen Unit</span>
                </a>
            </li>
        </ul>

    </div>


</aside>
