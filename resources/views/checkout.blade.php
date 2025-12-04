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
                                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' id='Capa_1' data-name='Capa 1' width='250.1771mm' height='84.8915mm' viewBox='0 0 709.1633 240.6375'%3E%3Cdefs%3E%3Cstyle%3E.cls-1%7Bfill:%23d0080f;%7D%3C/style%3E%3C/defs%3E%3Cpath class='cls-1' d='M286.7178,148.36l-1.9082-8.3767H266.6949l-4.5583,8.3767H250.0842l25.06-45.2013h13.76L300.0067,148.36Zm-5.7929-25.6509a36.881,36.881,0,0,1-1.1964-7.5061h-.1568a63.0484,63.0484,0,0,1-3.7883,8.043l-4.8076,8.4933h12.1388Z'/%3E%3Cpath class='cls-1' d='M335.63,148.36l-9.2071-19.48a82.9384,82.9384,0,0,1-3.8405-9.927c-.3037,3.354-.8747,7.9123-1.5744,12.1932L318.213,148.36H306.5487l7.3291-45.2013h15.549l8.616,17.89c1.7232,3.6716,3.2675,7.6288,4.5684,11.1013.2353-3.8807,1.05-9.8425,1.7513-14.1274l2.4049-14.8634h11.67L351.1086,148.36Z'/%3E%3Cpath class='cls-1' d='M437.9469,148.3593l-9.5791-17.6844h-.4725l-2.8533,17.6844H412.9073L420.24,103.158c4.4759-.2614,10.1582-.3318,16.46-.3318,12.36,0,19.2065,3.8144,17.6643,13.3875-1.0013,6.0926-6.3921,11.055-13.6207,12.5229,1.1139,1.7493,2.1535,3.4142,3.2232,5.0851l8.7568,14.5376Zm-3.571-36.9633c-1.2406,0-2.6461.07-3.362.1347l-1.878,11.5838c.6072.06,1.8438.1328,3.0121.1328,5.1253,0,8.7708-2.3445,9.3921-6.1589.5711-3.553-1.49-5.6924-7.1642-5.6924'/%3E%3Cpolygon class='cls-1' points='458.977 148.36 466.308 103.159 495.864 103.159 494.442 111.923 477.023 111.923 475.566 120.901 492.052 120.901 490.672 129.411 474.182 129.411 472.572 139.384 489.981 139.384 488.535 148.36 458.977 148.36'/%3E%3Cpath class='cls-1' d='M531.4747,148.36l-1.9122-8.3767H511.4458l-4.5483,8.3767H494.8512l25.0558-45.2013h13.7735L544.78,148.36Zm-5.7989-25.6509a39.25,39.25,0,0,1-1.1924-7.5061h-.1569a62.7354,62.7354,0,0,1-3.7862,8.043l-4.7956,8.4933h12.1268Z'/%3E%3Cpath class='cls-1' d='M578.7776,148.36l-9.2071-19.48a83.4276,83.4276,0,0,1-3.8285-9.927c-.3116,3.354-.8968,7.9123-1.59,12.1932L561.3566,148.36h-11.66l7.3251-45.2013h15.561l8.606,17.89c1.7413,3.6716,3.2654,7.6288,4.5784,11.1013.2393-3.8807,1.0456-9.8425,1.7433-14.1274l2.4169-14.8634h11.6623L594.2522,148.36Z'/%3E%3Cpolygon class='cls-1' points='606.385 148.36 613.728 103.159 625.855 103.159 618.52 148.36 606.385 148.36'/%3E%3Cpath class='cls-1' d='M222.5872,84.6876c-2.7064-7.1723-8.5778-12.9672-16.9746-16.7635-7.9022-3.5791-17.8614-5.2983-28.7877-4.9886a112.434,112.434,0,0,0-65.6,24.7722c-8.4009,6.9934-14.7407,14.8614-18.3037,22.7656-3.8043,8.3988-4.3714,16.6288-1.6569,23.8031,3.9994,10.6126,14.6482,17.9558,29.97,20.6663,15.1469,2.6944,33.1148.5308,50.574-6.0543a108.9828,108.9828,0,0,0,30.8266-17.64c8.4049-6.9813,14.7286-14.8674,18.2937-22.7656a34.9929,34.9929,0,0,0,3.34-14.4049,26.4154,26.4154,0,0,0-1.681-9.39M139.13,146.9962c-2.1938-.6836-2.7125-2.262-.8084-5.5053l9.7642-16.4881h26.8091l2.3385,10.4116a5.06,5.06,0,0,1-1.7976,4.9022c-.6253.2513-1.2427.4907-1.862.7319a88.0339,88.0339,0,0,1-34.4438,5.9477M151.98,118.9284l15.3439-25.3373,6.1066,25.3373Zm44.7067,9.4685a2.3855,2.3855,0,0,1-3.5792-1.4155l-11.435-44.0351s-1.5945-7.7091-8.3466-7.7091h-1.84L161.8892,91.54l-30.76,49.11c-2.0308,3.2111-4.1984,4.1441-6.41,3.9511-9.4424-2.9438-16.482-8.4511-19.4116-16.2106-7.0235-18.5852,12.1569-43.04,42.8326-54.6357,30.6758-11.608,61.247-5.9437,68.2545,12.6456,5.0208,13.2789-3.3318,29.5638-19.7072,41.9961'/%3E%3Cpath class='cls-1' d='M386.4066,103.164c-5.4994-.0362-11.4391.1307-14.6281.2232l-1.5362.0322-7.5825,45.26,4.6931.1749c3.2735.1307,6.6817.2916,11.0811.3178,9.8848.0683,17.97-2.9981,23.3849-8.8593,4.8177-5.1878,7.2608-12.3641,6.8847-20.1677-.5187-10.8459-8.433-16.8842-22.297-16.9807M379.3007,139.5c-.754,0-1.7373-.0583-2.6039-.1367.38-2.3928,4.0154-24.8286,4.2949-26.582,1.2607-.0562,2.8291-.09,4.679-.0784,9.0523.07,10.4618,4.1723,10.6528,8.18.3057,6.4866-2.8492,18.7139-17.0228,18.6174'/%3E%3C/svg%3E" alt="Andreani Logo" style="width: 100%; max-width: 200px; height: auto;">
                                                    <span style="font-weight: bold">Envío a domicilio</span><br>
                                                    <small>De 1 a 5 días hábiles</small>
                                                </div>
                                                <span class="fw-semibold small" id="andreani-price" style="color: green; font-weight: bold">GRATIS</span>
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
                                                    <span style="font-weight: bold">Retirar en CABA</span>
                                                    <br>
                                                    <small>Bernardo de Irigoyen 630 - Monserrat</small>
                                                    <br>
                                                    <small>CON CITA PREVIA</small>
                                                </div>
                                            </div>
                                        </button>
                                    </div>


                                    {{-- Opción "Llega hoy / mañana" --}}
                                    <div class="row mb-3">
                                        <div id="shipping-option-wrapper"
                                             class="col-md-12 d-flex justify-content-center d-none w-100">
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
                                    <h5 class="mb-3" style="font-weight: bold; font-size: 1.5rem">Método de pago</h5>
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

