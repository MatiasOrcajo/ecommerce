@extends('layouts.app')

@section('content')

    <style>

        @media (max-width: 991.98px) {
            main {
                padding-bottom: 5rem;
                width: 100%;
                display: inline-block;
            }

            body {
                padding-top: 5rem;
            }

        }

        @media (min-width: 992px) {
            main {
                padding-bottom: 0;
                width: 100%;
                display: inline-block;
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


        main {
            overflow-x: hidden;
        }
    </style>

    <div class="container my-5 ">
        <div class="row gx-5">
            <div class="col-12 d-block d-md-none mb-3">
                <h2 style="font-size: 24px">{{$product->name}}</h2>
            </div>
            <!-- Imágenes -->
            <div class="col-md-6">

                <!-- Carousel principal -->
                <div id="productCarousel" class="carousel slide " data-bs-ride="false">
                    <div class="carousel-inner ">
                        <div class="carousel-item active h-50">
                            <div class="zoom-container">
                                <img
                                    src="https://acdn-us.mitiendanube.com/stores/001/235/896/products/juvia15377-ceef678ea8979c120e17443976253148-640-0.webp"
                                    alt="Producto 1"
                                    class="d-block product-image"
                                    style="margin: 0 auto;"
                                />
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="zoom-container">
                                <img
                                    src="https://acdn-us.mitiendanube.com/stores/001/235/896/products/juvia15371-fef3314ea60073fc7917443976090898-640-0.webp"
                                    alt="Producto 2"
                                    class="d-block product-image"
                                    style="margin: 0 auto;"
                                />
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="zoom-container">
                                <img
                                    src="https://acdn-us.mitiendanube.com/stores/001/235/896/products/juvia15378-5cc5928087d1a0f55717443976383372-640-0.webp"
                                    alt="Producto 3"
                                    class="d-block product-image"
                                    style="margin: 0 auto;"
                                />
                            </div>
                        </div>
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
                        <img
                            src="https://acdn-us.mitiendanube.com/stores/001/235/896/products/juvia15377-ceef678ea8979c120e17443976253148-640-0.webp"
                            class="thumbnail-item active"
                            data-bs-target="#productCarousel"
                            data-bs-slide-to="0"
                            alt="Mini 1"
                        />
                        <img
                            src="https://acdn-us.mitiendanube.com/stores/001/235/896/products/juvia15371-fef3314ea60073fc7917443976090898-640-0.webp"
                            class="thumbnail-item"
                            data-bs-target="#productCarousel"
                            data-bs-slide-to="1"
                            alt="Mini 2"
                        />
                        <img
                            src="https://acdn-us.mitiendanube.com/stores/001/235/896/products/juvia15378-5cc5928087d1a0f55717443976383372-640-0.webp"
                            class="thumbnail-item"
                            data-bs-target="#productCarousel"
                            data-bs-slide-to="2"
                            alt="Mini 3"
                        />
                    </div>
                    <button class="arrow-btn arrow-right" id="thumbNext">›</button>
                </div>

                <div class="my-3">
                    <div class="mb-4">
                        <div class="bg-light border rounded p-2">
                            <em>Descripción</em>
                        </div>
                        <p class="mt-2 mb-0">
                            Cardigan cuello redondo de lanilla, lleva botones a contra tono. Al ser de lanilla es bien
                            abrigado y liviano. Largo a la cadera.
                        </p>
                    </div>

                    <!-- Medidas -->
                    <div class="mb-4">
                        <div class="bg-light border rounded p-2">
                            <em>Medidas</em>
                        </div>
                        <div class="mt-2">
                            <p class="fw-bold mb-1">Talle 1:</p>
                            <ul class="mb-3">
                                <li>Contorno de busto (sin estirar): 90 cm</li>
                                <li>Contorno de cadera (sin estirar): 90 cm</li>
                                <li>Largo manga: 59 cm</li>
                                <li>Largo total: 50 cm</li>
                            </ul>

                            <p class="fw-bold mb-1">Talle 2:</p>
                            <ul class="mb-0">
                                <li>Contorno de busto (sin estirar): 92 cm</li>
                                <li>Contorno de cadera (sin estirar): 92 cm</li>
                                <li>Largo manga: 60 cm</li>
                                <li>Largo total: 51 cm</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Referencia Modelo -->
                    <div class="mb-4">
                        <div class="bg-light border rounded p-2">
                            <em>Referencia Modelo</em>
                        </div>
                        <p class="mt-2 mb-0">Talle: S //</p>
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

                    <p class="h4 text-dark"><small><del>${{$product->price}}</del> %{{$product->discount}} off</small></p>
                    <p class="h3 text-dark">${{$productPrice}}</p>
                    <p class="text-secondary">${{$productPriceWithBankTransferCondition}} con Transferencia</p>
                    <div class="border p-2 d-inline-block mb-3">
                        3 CUOTAS SIN INTERÉS DE <strong>${{$threeInstallments}}</strong>
                    </div>

                @else

                    <p class="h3 text-dark">${{$product->price}}</p>
                    <p class="text-secondary">${{$productPriceWithBankTransferCondition}} con Transferencia</p>
                    <div class="border p-2 d-inline-block mb-3">
                        3 CUOTAS SIN INTERÉS DE <strong>${{$threeInstallments}}</strong>
                    </div>
                @endif

                <div class="mb-3">
                    <span class="me-2">Medios de pago:</span>
                    <i style="color: #6e6ee7" class="fab fa-cc-visa"></i>
                    <i style="color: #6e6ee7" class="fab fa-cc-mastercard"></i>
                    <i style="color: #6e6ee7" class="fab fa-cc-amex"></i>
                </div>
                <p><strong>15% de descuento</strong> pagando con transferencia</p>
                <p><i class="fas fa-truck"></i> Envío gratis superando los $30.000</p>

                {{--                <div class="mb-3">--}}
                {{--                    <label class="d-block mb-1"><strong>Color:</strong> NEGRO</label>--}}
                {{--                    <div class="d-flex gap-2">--}}
                {{--                        <button class="btn p-0 border" style="background:#000; width:32px; height:32px;"></button>--}}
                {{--                        <button class="btn p-0 border" style="background:#ccc; width:32px; height:32px;"></button>--}}
                {{--                        <button class="btn p-0 border" style="background:#fff; width:32px; height:32px;"></button>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                <div class="my-4">
                    <label class="d-block mb-1" id="sizeSelector"><strong>Talle:</strong> S</label>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary sizes">S</button>
                        <button class="btn btn-outline-secondary sizes">M</button>
                        <button class="btn btn-outline-secondary sizes">L</button>
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

    <!-- Bootstrap 5 JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
            integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    <script>

        let selectedSize;
        let selectedQuantity;

        document.querySelectorAll(".sizes").forEach(function (element){
            element.addEventListener('click', function (e){

                selectedSize = e.target.innerHTML;
                document.getElementById('sizeSelector').innerHTML = '<strong>Talle: </strong>' + selectedSize;
                document.querySelectorAll(".sizes").forEach(function (element){
                    element.classList.remove('active');
                });
                e.target.classList.add('active');

            })
        })


        $('#add-product-to-cart').click(function(){
            const id = {{$product->id}};
            const route = '/carts/products/' + id

            if(selectedSize == undefined){
               toastr.error('Debe seleccionar un talle');
            }
            else{
                $.ajax({
                    type: "POST",
                    url: route,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        size: selectedSize,
                        quantity: $('#quantity').val(),
                    },
                    success: function (xhr, status, error) {
                        console.log(xhr);
                        toastr.success('Producto agregado al carrito');
                    }
                })
            }
        })


        // Carousel y thumbnails
        const carouselEl = document.getElementById('productCarousel');
        const carousel = new bootstrap.Carousel(carouselEl, {ride: false});
        const thumbs = document.querySelectorAll('.thumbnail-item');
        const thumbContainer = document.querySelector('.thumbnail-container');

        // Flechas de scroll de thumbnails
        document.getElementById('thumbPrev').onclick = () => {
            thumbContainer.scrollBy({left: -100, behavior: 'smooth'});
        };
        document.getElementById('thumbNext').onclick = () => {
            thumbContainer.scrollBy({left: 100, behavior: 'smooth'});
        };

        // Sincronizar thumbnail activo
        carouselEl.addEventListener('slid.bs.carousel', () => {
            const items = Array.from(carouselEl.querySelectorAll('.carousel-item'));
            const idx = items.findIndex(i => i.classList.contains('active'));
            thumbs.forEach((t, i) => t.classList.toggle('active', i === idx));
        });

        // Click en thumbnail cambia slide
        thumbs.forEach((thumb, i) => {
            thumb.addEventListener('click', () => carousel.to(i));
        });

        // Zoom pan: transformar el origen al mover el mouse
        document.querySelectorAll('.zoom-container').forEach(container => {
            const img = container.querySelector('img');
            container.addEventListener('mouseenter', () => {
                container.classList.add('zoom-active');
            });
            container.addEventListener('mousemove', e => {
                const {left, top, width, height} = container.getBoundingClientRect();
                const x = ((e.clientX - left) / width) * 100;
                const y = ((e.clientY - top) / height) * 100;
                img.style.transformOrigin = `${x}% ${y}%`;
            });
            container.addEventListener('mouseleave', () => {
                container.classList.remove('zoom-active');
                img.style.transformOrigin = 'center center';
            });
        });
    </script>

@endsection
