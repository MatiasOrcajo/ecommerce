@extends('layouts.app')

@section('title')
    <title>Carrito - Ática</title>
@endsection

@section('content')

    <style>

        input, option, select {
            background-color: #ffffff !important;
        }

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
        }


    </style>


    <div class="container mt-5 mt-md-4 translate-y-mobile" style="max-width: 100%; ">
        <div class="row">
            <!-- Formulario de compra -->
            <div class="col-md-7 steps-container" style="padding-bottom: 20rem; background: #ffffff;">
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
                        <!-- Step 1: Información de contacto -->
                        <div class="tab-pane fade show active" id="step1">
                            <h5 class="mb-3">Información de contacto</h5>
                            <form id="contactForm">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="email@gmail.com"
                                           required>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="firstName" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="firstName" placeholder="Nombre"
                                               required>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="lastName" class="form-label">Apellido</label>
                                        <input type="text" class="form-control" id="lastName" placeholder="Apellido"
                                               required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="phone" placeholder="011 6172-1821"
                                           required>
                                </div>

                                <div class="row">

                                    <div class="mb-3 col-md-4">
                                        <label for="documentType" class="form-label">Tipo de documento</label>
                                        <select class="form-select" id="documentType" required>
                                            <option value="DNI" selected>DNI</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-8">
                                        <label for="documentNumber" class="form-label">Número de documento</label>
                                        <input type="text" class="form-control" id="documentNumber" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="province" class="form-label">Provincia</label>
                                        <select class="form-select" id="province" required>

                                        </select>
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
                                        <input type="text" class="form-control" id="apartment" required>
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label for="zip_code" class="form-label">Código postal</label>
                                        <input type="text" class="form-control" id="zip_code" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-3 col-md-12">
                                        <label for="observations" class="form-label">Observaciones para el
                                            envío:</label>
                                        <textarea class="form-control" id="observations" rows="3"
                                                  placeholder="Referencias para el repartidor; cosas a tener en cuenta; etc."></textarea>
                                    </div>

                                </div>

                                <button type="button" id="continue-to-payment-step-button" class="btn btn-primary"
                                        data-bs-target="#step2"
                                        data-bs-toggle="pill">Continuar con el método de pago
                                </button>
                            </form>
                        </div>

                        <!-- Step 2: Datos de facturación -->
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

                                <div class="row mb-5">
                                    <h5 class="mb-3">Método de envío</h5>

                                    <div id="shipping-option-wrapper"
                                         class="col-md-12 d-flex justify-content-center d-none">
                                        <button
                                            type="button"
                                            id="shipping-option"
                                            class="btn btn-outline-success btn-md w-75 payment-method-button mt-3 shipping_method"
                                            style="font-size: 1rem; border-color: green; color: green; font-weight: bold"
                                            data-shipment-method="shipping-option-wrapper"
                                        >
                                            <i class="mx-0" style="margin-right: 5px; font-size: 1.2rem;"></i>
                                            <span id="shipping-option-title">¡Llega hoy!</span>
                                        </button>
                                    </div>

                                    <div class="col-md-12 d-flex justify-content-center">
                                        <button type="button" id="andreani-button"
                                                class="btn btn-outline-success btn-md w-75 payment-method-button mt-3 shipping_method"
                                                style="font-size: 1rem;"
                                                data-shipment-method="andreani">
                                            <i class="mx-0" style="margin-right: 5px; font-size: 1.2rem;"></i>
                                            <span>Andreani</span>
                                            <br>
                                            <small>Gratis a todo el país</small>
                                        </button>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-center">
                                        <button type="button" id="take-away-button"
                                                class="btn btn-outline-success btn-md w-75 payment-method-button mt-3 shipping_method"
                                                style="font-size: 1rem;"
                                                data-shipment-method="take-away">
                                            <i class="mx-0" style="margin-right: 5px; font-size: 1.2rem;"></i>
                                            <span>Retiro en CABA</span>
                                            <br>
                                            <small>Bernardo de Irigoyen 630</small>
                                        </button>
                                    </div>
                                </div>

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
                                            <span>Transferencia Bancaria |
                                            10% off</span>
                                        </button>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-center">
                                        <button type="button" id="cash-button"
                                                class="btn btn-outline-success btn-md w-75 payment-method-button mt-3 payment_method"
                                                style="font-size: 1rem;"
                                                data-payment-method="cash">
                                            <i class="mx-0" style="margin-right: 5px; font-size: 1.2rem;"></i>
                                            <span>Efectivo |
                                            20% off</span>
                                            <br>
                                            <small>Solo para retirar en CABA</small>
                                        </button>
                                    </div>
                                </div>

                            </form>
                            <button id="submit" class="btn btn-success">Finalizar</button>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del producto -->
            <div class="col-md-5 checkout-info-container">
                <h3 class="mb-3">Resumen de la compra</h3>
                <div class="m-0 p-0" id="items-summary-container">

                </div>

                <form>
                    <div class="row">
                        <div class="mt-5 col-md-6" id="couponInputContainer">
                            <label for="billingFirstName" class="form-label">Tengo un cupón de descuento</label>
                            <div class="d-flex align-items-center justify-content-between">
                                <input type="text" class="mb-0 me-3 form-control" id="coupon"
                                       placeholder="Ingresa tu código" required>
                                <button type="button" class="btn btn-primary" id="validate-coupon-button">Validar
                                </button>
                            </div>
                            <div id="coupon-validated-success" class="mt-2"
                                 style="color: green; font-weight: bold"></div>
                            <div id="coupon-validated-failed" class="mt-2"
                                 style="color: red; font-weight: bold"></div>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="mt-4 col-md-6" style="border-top: 1px solid #ccc">
                        <div id="coupon-success-code"></div>
                        <div class="d-flex justify-content-between align-content-center mt-3">
                            <h2>Envío:</h2>
                            <div class="text-success">
                                GRATIS!
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-content-center mt-3">
                            <h2>Total</h2>
                            <div id="order_total" class="text-success">

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

