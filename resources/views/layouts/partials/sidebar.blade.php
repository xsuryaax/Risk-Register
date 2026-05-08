<aside class="sidenav navbar navbar-vertical navbar-expand-xs my-3 fixed-start ms-3 bg-transparent" id="sidenav-main">

    <div class="sidenav-header d-flex align-items-center justify-content-center px-4" style="position: relative;">
        <a class="navbar-brand m-0 d-flex align-items-center justify-content-center p-0 w-100" href="{{ route('dashboard') }}">
            <img src="{{ asset('img/favicon_azra.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-2 font-weight-bolder brand-text" style="color: #007774;">Risk Register</span>
        </a>

        <a href="javascript:;" class="nav-link text-body p-0 d-xl-none position-absolute" style="right: 1.5rem;" id="iconSidenav">
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
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6 nav-category-text">MENU UTAMA</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" data-mini-title="Dashboard">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-home text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('identifikasi-risiko*') ? 'active' : '' }}" href="{{ route('identifikasi-risiko.index') }}" data-mini-title="Identifikasi">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-search text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Identifikasi Resiko</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('analisis-risiko*') ? 'active' : '' }}" href="{{ route('analisis-risiko.index') }}" data-mini-title="Analisis">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-chart-pie text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Analisis Resiko</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('analisis-kecukupan*') ? 'active' : '' }}" href="{{ route('analisis-kecukupan.index') }}" data-mini-title="Kecukupan">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-balance-scale text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Analisis Kecukupan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('daftar-risiko*') ? 'active' : '' }}" href="{{ route('daftar-risiko.index') }}" data-mini-title="Daftar">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-list text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Daftar Lengkap</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('evaluasi-risiko*') ? 'active' : '' }}" href="{{ route('evaluasi-risiko.index') }}" data-mini-title="Evaluasi">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-check-square text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Evaluasi Resiko</span>
                </a>
            </li>



            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6 nav-category-text">MASTER DATA</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('master/kategori-risiko*') ? 'active' : '' }}" href="{{ route('kategori-risiko.index') }}" data-mini-title="Kategori">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-tags text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Kategori Risiko</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('master/ruang-lingkup*') ? 'active' : '' }}" href="{{ route('ruang-lingkup.index') }}" data-mini-title="Lingkup">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-crosshairs text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Ruang Lingkup</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('master/probabilitas*') ? 'active' : '' }}" href="{{ route('probabilitas.index') }}" data-mini-title="Probabilitas">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-clock text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Skala Probabilitas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('master/dampak*') ? 'active' : '' }}" href="{{ route('dampak.index') }}" data-mini-title="Dampak">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-exclamation-triangle text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Skala Dampak</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6 nav-category-text">PENGATURAN</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('master/hak-akses*') ? 'active' : '' }}"
                    href="{{ route('hak-akses.index') }}" data-mini-title="Hak Akses">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-lock text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Hak Akses</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('master/users*') ? 'active' : '' }}"
                    href="{{ route('users.index') }}" data-mini-title="Users">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-users text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajemen User</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('master/roles*') ? 'active' : '' }}"
                    href="{{ route('roles.index') }}" data-mini-title="Roles">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-user-tag text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajemen Role</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('master/units*') ? 'active' : '' }}"
                    href="{{ route('units.index') }}" data-mini-title="Units">
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
