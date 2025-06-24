@extends('layouts.app')

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
            src="https://acdn-us.mitiendanube.com/stores/001/235/896/themes/new_linkedman/1-slide-1740770503042-8262247398-a89c8fc06e13e021df5e5a38a142666d1740770505-1920-1920.jpg?247527440"
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
        <h2 class="d-block mt-5 text-center" style="font-size: 4rem">destacados.</h2>

    </div>
    <div class="row g-4 mx-3" id="products-container">

        <!-- …repite más columnas según necesites… -->

    </div>

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

    <!-- en tu Blade, donde quieras que vayan las cards: -->
    <div id="products-container" class="row g-4 mx-3"></div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // 1) Hacemos la petición a la ruta que devuelve el JSON
                fetch('/test-array-featured-products')
                    .then(res => res.json())
                    .then(data => renderProducts(data))
                    .catch(err => console.error(err));

                function renderProducts(data) {
                    const container = document.getElementById('products-container');
                    let html = '';

                    // 2) Por cada producto en el JSON
                    Object.values(data).forEach(item => {
                        const { product, colors } = item;
                        const colorNames = colors.names; // ["Visón","Negro",…]

                        // Montamos un array de variantes: { colorCode, pics }
                        const variants = colorNames.map((_, idx) => {
                            // colors[idx] es algo tipo {"#96897d": [[path1, path2]]}
                            const entry = colors[idx];
                            const code = Object.keys(entry)[0];
                            const pics = entry[code][0]; // [path1, path2]
                            return { colorCode: code, pics };
                        });

                        // Preparamos la primera y segunda imagen
                        const firstImg  = variants[0].pics[0];
                        const secondImg = variants[0].pics[1] || variants[0].pics[0];

                        // Montamos los swatches
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

                        // Montamos el card completo, respetando TODA la estructura y clases
                        html += `
        <div class="col-md-4">
          <div class="card border-0 h-75 p-0">

            <div
              class="image-container"
              data-variants='${JSON.stringify(variants)}'
            >
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
              <p class="text-center mb-1 fw-bold">$${product.price}</p>
              <p class="text-center mb-2 text-muted">$${(product.price*0.9).toFixed(2)} con Transferencia bancaria</p>
              <p class="text-center small text-muted mb-0">
                6 cuotas sin interés de $${(product.price/3).toFixed(2)}
              </p>
              <a
                href="/productos/${product.slug}"
                class="btn btn-white border-black w-25 mx-auto mt-3 d-block"
              >Ver</a>
            </div>
          </div>
        </div>
      `;
                    });

                    // 3) Inyectamos todo de una vez
                    container.innerHTML = html;

                    // 4) Adjuntamos los clicks PARA CADA CARD por separado
                    container.querySelectorAll('.image-container').forEach(container => {
                        const variants = JSON.parse(container.dataset.variants);
                        const imgFirst = container.querySelector('.img-first');
                        const imgSecond= container.querySelector('.img-second');
                        const card     = container.closest('.card');

                        card.querySelectorAll('.color-box').forEach(box => {
                            box.addEventListener('click', () => {
                                const i = parseInt(box.dataset.variantIndex, 10);
                                const pics = variants[i].pics || [];
                                if (!pics.length) return;
                                imgFirst.src  = pics[0];
                                imgSecond.src = pics[1] || pics[0];
                            });
                        });
                    });
                }
            });
        </script>
    @endpush


@endsection
