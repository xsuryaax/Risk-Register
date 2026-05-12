<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/favicon_azra.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon_azra.png') }}">

    <title>
        @yield('title', 'Soft UI Dashboard 3')
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('style/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('style/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('style/assets/css/soft-ui-dashboard.css?v=1.1.0') }}" rel="stylesheet" />

    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Tom Select Premium Integration */
        .ts-wrapper .ts-control {
            border: 1px solid #d2d6da !important;
            border-radius: 0.5rem !important;
            padding: 0px 12px !important;
            font-size: 0.75rem !important;
            font-weight: 400 !important;
            color: #495057 !important;
            box-shadow: none !important;
            background-color: #fff !important;
            transition: all 0.2s ease;
            min-height: 31px !important;
            display: flex;
            align-items: center;
        }

        .input-group>.ts-wrapper {
            flex: 1 1 auto;
            width: 1% !important;
        }

        /* Proper Input Group Border Radius Fixing */
        .input-group {
            display: flex;
            flex-direction: row;
            border: 1px solid #d2d6da !important;
            border-radius: 0.5rem !important;
            overflow: visible !important;
        }

        .input-group .input-group-text {
            background-color: #f8f9fa !important;
            border: none !important;
            min-width: 40px;
            justify-content: center;
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }

        .input-group .input-group-text:first-child {
            border-right: 1px solid #d2d6da !important;
            border-top-left-radius: 0.5rem !important;
            border-bottom-left-radius: 0.5rem !important;
        }

        .input-group .input-group-text:last-child {
            border-left: 1px solid #d2d6da !important;
            border-top-right-radius: 0.5rem !important;
            border-bottom-right-radius: 0.5rem !important;
        }

        .input-group>.form-control,
        .input-group>.form-select,
        .input-group>.ts-wrapper {
            border: none !important;
            border-radius: 0 !important;
            flex: 1 1 auto;
            width: 1% !important;
            /* Standard Bootstrap flex grow trick */
        }

        .input-group>.form-control:focus,
        .input-group>.form-select:focus {
            box-shadow: none !important;
            z-index: 5;
        }

        .input-group:focus-within {
            border-color: #007774 !important;
            box-shadow: 0 0 0 2px rgba(0, 119, 116, 0.1);
        }

        .input-group-text i {
            color: #67748e;
            font-size: 0.75rem;
        }

        .ts-wrapper.focus .ts-control {
            border-color: #007774 !important;
            box-shadow: 0 0 0 2px rgba(0, 119, 116, 0.1);
        }

        .ts-dropdown {
            border: 1px solid #d2d6da !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            margin-top: 5px !important;
            z-index: 10000 !important;
            font-size: 0.8rem !important;
        }

        .ts-dropdown-content {
            overscroll-behavior: contain;
            /* Fix for scroll chaining */
        }

        .ts-dropdown .active {
            background-color: #007774 !important;
            /* Azra Teal */
            color: #fff !important;
        }

        .ts-control input {
            font-size: 0.75rem !important;
        }

        /* Global Soft UI Overrides to remove Tacky Gradients */
        .bg-primary,
        .bg-gradient-primary {
            background-color: #007774 !important;
            background-image: none !important;
        }

        .bg-secondary,
        .bg-gradient-secondary {
            background-color: #8392ab !important;
            background-image: none !important;
        }

        .bg-success,
        .bg-gradient-success {
            background-color: #198754 !important;
            background-image: none !important;
        }

        .bg-info,
        .bg-gradient-info {
            background-color: #11cdef !important;
            background-image: none !important;
        }

        .bg-warning,
        .bg-gradient-warning {
            background-color: #f59e0b !important;
            background-image: none !important;
        }

        .bg-danger,
        .bg-gradient-danger {
            background-color: #ef4444 !important;
            background-image: none !important;
        }

        .bg-dark,
        .bg-gradient-dark {
            background-color: #1c1c1c !important;
            background-image: none !important;
        }

        /* Table Bottom Separator */
        .table tbody tr:last-child td {
            border-bottom: 2px solid #dee2e6 !important;
        }

        .table-bordered-light td,
        .table-bordered-light th {
            border: 1px solid #f1f5f9 !important;
        }

        .btn-primary,
        .btn.bg-primary {
            background-color: #007774 !important;
            color: #fff !important;
            border: none !important;
            text-transform: uppercase !important;
            font-size: 0.7rem !important;
            letter-spacing: 0.5px !important;
            font-weight: 700 !important;
            padding: 12px 24px !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 6px -1px rgba(0, 119, 116, 0.2), 0 2px 4px -1px rgba(0, 119, 116, 0.1) !important;
            transition: all 0.25s ease !important;
        }

        .btn-primary:hover,
        .btn.bg-primary:hover {
            background-color: #005f5c !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 7px 14px -3px rgba(0, 119, 116, 0.3), 0 4px 6px -2px rgba(0, 119, 116, 0.2) !important;
        }

        /* Custom Table Borders */
        .table-bordered-light td,
        .table-bordered-light th {
            border-right: 1px solid #e9ecef !important;
        }

        .table-bordered-light th:last-child,
        .table-bordered-light td:last-child {
            border-right: none !important;
        }

        .btn-primary:active,
        .btn.bg-primary:active {
            transform: translateY(0px) !important;
        }

        .nav-link.active .icon-shape {
            background-color: #007774 !important;
            background-image: none !important;
            color: #fff !important;
        }

        .nav-link.active .icon-shape i {
            color: #fff !important;
        }

        .nav-link.active span {
            color: #007774 !important;
            font-weight: bold;
        }

        /* Status Toggle Premium Component */
        .status-toggle-group {
            display: flex;
            background: #f8f9fa;
            padding: 4px;
            border-radius: 10px;
            border: 1px solid #d2d6da;
        }

        .status-toggle-item {
            flex: 1;
            text-align: center;
        }

        .status-toggle-item input {
            display: none;
        }

        .status-toggle-label {
            display: block;
            padding: 6px 10px;
            cursor: pointer;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 700;
            color: #67748e;
            margin-bottom: 0;
            transition: all 0.2s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-toggle-item input:checked+.status-toggle-label {
            background: #007774;
            color: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 119, 116, 0.2);
        }

        .cursor-pointer {
            cursor: pointer !important;
        }

        /* TomSelect Fixes */
        .ts-control {
            min-width: 130px !important;
            /* Default minimal width */
            padding: 0.35rem 0.5rem !important;
            border-radius: 8px !important;
            font-size: 0.75rem !important;
            border: 1px solid #d2d6da !important;
            display: flex !important;
            align-items: center !important;
        }

        /* Specific compact width for color filter */
        .select-pewarna+.ts-wrapper .ts-control {
            width: 140px !important;
            min-width: 140px !important;
        }

        /* Flexible width for unit filter */
        .select-filter:not(.select-pewarna)+.ts-wrapper .ts-control {
            min-width: 180px !important;
        }

        .ts-wrapper.single .ts-control {
            background-color: #fff !important;
        }

        .ts-dropdown {
            min-width: 160px !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }

        .color-indicator {
            width: 25px;
            height: 12px;
            display: inline-block;
            margin-right: 8px;
            border-radius: 2px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }
    </style>

    <style>
        html {
            font-size: 12.5px;
        }

        .card {
            padding: 0.5rem !important;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border-radius: 10px;
        }

        /* Sidebar Transparent */
        .navbar-vertical {
            background-color: transparent !important;
            border-right: none !important;
            box-shadow: none !important;
        }

        .table td,
        .table th {
            padding: 0.35rem 0.4rem !important;
        }

        .card-header {
            padding: 0.5rem 1rem !important;
        }

        .card-body {
            padding: 0.5rem !important;
        }

        .navbar-vertical .navbar-nav>.nav-item .nav-link.active .icon {
            background-image: linear-gradient(310deg, #007774 0%, #015c59 100%);
        }

        .navbar-vertical .navbar-nav>.nav-item .nav-link.active .icon i {
            color: #fff !important;
        }

        .btn-primary,
        .bg-gradient-primary {
            background-image: linear-gradient(310deg, #007774 0%, #015c59 100%);
        }

        .btn-primary:hover {
            background-color: #007774;
            border-color: #007774;
        }

        /* Stats Card Professional Styling */
        .card-stats {
            border-left: 5px solid #007774;
            /* Thicker Border */
        }

        .card-stats.success {
            border-left-color: #2dce89;
        }

        .card-stats.danger {
            border-left-color: #f5365c;
        }

        .icon-shape-premium {
            width: 48px;
            height: 48px;
            background: #ffffff;
            /* White background for icons */
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #007774;
            font-size: 1.2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .success .icon-shape-premium {
            color: #2dce89;
        }

        .danger .icon-shape-premium {
            color: #f5365c;
        }

        /* Table Header Styling */
        .table thead th {
            background-color: #f1f4f8 !important;
            /* Match Page BG */
            color: #000000 !important;
            text-transform: uppercase;
            font-size: 0.7rem !important;
            font-weight: 800 !important;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #007774 !important;
        }

        .btn-action {
            width: 26px;
            height: 26px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.2s;
            color: #fff !important;
            font-size: 0.75rem !important;
        }

        .btn-edit {
            background-color: #ffeb3b !important;
            /* Canary Yellow */
            color: #fff !important;
        }

        .btn-delete {
            background-color: #dc3545 !important;
            /* Standard Red */
            color: #fff !important;
        }

        .btn-action:hover {
            background-color: #000000 !important;
            color: #fff !important;
            opacity: 0.8;
        }

        .form-control-sm {
            padding: 0.5rem 0.75rem !important;
            border-radius: 8px !important;
        }

        /* Professional Accent Colors */
        .text-strong {
            color: #252f40;
            font-weight: 700 !important;
        }

        /* Pagination Refined Styling */
        .pagination {
            gap: 4px;
        }

        .page-link {
            border-radius: 50% !important;
            border: 1px solid #e9ecef !important;
            color: #007774 !important;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.75rem;
            transition: all 0.2s ease;
        }

        .page-item.active .page-link {
            background-color: #007774 !important;
            border-color: #007774 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 6px -1px rgba(0, 119, 116, 0.3);
        }

        .page-item.disabled .page-link {
            opacity: 0.5;
            background-color: #f8f9fa;
        }

        .page-item:not(.active):not(.disabled) .page-link:hover {
            background-color: #e9ecef;
            color: #007774;
            transform: translateY(-2px);
        }

        /* Aggressive Hide for "Showing X to Y" across Tailwind & Bootstrap */
        nav p,
        nav div.flex-1.sm\:hidden,
        nav div.d-none.flex-fill.d-sm-flex>div:first-child,
        .pagination-info,
        .card-footer nav div:first-child:not(:last-child) {
            display: none !important;
        }

        /* Center the pagination list */
        nav .justify-content-sm-between,
        nav .justify-between {
            justify-content: center !important;
            display: flex !important;
            width: 100%;
        }
    </style>


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Glassmorphism Sidebar for Mobile */
        @media (max-width: 1199.98px) {
            .sidenav {
                background-color: rgba(255, 255, 255, 0.85) !important;
                backdrop-filter: blur(12px) !important;
                -webkit-backdrop-filter: blur(12px) !important;
                border-right: 1px solid rgba(255, 255, 255, 0.2) !important;
                margin: 0 !important;
                border-radius: 0 !important;
                height: 100vh !important;
                max-height: 100vh !important;
                top: 0 !important;
                bottom: 0 !important;
                left: 0 !important;
            }

            .navbar-main .container-fluid {
                flex-wrap: nowrap !important;
            }
        }

        /* ===== SIDEBAR MINIMIZE SYSTEM (REFINED) ===== */

        /* Sidebar transition */
        #sidenav-main {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-x: hidden !important;
        }

        /* Toggle Button Visibility */
        @media (min-width: 1200px) {

            /* Hide sidebar toggle when mini */
            body.sidebar-mini .sidebar-toggle-container {
                display: none !important;
            }

            /* Navbar toggle always available on desktop */
            .navbar-toggle-container {
                display: block !important;
            }
        }

        /* Sidenav Toggler Style & Animation (Clearer) */
        .sidenav-toggler-line {
            background-color: #344767 !important;
            /* Standard Soft UI color for clarity */
            transition: all 0.2s ease !important;
        }

        .btn-sidebar-toggle {
            cursor: pointer;
            padding: 2px;
            transition: all 0.2s ease;
        }

        .btn-sidebar-toggle .sidenav-toggler-inner {
            width: 18px !important;
        }

        .btn-sidebar-toggle .sidenav-toggler-line {
            width: 18px !important;
            height: 2px !important;
            /* Clearer weight */
            margin-bottom: 4px !important;
            border-radius: 2px;
        }

        /* Navbar Toggle Specific Alignment */
        .navbar-toggle-container {
            margin-top: 5px;
            /* Slight push to align with text baseline */
        }

        .btn-sidebar-toggle:hover .sidenav-toggler-line {
            background-color: #007774 !important;
        }

        /* Main content transition */
        .main-content {
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        /* Text elements transition for fade out */
        .nav-link-text,
        .brand-text,
        .nav-category-text {
            transition: opacity 0.3s ease, width 0.3s ease;
            white-space: nowrap;
            overflow: hidden;
        }

        /* ===== MINIMIZED STATE ===== */
        @media (min-width: 1200px) {
            body.sidebar-mini #sidenav-main {
                width: 80px !important;
                min-width: 80px !important;
                max-width: 80px !important;
            }

            body.sidebar-mini .main-content {
                margin-left: 80px !important;
            }

            body.sidebar-mini .nav-link-text,
            body.sidebar-mini .brand-text {
                opacity: 0;
                width: 0;
                pointer-events: none;
                visibility: hidden;
            }

            body.sidebar-mini .nav-category-text {
                opacity: 0;
                width: 0;
                height: 0;
                overflow: hidden;
                pointer-events: none;
                visibility: hidden;
            }

            body.sidebar-mini .sidenav-header {
                justify-content: center !important;
                padding: 1.5rem 0 !important;
            }

            body.sidebar-mini .sidenav-header .navbar-brand {
                justify-content: center !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }

            body.sidebar-mini .sidenav-header .navbar-brand img {
                margin-right: 0 !important;
            }

            body.sidebar-mini .brand-text {
                display: none !important;
            }

            body.sidebar-mini .navbar-nav .nav-link {
                justify-content: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            body.sidebar-mini .navbar-nav .nav-link .icon,
            body.sidebar-mini .navbar-nav .nav-link .icon-shape {
                margin: 0 !important;
                left: 0;
            }

            body.sidebar-mini .navbar-nav .nav-link {
                padding: 10px 0 !important;
                display: flex !important;
                justify-content: center !important;
            }

            body.sidebar-mini .icon-shape {
                width: 32px !important;
                height: 32px !important;
                min-width: 32px !important;
                min-height: 32px !important;
                display: flex !important;
                align-items: center;
                justify-content: center;
            }

            body.sidebar-mini .nav-item.mt-3 {
                display: flex;
                justify-content: center;
            }

            body.sidebar-mini .navbar-nav .nav-item.mt-3 {
                margin-top: 0.5rem !important;
            }

            body.sidebar-mini .navbar-nav .nav-link.active {
                background-color: transparent !important;
                box-shadow: none !important;
            }

            /* Toggle icon direction */
            body.sidebar-mini #sidebarToggleIcon {
                transform: rotate(180deg);
            }

            #sidebarToggleIcon {
                transition: transform 0.3s ease;
            }

            /* Tooltip on hover for mini mode */
            body.sidebar-mini .navbar-nav .nav-link {
                position: relative;
            }

            body.sidebar-mini .navbar-nav .nav-link:hover::after {
                content: attr(data-mini-title);
                position: absolute;
                left: calc(100% + 12px);
                top: 50%;
                transform: translateY(-50%);
                background: #1c1c1c;
                color: #fff;
                padding: 5px 12px;
                border-radius: 6px;
                font-size: 0.7rem;
                font-weight: 600;
                white-space: nowrap;
                z-index: 10000;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                pointer-events: none;
                animation: tooltipFadeIn 0.15s ease;
            }

            body.sidebar-mini .navbar-nav .nav-link:hover::before {
                content: '';
                position: absolute;
                left: calc(100% + 6px);
                top: 50%;
                transform: translateY(-50%);
                border: 5px solid transparent;
                border-right-color: #1c1c1c;
                z-index: 10001;
                pointer-events: none;
                animation: tooltipFadeIn 0.15s ease;
            }
        }

        @keyframes tooltipFadeIn {
            from {
                opacity: 0;
                transform: translateY(-50%) translateX(-4px);
            }

            to {
                opacity: 1;
                transform: translateY(-50%) translateX(0);
            }
        }
    </style>
    @stack('css')
</head>


<body class="g-sidenav-show bg-gray-100 navbar-fixed">

    @include('layouts.partials.sidebar')

    <main class="main-content position-relative border-radius-lg d-flex flex-column"
        style="background-color: #e9ecef !important; min-height: 100vh;">

        @include('layouts.partials.navbar')

        <div class="container-fluid py-4 d-flex flex-column flex-grow-1" style="min-height: calc(100vh - 70px);">
            <div class="flex-grow-1 pb-5">
                @yield('content')
            </div>

            <footer class="footer py-3 mt-auto">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                Risk Register RS Azra -
                                <script>
                                    document.write(new Date().getFullYear())
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>

    <!--   Core JS Files   -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('style/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('style/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <script src="{{ asset('style/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('style/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('style/assets/js/plugins/chartjs.min.js') }}"></script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('style/assets/js/soft-ui-dashboard.min.js?v=1.1.0') }}"></script>

    <script>
        // Global AJAX Navigation Function
        function loadAjax(url, target = '#tableContainer') {
            if ($(target).length) {
                $(target).css('opacity', '0.5'); // Visual feedback

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        // Check if the returned data contains the target element
                        const $fetched = $(data);
                        let html;

                        if ($fetched.find(target).length) {
                            html = $fetched.find(target).html();
                        } else if ($fetched.is(target)) {
                            html = $fetched.html();
                        } else {
                            // If it's a partial (like _table.blade.php) it might not have the wrapper
                            html = data;
                        }

                        $(target).html(html);
                        $(target).css('opacity', '1');

                        // Re-init tooltips with correct container
                        if (window.bootstrap && bootstrap.Tooltip) {
                            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                            tooltipTriggerList.map(function(tooltipTriggerEl) {
                                // Dispose existing
                                const existing = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
                                if (existing) existing.dispose();
                                
                                return new bootstrap.Tooltip(tooltipTriggerEl, {
                                    container: 'body',
                                    trigger: 'hover'
                                });
                            });
                        }

                        // Update URL without reload
                        window.history.pushState({}, '', url);
                    }
                });
            } else {
                window.location.href = url;
            }
        }

        $(document).ready(function() {
            // Fix Sidebar Scroll Isolation
            var win = navigator.platform.indexOf('Win') > -1;
            if (win && document.querySelector('#sidenav-collapse-main')) {
                var options = {
                    damping: '0.5',
                    continuousScrolling: false // Prevent scroll propagation to body
                }
                Scrollbar.init(document.querySelector('#sidenav-collapse-main'), options);
            }

            $('.select-search').each(function() {
                new TomSelect(this, {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            });

            // Unified Filter Initialization
            $('.select-filter').each(function() {
                const isPewarna = $(this).hasClass('select-pewarna');
                const config = {
                    create: false,
                    allowEmptyOption: true,
                    // Enabled plugins: for non-pewarna, add dropdown_input to ensure search is visible
                    plugins: isPewarna ? [] : ['dropdown_input'],
                    controlInput: isPewarna ? null : undefined,
                };

                if (isPewarna) {
                    const colorMap = {
                        'sangat tinggi': '#c00000',
                        'tinggi': '#ff9900',
                        'sedang': '#ffeb3b',
                        'rendah': '#0d6efd',
                        'sangat rendah': '#198754'
                    };
                    config.render = {
                        option: function(data, escape) {
                            if (!data.value) return '<div class="px-2 py-1">' + escape(data.text) +
                                '</div>';
                            var color = colorMap[data.value.toLowerCase()] || 'transparent';
                            return '<div class="px-2 py-1 d-flex align-items-center"><div style="background-color:' +
                                escape(color) +
                                '; width:16px; height:16px; border-radius:50%; margin-right:8px; flex-shrink:0; border:1px solid rgba(0,0,0,0.1);"></div><span class="text-dark font-weight-normal">' +
                                escape(data.text) + '</span></div>';
                        },
                        item: function(data, escape) {
                            if (!data.value) return '<div class="px-2 py-1">' + escape(data.text) +
                                '</div>';
                            var color = colorMap[data.value.toLowerCase()] || 'transparent';
                            return '<div class="px-2 py-1 d-flex align-items-center"><div style="background-color:' +
                                escape(color) +
                                '; width:16px; height:16px; border-radius:50%; margin-right:8px; flex-shrink:0; border:1px solid rgba(0,0,0,0.1);"></div><span class="text-dark font-weight-normal">' +
                                escape(data.text) + '</span></div>';
                        }
                    };
                }

                new TomSelect(this, config);
            });

            // Global Password Toggle Logic
            $(document).on('click', '.toggle-password', function() {
                const input = $(this).siblings('.password-field');
                const icon = $(this).find('i');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // AJAX Pagination - Global Handler
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                loadAjax($(this).attr('href'));
            });

            // Global Search Script
            $(document).on('keyup', '#searchTable', function() {
                const value = $(this).val().toLowerCase();
                const table = $('#mainTable');
                if (!table.length) return;

                table.find('tbody tr').each(function() {
                    const row = $(this);
                    if (row.find('.empty-state').length) return;

                    const text = row.text().toLowerCase();
                    row.toggle(text.indexOf(value) > -1);
                });
            });
        });

        $(document).ready(function() {
            // Restore state from localStorage
            if (localStorage.getItem('sidebarMini') === 'true') {
                document.body.classList.add('sidebar-mini');
                $('.btn-sidebar-toggle').removeClass('active');
            }

            $('.btn-sidebar-toggle').on('click', function(e) {
                e.preventDefault();
                const isMini = document.body.classList.toggle('sidebar-mini');

                // Toggle active class for animation
                if (isMini) {
                    $('.btn-sidebar-toggle').removeClass('active');
                } else {
                    $('.btn-sidebar-toggle').addClass('active');
                }

                // Persist state
                localStorage.setItem('sidebarMini', isMini);
            });
        });
    </script>

    @stack('js')
</body>

</html>
