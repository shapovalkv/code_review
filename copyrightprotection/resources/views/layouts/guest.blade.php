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

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/scss/app.scss'])
    <!-- Scripts -->
    <wireui:scripts />
    <script src="//unpkg.com/alpinejs" defer></script>
    @livewireStyles
</head>
<body class="font-sans text-gray-900 antialiased">
{{ $slot }}

@livewireScripts
@stack('scripts')

@include('components.home.footer')
@include('components.dashboard.footer')
</body>
</html>
