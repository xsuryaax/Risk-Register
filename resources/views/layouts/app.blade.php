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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('style/assets/css/soft-ui-dashboard.css?v=1.1.0') }}" rel="stylesheet" />

    <style>
        html {
            font-size: 14px;
        }

        .card {
            padding: 1rem;
        }

        .table td,
        .table th {
            padding: 0.5rem;
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
    </style>


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('css')
</head>


<body class="g-sidenav-show  bg-gray-100 navbar-fixed">
    
    @include('layouts.partials.sidebar')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        
        @include('layouts.partials.navbar')

        <div class="container-fluid py-4">
            
            @yield('content')

            <footer class="footer pt-3">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                ©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script>,
                                made with <i class="fa fa-heart"></i> by
                                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                                for a better web.
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>

    <!--   Core JS Files   -->
    <script src="{{ asset('style/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('style/assets/js/core/bootstrap.min.js') }}"></script>
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
</body>

</html>
