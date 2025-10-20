@extends('layouts.app')

@section('title')
    <title>{{$category->name}} - Atica</title>
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


    {{--    products--}}

    <!-- Contenedor principal -->
    <div class="mt-5 py-5" id="destacados">
        <h2 class="d-block mt-5 text-center" style="font-size: 4rem">{{$category->name}}.</h2>

    </div>
    <div id="products-container"
         class="row row-cols-1 row-cols-lg-3 g-4 mx-3 justify-content-center mb-5">
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
                fetch('/categories/{{$category->slug}}/search-products')
                    .then(res => res.json())
                    .then(data => renderProducts(data))
                    .catch(err => console.error(err));

                function renderProducts(data) {
                    const container = document.getElementById('products-container');
                    let html = '';

                    // Si no hay productos, muestra un mensaje
                    if (!Object.keys(data).length) {
                        html = `
                <div class="text-center my-5">
                    <h1 class="display-4 text-muted">No se encontraron resultados para su búsqueda.</h1>
                </div>
            `;
                        container.innerHTML = html;
                        return;
                    }

                    Object.values(data).forEach(item => {
                        const {product, colors} = item;
                        const colorNames = colors.names;

                        // Construir variantes [{ colorCode, pics }]
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
                      background:${v.colorCode};
                      border:1px solid #ccc;
                      cursor:pointer;
                    "
                    title="${colorNames[i]}"
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
                                    <button class="btn position-absolute top-10 end-0 m-2" style="background-color: #bc8d8a; color: white;" >ENVÍO GRATIS</button>

                                    <button class="btn position-absolute top-0 end-0 m-2" style="background-color: #724d3a; color: white;">2 X 1</button>

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
