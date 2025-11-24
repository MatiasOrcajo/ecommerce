@extends('layouts.app')

@section('title')
    <title>Carrito - Ática</title>
@endsection

@section('content')

    <style>

        .cart-item-card {
            background: #ffffff;
        }

        /* Para que el título no sea gigante en mobile */
        .cart-item-title {
            font-size: 1rem;
            line-height: 1.2;
        }

        /* Precio más contenido dentro del bloque */
        .cart-item-price h4 {
            font-size: 1rem;
            margin: 0;
        }
        .cart-item-price del h4 {
            font-size: 0.9rem;
        }

        /* Ajustes extra solo mobile */
        @media (max-width: 575.98px) {
            .cart-item-card {
                padding: 0.75rem 0.9rem;
            }

            .order-summary-thumbnail img {
                max-width: 64px;
            }

            .cart-item-title {
                font-size: 0.95rem;
            }

            .cart-item-price {
                margin-top: 0.5rem;
                text-align: left; /* si lo preferís a la izquierda en mobile */
            }
        }



        input, option, select {
            background-color: #ffffff !important;
        }

        /* Layout general checkout */
        body {
            background: #f3f4f6;
        }

        .steps-container {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
            padding: 24px 24px 120px;
        }

        .checkout-info-container {
            background: transparent;
        }

        /* Barra de pasos */
        #steps {
            list-style: none;
            padding: 0;
            margin: 0 0 1.5rem;
            display: flex;
            gap: 12px;
        }

        .li-step {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            cursor: pointer;
            transition: background .2s ease, border-color .2s ease, box-shadow .2s ease;
        }

        .li-step-icon {
            width: 28px;
            height: 28px;
            border-radius: 999px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 0 1px #e5e7eb;
        }

        .li-step-title {
            font-size: 14px;
            font-weight: 600;
        }

        .li-step-description {
            font-size: 12px;
            color: #6b7280;
        }

        .li-step.grey-background,
        .li-step.active {
            background: #111827;
            border-color: #111827;
            box-shadow: 0 10px 25px rgba(17, 24, 39, .35);
            color: #ffffff;
        }

        .li-step.grey-background .li-step-description {
            color: #e5e7eb;
        }

        /* Botón principal */
        #continue-to-payment-step-button,
        #submit {
            background: #111827;
            border-radius: 999px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: 12px 18px;
        }

        #continue-to-payment-step-button:hover,
        #submit:hover {
            background: #020617;
        }

        /* Summary a la Nube */
        .checkout-summary {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.16);
            background: #ffffff;
        }

        .checkout-summary-toggle {
            border: none;
            background: #f9fafb;
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .checkout-summary-toggle:hover {
            background: #eef2ff;
        }

        .checkout-summary-toggle span {
            font-size: 14px;
        }

        .checkout-summary-total {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }

        .checkout-summary-body {
            display: none;
            background: #ffffff;
        }

        .checkout-summary-body.is-open {
            display: block;
        }

        .checkout-summary-chevron {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            border-radius: 999px;
            border: 1px solid #d1d5db;
            margin-right: 8px;
            transition: transform .2s ease;
        }

        .checkout-summary-chevron.rotated {
            transform: rotate(180deg);
        }

        /* Tarjetas de resumen (step 2) */
        #step2 .card {
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        #step2 .card-body {
            padding: 12px 14px;
        }

        /* Método de pago / envío (botones pill) */
        .payment-method-button {
            border-radius: 999px;
            padding: 10px 18px;
        }

        /* Mobile */
        @media (max-width: 991.98px) {
            footer {
                display: none;
            }

            .col-md-7.steps-container {
                padding-bottom: 5rem !important;
            }

            .translate-y-mobile {
                transform: translateY(30px);
            }

            body {
                padding-top: 3rem;
            }

            .steps-container {
                box-shadow: none;
                border-radius: 0;
            }
        }


        /* ====== PASOS (Cliente / Pago) ====== */

        .ul-steps {
            display: flex;
            gap: 0;
            padding: 4px;
            border-radius: 999px;
            background: #f5f7fb;
            border: 1px solid #e5e7eb;
        }

        .li-step {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            border-radius: 999px;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: background .2s ease, color .2s ease, box-shadow .2s ease;
        }

        .li-step-icon {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 0 1px #e5e7eb;
        }

        .li-step-title {
            font-size: 14px;
            font-weight: 700;
        }

        .li-step-description {
            font-size: 12px;
            color: #6b7280;
        }

        /* Paso activo (cliente/pago) – pastilla oscura */
        .li-step.grey-background,
        .li-step.active {
            background: #0b1220;
            color: #ffffff;
            box-shadow: 0 12px 25px rgba(15, 23, 42, .35);
        }

        .li-step.grey-background .li-step-description,
        .li-step.active .li-step-description {
            color: #e5e7eb;
        }


        /* ====== BOTONES PASTILLA (envío + pago) ====== */

        .shipping_method,
        .payment_method {
            border-radius: 999px;
            border: 2px solid #e7c1bc; /* rosado suave */
            background: #ffffff;
            color: #b66b62;
            padding: 12px 20px;
            text-align: left;
            transition: background .2s ease, border-color .2s ease,
            box-shadow .2s ease, color .2s ease;
        }

        .shipping_method:not(:last-child),
        .payment_method:not(:last-child) {
            margin-bottom: 12px;
        }

        .shipping_method .fw-semibold,
        .shipping_method span,
        .shipping_method small,
        .payment_method span,
        .payment_method small {
            color: inherit;
        }

        /* Estado seleccionado (relleno rosado, texto blanco) */
        .shipping_method.selected,
        .payment_method.selected {
            background: #bc8d8a;
            border-color: #bc8d8a;
            color: #ffffff;
            box-shadow: 0 10px 24px rgba(188, 141, 138, .45);
        }

        .shipping_method.selected small,
        .payment_method.selected small {
            color: #f9fafb;
        }

        /* Envío express "Llega hoy/mañana" con verde */
        #shipping-option {
            border-color: #6ee7b7;
            background: #ecfdf3;
            color: #047857;
        }

        #shipping-option small {
            color: #047857;
        }

        #shipping-option.selected {
            background: #047857;
            border-color: #bbf7d0;
            color: white !important;
        }

        #shipping-option.selected small {
            color: #e0f2fe;
        }

        /* Mercado Pago: logo + texto más prolijo */
        #mercado-pago-button .d-flex {
            gap: 10px;
        }

    </style>



    <div class="container mt-5 mt-md-4 translate-y-mobile" style="max-width: 100%; ">
        <div class="row">
            <!-- Formulario de compra -->
            <div class="col-md-7 steps-container order-2 order-md-1"
                 style="padding-bottom: 20rem; background:#ffffff;">
                <div id="stepForm" class="step-form-container">
                    <!-- Step navigation -->
                    <ul class="nav nav-pills mb-4 ul-steps" id="steps">
                        <!-- Primer paso: Cliente -->
                        <li class="nav-item li-step li-steps-form d-flex align-items-center grey-background"
                            id="step-client">
                            <div class="li-step-icon">
                                <i class="fa-solid fa-user" style="color: #bc8d8a"></i>
                            </div>
                            <div>
                                <div class="li-step-title">Cliente</div>
                                <div class="li-step-description">Ingresá tus datos</div>
                            </div>
                        </li>
                        <!-- Segundo paso: Pago -->
                        <li class="nav-item li-step li-step-second d-flex align-items-center" id="step-payment">
                            <div class="li-step-icon">
                                <i class="fa-regular fa-credit-card" style="color: #bc8d8a"></i>
                            </div>
                            <div>
                                <div class="li-step-title">Pago</div>
                                <div class="li-step-description">Elegí cómo pagar</div>
                            </div>
                        </li>
                    </ul>

                    <!-- Form content -->
                    <div class="tab-content">

                        <!-- Step 1: Información de contacto + Entrega -->
                        <div class="tab-pane fade show active" id="step1">
                            {{-- DATOS DE CONTACTO --}}
                            <h5 class="mb-3" style="font-weight: bold; font-size: 1.5rem">Datos de facturación</h5>
                            <form id="contactForm">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="email@gmail.com" required>
                                </div>

                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="firstName" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="firstName" placeholder="Nombre" required>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="lastName" class="form-label">Apellido</label>
                                        <input type="text" class="form-control" id="lastName" placeholder="Apellido" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="phone" placeholder="011 6172-1821" required>
                                </div>

                                <div class="row">
                                    <div class="mb-3 col-md-4">
                                        <label for="documentType" class="form-label">Documento</label>
                                        <select class="form-select" id="documentType" required>
                                            <option value="DNI" selected>DNI</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-8">
                                        <label for="documentNumber" class="form-label">Número de documento</label>
                                        <input type="text" class="form-control" id="documentNumber" required>
                                    </div>
                                </div>

                                {{-- ENTREGA --}}
                                <hr class="my-4">
                                <h5 class="mb-3" style="font-weight: bold; font-size: 1.5rem">Entrega</h5>

                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <label for="zip_code" class="form-label">Código postal</label>
                                        <input type="text" class="form-control" id="zip_code" required>
                                    </div>
                                </div>

                                {{-- Andreani / Retiro --}}
                                <div class="row mb-4">
                                    <div class="col-md-12 d-flex justify-content-center">
                                        <button type="button" id="andreani-button"
                                                class="btn btn-outline-success btn-md w-100 payment-method-button mt-3 shipping_method"
                                                style="font-size: 1rem;"
                                                data-shipment-method="andreani">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-start">
                                                    <span>Andreani Envío a domicilio</span><br>
                                                    <small>De 1 a 3 días hábiles</small>
                                                </div>
                                                <span class="fw-semibold small" id="andreani-price">GRATIS</span>
                                            </div>
                                        </button>
                                    </div>

                                    <div class="col-md-12 d-flex justify-content-center">
                                        <button type="button" id="take-away-button"
                                                class="btn btn-outline-success btn-md w-100 payment-method-button mt-3 shipping_method"
                                                style="font-size: 1rem;"
                                                data-shipment-method="take-away">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-start">
                                                    <span>Retirar en CABA</span><br>
                                                    <small>Bernardo de Irigoyen 630</small>
                                                </div>
                                            </div>
                                        </button>
                                    </div>


                                    {{-- Opción "Llega hoy / mañana" --}}
                                    <div class="row mb-3">
                                        <div id="shipping-option-wrapper"
                                             class="col-md-12 d-flex justify-content-center d-none">
                                            <button
                                                type="button"
                                                id="shipping-option"
                                                class="btn btn-outline-success btn-md w-100 payment-method-button mt-3 shipping_method"
                                                style="font-size: 1rem; border-color: green; color: green; font-weight: bold"
                                                data-shipment-method="shipping-option-wrapper"
                                            >
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="text-start">
                                                        <span id="shipping-option-title">¡Llega hoy!</span><br>
                                                        <small id="shipping-option-subtitle">Comprando antes de las 13:00 hs</small>
                                                    </div>
                                                    <span class="fw-semibold small">Gratis</span>
                                                </div>
                                            </button>
                                        </div>
                                    </div>


                                </div>

                                {{-- DOMICILIO (solo para envíos, se oculta en retiro) --}}
                                <div id="address-section" class="border rounded-3 p-3 mb-4 d-none">
                                    <h6 class="mb-3" style="font-weight: bold; font-size: 1.5rem">Datos del destinatario</h6>

                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="province" class="form-label">Provincia</label>
                                            <select class="form-select" id="province" required></select>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="locality" class="form-label">Localidad</label>
                                            <select class="form-select" id="locality" required>
                                                <option value="">Selecciona una opción</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-12">
                                            <label for="street" class="form-label">Calle</label>
                                            <input type="text" class="form-control" id="street" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-md-4">
                                            <label for="number" class="form-label">Número</label>
                                            <input type="text" class="form-control" id="number" required>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="apartment" class="form-label">Piso/Depto</label>
                                            <input type="text" class="form-control" id="apartment">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-md-12">
                                            <label for="observations" class="form-label">Observaciones para el envío:</label>
                                            <textarea class="form-control" id="observations" rows="3"
                                                      placeholder="Referencias para el repartidor; cosas a tener en cuenta; etc."></textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- Campo hidden opcional si después querés leerlo desde el back --}}
                                <input type="hidden" id="selected_shipping_method" name="selected_shipping_method">

                                <button type="button"
                                        id="continue-to-payment-step-button"
                                        class="btn btn-primary w-100"
                                        data-bs-target="#step2"
                                        data-bs-toggle="pill">
                                    Continuar al pago
                                </button>
                            </form>
                        </div>



                        <!-- Step 2: Resumen + método de pago -->
                        <div class="tab-pane fade" id="step2">
                            <form id="billingForm">
                                <style>
                                    .btn-outline-success {
                                        color: #bc8d8a;
                                        border-color: #bc8d8a;
                                    }
                                    .btn:hover {
                                        color: var(--bs-btn-hover-color);
                                        background-color: #bc8d8a;
                                        border-color: #bc8d8a;
                                    }
                                </style>

                                {{-- SUMMARY CONTACTO + ENTREGA --}}
                                <div class="mb-4">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="fw-semibold">Datos de facturación</span>
                                                <button type="button" class="btn btn-link btn-sm p-0" id="summaryEditContact">
                                                    Cambiar
                                                </button>
                                            </div>
                                            <p class="mb-0" id="summaryEmail"></p>
                                            <p class="mb-0 small text-muted" id="summaryPhone"></p>
                                        </div>
                                    </div>

                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="fw-semibold">Entrega</span>
                                                <button type="button" class="btn btn-link btn-sm p-0" id="summaryEditShipping">
                                                    Cambiar
                                                </button>
                                            </div>
                                            <p class="mb-0" id="summaryShippingMethod"></p>
                                            <p class="mb-0 small text-muted" id="summaryAddress"></p>
                                            <p class="mb-0 small text-muted" id="summaryCityZip"></p>
                                        </div>
                                    </div>
                                </div>

                                {{-- MÉTODO DE PAGO (tus botones originales) --}}
                                <div class="row mb-5">
                                    <h5 class="mb-3">Método de pago</h5>
                                    <div class="col-md-12 d-flex justify-content-center">
                                        <button type="button" id="mercado-pago-button"
                                                class="btn btn-outline-success btn-md w-75 payment-method-button mt-3 payment_method"
                                                style="font-size: 1rem;"
                                                data-payment-method="mercado-pago">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <img src="{{asset('MP_RGB_HANDSHAKE_color_vertical.png')}}"
                                                     style="width: 60px;" alt="">
                                                <span>Mercado Pago</span>
                                            </div>
                                        </button>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-center">
                                        <button type="button" id="bank-transfer-button"
                                                class="btn btn-outline-success btn-md w-75 payment-method-button mt-3 payment_method"
                                                style="font-size: 1rem;"
                                                data-payment-method="bank-transfer">
                                            <i class="mx-0" style="margin-right: 5px; font-size: 1.2rem;"></i>
                                            <span>Transferencia Bancaria | 10% off</span>
                                        </button>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-center">
                                        <button type="button" id="cash-button"
                                                class="btn btn-outline-success btn-md w-75 payment-method-button mt-3 payment_method"
                                                style="font-size: 1rem;"
                                                data-payment-method="cash">
                                            <i class="mx-0" style="margin-right: 5px; font-size: 1.2rem;"></i>
                                            <span>Efectivo | 20% off</span><br>
                                            <small>Solo para retirar en CABA</small>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <button id="submit" class="btn btn-success w-100">Finalizar</button>
                        </div>


                    </div>
                </div>
            </div>

            <!-- Información del producto -->
            <div class="col-md-5 checkout-info-container order-1 order-md-2 px-0 px-md-3">
                <div class="checkout-summary">
                    {{-- Cabecera tipo Nube --}}
                    <button type="button"
                            class="checkout-summary-toggle"
                            id="checkoutSummaryToggle">
                        <div class="d-flex align-items-center">
                <span id="checkoutSummaryChevron" class="checkout-summary-chevron">
                    <i class="fa-solid fa-chevron-down" style="font-size:11px;"></i>
                </span>
                            <span>Ver detalles de mi compra</span>
                        </div>
                        <span class="checkout-summary-total" id="order_total_inline">$0,00</span>
                    </button>

                    {{-- Cuerpo desplegable --}}
                    <div class="checkout-summary-body" id="checkoutSummaryBody">
                        <div class="p-3 border-top">
                            <div class="m-0 p-0" id="items-summary-container"></div>
                        </div>

                        <form class="px-3 pb-3">
                            <div class="row">
                                <div class="mt-3 col-md-12" id="couponInputContainer">
                                    <label for="coupon" class="form-label">Tengo un cupón de descuento</label>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <input type="text" class="mb-0 me-3 form-control" id="coupon"
                                               placeholder="Ingresa tu código" required>
                                        <button type="button" class="btn btn-primary" id="validate-coupon-button">
                                            Validar
                                        </button>
                                    </div>
                                    <div id="coupon-validated-success" class="mt-2"
                                         style="color: green; font-weight: bold"></div>
                                    <div id="coupon-validated-failed" class="mt-2"
                                         style="color: red; font-weight: bold"></div>
                                </div>
                            </div>
                        </form>

                        <div class="px-3 pb-3 border-top">
                            <div id="coupon-success-code" class="mb-2 small text-success"></div>

                            <div class="d-flex justify-content-between align-content-center mt-3">
                                <span class="fw-semibold">Envío:</span>
                                <span class="text-success fw-semibold">GRATIS</span>
                            </div>

                            <div class="d-flex justify-content-between align-content-center mt-3">
                                <span class="fw-bold">Total</span>
                                <div id="order_total" class="text-success fw-bold"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/updateCartProductsQuantity.js') }}"></script>
    <script src="{{ asset('js/checkout.js') }}"></script>

@endsection

