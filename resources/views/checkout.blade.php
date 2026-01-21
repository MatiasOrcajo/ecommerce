@extends('layouts.app')

@section('title')
    <title>Carrito - Ática</title>
@endsection

@section('content')

    <style>

        .payment-methods {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
        }

        .payment-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 16px;
            margin: 0;
            cursor: pointer;
            user-select: none;
        }

        .payment-option + .payment-option {
            border-top: 1px solid #e5e7eb;
        }

        /* Radio “tipo imagen” */
        .payment-option__radio {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .payment-option__circle {
            width: 18px;
            height: 18px;
            border-radius: 999px;
            border: 2px solid #9ca3af;
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 18px;
        }

        .payment-option__radio:checked + .payment-option__circle {
            border-color: #111827;
        }

        .payment-option__radio:checked + .payment-option__circle::after {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #111827;
        }

        /* Texto y badge */
        .payment-option__label {
            font-weight: 600;
            color: #111827;
            line-height: 1.1;
        }

        .payment-option__badge {
            margin-left: 10px;
            font-size: 12px;
            font-weight: 700;
            color: #0f5132;
            background: #d1e7dd;
            border-radius: 999px;
            padding: 4px 10px;
            white-space: nowrap;
        }

        /* Mercado Pago logo */
        .payment-option__label--mp {
            font-weight: 600;
        }

        .payment-option__mp-logo {
            height: 34px;
            width: auto;
            display: block;
        }

        /* Hover suave como en listas */
        .payment-option:hover {
            background: #f9fafb;
        }

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
            padding: 10px 18px;
        }

/* Contenedor tipo lista */
.shipping-methods{
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
}

/* Item */
.shipping-option{
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    margin: 0;
    cursor: pointer;
    user-select: none;
}

.shipping-option + .shipping-option{
    border-top: 1px solid #e5e7eb;
}

.shipping-option:hover{
    background: #f9fafb;
}

/* Radio “custom” */
.shipping-option__radio{
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.shipping-option__circle{
    width: 18px;
    height: 18px;
    border-radius: 999px;
    border: 2px solid #9ca3af;
    background: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 18px;
}

.shipping-option__radio:checked + .shipping-option__circle{
    border-color: #111827;
}

.shipping-option__radio:checked + .shipping-option__circle::after{
    content: "";
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background: #111827;
}

/* Contenido (texto/logo) */
.shipping-option__content{
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
}

.shipping-option__logo{
    width: 160px;
    max-width: 100%;
    height: auto;
    display: block;
    margin-bottom: 2px;
}

.shipping-option__title{
    font-weight: 700;
    color: #111827;
    line-height: 1.1;
}

.shipping-option__subtitle{
    color: #6b7280;
    line-height: 1.2;
}

/* Precio a la derecha */
.shipping-option__price{
    margin-left: auto;
    font-weight: 800;
    color: #16a34a;
    white-space: nowrap;
}

/* Variante destacada (Llega hoy/mañana) */
.shipping-option--highlight .shipping-option__price{
    color: #16a34a;
}

/* Mobile: logo un poquito más chico */
@media (max-width: 575.98px){
    .shipping-option__logo{ width: 140px; }
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
            border: 2px solid black !important; /* rosado suave */
            background: #ffffff;
            color: black !important;
            padding: 12px 20px;
            text-align: left;
            transition: background .2s ease, border-color .2s ease,
            box-shadow .2s ease, color .2s ease;
            font-size: 1.3rem !important;
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
            background: #fceceb;
            border-color: #fceceb;
            color: #ffffff;
            box-shadow: 0 10px 24px rgba(188, 141, 138, .45);
        }

        .shipping_method.selected small,
        .payment_method.selected small {
            color: black !important;
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

                                <div id="address-section" class="border rounded-3 p-3 mb-4 d-block">
                                    <h6 class="mb-3" style="font-weight: bold; font-size: 1.5rem">Datos del
                                        destinatario</h6>

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
                                            <label for="observations" class="form-label">Observaciones para el
                                                envío:</label>
                                            <label for="observations" class="form-label">Si preferís que te enviemos a
                                                una sucursal de Correo Argentino, dejalo escrito acá</label>
                                            <textarea class="form-control" id="observations" rows="3"
                                                      placeholder="Referencias; cosas a tener en cuenta; etc."></textarea>
                                        </div>
                                    </div>
                                </div>


                                {{-- correo-argentino / Retiro --}}
                                <div class="row mb-4">

                                    <div class="shipping-methods">
                                        <label class="shipping-option" for="sm_correo">
                                            <input
                                                class="shipping-option__radio"
                                                type="radio"
                                                name="shipping_method"
                                                id="sm_correo"
                                                value="correo-argentino"
                                                data-shipment-method="correo-argentino"
                                            >
                                            <span class="shipping-option__circle" aria-hidden="true"></span>

                                            <span class="shipping-option__content">
                                                <img
                                                    src="https://www.correoargentino.com.ar/sites/default/files/logo-correo.png"
                                                    alt="Correo Argentino"
                                                    class="shipping-option__logo"
                                                >
                                                <span class="shipping-option__title">Envío a domicilio</span>
                                                <small class="shipping-option__subtitle">De 1 a 5 días hábiles</small>
                                            </span>

                                            <span
                                                class="shipping-option__price shipping-cost-price"
                                                id="correo-argentino-price"
                                            >GRATIS</span>
                                        </label>

                                        <label class="shipping-option" for="sm_takeaway">
                                            <input
                                                class="shipping-option__radio"
                                                type="radio"
                                                name="shipping_method"
                                                id="sm_takeaway"
                                                value="take-away"
                                                data-shipment-method="take-away"
                                            >
                                            <span class="shipping-option__circle" aria-hidden="true"></span>

                                            <span class="shipping-option__content">
            <span class="shipping-option__title">Retirar en CABA</span>
            <small class="shipping-option__subtitle">Bernardo de Irigoyen 630 - Monserrat</small>
            <small class="shipping-option__subtitle">CON CITA PREVIA</small>
        </span>
                                        </label>

                                        {{-- Opción "Llega hoy / mañana" --}}
                                        <div id="shipping-option-wrapper" class="d-none shipping-option-selector">
                                            <label class="shipping-option shipping-option--highlight" for="sm_arrives">
                                                <input
                                                    class="shipping-option__radio"
                                                    type="radio"
                                                    name="shipping_method"
                                                    id="sm_arrives"
                                                    value="shipping-option-wrapper"
                                                    data-shipment-method="shipping-option-wrapper"
                                                >
                                                <span class="shipping-option__circle" aria-hidden="true"></span>

                                                <span class="shipping-option__content">
                <span class="shipping-option__title" id="shipping-option-title">¡Llega hoy!</span>
                <small class="shipping-option__subtitle" id="shipping-option-subtitle">
                    Comprando antes de las 13:00 hs
                </small>
            </span>

                                                <span class="shipping-option__price shipping-cost-price">Gratis</span>
                                            </label>
                                        </div>
                                    </div>


                                </div>

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
                                                <button type="button" class="btn btn-link btn-sm p-0"
                                                        id="summaryEditContact">
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
                                                <button type="button" class="btn btn-link btn-sm p-0"
                                                        id="summaryEditShipping">
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
                                    <h5 class="mb-3" style="font-weight: bold; font-size: 1.5rem">Método de pago</h5>
                                    <div class="payment-methods">
                                        <label class="payment-option" for="pm_bank">
                                            <input
                                                class="payment-option__radio"
                                                type="radio"
                                                name="payment_method"
                                                id="pm_bank"
                                                value="bank-transfer"
                                                data-payment-method="bank-transfer"
                                            >
                                            <span class="payment-option__circle" aria-hidden="true"></span>

                                            <span class="payment-option__label">Transferencia bancaria</span>
                                            <span class="payment-option__badge">10% de descuento</span>
                                        </label>

                                        <label class="payment-option" for="pm_cash">
                                            <input
                                                class="payment-option__radio"
                                                type="radio"
                                                name="payment_method"
                                                id="pm_cash"
                                                value="cash"
                                                data-payment-method="cash"
                                            >
                                            <span class="payment-option__circle" aria-hidden="true"></span>

                                            <span class="payment-option__label">Efectivo</span>
                                            <span class="payment-option__badge">10% de descuento</span>
                                        </label>

                                        <label class="payment-option" for="pm_mp">
                                            <input
                                                class="payment-option__radio"
                                                type="radio"
                                                name="payment_method"
                                                id="pm_mp"
                                                value="mercado-pago"
                                                data-payment-method="mercado-pago"
                                            >
                                            <span class="payment-option__circle" aria-hidden="true"></span>

                                            <span class="payment-option__label payment-option__label--mp d-flex align-items-center justify-content-center">
                                                <span class="payment-option__label">Mercado Pago</span>
                                                <img
                                                    src="{{ asset('MP_RGB_HANDSHAKE_color_vertical.png') }}"
                                                    alt="Mercado Pago"
                                                    class="payment-option__mp-logo"
                                                >
                                            </span>
                                        </label>
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
                                <span class="fw-semibold">Envío (gratis a partir de $35.000):</span>
                                <span class="text-success fw-semibold shipping-cost-price">GRATIS</span>
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

