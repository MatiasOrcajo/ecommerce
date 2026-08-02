@extends('layouts.app')

@section('title')
    <title>Atica</title>
@endsection

@section('content')

    <style>

        @media (max-width: 991.98px) {
            body {
                padding-top: 60px;

            }
        }

        /* Contenedor de imágenes */
        .image-container {
            position: relative;
            overflow: hidden;
        }

        /* Ambas imágenes ocupan todo el ancho */
        .image-container img {
            display: block;
            width: 100%;
            height: 100%;
            transition: opacity 0.5s ease-in-out;
            object-fit: cover;
        }

        /* La segunda imagen arranca oculta */
        .image-container .img-second {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
        }

        /* Al hacer hover, desvanecen/ aparecen */
        .image-container:hover .img-first {
            opacity: 0;
        }

        .image-container:hover .img-second {
            opacity: 1;
        }

        .color-box {
            width: 1rem;
            height: 1rem;
            background-color: #000;
            border-radius: 100%;
            margin: 0 0.25rem;
        }

        .color-box-parent {
            position: absolute;
            top: -10%;
            left: 50%;
            z-index: 99;
            width: fit-content;
            padding: 8px 10px;
            transform: translateX(-50%);
            border-radius: 32px;
            background-color: #fff;
        }


    </style>


    {{-- Banner desktop --}}
    <div class="container-fluid mt-4 px-0 d-none d-lg-block">
        <a href="/search?q=SUMMER SALE">
            <img
                src="{{ asset('banner.png') }}"
                alt="Banner principal"
                width="1920" height="960" {{-- ajustar al tamaño real --}}
                fetchpriority="high" {{-- prioriza el LCP en home --}}
                decoding="async"
                class="img-fluid w-100"
            />
        </a>
    </div>

    {{-- Banner mobile --}}
    <div class="container-fluid mt-4 px-0 d-block d-lg-none">
        <a href="/search?q=Todos los productos">
            <img
                src="{{ asset('banner_mobile.png') }}"
                alt="Banner principal móvil"
                width="608" height="1080" {{-- ajustar al tamaño real --}}
                fetchpriority="high"
                decoding="async"
                class="img-fluid w-100"
            />
        </a>
    </div>



    {{--    info desktop--}}
    <div class="d-none d-lg-flex row justify-content-center align-items-center bg-black text-white"
         style="padding: 3rem 0">
        <div class="col-lg-4 d-flex justify-content-center align-items-center border-end flex-column text-center">
            <div class="mb-3">
                <i style="font-size: 2rem; color: white;" class="fa-solid fa-truck"></i>
            </div>
            <div>
                <b>
                    <h3 class="mb-2" style="font-size: 1.5rem">ENVÍOS A TODO EL PAÍS</h3>
                    <p>Por Correo Argentino o motomensajería</p>
                </b>
            </div>
        </div>

        <div class="col-lg-4 d-flex justify-content-center align-items-center border-end flex-column text-center">
            <div class="mb-3">
                <i style="font-size: 2rem; color: white;" class="fa-solid fa-cart-shopping"></i>
            </div>
            <div>
                <b>
                    <h3 class="mb-2" style="font-size: 1.5rem">ENVÍOS RÁPIDOS</h3>
                    <p>Tu pedido llega de 1 a 5 días hábiles</p>
                </b>
            </div>
        </div>

        <div class="col-lg-4 d-flex justify-content-center align-items-center flex-column text-center">
            <div class="mb-3">
                <i style="font-size: 2rem; color: white;" class="fa-solid fa-shop"></i>
            </div>
            <div>
                <b>
                    <h3 class="mb-2" style="font-size: 1.5rem">PUNTO DE RETIRO</h3>
                    <p>Retirá tu compra en Monserrat, CABA</p>
                </b>
            </div>
        </div>
    </div>

    <div class="d-block d-lg-none bg-black text-white" style="padding: 3rem 0">
        <div id="mobileInfoSlider" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="col-12 my-4 d-flex justify-content-center align-items-center flex-column text-center">
                        <div class="mb-3">
                            <i style="font-size: 2rem; color: white;" class="mb-2 mt-3 fa-solid fa-truck"></i>
                        </div>
                        <div>
                            <b>
                                <h3 class="mb-2" style="font-size: 1.5rem">ENVÍOS A TODO EL PAÍS</h3>
                                <p>Por Correo Argentino o motomensajería</p>
                            </b>
                        </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="col-12 my-4 d-flex justify-content-center align-items-center flex-column text-center">
                        <div class="mb-3">
                            <i style="font-size: 2rem; color: white;" class="mb-2 mt-3 fa-solid fa-cart-shopping"></i>
                        </div>
                        <div>
                            <b>
                                <h3 class="mb-2" style="font-size: 1.5rem">ENVÍOS RÁPIDOS</h3>
                                <p>Tu pedido llega de 1 a 5 días hábiles</p>
                            </b>
                        </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="col-12 my-4 d-flex justify-content-center align-items-center flex-column text-center">
                        <div class="mb-3">
                            <i style="font-size: 2rem; color: white;" class="mb-2 mt-3 fa-solid fa-shop"></i>
                        </div>
                        <div>
                            <b>
                                <h3 class="mb-2" style="font-size: 1.5rem">PUNTO DE RETIRO</h3>
                                <p>Retirá tu compra en Monserrat, CABA</p>
                            </b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- categorias --}}
    <section>
        <!-- Sección de Categorías -->
        <div class="container-fluid my-md-5 py-md-5 px-0">

            <!-- Contenedor principal con ancho limitado en desktop -->
            <div class="categories-container">
                <!-- Grid para escritorio (2x2) -->
                <div class="row g-0 d-none d-lg-flex justify-content-center">
                    <div class="col-auto">
                        <div class="row g-0">
                            <!-- Categoría 1 -->
                            <div class="col-3 position-relative category-square m-2">
                                <a href="/search?q=SUMMER SALE">

                                    <img src="{{ asset('50_off.jpg') }}" alt="Categoría 1" class="category-img">
                                    <div class="category-content">
                                    </div>
                                </a>
                            </div>

                            <!-- Categoría 2 -->
                            <div class="col-3 position-relative category-square m-2">

                                <a href="{{route('category.show', "bodys-reductores")}}">
                                    <img src="{{ asset('bodys_moldeadores.jpg') }}" alt="Categoría 2"
                                         class="category-img">
                                    <div class="category-content">
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-auto">
                        <div class="row g-0">
                            <!-- Categoría 3 -->
                            <div class="col-3 position-relative category-square m-2">
                                <a href="{{route('category.show', "fajas-modeladoras")}}">

                                    <img src="{{ asset('trusas_moldeadoras.jpg') }}" alt="Categoría 3"
                                         class="category-img">
                                    <div class="category-content">
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Versión móvil con layout de columna -->
                <div class="d-flex flex-column d-lg-none">
                    <!-- Categoría 1 -->
                    <div class="position-relative category-square">
                        <a href="/search?q=SUMMER SALE">

                            <img src="{{ asset('50_off.jpg') }}" alt="Categoría 1" class="category-img">
                            <div class="category-content">
                            </div>
                        </a>
                    </div>

                    <!-- Categoría 2 -->
                    <div class="position-relative category-square">
                        <a href="{{route('category.show', "bodys-reductores")}}">

                            <img src="{{ asset('bodys_moldeadores.jpg') }}" alt="Categoría 2" class="category-img">
                            <div class="category-content">
                            </div>
                        </a>
                    </div>

                    <!-- Categoría 3 -->
                    <div class="position-relative category-square">
                        <a href="{{route('category.show', "fajas-modeladoras")}}">

                            <img src="{{ asset('trusas_moldeadoras.jpg') }}" alt="Categoría 3" class="category-img">
                            <div class="category-content">
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <style>
            /* Contenedor principal para limitar el ancho en desktop */
            .categories-container {
                width: 100%;
            }

            @media (min-width: 992px) {
                .categories-container {
                    max-width: 100%;
                    margin: 0 auto;
                }

                .category-square {
                    width: 400px;
                    height: auto;
                }
            }

            /* Estilos para las categorías */
            .category-square {
                aspect-ratio: 1 / 1; /* Hace que sea un cuadrado perfecto */
                overflow: hidden;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .category-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s ease;
            }

            .category-square:hover .category-img {
                transform: scale(1.05);
            }

            .category-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 1;
                transition: background-color 0.3s ease;
            }

            .category-content {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 2;
                text-align: center;
                width: 100%;
                padding: 0 1rem;
            }

            .category-title {
                color: white;
                font-size: 2rem;
                font-weight: 400;
                letter-spacing: 2px;
                margin: 0 0 0.5rem 0;
                line-height: 1;
                font-family: 'Helvetica Neue', Arial, sans-serif;
            }

            .category-subtitle {
                color: white;
                font-size: 1.5rem;
                font-weight: 300;
                letter-spacing: 1px;
                text-transform: uppercase;
                margin: 0;
                line-height: 1.2;
                font-family: 'Helvetica Neue', Arial, sans-serif;
                opacity: 0.9;
            }

            /* Estilos para móvil */
            @media (max-width: 991.98px) {
                .category-title {
                    font-size: 2.5rem;
                }

                .category-subtitle {
                    font-size: 1.2rem;
                }
            }
        </style>
    </section>


    {{--    products--}}

    <!-- Contenedor destacados -->
    <section>
        <div class="mt-5" id="destacados" style="margin-top: 7rem; margin-bottom: 3rem">
            <h2 class="d-block mt-5 text-center" style="font-size: 2rem; font-weight: bold">Los más pedidos ❤️</h2>

        </div>
        <div class="swiper products-swiper mx-3">
            <div class="swiper-wrapper pb-3 justify-content-md-center" id="products-container">


            </div>
            <!-- Paginación -->
            <div class="swiper-pagination"></div>
            <!-- Scrollbar -->
            <div class="swiper-scrollbar"></div>
        </div>
    </section>

    <!-- banner movimiento -->
    <section>
        <!-- Banner con texto deslizante -->
        <div class="announcement-bar mt-5" style="background-color: black; overflow: hidden; padding: 10px 0;">
            <div class="announcement-container">
                <div class="announcement-track" style="display: flex; white-space: nowrap;">
                    <!-- Primera secuencia -->
                    <div class="announcement-item">
                        <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                        <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                        <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                        <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                        <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                        <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                        <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                        <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                    </div>
                    <!-- Segunda secuencia (duplicada para efecto continuo) -->
                    <div class="announcement-item">
                        <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                        <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                        <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                        <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                        <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                        <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                        <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                        <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenedor infaltables -->
    <section>
        <div class="mt-5" id="destacados" style="margin-top: 7rem; margin-bottom: 3rem">
            <h2 class="d-block mt-5 text-center" style="font-size: 2rem; font-weight: bold">¡Los infaltables para
                modelar tu figura!</h2>

        </div>
        <div id="main-products-container"
             class="row row-cols-1 row-cols-lg-2 g-4 justify-content-center col-lg-8 col-12 mx-auto">
        </div>
    </section>


    @section('reviews')
        @include('layouts.reviews')
    @endsection

    <style>
        /* Estilos para el banner animado */
        .announcement-bar {
            background-color: black; /* Color marrón de tu imagen */
            overflow: hidden;
            padding: 12px 0;
            position: relative;
            z-index: 1000;
        }

        .announcement-container {
            width: 100%;
            overflow: hidden;
        }

        .announcement-track {
            display: flex;
            white-space: nowrap;
            animation: scrollLeft 10s linear infinite;
        }

        .announcement-item {
            display: flex;
            flex-shrink: 0;
        }

        .announcement-text {
            display: inline-block;
            color: white;
            font-weight: bold;
            font-size: 18px;
            margin: 0 25px;
            padding: 5px 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Animación de desplazamiento */
        @keyframes scrollLeft {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        /* Efecto de borde para mejor legibilidad */
        .announcement-text {
            position: relative;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        /* Efecto hover opcional */
        .announcement-bar:hover .announcement-track {
            animation-play-state: paused;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .announcement-text {
                font-size: 16px;
                margin: 0 15px;
                padding: 3px 10px;
            }

            .announcement-track {
                animation: scrollLeft 10s linear infinite;
            }
        }

        @media (max-width: 480px) {
            .announcement-text {
                font-size: 14px;
                margin: 0 10px;
                padding: 2px 8px;
            }
        }
    </style>

    <!-- Clientas -->


    {{--    <div class="container-fluid px-0">--}}
    {{--        <div class="video-wrapper w-100">--}}
    {{--            <video autoplay muted loop playsinline>--}}
    {{--                <source src="/storage/videos/loop.mp4" type="video/mp4">--}}
    {{--                Tu navegador no soporta la reproducción de vídeo.--}}
    {{--            </video>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    <style>
        .video-wrapper {
            position: relative;
            width: 100%;
            height: 80vh; /* Altura del vídeo: ajustá según necesites */
            overflow: hidden; /* Oculta las partes que sobresalgan */
            margin-top: 10rem;
        }

        .video-wrapper video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%; /* Siempre al menos 100% ancho */
            min-height: 70%; /* Aumenta la altura para “estirar” */
            transform: translate(-50%, -50%);
            object-fit: cover; /* Rellena el contenedor recortando si hace falta */
        }


        /* Opcional: diferente altura en móviles */
        @media (max-width: 576px) {
            .video-wrapper {
                height: 70vh;
                margin-top: 3rem;
            }


        }
    </style>



    <!-- Newsletter Subscription Form (Bootstrap 5) -->


    <style>
        .btn-white.border-black {
            background-color: #fff;
            color: #000;
            border: 1px solid #000;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .btn-white.border-black:hover {
            background-color: #000;
            color: #fff;
        }
    </style>


    @push('scripts')

        <script src="{{ asset('js/updateCartProductsQuantity.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {

                const swiper = new Swiper('.products-swiper', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: true,
                    infinity: true,
                    scrollbar: {
                        el: '.swiper-scrollbar',
                    },
                    breakpoints: {
                        576: {
                            slidesPerView: 2,
                        },
                        992: {
                            slidesPerView: 3,
                        },
                        1200: {
                            slidesPerView: 4,
                            scrollbar: false
                        }
                    }
                });


                fetch('/featured-products')
                    .then(res => res.json())
                    .then(data => renderProducts(data))
                    .catch(err => console.error(err));

                function renderProducts(data) {
                    const container = document.getElementById('products-container');
                    let html = '';

                    const sortedData = Object.values(data).sort((a, b) => b.product.price - a.product.price);

                    sortedData.forEach(item => {
                        const {product, colors} = item;

                        const variants = colors.map(c => ({
                            colorCode: c.hex,
                            colorName: c.name,
                            pics: c.paths,
                        }));

                        // Primeras dos imágenes
                        const firstImg = variants[0].pics[0];
                        const secondImg = variants[0].pics[1] || variants[0].pics[0];

                        // Swatches
                        let swatches = '';
                        variants.forEach((v, i) => {
                            v.colorName.includes('PACK') || v.colorName.includes('Pack') ? swatches += '' :
                                swatches += `
                              <div
                                class="color-box mx-1"
                                data-variant-index="${i}"
                                style="
                                  width:24px; height:24px;
                                  background:${v.colorCode};
                                  border:1px solid #ccc;
                                  cursor:pointer;
                                "
                                title="${v.colorName}"
                              ></div>
                            `;
                        })

                        const productPrice = product.discount ? product.price * (1 - product.discount / 100) : product.price;
                        const priceWithTransfer = (productPrice * 0.9).toFixed(2);

                        const moneyAR = (v) => {
                            const nf = new Intl.NumberFormat('es-AR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2,
                            });
                            return `$${nf.format(Number(v))}`;
                        };

                        // **Generación de la card**
                        html += `
                          <div class="swiper-slide">
                            <div class="card border-0 p-0 h-50">
                              <div class="ratio image-container"
                                   style="--bs-aspect-ratio:120%;"
                                   data-variants='${JSON.stringify(variants)}'>
                                <img
                                  src="${firstImg}"
                                  class="card-img-top img-first"
                                  alt="${product.name}"
                                >
                                <img
                                  src="${secondImg}"
                                  class="card-img-top img-second"
                                  alt="${product.name} - Hover"
                                >
                                <a>

                                    ${product.discount != null ? `<button class="btn position-absolute top-0 end-0 m-2" style="background-color: #724d3a; color: white;">${product.discount}% OFF</button>` : ''}

                                </a>
                                <a href="/productos/${product.slug}">
                                  <button class="btn btn-light position-absolute bottom-0 end-0 m-2">
                                    <i class="fas fa-shopping-bag"></i>
                                  </button>
                                </a>
                              </div>
                              <div class="card-body d-flex flex-column position-relative">
                                <div class="d-flex justify-content-center mb-3">
                                  <div class="color-box-parent d-flex justify-content-center align-items-center">
                                    ${swatches}
                                  </div>
                                </div>
                                <h5 class="card-title text-center mb-2">${product.name}</h5>

                                ${product.discount
                            ? `<p class="text-center mb-1 fw-bold">
                                       <del>${moneyAR(product.price)}</del>
                                       ${moneyAR((product.price * (1 - product.discount / 100)).toFixed(2))}
                                     </p>`
                            : `<p class="text-center mb-1 fw-bold">${moneyAR(product.price)}</p>`
                        }

                                <p class="text-center mb-2 text-muted">
                                  ${moneyAR(priceWithTransfer)} con Transferencia bancaria
                                </p>
                                <a
                                  href="/productos/${product.slug}"
                                  class="btn btn-white border-black w-25 mx-auto mt-auto d-block"
                                >
                                  Ver
                                </a>
                              </div>
                            </div>
                          </div>
                        `;

                        container.innerHTML = html;
                        swiper.update();

                    });


                    // Click sobre swatches
                    container.querySelectorAll('.image-container').forEach(container => {
                        const variants = JSON.parse(container.dataset.variants);
                        const imgFirst = container.querySelector('.img-first');
                        const imgSecond = container.querySelector('.img-second');
                        const card = container.closest('.card');

                        card.querySelectorAll('.color-box').forEach(box => {
                            box.addEventListener('click', () => {
                                const i = parseInt(box.dataset.variantIndex, 10);
                                const pics = variants[i].pics || [];
                                if (!pics.length) return;
                                imgFirst.src = pics[0];
                                imgSecond.src = pics[1] || pics[0];
                            });
                        });
                    });
                }
            })


            //main products

            fetch('/main-products')
                .then(res => res.json())
                .then(data => renderMainProducts(data))
                .catch(err => console.error(err));

            function renderMainProducts(data) {
                const container = document.getElementById('main-products-container');
                let html = '';

                const sortedData = Object.values(data).sort((a, b) => b.product.price - a.product.price);

                sortedData.forEach(item => {
                    const {product, colors} = item;

                    const variants = colors.map(c => ({
                        colorCode: c.hex,
                        colorName: c.name,
                        pics: c.paths,
                    }));

                    // Primeras dos imágenes
                    const firstImg = variants[0].pics[0];
                    const secondImg = variants[0].pics[1] || variants[0].pics[0];

                    // Swatches
                    let swatches = '';
                    variants.forEach((v, i) => {
                        v.colorName.includes('PACK') || v.colorName.includes('Pack') ? swatches += '' :
                            swatches += `
                          <div
                            class="color-box mx-1"
                            data-variant-index="${i}"
                            style="
                              width:24px; height:24px;
                              background:${v.colorCode};
                              border:1px solid #ccc;
                              cursor:pointer;
                            "
                            title="${v.colorName}"
                          ></div>
                        `;
                    });

                    const productPrice = product.discount ? product.price * (1 - product.discount / 100) : product.price;
                    const priceWithTransfer = (productPrice * 0.9).toFixed(2);

                    const moneyAR = (v) => {
                        const nf = new Intl.NumberFormat('es-AR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                        });
                        return `$${nf.format(Number(v))}`;
                    };

                    // **Generación de la card**
                    html += `
                          <div class="col">
                            <div class="card border-0 p-0 h-100">
                              <div class="ratio image-container"
                                   style="--bs-aspect-ratio:120%;"
                                   data-variants='${JSON.stringify(variants)}'>
                                <img
                                  src="${firstImg}"
                                  class="card-img-top img-first"
                                  alt="${product.name}"
                                >
                                <img
                                  src="${secondImg}"
                                  class="card-img-top img-second"
                                  alt="${product.name} - Hover"
                                >
                                <a>

                                    ${product.discount != null ? `<button class="btn position-absolute top-0 end-0 m-2" style="background-color: #724d3a; color: white;">${product.discount}% OFF</button>` : ''}

                                </a>
                                <a href="/productos/${product.slug}">
                                  <button class="btn btn-light position-absolute bottom-0 end-0 m-2">
                                    <i class="fas fa-shopping-bag"></i>
                                  </button>
                                </a>
                              </div>
                              <div class="card-body d-flex flex-column position-relative">
                                <div class="d-flex justify-content-center mb-3">
                                  <div class="color-box-parent d-flex justify-content-center align-items-center">
                                    ${swatches}
                                  </div>
                                </div>
                                <h5 class="card-title text-center mb-2">${product.name}</h5>

                                ${product.discount
                        ? `<p class="text-center mb-1 fw-bold">
                                       <del>${moneyAR(product.price)}</del>
                                       ${moneyAR((product.price * (1 - product.discount / 100)).toFixed(2))}
                                     </p>`
                        : `<p class="text-center mb-1 fw-bold">${moneyAR(product.price)}</p>`
                    }

                                <p class="text-center mb-2 text-muted">
                                  ${moneyAR(priceWithTransfer)} con Transferencia bancaria
                                </p>
                                <a
                                  href="/productos/${product.slug}"
                                  class="btn btn-white border-black w-25 mx-auto mt-auto d-block"
                                >
                                  Ver
                                </a>
                              </div>
                            </div>
                          </div>
                        `;

                    container.innerHTML = html;


                });


                // Click sobre swatches
                container.querySelectorAll('.image-container').forEach(container => {
                    const variants = JSON.parse(container.dataset.variants);
                    const imgFirst = container.querySelector('.img-first');
                    const imgSecond = container.querySelector('.img-second');
                    const card = container.closest('.card');

                    card.querySelectorAll('.color-box').forEach(box => {
                        box.addEventListener('click', () => {
                            const i = parseInt(box.dataset.variantIndex, 10);
                            const pics = variants[i].pics || [];
                            if (!pics.length) return;
                            imgFirst.src = pics[0];
                            imgSecond.src = pics[1] || pics[0];
                        });
                    });
                });

            }

            updateCartCounter();
        </script>
    @endpush

@endsection

@section('footer')
    @include('layouts.footer')

@endsection
