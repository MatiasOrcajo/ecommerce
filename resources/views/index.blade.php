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
        @foreach($products as $product)

            <div class="col-md-4">
                <div class="card border-0 h-100 p-0">

                    <!-- Imagen con hover -->
                    <div class="image-container">

                        <!-- Imagen por defecto -->
                        @php
                            $pictures = $product->pictures->toArray();
                            $firstPicture = $pictures[0];
                            $secondPicture = $pictures[1];
                        @endphp

                        <img
                            src="{{ $firstPicture["path"] }}"
                            class="card-img-top img-first"
                            alt="{{$product->name}}">
                        <!-- Imagen que aparece al pasar el mouse -->
                        <img
                            src="{{ $secondPicture["path"] }}"
                            class="card-img-top img-second"
                            alt="{{$product->name}} - Hover">

                        <!-- Iconos superpuestos -->
                        <a href="{{ route('product.show', $product->slug) }}">
                            <button class="btn btn-light position-absolute bottom-0 end-0 m-2">
                                <i class="fas fa-shopping-bag"></i>
                            </button>
                        </a>
                    </div>

                    <!-- Body: título y precios -->
                    <div class="card-body d-flex flex-column position-relative">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="color-box-parent d-flex justify-content-center align-items-center">
                                <div class="color-box" style="background-color: {{$product->color}}">

                                </div>
                            </div>
                        </div>
                        <h5 class="card-title text-center mb-2">{{$product->name}}</h5>
                        <p class="text-center mb-1 fw-bold">${{$product->price}}</p>
                        <p class="text-center mb-2 text-muted">${{$product->price * 0.9}} con Transferencia bancaria</p>
                        <p class="text-center small text-muted mb-0">
                            6 cuotas sin interés de ${{$product->price / 3}}
                        </p>


                        <!-- Botón "Ver" -->
                        <a href="{{ route('product.show', $product->slug) }}"
                           class="btn btn-white border-black w-25 mx-auto mt-3 d-block">
                            Ver
                        </a>

                    </div>
                </div>
            </div>
        @endforeach

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
    <div class="p-0 mb-5 w-100 translate-y-0 d-flex justify-content-center align-items-center">

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

@endsection
