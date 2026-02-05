<style>


    .cart-panel-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .35);
        opacity: 0;
        pointer-events: none;
        transition: opacity .25s ease;
        z-index: 1040;
    }

    .cart-panel-overlay.is-open {
        opacity: 1;
        pointer-events: auto;
    }

    .cart-panel {
        position: fixed;
        top: 0;
        right: -420px;
        width: 420px;
        max-width: 100%;
        height: 100vh;
        background: #ffffff;
        box-shadow: -4px 0 20px rgba(15, 23, 42, .25);
        z-index: 1041;
        display: flex;
        flex-direction: column;
        transition: right .35s ease;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .cart-panel.is-open {
        right: 0;
    }

    .cart-panel-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .cart-panel-body {
        padding: 16px 20px;
        overflow-y: auto;
        flex: 1;
    }

    .cart-panel-footer {
        padding: 16px 20px 24px;
        border-top: 1px solid #e5e7eb;
        background: #ffffff;
    }

    .cart-panel-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 16px;
    }

    .cart-panel-item-img {
        width: 64px;
        height: 64px;
        border-radius: 4px;
        overflow: hidden;
        background: #f3f4f6;
        flex-shrink: 0;
    }

    .cart-panel-item-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-panel-item-info {
        flex: 1;
    }

    .cart-panel-item-name {
        font-size: 14px;
        font-weight: 500;
        color: #111827;
        margin-bottom: 4px;
    }

    .cart-panel-item-qty {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        border: 1px solid #d1d5db;
        overflow: hidden;
    }

    .cart-panel-item-qty button {
        border: none;
        background: #f9fafb;
        padding: 4px 10px;
        cursor: pointer;
        font-size: 16px;
        line-height: 1;
    }

    .cart-panel-item-qty span {
        padding: 2px 12px;
        font-size: 14px;
    }

    .cart-panel-item-price {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
        white-space: nowrap;
    }

    .cart-panel-btn-primary {
        background: #1f2933;
        color: #ffffff;
        border-radius: 999px;
        padding: 10px 16px;
        border: none;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .08em;
        cursor: pointer;
    }

    .cart-panel-btn-primary:hover {
        filter: brightness(1.05);
    }


    /* Ancho del offcanvas móvil al 80% */
    @media (max-width: 991.98px) {
        #mainOffcanvas {
            --bs-offcanvas-width: 80vw; /* 80% del viewport */
        }
    }

    /* Navbar transition styles */
    header {
        transition: transform 0.3s ease-in-out;
    }

    header.nav-up {
        transform: translateY(-100%);
    }

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
        animation: marquee 20s linear infinite;
    }

</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let lastScroll = 0;
        const header = document.querySelector('header');

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;

            if (currentScroll <= 0) {
                header.classList.remove('nav-up');
                return;
            }

            if (currentScroll > lastScroll && !header.classList.contains('nav-up')) {
                // Scroll Down
                header.classList.add('nav-up');
            } else if (currentScroll < lastScroll && header.classList.contains('nav-up')) {
                // Scroll Up
                header.classList.remove('nav-up');
            }

            lastScroll = currentScroll;
        });

        function formatMoneyAR(value) {
            const nf = new Intl.NumberFormat('es-AR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            return `$${nf.format(Number(value) || 0)}`;
        }

// Cargar la info del carrito desde /cart-info
        function loadCartPanelData() {
            $.ajax({
                type: 'GET',
                url: '/cart-info',
                success: function (cart) {
                    const products = cart.products || [];

                    const itemsHtml = products.map(function (p) {
                        // precio unitario aproximado (por las dudas)
                        const unitPrice = p.quantity ? (p.subtotal / p.quantity) : p.subtotal;

                        return `
                <div class="cart-panel-item">
                    <div class="cart-panel-item-img">
                        <img src="${p.pic}" alt="${p.product_name}">
                    </div>

                    <div class="cart-panel-item-info">
                        <div class="cart-panel-item-name p-0">${p.product_name}</div>

                        <div class="d-flex align-items-center">
                            <div class="color-box"
                                 data-color="${p.color}"
                                 title="${p.color_name}"
                                 style="background:${p.color}; width:18px; height:18px; margin-right:0.5rem;">
                            </div>
                            <span class="small text-muted">${p.color_name}</span>
                        </div>

                        <div class="cart-panel-item-qty">
                            <button type="button" class="cartProductQuantityButton" data-action="minus" data-id="${p.product_variant_id}">−</button>
                            <span>${p.quantity}</span>
                            <button type="button" class="cartProductQuantityButton" data-action="plus" data-id="${p.product_variant_id}">+</button>
                        </div>
                    </div>

                    <div class="cart-panel-item-price">
                        ${formatMoneyAR(unitPrice)}
                    </div>
                </div>`;
                    }).join('');

                    $('#cartPanelItems').html(itemsHtml);

                    const orderTotal = cart.order_total_after_coupon_applied ?? cart.order_total ?? 0;

                    $('#cartPanelTotal').text(formatMoneyAR(orderTotal));


                    function updateCartProductQuantity(productVariantId, action) {
                        axios.put('/carts/products/update-quantity', {
                            productVariantId: productVariantId,
                            action: action
                        })
                            .then(function () {
                                loadCartPanelData();
                            })
                            .catch(function (error) {
                                console.error('Error updating quantity:', error);
                            });
                    }

                    document.querySelectorAll('.cartProductQuantityButton').forEach(function (el) {
                        el.addEventListener('click', function (button) {
                            let productVariantId = button.target.getAttribute('data-id')
                            let action = button.target.getAttribute('data-action')
                            updateCartProductQuantity(productVariantId, action);
                        })
                    })

                },
                error: function () {
                    $('#cartPanelItems').html('<p>No se pudo cargar el carrito.</p>');
                    $('#cartPanelTotal').text('$0,00');
                }
            });
        }

        document.querySelectorAll('.js-open-cart').forEach(function (event) {

            event.addEventListener('click', function (e) {
                e.preventDefault();

                $('#cartPanelOverlay').addClass('is-open');
                $('#cartPanel').addClass('is-open');
                loadCartPanelData();

                $('#cartPanelOverlay').on('click', function () {
                    $('#cartPanelOverlay').removeClass('is-open');
                    $('#cartPanel').removeClass('is-open');
                });

                $('#cartPanelClose').on('click', function () {
                    $('#cartPanelOverlay').removeClass('is-open');
                    $('#cartPanel').removeClass('is-open');
                });
            })

        })


    });

</script>

<!-- Overlay oscuro -->
<div id="cartPanelOverlay" class="cart-panel-overlay"></div>

<!-- Panel lateral de carrito -->
<div id="cartPanel" class="cart-panel">
    <div class="cart-panel-header">
        <h5 class="mb-0">Carrito de Compras</h5>
        <button type="button" class="btn-close" id="cartPanelClose" aria-label="Cerrar"></button>
    </div>

    <div class="cart-panel-body">
        <div id="cartPanelItems"></div>
    </div>

    <div class="cart-panel-footer">

        <div class="cart-panel-total d-flex justify-content-between mt-2">
            <span class="fw-bold">Subtotal:</span>
            <span class="fw-bold fs-5" id="cartPanelTotal">$0,00</span>
        </div>

        <button class="cart-panel-btn-primary mt-3 w-100"
                onclick="window.location.href='{{ route('cart') }}'">
            INICIAR COMPRA
        </button>

        <a href="{{ route('cart') }}" class="d-block text-center mt-3 small text-decoration-underline">
            Ver más productos
        </a>
    </div>
</div>


<header class="d-none d-lg-block fixed-top">
    {{-- Top Bar Negra con Texto Deslizante --}}
    <div class="bg-dark text-white text-center" style="height:36px; line-height:36px; overflow:hidden;">
          <span class="animate-marquee d-inline-block" style="padding-left:100%; animation-duration: 20s;">
          ENVÍOS GRATIS A TODO EL PAÍS A PARTIR DE $35.000. 10% OFF PAGANDO CON TRANSFERENCIA. 20% OFF EN EFECTIVO.
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
                <a
                    class="position-relative text-dark js-open-cart">
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
                        <li><a class="dropdown-item" href="/search?q=SUMMER SALE">SUMMER SALE</a></li>
                        <li><a class="dropdown-item" href="{{route('category.show', 'bodys-reductores')}}">Bodys reductores</a></li>
                        <li><a class="dropdown-item" href="{{route('category.show', 'camisetas-reductoras')}}">Camisetas
                                reductoras</a></li>
                        <li><a class="dropdown-item" href="{{route('category.show', 'fajas-modeladoras')}}">Fajas
                                modeladoras</a></li>
                        <li><a class="dropdown-item" href="/search?q=Todos los productos">Todos los productos</a></li>
                    </ul>
                </li>
                <!-- ↑ Fin dropdown ↑ -->

                <li class="nav-item">
                    <a class="nav-link" href="{{route('sizes-guide')}}">Guía de talles</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('faqs')}}">Preguntas Frecuentes</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="https://wa.link/n5il16" target="_blank" style="font-weight: bold; text-decoration: underline">Necesito Ayuda</a>
                </li>
            </ul>
        </div>
    </nav>
</header>


{{-- mobile --}}
<header class="d-lg-none fixed-top">

    <div class="bg-dark text-white text-center" style="height:36px; line-height:36px; overflow:hidden;">
  <span class="animate-marquee d-inline-block" style="padding-left:100%">
    ENVÍOS GRATIS A TODO EL PAÍS A PARTIR DE $35.000. 10% OFF PAGANDO CON TRANSFERENCIA. 20% OFF EN EFECTIVO.
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
            <a class="d-flex align-items-center text-dark js-open-cart"
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
                                <li><a class="dropdown-item" href="/search?q=SUMMER SALE">SUMMER SALE</a></li>
                                <li><a class="dropdown-item" href="{{route('category.show', 'bodys-reductores')}}">Bodys reductores</a></li>
                                <li><a class="dropdown-item" href="{{route('category.show', 'camisetas-reductoras')}}">Camisetas
                                        reductoras</a></li>
                                <li><a class="dropdown-item" href="{{route('category.show', 'fajas-modeladoras')}}">Fajas
                                        modeladoras</a></li>
                                <li><a class="dropdown-item" href="/search?q=Todos los productos">Todos los productos</a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{route('sizes-guide')}}">Guía de talles</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{route('faqs')}}">Preguntas Frecuentes</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="https://wa.link/n5il16" target="_blank" style="font-weight: bold; text-decoration: underline">Necesito Ayuda</a>
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
