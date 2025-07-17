
<header class="d-none d-lg-block fixed-top">
    {{-- Top Bar Negra con Texto Deslizante --}}
    <div class="bg-dark text-white text-center py-0">
        <marquee behavior="scroll" direction="left">
            ENVÍOS GRATIS A TODO EL PAÍS. 10% OFF PAGANDO CON TRANSFERENCIA.
        </marquee>
    </div>

    {{-- Navbar Blanca (buscador, logo, carrito) --}}
    <nav class="navbar navbar-expand-lg navbar-light pb-0 px-3" style="background:#ffffff">
        <div class="container-fluid d-flex align-items-center">
            <div class="col-lg-4 d-none d-lg-flex justify-content-start">
                <form class="d-flex align-items-center w-100" style="max-width: 220px;">
                    <input class="form-control border-0 border-bottom rounded-0 w-100" type="search" placeholder="Buscar" style="background-color:#ffffff">
                    <button class="btn p-0 ms-2" type="submit"><i class="bi bi-search"></i></button>
                </form>
            </div>

            <div class="col-12 col-lg-4">
                <div class="d-flex justify-content-center">
                    <a class="navbar-brand fw-bold m-0 text-center" href="{{ route('index') }}"
                       style="font-size: 2.5rem; letter-spacing: 0.1rem;">
                        Ática
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
                        Collections
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="collectionsDropdown">
                        <li><a class="dropdown-item" href="#">Collection A</a></li>
                        <li><a class="dropdown-item" href="#">Collection B</a></li>
                        <li><a class="dropdown-item" href="#">Collection C</a></li>
                    </ul>
                </li>
                <!-- ↑ Fin dropdown ↑ -->

                <li class="nav-item">
                    <a class="nav-link" href="#">Contacto</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<header class="d-lg-none fixed-top">

    <!-- Navbar Principal -->
    <nav class="navbar navbar-expand-lg navbar-light  shadow-sm" style="background:#ffffff">
        <div class="container-fluid">

            <!-- Toggler Offcanvas -->
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mainOffcanvas"
                    aria-controls="mainOffcanvas">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Logo Centrado (siempre) -->
            <a class="navbar-brand mx-auto mx-lg-0 fw-bold" href="#" style="font-size:2rem; letter-spacing:0.1rem;">
                Ática
            </a>

            <!-- Offcanvas: Buscador + Carrito + Enlaces -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="mainOffcanvas" aria-labelledby="offcanvasLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasLabel">Menú</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">

                    <!-- Buscador -->
                    <form class="d-flex mb-4">
                        <input class="form-control rounded-0 border-bottom" type="search" placeholder="Buscar"
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
                            <a class="nav-link dropdown-toggle" id="productDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Collections
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="productDropdown">
                                <li><a class="dropdown-item" href="#">Collection A</a></li>
                                <li><a class="dropdown-item" href="#">Collection A</a></li>
                                <li><a class="dropdown-item" href="#">Collection A</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
                    </ul>

                    <!-- Carrito -->
                    <a href="#" class="d-flex align-items-center text-dark">
                        <i class="fa-solid fa-cart-shopping fs-4"></i>
                        <span class="badge bg-dark text-white ms-2" id="cart_counter_responsive">0</span>
                        <span class="ms-2">Carrito</span>
                    </a>

                </div>
            </div>

            <!-- Íconos en pantallas lg+ -->
            <div class="d-none d-lg-flex align-items-center ms-auto">
                <!-- Buscador inline -->
                <form class="d-flex align-items-center me-4" style="max-width:200px;">
                    <input class="form-control rounded-0 border-bottom" type="search" placeholder="Buscar">
                    <button class="btn ms-2" type="submit"><i class="bi bi-search"></i></button>
                </form>
                <!-- Carrito -->
                <a href="#" class="position-relative text-dark fs-5">
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


    @media (max-width: 991.98px) {
        body {
            padding-top: 3rem;
        }
    }

</style>
