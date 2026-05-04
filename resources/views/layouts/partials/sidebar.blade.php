<aside class="sidenav navbar navbar-vertical navbar-expand-xs my-3 fixed-start ms-3 bg-transparent" id="sidenav-main">
    <div class="sidenav-header d-flex align-items-center justify-content-between px-4">
        <a class="navbar-brand m-0 d-flex align-items-center p-0" href="{{ route('dashboard') }}">
            <img src="{{ asset('img/favicon_azra.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-2 font-weight-bolder" style="color: #007774;">Risk Register</span>
        </a>
        <a href="javascript:;" class="nav-link text-body p-0 d-xl-none" id="iconSidenav">
            <div class="sidenav-toggler-inner">
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
            </div>
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
                <a class="nav-link {{ request()->is('identifikasi-risiko*') ? 'active' : '' }}" href="{{ route('identifikasi-risiko.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-search text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Identifikasi Resiko</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('analisis-risiko*') ? 'active' : '' }}" href="{{ route('analisis-risiko.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-chart-pie text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Analisis Resiko</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('analisis-kecukupan*') ? 'active' : '' }}" href="{{ route('analisis-kecukupan.index') }}">
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
                <a class="nav-link {{ request()->is('master/kategori-risiko*') ? 'active' : '' }}" href="{{ route('kategori-risiko.index') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-tags text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Kategori Risiko</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('master/ruang-lingkup*') ? 'active' : '' }}" href="{{ route('ruang-lingkup.index') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-crosshairs text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Ruang Lingkup</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('master/probabilitas*') ? 'active' : '' }}" href="{{ route('probabilitas.index') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-clock text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Skala Probabilitas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('master/dampak*') ? 'active' : '' }}" href="{{ route('dampak.index') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-exclamation-triangle text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Skala Dampak</span>
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
