@extends('layouts.app')

@section('title')
    <title>{{$product->name}} - Atica</title>
@endsection

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
            min-height: auto;
            padding-bottom: 30rem;
        }

        @media (max-width: 991.98px) {
            @media (max-width: 991.98px) {
                .product-page-container {
                    padding-bottom: 0;
                    margin-top: 100px; /* reemplaza el translateY */
                    transform: none; /* clave */
                }
            }

        }

        /* Contenedor y zoom */
        .zoom-container {
            overflow: hidden;
            position: relative;
            cursor: zoom-in;
            height: 80vh;
        }

        .zoom-container img {
            transition: transform 0.3s ease;
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
            transform-origin: center center;
        }

        .zoom-container:hover img {
            transform: scale(1.8);
            cursor: zoom-out;
        }

        @media (max-width: 991.98px) {
            /* el culpable principal: que NO tenga 80vh en mobile */
            .zoom-container {
                height: auto;
            }

            /* limitá la imagen, no el contenedor */
            .product-image {
                height: auto;
                max-height: 60dvh; /* 55–65dvh suele ir bien */
                width: 100% !important;
            }

            /* el hover-zoom no aplica en touch (evita “saltos”) */
            .zoom-container:hover img {
                transform: none;
                cursor: auto;
            }
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

        /* Lightbox */
        #lightbox-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            justify-content: center;
            align-items: center;
            z-index: 2000;
            cursor: zoom-out;
        }

        #lightbox-overlay img {
            max-width: 90%;
            max-height: 90%;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        #lightbox-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 2rem;
            color: #fff;
            cursor: pointer;
            z-index: 2001;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            font-size: 0.95rem;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .carousel-video { width: 100%; }
        .carousel-video iframe { width: 100%; height: 100%; border: 0; }
    </style>

    <div class="product-page-container">
        <div class="container my-md-5">
            <div class="row gx-5">

                <div class="col-md-6" style="margin-top: 3rem">
                    <div id="productCarousel" class="carousel slide" data-bs-ride="false">
                        <div class="carousel-inner">
                            @foreach($product->pictures as $index => $picture)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <div class="zoom-container d-flex justify-content-center align-items-center">
                                        <img src="{{ $picture->path }}"
                                             alt="Producto {{ $index + 1 }}"
                                             class="d-block product-image"
                                             style="margin: 0 auto;"/>
                                    </div>
                                </div>
                            @endforeach

                            @if(!empty($product->youtube_link))
                                <div class="carousel-item">
                                    <div class="ratio ratio-16x9 carousel-video">
                                        <iframe
                                            data-youtube-src="{{ $product->youtube_link }}"
                                            src=""
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen
                                        ></iframe>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                                data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                                data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>

                    <!-- Thumbnails con flechas -->
                    <div class="thumbnail-wrapper mt-3">
                        <button class="arrow-btn arrow-left" id="thumbPrev">‹</button>
                        <div class="thumbnail-container">
                            @foreach($product->pictures as $index => $picture)
                                <img src="{{ $picture->path }}"
                                     class="thumbnail-item {{ $index === 0 ? 'active' : '' }}"
                                     data-bs-target="#productCarousel"
                                     data-bs-slide-to="{{ $index }}"
                                     alt="Mini {{ $index + 1 }}"/>
                            @endforeach
                        </div>
                        <button class="arrow-btn arrow-right" id="thumbNext">›</button>
                    </div>

                    <!-- Descripciones -->
                    <div class="col-12">
                        <div class="my-3 d-none d-md-block">
                            <div class="mb-4">
                                <div class="bg-light border rounded p-2"><em>Descripción</em></div>
                                <div class="mt-2">{!! $product->description !!}</div>
                            </div>
                            <div class="mb-4">
                                <div class="bg-light border rounded p-2"><em>Medidas</em></div>
                                <div class="mt-2">{!! $product->sizes_description !!}</div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Información del producto -->
                <div class="col-md-6">
                    <h2 class="text-uppercase my-2" style="font-size: 32px">{{ $product->name }}</h2>
                    @php
                        // Precios base
                        $productPrice  = $product->discount
                            ? $product->price * (1 - $product->discount/100)
                            : $product->price;

                        $transferPrice = $productPrice * 0.90; // 10% off por transferencia
                        $cashPrice = $productPrice * 0.80; // 20% off en efectivo

                        // Helper local para formatear moneda AR
                        $money = fn ($v) => '$' . number_format((float)$v, 2, ',', '.');
                    @endphp

                    @if ($product->discount)
                        <p class="h4 text-dark">
                            <small>
                                <del>{{ $money($product->price) }}</del>
                                {{ $product->discount }}% OFF
                            </small>
                        </p>
                        <p class="text-dark" style="font-size: 3rem;">{{ $money($productPrice) }}</p>
                    @else
                        <p class="text-dark" style="font-size: 3rem;">{{ $money($productPrice) }}</p>
                    @endif

                    <p class="text-secondary"
                       style="font-size: 1.5rem; color: #785c64 !important">{{ $money($transferPrice) }} con
                        Transferencia</p>
                    <p class="text-secondary"
                       style="font-size: 1.5rem; color: #785c64 !important">{{ $money($cashPrice) }} en efectivo</p>

                    <span id="flex-shipping-title" style="color: green; font-weight: bold; font-size: 19px"></span>
                    <br>
                    <span
                        style="text-decoration: underline; cursor: pointer; font-size: 15px">Válido para CABA y GBA</span>

                    <div class="my-3">
                        <span class="me-2"><strong>Medios de pago:</strong></span>
                        <div class="d-flex gap-2">
                            <img src="https://logowik.com/content/uploads/images/mercado-pago3162.logowik.com.webp"
                                 alt="MP" width="30" height="25">
                            <img
                                src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/visa@2x.png"
                                alt="Visa" width="30" height="25">
                            <img
                                src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/mastercard@2x.png"
                                alt="MC" width="30" height="25">
                            <img
                                src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/amex@2x.png"
                                alt="Amex" width="30" height="25">
                            <img
                                src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/ar/tarjeta-naranja@2x.png"
                                alt="Tarjeta Naranja" width="30" height="25">
                        </div>
                    </div>
                    <p><strong>10% de descuento</strong> pagando con transferencia</p>
                    <p><strong>20% de descuento</strong> pagando en efectivo</p>

                    <div class="my-3">
                        <label class="d-block mb-1"><strong>Color:</strong></label>
                        <div class="d-flex gap-2" id="colors-container"></div>
                    </div>

                    <div class="my-4">
                        <label id="sizeSelector" class="d-block mb-1"><strong>Talle:</strong></label>
                        <div class="d-flex gap-2" id="sizes-container"></div>
                        <span id="sizes_help" style="text-decoration: underline; cursor: pointer" class="mt-1">¿Qué talle elijo?</span>👈
                    </div>


                    <!-- Dropdown panel -->
                    <div id="size-help-panel" class="position-fixed top-0 end-0 vh-100 shadow-lg overflow-y-scroll"
                         style="width: 0; overflow: hidden; transition: all 0.3s ease; z-index: 999999999 !important; background-color: #f4f5f4">
                        <button id="close-size-help"
                                class="btn btn-link text-dark fw-bold position-absolute top-0 end-0 me-3 mt-3"
                                aria-label="Close">×
                        </button>
                        <div class="p-4">
                            <h4 style="font-size: 24px; font-weight: bold" class="mb-3">¿Qué talle elijo?</h4>

                            <p>Cada cuerpo es distinto, por eso queremos ayudarte a encontrar el talle que mejor se
                                adapte a vos. Nuestros productos vienen del talle S al XXL, y acá te dejamos una guía de
                                equivalencias y recomendaciones para que elijas con confianza.</p>

                            <small>👉 Todos nuestros productos tienen compresión, así que no hace falta pedir un talle
                                más chico.</small>

                            <img src="{{asset('guia-talles-body2.png')}}" alt="Guia talles Body"
                                 style="width: 100%; margin-top: 2rem">

                            <img src="{{asset('guia-talles-faja3.png')}}" alt="Guia talles Faja"
                                 style="width: 100%; margin-top: 2rem">

                            <img src="{{asset('guia-talles-camiseta.png')}}" alt="Guia talles Camiseta"
                                 style="width: 100%; margin-top: 2rem">

                        </div>
                    </div>

                    <style>
                        #size-help-panel {
                            position: fixed !important;
                            top: 0;
                            right: 0;
                            width: 0;
                            height: 100%;
                            overflow-y: auto; /* Permitir scroll interno si es necesario */
                            transition: all 0.3s ease;
                            z-index: 2147483647; /* Valor máximo de z-index */
                            background-color: #f4f5f4;
                            /* Asegurar que ocupe toda la pantalla en mobile */
                            max-width: 100vw;
                        }

                        /* Overlay de fondo para capturar clicks */
                        #size-help-overlay {
                            display: none;
                            position: fixed !important;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background: rgba(0, 0, 0, 0.5);
                            z-index: 10000;
                        }

                        body.panel-open {
                            overflow: hidden;
                            height: 100vh;
                        }
                    </style>

                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const sizeHelpButton = document.getElementById('sizes_help');
                            const sizeHelpPanel = document.getElementById('size-help-panel');
                            const closeSizeHelp = document.getElementById('close-size-help');
                            const body = document.body;

                            // Crear overlay si no existe
                            let overlay = document.getElementById('size-help-overlay');
                            if (!overlay) {
                                overlay = document.createElement('div');
                                overlay.id = 'size-help-overlay';
                                document.body.appendChild(overlay);
                            }

                            sizeHelpButton.addEventListener('click', () => {
                                const isMobile = window.matchMedia("(max-width: 768px)").matches;
                                sizeHelpPanel.style.width = isMobile ? '95%' : '40%';
                                overlay.style.display = 'block';
                                body.classList.add('panel-open');
                                window.scrollTo(0, 0);
                            });

                            function closePanel() {
                                sizeHelpPanel.style.width = '0';
                                overlay.style.display = 'none';
                                body.classList.remove('panel-open');
                            }

                            closeSizeHelp.addEventListener('click', closePanel);
                            overlay.addEventListener('click', closePanel);
                        });
                    </script>

                    <div class="mb-4">
                        <label class="form-label"><strong>Cantidad</strong></label>
                        <input id="quantity" type="number" class="form-control" value="1" min="1"
                               style="max-width:100px;">
                    </div>

                    <div id="added-to-cart-succesfully" class="mb-3">

                    </div>

                    <button id="add-product-to-cart" class="btn btn-lg w-100"
                            style="background-color: #bc8d8a; color: white; font-weight: bold">AGREGAR AL CARRITO
                    </button>

                    <!-- Descripciones -->
                    <div class="col-md-6">
                        <div class="my-3 d-block d-md-none">
                            <div class="mb-4">
                                <div class="bg-light border rounded p-2"><em>Descripción</em></div>
                                <div class="mt-2">{!! $product->description !!}</div>
                            </div>
                            <div class="mb-4">
                                <div class="bg-light border rounded p-2"><em>Medidas</em></div>
                                <div class="mt-2">{!! $product->sizes_description !!}</div>
                            </div>
                            <div class="mb-4">
                                <div class="bg-light border rounded p-2"><em>Referencia Modelo</em></div>
                                <div class="mt-2">{!! $product->model_reference !!}</div>
                            </div>
                        </div>
                    </div>
                </div>

                @section('reviews')
                    @include('layouts.reviews')
                @endsection

            </div>


        </div>

        <!-- Lightbox Overlay -->
        <div id="lightbox-overlay">
            <span id="lightbox-close">&times;</span>
            <img id="lightbox-img" src="" alt="Ampliado">
        </div>


    </div>

    @push('scripts')
        <script src="{{ asset('js/updateCartProductsQuantity.js') }}"></script>
        <script src="{{ asset('js/hoursCalc.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                /**
                 * Setea el título dinámico de la opción “llega …”
                 */
                function setTitle() {
                    const nowBA = getNowInBuenosAires();

                    document.getElementById('flex-shipping-title').innerHTML = computeShippingTitleForBA(nowBA);
                }

                setInterval(setTitle, 1000); // igual que en el código original

                let selectedColor, selectedSize;
                const productId = {{$product->id}};

                $('#add-product-to-cart').click(function () {
                    const id = {{$product->id}};
                    const route = '/carts/products/' + id
                    const button = $(this);
                    const successDiv = $('#added-to-cart-succesfully');

                    if (selectedSize == undefined) {
                        toastr.error('Debe seleccionar un talle');
                    } else if (selectedColor == undefined) {
                        toastr.error('Debe seleccionar un color');
                    } else {
                        button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Agregando...').prop('disabled', true);

                        $.ajax({
                            type: "POST",
                            url: route,
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                size: selectedSize,
                                color: selectedColor,
                                product_id: productId,
                                quantity: $('#quantity').val(),
                            },
                            success: function (xhr, status, error) {
                                button.html('AGREGAR AL CARRITO').prop('disabled', false);
                                successDiv.html('<div class="alert alert-success mt-3">¡Producto agregado al carrito!</div>');
                                updateCartCounter(true);
                            },
                            error: function () {
                                button.html('AGREGAR AL CARRITO').prop('disabled', false);
                            }
                        })
                    }
                })

                let youtubeLink = @json($product->youtube_link);

                function toYoutubeEmbed(url) {
                    if (!url) return null;
                    url = String(url).trim();

                    // Si ya es embed, lo dejamos.
                    if (url.includes('/embed/')) return url;

                    // youtu.be/VIDEOID
                    const shortMatch = url.match(/youtu\.be\/([a-zA-Z0-9_-]{6,})/);
                    if (shortMatch && shortMatch[1]) {
                        return `https://www.youtube.com/embed/${shortMatch[1]}`;
                    }

                    // youtube.com/watch?v=VIDEOID
                    try {
                        const u = new URL(url);
                        const v = u.searchParams.get('v');
                        if (v) return `https://www.youtube.com/embed/${v}`;
                    } catch (e) {
                        // si no es URL válida, no rompemos nada
                    }

                    return null;
                }

                function hydrateInitialYoutubeIframes() {
                    document.querySelectorAll('iframe[data-youtube-src]').forEach(iframe => {
                        const raw = iframe.getAttribute('data-youtube-src');
                        const embed = toYoutubeEmbed(raw);
                        if (embed) iframe.src = embed;
                    });
                }

                hydrateInitialYoutubeIframes();

                function normalizePaths(pics) {
                    return Array.isArray(pics.paths) ? pics.paths : Object.values(pics.paths);
                }

                function bindZoomFollow() {
                    document.querySelectorAll('.zoom-container').forEach(container => {
                        const img = container.querySelector('img');
                        container.onmousemove = null;
                        container.onmouseleave = null;
                        container.addEventListener('mousemove', e => {
                            const rect = container.getBoundingClientRect();
                            const x = ((e.clientX - rect.left) / rect.width) * 100;
                            const y = ((e.clientY - rect.top) / rect.height) * 100;
                            img.style.transformOrigin = `${x}% ${y}%`;
                        });
                        container.addEventListener('mouseleave', () => {
                            img.style.transformOrigin = 'center center';
                        });
                    });
                }

                function rebindCarousel() {
                    const carouselEl = document.getElementById('productCarousel');
                    if (carouselEl._bsCarousel) carouselEl._bsCarousel.dispose();
                    carouselEl._bsCarousel = new bootstrap.Carousel(carouselEl, {ride: false});

                    const thumbs = document.querySelectorAll('.thumbnail-item');
                    const thumbContainer = document.querySelector('.thumbnail-container');
                    document.getElementById('thumbPrev').onclick = () => thumbContainer.scrollBy({
                        left: -100,
                        behavior: 'smooth'
                    });
                    document.getElementById('thumbNext').onclick = () => thumbContainer.scrollBy({
                        left: 100,
                        behavior: 'smooth'
                    });

                    carouselEl.addEventListener('slid.bs.carousel', () => {
                        const items = Array.from(carouselEl.querySelectorAll('.carousel-item'));
                        const idx = items.findIndex(i => i.classList.contains('active'));
                        thumbs.forEach((t, i) => t.classList.toggle('active', i === idx));
                    });
                    thumbs.forEach((thumb, i) => thumb.addEventListener('click', () => carouselEl._bsCarousel.to(i)));

                    bindZoomFollow();
                }

                function updateCarousel() {
                    const colorObj = availableColors.find(c => c.color === selectedColor);
                    if (!colorObj) return;
                    const paths = normalizePaths(colorObj.pics);

                    const innerHtml = paths.map((path, i) => `
                        <div class="carousel-item ${i === 0 ? 'active' : ''}">
                            <div class="zoom-container d-flex justify-content-center align-items-center">
                                <img src="${path}" class="d-block product-image" alt="Producto ${i + 1}">
                            </div>
                        </div>
                    `).join('');

                    const embed = toYoutubeEmbed(youtubeLink);
                    const videoHtml = embed ? `
                        <div class="carousel-item">
                            <div class="ratio ratio-16x9 carousel-video">
                                <iframe
                                    src="${embed}"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                ></iframe>
                            </div>
                        </div>
                    ` : '';

                    document.querySelector('#productCarousel .carousel-inner').innerHTML = innerHtml + videoHtml;

                    const thumbsHtml = paths.map((path, i) => `
                        <img src="${path}" class="thumbnail-item ${i === 0 ? 'active' : ''}" data-bs-target="#productCarousel" data-bs-slide-to="${i}" alt="Mini ${i + 1}">
                    `).join('');
                    document.querySelector('.thumbnail-container').innerHTML = thumbsHtml;

                    rebindCarousel();
                }

                function printAvailableColors() {
                    const container = document.getElementById('colors-container');

                    // Si hay 0 o 1 color, no mostramos selector (caso pack u “un solo color”)
                    if (!availableColors || availableColors.length <= 1 || availableColors[0].color_name.includes('PACK')) {
                        container.innerHTML = '';
                        container.style.display = 'none';

                        // Auto-selecciona el único color si existe
                        selectedColor = availableColors?.[0]?.color;

                        updateCarousel();
                        printAvailableSizes();
                        return;
                    }

                    // Si hay más de 1 color, comportamiento actual
                    container.style.display = '';
                    container.innerHTML = availableColors.map((c, i) => `
            <div class="btn btn-outline-secondary color-box" data-color="${c.color}" title="${c.color_name}"
                 style="background:${c.color}; width:32px; height:32px; margin-right:0.5rem;"></div>
        `).join('');

                    const boxes = container.querySelectorAll('.color-box');
                    boxes.forEach((box, i) => {
                        if (i === 0) box.style.outline = '1px solid black';
                        box.addEventListener('click', e => {
                            boxes.forEach(b => b.style.outline = 'none');
                            e.target.style.outline = '1px solid black';
                            selectedColor = e.target.dataset.color;
                            updateCarousel();
                            printAvailableSizes();
                        });
                    });

                    selectedColor = availableColors[0].color;
                    updateCarousel();
                }

                function printAvailableSizes() {
                    const container = document.getElementById('sizes-container');
                    const variants = productsVariantsArray.filter(v => v.color === selectedColor);
                    const arr = variants.map(v => ({size: v.size, stock: v.stock}));
                    const isLetter = arr.every(s => isNaN(s.size));
                    const order = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

                    arr.sort((a, b) => isLetter ? order.indexOf(a.size) - order.indexOf(b.size) : a.size - b.size);

                    container.innerHTML = arr.map(s => `
                        <div class="btn btn-outline-secondary size-box sizes ${s.stock == 0 ? 'no-stock' : ''}" data-size="${s.size}" data-stock="${s.stock}" style="margin-right:0.5rem; cursor:pointer;">${s.size}</div>
                    `).join('');
                    document.querySelectorAll('.size-box').forEach(box => {
                        box.addEventListener('click', e => {
                            document.querySelectorAll('.size-box').forEach(b => b.classList.remove('active'));
                            e.target.classList.add('active');
                            selectedSize = e.target.dataset.size;
                            document.getElementById('sizeSelector').innerHTML = '<strong>Talle: </strong>' + selectedSize;
                        });
                    });
                }

                fetch("{{ route('product.variants.show', $product->id) }}")
                    .then(res => res.json())
                    .then(data => {
                        availableColors = data.availableColors;
                        productsVariantsArray = data.productsVariantsArray;
                        youtubeLink = data.youtube_link ?? youtubeLink;

                        printAvailableColors();
                        printAvailableSizes();
                    })
                    .catch(err => console.error(err));

                // Lightbox
                const overlay = document.getElementById('lightbox-overlay');
                const overlayImg = document.getElementById('lightbox-img');
                const closeBtn = document.getElementById('lightbox-close');
                document.querySelector('#productCarousel').addEventListener('click', e => {
                    const img = e.target.closest('.zoom-container img');
                    if (!img) return;
                    overlayImg.src = img.src;
                    overlay.style.display = 'flex';
                });
                [overlay, closeBtn].forEach(el => el.addEventListener('click', () => overlay.style.display = 'none'));
            });
        </script>
    @endpush

@endsection
