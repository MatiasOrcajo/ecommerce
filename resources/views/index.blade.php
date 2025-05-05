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
            height: auto;
            transition: opacity 0.5s ease-in-out;
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
    </style>


    {{--    Banner desktop--}}
    <div class="container-fluid mt-4 px-0">
        <img
            src="https://acdn-us.mitiendanube.com/stores/001/235/896/themes/new_linkedman/1-slide-1740770503042-8262247398-a89c8fc06e13e021df5e5a38a142666d1740770505-1920-1920.jpg?247527440"
            class="img-fluid w-100" alt="Banner principal">
    </div>


    {{--    info desktop--}}
    <div class="row d-flex justify-content-center align-items-center mx-5 my-5 py-5">
        <div class="col-4 d-flex justify-content-center align-items-center border-end">
            <div class="me-3">
                <i class="fa-solid fa-truck"></i>
            </div>
            <div>
                <h3>ENVÍOS GRATIS</h3>
                <p>En compras +$250.000</p>
            </div>
        </div>

        <div class="col-4 d-flex justify-content-center align-items-center border-end">
            <div class="me-3">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div>
                <h3>ENVÍOS A TODO EL PAÍS</h3>
                <p>Comprá desde cualquier lugar</p>
            </div>
        </div>

        <div class="col-4 d-flex justify-content-center align-items-center">
            <div class="me-3">
                <i class="fa-solid fa-credit-card"></i>
            </div>
            <div>
                <h3>3 CUOTAS SIN INTERÉS</h3>
                <p>Con todas las tarjetas</p>
            </div>
        </div>
    </div>


    {{--    products--}}

    <!-- Contenedor principal -->
    <div class="mt-5 py-2">
        <h2 class="d-block mt-5 text-center" style="font-size: 4rem">destacados.</h2>

    </div>
    <div class="row g-4 mx-3">

        <div class="col-md-4">
            <div class="card border-0 h-100">

                <!-- Imagen con hover -->
                <div class="image-container">
                    <!-- Imagen por defecto -->
                    <img
                        src="https://acdn-us.mitiendanube.com/stores/001/126/411/products/dsc08356-9ee1442c9ba5a3e3fe17431121244703-1024-1024.webp"
                        class="card-img-top img-first"
                        alt="Remera Juli">
                    <!-- Imagen que aparece al pasar el mouse -->
                    <img
                        src="https://acdn-us.mitiendanube.com/stores/001/126/411/products/img_2132-c92a1e82140e5ec5fa17439931974082-1024-1024.jpeg"
                        class="card-img-top img-second"
                        alt="Remera Juli - Hover">

                    <!-- Iconos superpuestos -->
                    <button class="btn btn-dark position-absolute bottom-0 start-0 m-2">
                        <i class="fas fa-shopping-bag"></i>
                    </button>
                    <button class="btn btn-light position-absolute bottom-0 end-0 m-2">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>

                <!-- Body: título y precios -->
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-center mb-2">Remera Juli</h5>
                    <p class="text-center mb-1 fw-bold">$19.900</p>
                    <p class="text-center mb-2 text-muted">$17.910 con Transferencia bancaria</p>
                    <p class="text-center small text-muted mb-0">
                        6 cuotas sin interés de $3.316,67 a partir de $250.000
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 h-100">

                <!-- Imagen con hover -->
                <div class="image-container">
                    <!-- Imagen por defecto -->
                    <img
                        src="https://acdn-us.mitiendanube.com/stores/001/126/411/products/dsc08356-9ee1442c9ba5a3e3fe17431121244703-1024-1024.webp"
                        class="card-img-top img-first"
                        alt="Remera Juli">
                    <!-- Imagen que aparece al pasar el mouse -->
                    <img
                        src="https://acdn-us.mitiendanube.com/stores/001/126/411/products/dsc08322-5707bccabda724606817431086677484-1024-1024.jpeg"
                        class="card-img-top img-second"
                        alt="Remera Juli - Hover">

                    <!-- Iconos superpuestos -->
                    <button class="btn btn-dark position-absolute bottom-0 start-0 m-2">
                        <i class="fas fa-shopping-bag"></i>
                    </button>
                    <button class="btn btn-light position-absolute bottom-0 end-0 m-2">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>

                <!-- Body: título y precios -->
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-center mb-2">Remera Juli</h5>
                    <p class="text-center mb-1 fw-bold">$19.900</p>
                    <p class="text-center mb-2 text-muted">$17.910 con Transferencia bancaria</p>
                    <p class="text-center small text-muted mb-0">
                        6 cuotas sin interés de $3.316,67 a partir de $250.000
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 h-100">

                <!-- Imagen con hover -->
                <div class="image-container">
                    <!-- Imagen por defecto -->
                    <img
                        src="https://acdn-us.mitiendanube.com/stores/001/126/411/products/dsc08356-9ee1442c9ba5a3e3fe17431121244703-1024-1024.webp"
                        class="card-img-top img-first"
                        alt="Remera Juli">
                    <!-- Imagen que aparece al pasar el mouse -->
                    <img
                        src="https://acdn-us.mitiendanube.com/stores/001/126/411/products/img_2132-c92a1e82140e5ec5fa17439931974082-1024-1024.jpeg"
                        class="card-img-top img-second"
                        alt="Remera Juli - Hover">

                    <!-- Iconos superpuestos -->
                    <button class="btn btn-dark position-absolute bottom-0 start-0 m-2">
                        <i class="fas fa-shopping-bag"></i>
                    </button>
                    <button class="btn btn-light position-absolute bottom-0 end-0 m-2">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>

                <!-- Body: título y precios -->
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-center mb-2">Remera Juli</h5>
                    <p class="text-center mb-1 fw-bold">$19.900</p>
                    <p class="text-center mb-2 text-muted">$17.910 con Transferencia bancaria</p>
                    <p class="text-center small text-muted mb-0">
                        6 cuotas sin interés de $3.316,67 a partir de $250.000
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 h-100">

                <!-- Imagen con hover -->
                <div class="image-container">
                    <!-- Imagen por defecto -->
                    <img
                        src="https://acdn-us.mitiendanube.com/stores/001/126/411/products/dsc08356-9ee1442c9ba5a3e3fe17431121244703-1024-1024.webp"
                        class="card-img-top img-first"
                        alt="Remera Juli">
                    <!-- Imagen que aparece al pasar el mouse -->
                    <img
                        src="https://acdn-us.mitiendanube.com/stores/001/126/411/products/img_2132-c92a1e82140e5ec5fa17439931974082-1024-1024.jpeg"
                        class="card-img-top img-second"
                        alt="Remera Juli - Hover">

                    <!-- Iconos superpuestos -->
                    <button class="btn btn-dark position-absolute bottom-0 start-0 m-2">
                        <i class="fas fa-shopping-bag"></i>
                    </button>
                    <button class="btn btn-light position-absolute bottom-0 end-0 m-2">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>

                <!-- Body: título y precios -->
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-center mb-2">Remera Juli</h5>
                    <p class="text-center mb-1 fw-bold">$19.900</p>
                    <p class="text-center mb-2 text-muted">$17.910 con Transferencia bancaria</p>
                    <p class="text-center small text-muted mb-0">
                        6 cuotas sin interés de $3.316,67 a partir de $250.000
                    </p>
                </div>
            </div>
        </div>

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
    <div class="container p-0 w-75 translate-y-0">

        <div>
            <!-- Top bar -->
            <div class="bg-dark text-white d-flex justify-content-between align-items-center px-4 py-3">
                <h5 class="m-0" style="font-size: 1.5rem">Unite a nuestro <strong>#ClubAtica</strong> y recibí muchos beneficios</h5>
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
                        <input type="text" class="form-control form-control-lg" placeholder="cumpleaños (dd/mm)" pattern="\d{2}/\d{2}">
                    </div>

                    <!-- Checkboxes -->
                    <div class="mb-4">
                        <label class="form-label d-block mb-2">Qué te gustaría recibir?</label>
                        <div class="d-flex align-items-center mb-2">
                            <input class="form-check-input w-auto me-2" type="checkbox" id="optGiftcard">
                            <label class="form-check-label text-uppercase fw-bold" for="optGiftcard">
                                giftcard
                            </label>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <input class="form-check-input w-auto me-2" type="checkbox" id="optGiftcard">
                            <label class="form-check-label text-uppercase fw-bold" for="optGiftcard">
                                newsletter
                            </label>
                        </div>
                    </div>

                    <!-- Botón Enviar -->
                    <button type="submit" class="btn btn-dark btn-lg w-100">ENVIAR</button>
                </form>
            </div>
        </div>
    </div>



    <!-- instagram -->


@endsection
