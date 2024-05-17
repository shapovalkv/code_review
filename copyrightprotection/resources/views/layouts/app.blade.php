<!DOCTYPE html>
<html data-bs-theme="{{ $_COOKIE['theme'] ?? 'light' }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- ===============================================-->
        <!--    Favicons-->
        <!-- ===============================================-->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicons/favicon1-180-180.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicons/favicon1-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicons/favicon1-16x16.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/favicons/favicon1.png') }}">
        <link rel="manifest" href="{{ asset('assets/img/favicons/favicon1.png') }}">
        <meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">
        <meta name="theme-color" content="#ffffff">
        <script src="{{ asset('vendors/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('vendors/dropzone/dropzone.min.js') }}"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- ===============================================-->
        <!--    Stylesheets-->
        <!-- ===============================================-->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
        <link href="{{ asset('vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
        <link href="{{ asset('vendors/dropzone/dropzone.min.css') }}" rel="stylesheet">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/scss/app.scss'])
        <!-- Scripts -->
        <wireui:scripts />
        <script src="//unpkg.com/alpinejs" defer></script>
        @livewireStyles
    </head>
    <body>
        <div>
            <!-- Page Heading -->
            @if (isset($header))
                <header>
                        {{ $header }}
                </header>
            @endif

            <!-- Page Content -->
            <main class="main" id="top">
                <div class="container" data-layout="container">
                    <script>
                        var isFluid = JSON.parse(localStorage.getItem('isFluid'));
                        if (isFluid) {
                            var container = document.querySelector('[data-layout]');
                            container.classList.remove('container');
                            container.classList.add('container-fluid');
                        }
                    </script>
                    @include('components.dashboard.nav-bar')
                    <div class="content">
                        @include('components.dashboard.header')
                        @include('parts.flash-message')
                        {{ $slot }}
                    </div>
                </div>
            </main>

            @include('components.dashboard.footer')

            @if (isset($footer))
                    {{ $footer }}
            @endif

        </div>
        @livewireScripts
        @stack('scripts')
    </body>
</html>
