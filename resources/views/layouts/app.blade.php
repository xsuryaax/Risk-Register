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

    <style>
        /* Tom Select Premium Integration */
        .ts-wrapper .ts-control {
            border: 1px solid #d2d6da !important;
            border-radius: 0.5rem !important;
            padding: 2px 12px !important;
            font-size: 0.75rem !important;
            font-weight: 400 !important;
            color: #495057 !important;
            box-shadow: none !important;
            background-color: #fff !important;
            transition: all 0.2s ease;
            min-height: 32px !important;
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

        .btn-primary,
        .btn.bg-primary {
            background-color: #007774 !important;
            color: #fff !important;
            border: none !important;
            text-transform: uppercase !important;
            font-size: 0.7rem !important;
            letter-spacing: 1px !important;
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
            background-color: #ffc107 !important;
            color: #fff !important;
        }

        .btn-delete {
            background-color: #ff0000 !important;
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
            border-radius: 8px !important;
            border: 1px solid #e9ecef !important;
            color: #007774 !important;
            padding: 0.4rem 0.8rem;
            font-weight: 600;
        }

        .page-item.active .page-link {
            background-color: #007774 !important;
            border-color: #007774 !important;
            color: #ffffff !important;
            /* Force white for active page */
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

    <script>
        $(document).ready(function() {
            $('.select-search').each(function() {
                new TomSelect(this, {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
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
        });
    </script>

    <script src="{{ asset('style/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('style/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('style/assets/js/plugins/chartjs.min.js') }}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('style/assets/js/soft-ui-dashboard.min.js?v=1.1.0') }}"></script>
    @stack('js')
    <!-- Global Search Script -->
    <script>
        document.addEventListener('keyup', function(e) {
            if (e.target && e.target.id === 'searchTable') {
                const value = e.target.value.toLowerCase();
                const table = document.getElementById('mainTable');
                if (!table) return;
                
                const rows = table.getElementsByTagName('tr');

                for (let i = 1; i < rows.length; i++) {
                    const row = rows[i];
                    // Skip if it's the empty state row
                    if (row.querySelector('.empty-state')) continue;
                    
                    const text = row.innerText.toLowerCase();
                    if (text.includes(value)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            }
        });
    </script>
</body>

</html>
