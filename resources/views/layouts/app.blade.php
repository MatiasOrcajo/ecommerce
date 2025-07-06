<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @yield('title')

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link type="text/css" rel="stylesheet" href="{{asset('css/styles.css')}}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
            integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    <link type="text/css" rel="stylesheet" href="{{asset('css/toastr.css')}}">
    <script src="{{asset('/js/toastr.min.js')}}"></script>

    <style>
        body{
            background: #fffdfa;
        }
    </style>


    <!-- No olvides incluir el JS de Bootstrap (con Popper) justo antes del cierre de body -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="d-flex flex-column min-vh-100 ">
    @include('layouts.navbar')
    @include('layouts.popup')

    @isset($header)
        <header class=" shadow">
            <!-- … -->
        </header>
    @endisset

    <main class="flex-grow-1">
        @yield('content')
    </main>

    @include('layouts.footer')
</div>

<!-- jQuery (si lo necesitas para tus propios scripts) -->
<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<!-- Bootstrap Bundle (Popper incluido) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Tus scripts específicos -->
@stack('scripts')
</body>
</html>
