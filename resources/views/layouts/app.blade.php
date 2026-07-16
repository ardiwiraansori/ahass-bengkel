<!--
=========================================================
* Soft UI Dashboard - v1.0.3
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2021 Creative Tim
* Licensed under MIT
=========================================================
-->

<!DOCTYPE html>

@if (Request::is('rtl'))
    <html dir="rtl" lang="ar">
@else
    <html lang="id">
@endif

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (env('IS_DEMO'))
        <x-demo-metas />
    @endif

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">

    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

    <title>
        @yield('title', 'AHASS Workshop')
    </title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet">

    <!-- Soft UI CSS -->
    <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css') }}?v=1.0.3" rel="stylesheet">

    @stack('styles')
</head>

<body
    class="g-sidenav-show bg-gray-100
    {{ Request::is('rtl') ? 'rtl' : '' }}
    {{ Request::is('virtual-reality') ? 'virtual-reality' : '' }}">

    @auth
        @yield('auth')
    @endauth

    @guest
        @yield('guest')
    @endguest

    @if (session()->has('success'))
        <div class="position-fixed bg-success rounded text-white text-sm py-2 px-4"
            style="right: 20px; top: 20px; z-index: 9999;">

            <p class="m-0">
                {{ session('success') }}
            </p>
        </div>
    @endif

    <!-- Soft UI Core JS -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>

    @stack('rtl')
    @stack('dashboard')

    <script>
        const isWindows = navigator.platform.indexOf('Win') > -1;

        if (
            isWindows &&
            document.querySelector('#sidenav-scrollbar') &&
            typeof Scrollbar !== 'undefined'
        ) {
            Scrollbar.init(
                document.querySelector('#sidenav-scrollbar'), {
                    damping: '0.5'
                }
            );
        }
    </script>

    <!-- Soft UI -->
    <script src="{{ asset('assets/js/soft-ui-dashboard.min.js') }}?v=1.0.3"></script>

    <!-- Hasil kompilasi Laravel Mix: jQuery dan Axios -->
    <script src="{{ mix('js/app.js') }}"></script>

    <!-- Konfigurasi AJAX Laravel -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'),

                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
