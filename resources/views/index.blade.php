@extends('layouts.app')

@section('title')
    <title>Atica</title>
@endsection

@section('content')

    <style>
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


    {{--    Banner desktop--}}
    <div class="container-fluid mt-4 px-0">
        <img
            src="{{ asset('30_OFF.png') }}"
            class="img-fluid w-100" alt="Banner principal">
    </div>


    {{--    info desktop--}}
    <div class="d-none d-lg-flex row justify-content-center align-items-center mx-5 my-5 py-5">
        <div class="col-lg-4 d-flex justify-content-center align-items-center border-end">
            <div class="me-3">
                <i style="font-size: 2rem" class="fa-solid fa-truck"></i>
            </div>
            <div>
                <h3>ENVÍOS GRATIS</h3>
                <p>En todas tus compras</p>
            </div>
        </div>

        <div class="col-lg-4 d-flex justify-content-center align-items-center border-end">
            <div class="me-3">
                <i style="font-size: 2rem" class="fa-solid fa-location-dot"></i>
            </div>
            <div>
                <h3>ENVÍOS A TODO EL PAÍS</h3>
                <p>Comprá desde cualquier lugar</p>
            </div>
        </div>

        <div class="col-lg-4 d-flex justify-content-center align-items-center">
            <div class="me-3">
                <i style="font-size: 2rem" class="fa-solid fa-credit-card"></i>
            </div>
            <div>
                <h3>3 CUOTAS SIN INTERÉS</h3>
                <p>Con todas las tarjetas</p>
            </div>
        </div>
    </div>

    <div class="d-block d-lg-none mt-5">
        <div class="col-12 my-4 d-flex justify-content-center align-items-center">
            <div class="me-3">
                <i style="font-size: 2rem" class="fa-solid fa-truck"></i>
            </div>
            <div>
                <h3>ENVÍOS GRATIS</h3>
                <p>En todas tus compras</p>
            </div>
        </div>

        <div class="col-12 my-4 d-flex justify-content-center align-items-center">
            <div class="me-3">
                <i style="font-size: 2rem" class="fa-solid fa-location-dot"></i>
            </div>
            <div>
                <h3>ENVÍOS A TODO EL PAÍS</h3>
                <p>Comprá desde cualquier lugar</p>
            </div>
        </div>

        <div class="col-12 my-4 d-flex justify-content-center align-items-center">
            <div class="me-3">
                <i style="font-size: 2rem" class="fa-solid fa-credit-card"></i>
            </div>
            <div>
                <h3>3 CUOTAS SIN INTERÉS</h3>
                <p>Con todas las tarjetas</p>
            </div>
        </div>
    </div>


    {{--    products--}}

    <!-- Contenedor principal -->
    <div class="mt-5 py-2" id="destacados">
        <h2 class="d-block mt-5 text-center" style="font-size: 4rem">best sellers.</h2>

    </div>
    <div id="products-container"
         class="row row-cols-1 row-cols-lg-3 g-4 mx-3 justify-content-center">
    </div>

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
            height: 80vh;        /* Altura del vídeo: ajustá según necesites */
            overflow: hidden;    /* Oculta las partes que sobresalgan */
            margin-top: 10rem;
        }
        .video-wrapper video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;     /* Siempre al menos 100% ancho */
            min-height: 70%;    /* Aumenta la altura para “estirar” */
            transform: translate(-50%, -50%);
            object-fit: cover;   /* Rellena el contenedor recortando si hace falta */
        }



        /* Opcional: diferente altura en móviles */
        @media (max-width: 576px) {
            .video-wrapper {
                height: 70vh;
                margin-top: 3rem;
            }


        }
    </style>

    <div class="my-5 py-5">
        <div class="d-flex justify-content-center align-items-center">
            <div class="me-4">
                <i class="fa-brands fa-instagram" style="font-size: 4rem"></i>
            </div>
            <div style="transform: translateY(-20%)">
                <p class="d-block mt-5 text-center" style="font-size: 1.5rem;">SEGUINOS EN INSTAGRAM</p>
                <h3 style="font-size: 2.5rem">@atica.arg</h3>
            </div>
        </div>
    </div>


    <!-- Newsletter Subscription Form (Bootstrap 5) -->
    <div class="p-0 px-3 mb-5 w-100 translate-y-0 d-flex justify-content-center align-items-center">

        <div>
            <!-- Top bar -->
            <div class="bg-dark text-white d-flex justify-content-between align-items-center px-4 py-3">
                <h5 class="m-0" style="font-size: 1.5rem">Unite a nuestro <strong>#ClubAtica</strong> y recibí muchos
                    beneficios</h5>
            </div>

            <!-- Formulario -->
            <div class="bg-white border shadow-sm p-4 p-md-5">
                <h6 class="mb-4">Completa tus datos para suscribirte</h6>
                <form>

                    <!-- Campos de texto -->
                    <div class="mb-3">
                        <input type="email" class="form-control form-control-lg" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control form-control-lg" placeholder="Nombre" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control form-control-lg" placeholder="Apellido" required>
                    </div>
                    <div class="mb-4">
                        <input type="text" class="form-control form-control-lg" placeholder="cumpleaños (dd/mm)"
                               pattern="\d{2}/\d{2}">
                    </div>

                    <!-- Botón Enviar -->
                    <button type="submit" class="btn btn-dark btn-lg w-100">ENVIAR</button>
                </form>
            </div>
        </div>
    </div>


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
                fetch('/featured-products')
                    .then(res => res.json())
                    .then(data => renderProducts(data))
                    .catch(err => console.error(err));

                function renderProducts(data) {
                    const container = document.getElementById('products-container');
                    let html = '';

                    Object.values(data).forEach(item => {
                        const {product, colors} = item;

                        const colorNames = colors.names;

                        // Construyo variantes [{ colorCode, pics }]
                        const variants = colorNames.map((_, idx) => {
                            const entry = colors[idx];
                            const code = Object.keys(entry)[0];
                            const pics = entry[code][0];
                            return {colorCode: code, pics};
                        });

                        // Primeras dos imágenes
                        const firstImg = variants[0].pics[0];
                        const secondImg = variants[0].pics[1] || variants[0].pics[0];

                        // Swatches
                        let swatches = '';
                        variants.forEach((v, i) => {
                            swatches += `
                          <div
                            class="color-box mx-1"
                            data-variant-index="${i}"
                            style="
                              width:24px; height:24px;
                              background-color:${v.colorCode};
                              border:1px solid #ccc;
                              cursor:pointer;
                            "
                            title="${colorNames[i]}"
                          ></div>
                        `;
                        });

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
                                       <del>$${product.price.toFixed(2)}</del>
                                       $${(product.price * (1 - product.discount / 100)).toFixed(2)}
                                     </p>`
                                                    : `<p class="text-center mb-1 fw-bold">$${product.price}</p>`
                                                }

                                <p class="text-center mb-2 text-muted">
                                  $${(product.price * 0.9).toFixed(2)} con Transferencia bancaria
                                </p>
                                <a
                                  href="/productos/${product.slug}"
                                  class="btn btn-white border-black w-25 mx-auto mt-auto d-block"
                                >
                                  Shop
                                </a>
                              </div>
                            </div>
                          </div>
                        `;

                    });

                    container.innerHTML = html;

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
            });

            updateCartCounter();
        </script>
    @endpush

@endsection
