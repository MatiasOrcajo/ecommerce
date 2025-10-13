<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @yield('title')
    @php
        use Illuminate\Support\Str;
        $vst = request()->cookie('vst') ?? Str::uuid()->toString();
    @endphp
    <meta name="vst-token" content="{{ $vst }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('PERFIL-05.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('PERFIL-05.png') }}" type="image/x-icon">

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
            background: #ffffff;
        }

        .whatsapp-float {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #25d366;
            color: white;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            text-align: center;
            font-size: 30px;
            box-shadow: 2px 2px 3px #999;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            opacity: 0.8;
            visibility: visible;
        }

        .whatsapp-float:hover {
            background-color: #128C7E;
            color: white;
        }

        .whatsapp-float.hidden {
            opacity: 0;
            visibility: hidden;
        }
    </style>


    <!-- Meta Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1503691540658440');
        fbq('track', 'PageView');
    </script>
    <!-- End Meta Pixel Code -->
</head>
<body class="font-sans antialiased">

<noscript>
    <img height="1" width="1" style="display:none"
         src="https://www.facebook.com/tr?id=1503691540658440&ev=PageView&noscript=1"/>
</noscript>

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

    @yield('footer')
</div>

<!-- jQuery (si lo necesitas para tus propios scripts) -->
<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<!-- Bootstrap Bundle (Popper incluido) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Tus scripts específicos -->
@stack('scripts')
<a href="https://wa.link/y0c4mg" class="whatsapp-float" target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- Scripts -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    window.addEventListener('scroll', function () {
        const whatsappFloat = document.querySelector('.whatsapp-float');
        const scrollHeight = document.documentElement.scrollHeight;
        const scrollTop = window.scrollY;
        const clientHeight = document.documentElement.clientHeight;

        if (scrollTop + clientHeight >= scrollHeight - 100) {
            whatsappFloat.classList.add('hidden');
        } else {
            whatsappFloat.classList.remove('hidden');
        }
    });
</script>


<script>
    (function(){
        try {
            const token = document.querySelector('meta[name="vst-token"]')?.content;
            const csrf  = document.querySelector('meta[name="csrf-token"]')?.content;
            if(!token || !csrf) return;

            const payload = {
                token,
                tz: Intl.DateTimeFormat().resolvedOptions().timeZone || null,
                sw: screen?.width || null,
                sh: screen?.height || null,
                pn: Math.round(performance?.now?.() || 0),
                _token: csrf
            };

            const send = ()=> {
                if (navigator.sendBeacon) {
                    const ok = navigator.sendBeacon('/v-beacon', new Blob([JSON.stringify(payload)], {type:'application/json'}));
                    if(!ok) fetch('/v-beacon', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(payload)});
                } else {
                    fetch('/v-beacon', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(payload)});
                }
            };

            send();
            document.addEventListener('visibilitychange', ()=>{ if(document.visibilityState==='visible') send(); }, {once:true});
        } catch(e){}
    })();
</script>




</body>
</html>
