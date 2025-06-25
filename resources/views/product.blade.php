@extends('layouts.app')

@section('content')

    <style>

        .no-stock {
            opacity: 0.5;
            pointer-events: none;
            position: relative;
            border: 1px dashed #999 !important;
            color: #666 !important;
        }

        .no-stock::after {
            content: "Sin stock";
            position: absolute;
            bottom: 2px;
            right: 4px;
            font-size: 0.65rem;
            color: #999;
        }

        main {
            overflow-x: hidden;
        }

        .product-page-container {
            /* altura mínima para que, incluso en pantallas grandes,
       siempre se extienda hasta “casi” el footer */
            min-height: calc(100vh - 30rem);
            /* espacio final igual a la altura del footer */
            padding-bottom: 30rem;
        }

        @media (max-width: 991.98px) {
            main {
                padding-bottom: 8rem; /* espacio extra debajo para que no tape el footer */
            }

            .product-page-container {
                /* altura mínima para que, incluso en pantallas grandes,
           siempre se extienda hasta “casi” el footer */
                min-height: calc(100vh - 50rem);
                /* espacio final igual a la altura del footer */
                padding-bottom: 50rem;
            }
        }

        /* Contenedor y zoom */
        .zoom-container {
            overflow: hidden;
            position: relative;
            cursor: zoom-in;
        }

        .zoom-container.zoom-active img {
            transform: scale(2);
            cursor: zoom-out;
        }

        .zoom-container img {
            transition: transform 0.3s ease;
            display: block;
            width: 100%;
            height: auto;
            transform-origin: center center;
        }

        /* Thumbnails scrollables */
        .thumbnail-wrapper {
            position: relative;
        }

        .thumbnail-container {
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 0.5rem 2rem;
        }

        .thumbnail-item {
            flex: 0 0 auto;
            width: 60px;
            height: 60px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.2s;
        }

        .thumbnail-item.active {
            border-color: #000;
        }

        .product-image {
            max-height: 90vh;
            width: auto !important;
            max-width: 100%;
        }

        /* Flechas */
        .arrow-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 1.5rem;
            height: 1.5rem;
            background: rgba(0, 0, 0, 0.5);
            border: none;
            color: #fff;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            line-height: 1;
            cursor: pointer;
        }

        .arrow-left {
            left: 0.2rem;
        }

        .arrow-right {
            right: 0.2rem;
        }


        .zoom-container img {
            transition: transform 0.3s ease;
        }
        .zoom-container:hover img {
            transform: scale(1.8);
            cursor: zoom-out;
        }


    </style>

    <div class="product-page-container">
        <div class="container my-5 ">
            <div class="row gx-5">
                <div class="col-12 d-block d-md-none my-3">
                    <h2 style="font-size: 24px">{{$product->name}}</h2>
                </div>
                <!-- Imágenes -->
                <div class="col-md-6">

                    <!-- Carousel principal -->
                    <div id="productCarousel" class="carousel slide " data-bs-ride="false">
                        <div class="carousel-inner ">
                            @foreach($product->pictures as $index => $picture)
                                @if($index == 0)
                                    <div class="carousel-item active">
                                        @else
                                            <div class="carousel-item">
                                                @endif
                                                <div class="zoom-container">
                                                    <img
                                                        src="{{$picture->path}}"
                                                        alt="Producto 1"
                                                        class="d-block product-image"
                                                        style="margin: 0 auto;"
                                                    />
                                                </div>
                                            </div>
                                            @endforeach
                                    </div>
                                    <button
                                        class="carousel-control-prev"
                                        type="button"
                                        data-bs-target="#productCarousel"
                                        data-bs-slide="prev"
                                    >
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button
                                        class="carousel-control-next"
                                        type="button"
                                        data-bs-target="#productCarousel"
                                        data-bs-slide="next"
                                    >
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                        </div>

                        <!-- Thumbnails con flechas -->
                        <div class="thumbnail-wrapper mt-3">
                            <button class="arrow-btn arrow-left" id="thumbPrev">‹</button>
                            <div class="thumbnail-container">
                                @foreach($product->pictures as $index => $picture)
                                    <img
                                        src="{{$picture->path}}"
                                        @if($index == 0)
                                            class="thumbnail-item active"
                                        @else
                                            class="thumbnail-item"

                                        @endif
                                        data-bs-target="#productCarousel"
                                        data-bs-slide-to="0"
                                        alt="Mini 1"
                                    />
                                @endforeach
                            </div>
                            <button class="arrow-btn arrow-right" id="thumbNext">›</button>
                        </div>

                        <div class="my-3">
                            <div class="mb-4">
                                <div class="bg-light border rounded p-2">
                                    <em>Descripción</em>
                                </div>
                                <div class="mt-2">
                                    {!! $product->description !!}
                                </div>
                            </div>

                            <!-- Medidas -->
                            <div class="mb-4">
                                <div class="bg-light border rounded p-2">
                                    <em>Medidas</em>
                                </div>
                                <div class="mt-2">
                                    {!! $product->sizes_description !!}
                                </div>
                            </div>

                            <!-- Referencia Modelo -->
                            <div class="mb-4">
                                <div class="bg-light border rounded p-2">
                                    <em>Referencia Modelo</em>
                                </div>
                                <div class="mt-2">
                                    {!! $product->model_reference !!}
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Información del producto -->
                    <div class="col-md-6">
                        <h2 class="d-none d-md-block text-uppercase" style="font-size: 32px">{{$product->name}}</h2>

                        @php
                            $productPrice = $product->discount != 0 ? $product->price * (1 - ($product->discount / 100)) : $product->price;
                            $threeInstallments = round($productPrice/3, 2);
                            $productPriceWithBankTransferCondition = round($productPrice * (1 - (10 / 100)), 2);
                        @endphp

                        @if($product->discount != 0)

                            <p class="h4 text-dark"><small>
                                    <del>${{$product->price}}</del>
                                    %{{$product->discount}} off</small></p>
                            <p class="h3 text-dark">${{$productPrice}}</p>
                            <p class="text-secondary">${{$productPriceWithBankTransferCondition}} con Transferencia</p>
                            <div class="border p-2 d-inline-block my-3">
                                3 CUOTAS SIN INTERÉS DE <strong>${{$threeInstallments}}</strong>
                            </div>

                        @else

                            <p class="h3 text-dark">${{$product->price}}</p>
                            <p class="text-secondary">${{$productPriceWithBankTransferCondition}} con Transferencia</p>
                            <div class="border p-2 d-inline-block my-3">
                                3 CUOTAS SIN INTERÉS DE <strong>${{$threeInstallments}}</strong>
                            </div>
                        @endif

                        <div class="my-3">
                            <span class="me-2"><strong>Medios de pago:</strong></span>
                            <div class="d-flex gap-2">
                                <img
                                    src="https://logowik.com/content/uploads/images/mercado-pago3162.logowik.com.webp"
                                    data-src="https://logowik.com/content/uploads/images/mercado-pago3162.logowik.com.webp"
                                    class="me-1 mt-1" alt="visa" width="30" height="25">

                                <img
                                    src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/visa@2x.png"
                                    data-src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/visa@2x.png"
                                    class="me-1 mt-1" alt="visa" width="30" height="25">


                                <img
                                    src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/mastercard@2x.png"
                                    data-src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/mastercard@2x.png"
                                    class="me-1 mt-1" alt="mastercard" width="30" height="25">


                                <img
                                    src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/amex@2x.png"
                                    data-src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/amex@2x.png"
                                    class="me-1 mt-1" alt="amex" width="30" height="25">


                                <img
                                    src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/ar/tarjeta-naranja@2x.png"
                                    data-src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/ar/tarjeta-naranja@2x.png"
                                    class="me-1 mt-1" alt="ar_tarjeta-naranja" width="30" height="25">
                            </div>
                        </div>
                        <p><strong>10% de descuento</strong> pagando con transferencia</p>

                        <div class="my-3">
                            <label class="d-block mb-1"><strong>Color:</strong></label>
                            <div class="d-flex gap-2" id="colors-container">

                            </div>
                        </div>

                        <div class="my-4">
                            <label class="d-block mb-1" id="sizeSelector"><strong>Talle:</strong></label>
                            <div class="d-flex gap-2" id="sizes-container">

                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label"><strong>Cantidad</strong></label>
                            <input
                                id="quantity"
                                type="number"
                                class="form-control"
                                value="1"
                                min="1"
                                style="max-width:100px;"
                            />
                        </div>

                        <button id="add-product-to-cart" class="btn btn-dark btn-lg w-100">
                            AGREGAR AL CARRITO
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Lightbox Overlay -->
        <div id="lightbox-overlay">
            <span id="lightbox-close">&times;</span>
            <img id="lightbox-img" src="" alt="Ampliado">
        </div>

    </div>

    <style>


        /* ————— Lightbox ————— */
        #lightbox-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.85);
            justify-content: center;
            align-items: center;
            z-index: 2000;
            cursor: zoom-out;
        }
        #lightbox-overlay img {
            max-width: 90%;
            max-height: 90%;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
        #lightbox-close {
            position: absolute;
            top: 1rem; right: 1rem;
            font-size: 2rem;
            color: #fff;
            cursor: pointer;
            z-index: 2001;
        }

        /* ————— Hover-Zoom ————— */
        .zoom-container img {
            transition: transform 0.3s ease;
        }
        .zoom-container:hover img {
            transform: scale(1.8);
            cursor: zoom-out;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            font-size: 0.95rem;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
    </style>

    @push('scripts')
        <!-- Tu script de cantidad en el carrito -->
        <script src="{{ asset('js/updateCartProductsQuantity.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let availableColors, productsVariantsArray;
                let selectedColor, selectedSize;

                // Normaliza paths (puede ser objeto o array)
                function normalizePaths(pics) {
                    const p = pics.paths;
                    return Array.isArray(p) ? p : Object.values(p);
                }

                // Reconstruye el carousel según el color seleccionado
                function updateCarousel() {
                    const colorObj = availableColors.find(c => c.color === selectedColor);
                    if (!colorObj) return;
                    const paths = normalizePaths(colorObj.pics);

                    // Carousel inner
                    const innerHtml = paths.map((path, i) => `
                <div class="carousel-item ${i === 0 ? 'active' : ''}">
                    <div class="zoom-container d-flex justify-content-center align-items-center">
                        <img src="${path}"
                             class="d-block product-image"
                             alt="Producto ${i + 1}">
                    </div>
                </div>
            `).join('');
                    document.querySelector('#productCarousel .carousel-inner').innerHTML = innerHtml;

                    // Thumbnails
                    const thumbsHtml = paths.map((path, i) => `
                <img src="${path}"
                     class="thumbnail-item ${i === 0 ? 'active' : ''}"
                     data-bs-target="#productCarousel"
                     data-bs-slide-to="${i}"
                     alt="Mini ${i + 1}">
            `).join('');
                    document.querySelector('.thumbnail-container').innerHTML = thumbsHtml;

                    rebindCarousel();
                }

                // Vuelve a enlazar controles del carousel tras cambiar imágenes
                function rebindCarousel() {
                    const carouselEl = document.getElementById('productCarousel');

                    // Si ya existe una instancia, la eliminamos
                    if (carouselEl._bsCarousel) {
                        carouselEl._bsCarousel.dispose();
                    }

                    // Creamos una nueva
                    const carousel = new bootstrap.Carousel(carouselEl, {ride: false});
                    carouselEl._bsCarousel = carousel;

                    const thumbs = document.querySelectorAll('.thumbnail-item');
                    const thumbContainer = document.querySelector('.thumbnail-container');

                    // Flechas de scroll
                    document.getElementById('thumbPrev').onclick = () =>
                        thumbContainer.scrollBy({left: -100, behavior: 'smooth'});
                    document.getElementById('thumbNext').onclick = () =>
                        thumbContainer.scrollBy({left: 100, behavior: 'smooth'});

                    // Al cambiar slide, actualizo clase .active en thumbs
                    carouselEl.addEventListener('slid.bs.carousel', () => {
                        const items = Array.from(carouselEl.querySelectorAll('.carousel-item'));
                        const idx = items.findIndex(i => i.classList.contains('active'));
                        thumbs.forEach((t, i) => t.classList.toggle('active', i === idx));
                    });

                    // Click en miniatura mueve el carousel
                    thumbs.forEach((thumb, i) => {
                        thumb.addEventListener('click', () => carousel.to(i));
                    });
                }

                // Dibuja los botonitos de color
                function printAvailableColors() {
                    const container = document.getElementById('colors-container');
                    container.innerHTML = availableColors.map((c, i) => `
                <div class="btn btn-outline-secondary color-box"
                     data-color="${c.color}"
                     title="${c.color_name}"
                     style="background:${c.color}; width:32px; height:32px; cursor:pointer; margin-right:0.5rem;">
                </div>
            `).join('');

                    const boxes = container.querySelectorAll('.color-box');
                    boxes.forEach((box, i) => {
                        // Por defecto selecciono el primero
                        if (i === 0) box.style.outline = '1px solid black';

                        box.addEventListener('click', e => {
                            boxes.forEach(b => b.style.outline = 'none');
                            e.target.style.outline = '1px solid black';
                            selectedColor = e.target.dataset.color;
                            updateCarousel();
                            printAvailableSizes();
                        });
                    });

                    // Inicializo carousel con el primer color
                    selectedColor = availableColors[0].color;
                    updateCarousel();
                }

                // Dibuja los talles filtrados por color y añade eventos
                function printAvailableSizes() {
                    const sizesContainer = document.getElementById('sizes-container');
                    // Filtro variantes por color
                    const variants = productsVariantsArray.filter(v => v.color === selectedColor);
                    const arr = variants.map(v => ({size: v.size, stock: v.stock}));
                    // Orden personalizado
                    const isLetter = arr.every(s => isNaN(s.size));
                    const letterOrder = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
                    arr.sort((a, b) => isLetter
                        ? letterOrder.indexOf(a.size) - letterOrder.indexOf(b.size)
                        : parseInt(a.size) - parseInt(b.size)
                    );
                    // Render
                    sizesContainer.innerHTML = arr.map(s => `
                <div class="btn btn-outline-secondary size-box sizes ${s.stock == 0 ? 'no-stock' : ''}"
                     data-size="${s.size}"
                     data-stock="${s.stock}"
                     style="cursor:pointer; margin-right:0.5rem;">
                  ${s.size}
                </div>
            `).join('');

                    // Eventos de selección
                    document.querySelectorAll('.size-box').forEach(box => {
                        box.addEventListener('click', e => {
                            document.querySelectorAll('.size-box').forEach(b => b.classList.remove('active'));
                            e.target.classList.add('active');
                            selectedSize = e.target.dataset.size;
                            document.getElementById('sizeSelector').innerHTML =
                                '<strong>Talle: </strong>' + selectedSize;
                        });
                    });
                }

                // Traigo datos desde el controlador
                fetch("{{ route('product.variants.show', $product->id) }}")
                    .then(res => res.json())
                    .then(data => {
                        availableColors = data.availableColors;
                        productsVariantsArray = data.productsVariantsArray;
                        printAvailableColors();
                        printAvailableSizes();
                    })
                    .catch(err => console.error(err));


                // ————— Configuro Lightbox —————
                const overlay = document.getElementById('lightbox-overlay');
                const overlayImg = document.getElementById('lightbox-img');
                const closeBtn  = document.getElementById('lightbox-close');

// Abre lightbox al click sobre la imagen principal del carousel
                document.querySelector('#productCarousel').addEventListener('click', e => {
                    const img = e.target.closest('.zoom-container img');
                    if (!img) return;
                    overlayImg.src = img.src;
                    overlay.style.display = 'flex';
                });

// Cierra lightbox si clickeo el fondo o la X
                [overlay, closeBtn].forEach(el =>
                    el.addEventListener('click', () => overlay.style.display = 'none')
                );


                // ————— Zoom sigue al mouse —————
                document.querySelectorAll('.zoom-container').forEach(container => {
                    const img = container.querySelector('img');
                    container.addEventListener('mousemove', e => {
                        const rect = container.getBoundingClientRect();
                        // calcular porcentaje de posición dentro del container
                        const x = ((e.clientX - rect.left) / rect.width) * 100;
                        const y = ((e.clientY - rect.top) / rect.height) * 100;
                        // mover el origen del zoom
                        img.style.transformOrigin = `${x}% ${y}%`;
                    });
                    // opcional: al salir, vuelves al centro
                    container.addEventListener('mouseleave', () => {
                        img.style.transformOrigin = `center center`;
                    });
                });

            });
        </script>
    @endpush

@endsection
