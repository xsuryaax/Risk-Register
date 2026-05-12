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
                <!-- Active Periode Dropdown: Premium Teal Dual-Tone -->
                @if($globalActivePeriode)
                @php
                    $currentViewId = request('view_periode', $globalActivePeriode->id);
                    $currentViewPeriode = $globalPeriodes->firstWhere('id', $currentViewId) ?? $globalActivePeriode;
                @endphp
                <li class="nav-item d-flex align-items-center me-3 d-none d-md-flex dropdown">
                    <a href="#" class="d-flex align-items-center shadow-sm border-radius-lg overflow-hidden text-decoration-none" 
                       style="height: 30px; border: 1px solid rgba(0, 119, 116, 0.2);" 
                       id="dropdownPeriode" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="px-2 h-100 d-flex align-items-center" style="background-color: rgba(0, 119, 116, 0.08);">
                            <i class="fa fa-calendar-alt text-teal me-1" style="font-size: 10px; color: #007774;"></i>
                            <span class="text-uppercase font-weight-bolder" style="font-size: 9px; letter-spacing: 0.5px; color: #007774;">
                                @if(Route::is('dashboard')) Lihat Periode @else Periode @endif
                            </span>
                            <i class="fa fa-chevron-down ms-1 text-teal" style="font-size: 8px; color: #007774;"></i>
                        </div>
                        <div class="px-3 h-100 d-flex align-items-center" style="background-color: #007774;">
                            <span class="text-white font-weight-bold" style="font-size: 12px; letter-spacing: 0.5px;">{{ $currentViewPeriode->tahun }}</span>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 border-radius-lg mt-2 py-2" aria-labelledby="dropdownPeriode">
                        <li class="px-3 py-1 mb-1 border-bottom">
                            <span class="text-xxs font-weight-bolder text-uppercase text-secondary">Pilih Tahun Analisis</span>
                        </li>
                        @foreach($globalPeriodes as $p)
                        <li>
                            <a class="dropdown-item py-2 px-3 border-radius-md {{ $currentViewId == $p->id ? 'bg-soft-teal active-periode' : '' }}" 
                               href="{{ route('dashboard', ['view_periode' => $p->id]) }}">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="font-weight-bold {{ $currentViewId == $p->id ? 'text-teal' : 'text-dark' }}" style="font-size: 0.8rem;">
                                        {{ $p->tahun }}
                                    </span>
                                    @if($p->status)
                                        <span class="badge badge-sm bg-gradient-info border-radius-sm" style="font-size: 0.55rem; padding: 0.25em 0.5em;">AKTIF</span>
                                    @endif
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endif

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
    
    .bg-soft-info { background-color: rgba(33, 150, 243, 0.1) !important; }
    .bg-soft-danger { background-color: rgba(244, 67, 54, 0.1) !important; }
    
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

    .text-teal { color: #007774 !important; }
    .bg-soft-teal { background-color: rgba(0, 119, 116, 0.08) !important; }
    .active-periode { border-left: 3px solid #007774; }
</style>
