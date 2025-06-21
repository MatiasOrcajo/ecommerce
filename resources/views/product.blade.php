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
                                <div class="carousel-item active h-50">
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

                    <p class="h4 text-dark"><small><del>${{$product->price}}</del> %{{$product->discount}} off</small></p>
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
                    <div class="d-flex gap-2">
                        <button class="btn p-0 border" style="background:{{$product->color}}; width:32px; height:32px;"></button>
                    </div>
                </div>

                <div class="my-4">
                    <label class="d-block mb-1" id="sizeSelector"><strong>Talle:</strong></label>
                    <div class="d-flex gap-2">
                        @foreach($product->sizes as $size)
                            <button
                                class="btn btn-outline-secondary sizes"
                                @if($size->stock == 0) disabled @endif>
                                {{$size->size}}
                            </button>
                        @endforeach
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

    <style>
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
        <script src="{{ asset('js/updateCartProductsQuantity.js') }}"></script>

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
                            toastr.success('Producto agregado al carrito');
                            updateCartCounter();

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
    @endpush


@endsection
