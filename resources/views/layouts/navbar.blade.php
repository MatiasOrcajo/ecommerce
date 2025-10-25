<style>
    /* Ancho del offcanvas móvil al 80% */
    @media (max-width: 991.98px) {
        #mainOffcanvas {
            --bs-offcanvas-width: 80vw; /* 80% del viewport */
        }
    }
</style>


<header class="d-none d-lg-block fixed-top">
    {{-- Top Bar Negra con Texto Deslizante --}}
    <div class="bg-dark text-white text-center" style="height:36px; line-height:36px; overflow:hidden;">
  <span class="animate-marquee d-inline-block" style="padding-left:100%; animation-duration: 20s;">
  ENVÍOS GRATIS A TODO EL PAÍS. 10% OFF PAGANDO CON TRANSFERENCIA. 20% OFF EN EFECTIVO.
</span>

    </div>

    {{-- Navbar Blanca (buscador, logo, carrito) --}}
    <nav class="navbar navbar-expand-lg navbar-light pb-0 px-3" style="background:#ffffff">
        <div class="container-fluid d-flex align-items-center">
            <div class="col-lg-4 d-none d-lg-flex justify-content-start">
                <form class="d-flex align-items-center w-100" style="max-width: 220px;" METHOD="GET"
                      ACTION="{{ route('search') }}">
                    <input class="form-control border-0 border-bottom rounded-0 w-100" type="search"
                           placeholder="Buscar" style="background-color:#ffffff" name="q">
                    <button class="btn p-0 ms-2" type="submit"><i class="bi bi-search"></i></button>
                </form>
            </div>

            <div class="col-12 col-lg-4">
                <div class="d-flex justify-content-center">
                    <a class="navbar-brand d-flex justify-content-center align-items-center" href="{{ route('index') }}"
                       style="font-size: 2.5rem; letter-spacing: 0.1rem;">
                        <img
                            src="{{ asset('LOGO_PNG.png') }}"
                            alt="Logo Ática"
                            width="118" height="40"
                            decoding="async"
                            style="height:auto"
                        />
                    </a>
                </div>
            </div>

            <div class="col-lg-4 d-none d-lg-flex justify-content-end align-items-center">
                <a href="{{ route('cart') }}" class="position-relative text-dark">
                    <i class="fa-solid fa-cart-shopping fs-5"></i>
                    <span id="cart_counter"
                          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">0</span>
                </a>
            </div>
        </div>
    </nav>

    {{-- Navbar de Links --}}
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm p-0" style="background:#ffffff">
        <div class="container-fluid">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="/">Inicio</a>
                </li>

                <!-- ↓ Dropdown Collections ↓ -->
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        id="collectionsDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        Productos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="collectionsDropdown">
                        <li><a class="dropdown-item" href="{{route('category.show', 'bodys')}}">Bodys</a></li>
                        <li><a class="dropdown-item" href="{{route('category.show', 'camiseta-reductora')}}">Camiseta
                                reductora</a></li>
                        <li><a class="dropdown-item" href="{{route('category.show', 'faja-reductora')}}">Faja
                                reductora</a></li>
                        <li><a class="dropdown-item" href="/search?q=Todos los productos">Todos los productos</a></li>
                    </ul>
                </li>
                <!-- ↑ Fin dropdown ↑ -->

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('index') }}#footer">Contacto</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('faqs')}}">Preguntas Frecuentes</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="https://wa.link/n5il16" target="_blank">Necesito Ayuda</a>
                </li>
            </ul>
        </div>
    </nav>
</header>


{{-- mobile --}}
<header class="d-lg-none fixed-top">

    <div class="bg-dark text-white text-center" style="height:36px; line-height:36px; overflow:hidden;">
  <span class="animate-marquee d-inline-block" style="padding-left:100%">
    ENVÍOS GRATIS A TODO EL PAÍS. 10% OFF PAGANDO CON TRANSFERENCIA. 20% OFF EN EFECTIVO.
  </span>
    </div>

    <!-- Navbar Principal -->
    <nav class="navbar navbar-light shadow-sm" style="background:#fff; position:relative; min-height:64px;">
        <div class="container-fluid">

            <!-- Izquierda: toggler -->
            <button class="navbar-toggler" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#mainOffcanvas"
                    aria-controls="mainOffcanvas"
                    style="position:absolute; left:12px; top:10px; z-index:2;">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Centro: logo ABSOLUTO (siempre centrado) -->
            <a class="navbar-brand m-0 p-0"
               href="{{ route('index') }}"
               style="position:absolute; left:50%; top:8px; transform:translateX(-50%); z-index:1;">
                <img src="{{ asset('LOGO_PNG.png') }}"
                     alt="Logo Ática"
                     width="118" height="40" decoding="async" style="height:auto;">
            </a>

            <!-- Derecha: carrito -->
            <a href="{{ route('cart') }}" class="d-flex align-items-center text-dark"
               style="position:absolute; right:12px; top:12px; z-index:2;">
                <i class="fa-solid fa-cart-shopping fs-4"></i>
                <span class="badge bg-dark text-white ms-2" id="cart_counter_responsive">0</span>
            </a>
            <!-- Offcanvas: Buscador + Carrito + Enlaces -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="mainOffcanvas" aria-labelledby="offcanvasLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasLabel">Menú</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">

                    <!-- Buscador -->
                    <form class="d-flex mb-4" METHOD="GET" ACTION="{{ route('search') }}">
                        <input class="form-control rounded-0 border-bottom" type="search" placeholder="Buscar" name="q"
                               aria-label="Buscar">
                        <button class="btn ms-2" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>

                    <!-- Enlaces -->
                    <ul class="navbar-nav mb-4">
                        <li class="nav-item"><a class="nav-link active" href="/">Inicio</a></li>
                        <!-- Elemento desplegable -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="productDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                Productos
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="productDropdown">
                                <li><a class="dropdown-item" href="{{route('category.show', 'bodys')}}">Bodys</a></li>
                                <li><a class="dropdown-item" href="{{route('category.show', 'camiseta-reductora')}}">Camiseta
                                        reductora</a></li>
                                <li><a class="dropdown-item" href="{{route('category.show', 'faja-reductora')}}">Faja
                                        reductora</a></li>
                                <li><a class="dropdown-item" href="/search?q=Todos los productos">Todos los
                                        productos</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('index') }}#footer">Contacto</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{route('faqs')}}">Preguntas Frecuentes</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="https://wa.link/n5il16" target="_blank">Necesito Ayuda</a>
                        </li>
                    </ul>

                </div>
            </div>

            <!-- Íconos en pantallas lg+ -->
            <div class="d-none d-lg-flex align-items-center ms-auto">
                <!-- Buscador inline -->
                <form class="d-flex align-items-center me-4" style="max-width:200px;" METHOD="GET"
                      ACTION="{{ route('search') }}">
                    <input class="form-control rounded-0 border-bottom" type="search" placeholder="Buscar" name="q">
                    <button class="btn ms-2" type="submit"><i class="bi bi-search"></i></button>
                </form>
                <!-- Carrito -->
                <a href="{{ route('cart') }}" class="position-relative text-dark fs-5">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">0</span>
                </a>
            </div>

        </div>
    </nav>

</header>

<style>
    /* Animación marquee CSS (reemplaza <marquee>) */
    @keyframes marquee {
        0% {
            transform: translateX(100%);
        }
        100% {
            transform: translateX(-100%);
        }
    }

    .animate-marquee {
        white-space: nowrap;
        animation: marquee 15s linear infinite;
    }
</style>


{{-- Espaciado para que el contenido no quede detrás del header fijo --}}
<style>
    body {
        padding-top: 120px;
    }

    /* Desktop: altura reservada */
    @media (max-width: 991.98px) {
        body {
            padding-top: 72px;
        }

        /* Mobile: top bar + navbar compacta */
    }

</style>
