@extends('layouts.app')

@section('content')

    <div class="container m-0" style="max-width: 100%">
        <div class="row">
            <!-- Formulario de compra -->
            <div class="col-md-7 steps-container">
                <div id="stepForm" class="step-form-container">
                    <!-- Step navigation -->
                    <ul class="nav nav-pills mb-4 ul-steps" id="steps">
                        <!-- Primer paso: Cliente -->
                        <li class="nav-item li-step li-steps-form d-flex align-items-center grey-background"
                            id="step-client">
                            <div class="li-step-icon">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div>
                                <div class="li-step-title">Cliente</div>
                                <div class="li-step-description">Ingresa tus datos</div>
                            </div>
                        </li>
                        <!-- Segundo paso: Pago -->
                        <li class="nav-item li-step li-step-second d-flex align-items-center" id="step-payment">
                            <div class="li-step-icon">
                                <i class="fa-regular fa-credit-card" style="color: blue"></i>
                            </div>
                            <div>
                                <div class="li-step-title">Pago</div>
                                <div class="li-step-description">Elige cómo pagar</div>
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
                                    <input type="email" class="form-control" id="email" placeholder="matias@gmail.com"
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
                                <button type="button" id="continue-to-payment-step-button" class="btn btn-primary" data-bs-target="#step2"
                                        data-bs-toggle="pill">Continuar con el método de pago
                                </button>
                            </form>
                        </div>

                        <!-- Step 2: Datos de facturación -->
                        <div class="tab-pane fade" id="step2">
                            <h5 class="mb-3">Datos de facturación</h5>
                            <form id="billingForm">
                                <div class="row">
                                    <div class="mb-3 col-md-4">
                                        <label for="documentType" class="form-label">Tipo de documento</label>
                                        <select class="form-select" id="documentType" required>
                                            <option value="">Selecciona un tipo</option>
                                            <option value="DNI">DNI</option>
                                            <option value="CUIT">CUIT</option>
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
                        <div class="mt-5 col-md-6">
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
                            <h2>Total</h2>
                            <div id="total-price" class="text-success">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        document.addEventListener("DOMContentLoaded", () => {

            let helperTotalAmountToBeDisplayed = 0;
            let couponIsApplied = 0;

            /**
             * Retrieves a list of province and populates a dropdown menu with them.
             * Also fetches and updates a list of locality in another dropdown when a province is selected.
             *
             * @return {void} This method does not return a value. It updates the dropdown menus in the DOM.
             */
            function listProvinces() {

                axios.get("https://apis.datos.gob.ar/georef/api/provincias?campos=id,nombre")
                    .then(response => {
                        let province = response.data.provincias;
                        let html = "";
                        province.forEach(province => {
                            html += `<option value="${province.nombre}">${province.nombre}</option>`
                        });

                        html += `<option selected="true" disabled="disabled">Seleccione una opción</option>`
                        document.getElementById('province').innerHTML = html;
                    })

                document.getElementById('province').addEventListener('change', (event) => {
                    axios.get(`https://apis.datos.gob.ar/georef/api/municipios?provincia=${event.target.value}&campos=id,nombre&max=500`)
                        .then(response => {
                            let locality = response.data.municipios;
                            let html = "";
                            locality.forEach(locality => {
                                html += `<option value="${locality.nombre}">${locality.nombre}</option>`
                            });

                            html += `<option selected="true" disabled="disabled">Seleccione una opción</option>`

                            document.getElementById('locality').innerHTML = html;
                        })
                })
            }

            listProvinces();



            const steps = document.querySelectorAll("#steps .li-step");
            const tabs = document.querySelectorAll(".tab-pane");

            // Manejador de clics en los pasos (navegación por pestañas)
            steps.forEach((step) => {
                step.addEventListener("click", (event) => {
                    const clickedStep = event.target.closest(".li-step");
                    if (clickedStep) {
                        let targetStep = null;

                        // Activa la pestaña correspondiente
                        if (clickedStep.id === "step-client") {
                            targetStep = "step1";
                            document.getElementById('step-client').classList.add("grey-background")
                            document.getElementById('step-payment').classList.remove("grey-background")

                        } else {
                            targetStep = "step2";
                            document.getElementById('step-client').classList.remove("grey-background")
                            document.getElementById('step-payment').classList.add("grey-background")
                        }

                        setActiveTab(targetStep);
                    }

                    const otherStep = steps.find(step => step !== clickedStep);
                    clickedStep.classList.add("grey-background");
                    otherStep.classList.remove("grey-background");
                });
            });

            document.getElementById('continue-to-payment-step-button').addEventListener('click', () => {

                setActiveTab("step2");
                document.getElementById('step-client').classList.remove("grey-background")
                document.getElementById('step-payment').classList.add("grey-background")

            })


            // Función para activar la pestaña correspondiente
            function setActiveTab(targetId) {
                tabs.forEach((tab) => {
                    tab.classList.remove("show", "active");
                    if (tab.id === targetId) {
                        tab.classList.add("show", "active");
                    }
                });

                // Actualiza el estado visual de los pasos
                steps.forEach((step) => {
                    step.classList.remove("active");
                    if (step.id === "step-client" && targetId === "step1") {
                        step.classList.add("active", "grey-background");
                    } else if (step.id === "step-payment" && targetId === "step2") {
                        step.classList.add("active", "grey-background");
                    }
                });
            }


            //Validate coupon

            function getRemainingPercentageInDecimals(discount){
                return 1 - (discount / 100)
            }

            const btnValidateCoupon = document.getElementById('validate-coupon-button');
            let coupon_id = null;

            btnValidateCoupon.addEventListener('click', () => {

                let coupon = document.getElementById('coupon').value;

                axios.get(`{{route('validate-coupon')}}` + '?code=' + coupon)
                .then(response => {
                        $('#coupon-validated-success').html("Cupón validado");
                        $('#coupon-validated-failed').html("");
                        $('#coupon-success-code').html(`Aplicado ${response.data.coupon_discount}% OFF`)

                        $('#total-price').html(`<del><h1>$${helperTotalAmountToBeDisplayed}</h1></del> <h1>$${helperTotalAmountToBeDisplayed * getRemainingPercentageInDecimals(response.data.coupon_discount)}</h1>`);

                        coupon_id = response.data.coupon_id;

                        couponIsApplied = response.data.coupon_discount

                    })
                .catch(error => {
                    $('#coupon-validated-success').html("");
                    const errorMessage = error?.response?.data?.message || "Unexpected error occurred.";
                    $('#coupon-validated-failed').html(errorMessage);
                })

            });


            const btnSubmit = document.getElementById('submit');

            // Recopilar datos y enviarlos
            btnSubmit.addEventListener('click', () => {
                const data = {
                    name: document.getElementById('firstName').value,
                    surname: document.getElementById('lastName').value,
                    phone: document.getElementById('phone').value,
                    dni: document.getElementById('documentNumber').value,
                    email: document.getElementById('email').value,
                    locality: document.getElementById('locality').value,
                    province: document.getElementById('province').value,
                    street: document.getElementById('street').value,
                    number: document.getElementById('number').value,
                    apartment: document.getElementById('apartment').value,
                    zip_code: document.getElementById('zip_code').value,
                    coupon_id: coupon_id,
                };

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });


                $.ajax({
                    type: "POST",
                    url: '{{route('pay')}}',
                    data: {
                        data: JSON.stringify(data),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        window.open(res.init_point, '_blank');
                    },
                    error: function (res, textStatus, errorThrown) {
                        console.log(res);
                    },
                });

            });


            //items summary

            function getItemsSummary() {

                $('#items-summary-container').html("");

                $.ajax({
                    type: "GET",
                    url: '{{route('cart-info')}}',
                    success: function (xhr, status, error) {

                        let key = Object.keys(xhr)[0];
                        let items = xhr[key];
                        let html = "";
                        let total = 0;

                        if(Object.entries(items).length <= 0){
                            location.reload();
                        }

                        Object.entries(items).forEach(([key, item]) => {
                            let priceHtml = ``;

                            if (item.price * item.quantity > item.total_amount_with_discount_to_be_shown) {
                                priceHtml = `<del><h4>$${item.price * item.quantity} </h4> </del>
                                             <h4 class="text-success">$${item.total_amount_with_discount_to_be_shown}</h4>
                                            `
                                total += item.total_amount_with_discount_to_be_shown;
                            } else {
                                priceHtml = `<h4 class="text-success">$${item.price * item.quantity}</h4> `

                                total += item.price * item.quantity;
                            }

                            html += `

                                <div class="p-3 my-3 d-flex align-items-center border rounded w-75" style="position: relative">
                                    <button class="x-cart-button delete_cart_product" id="${item.id}">X</button>
                                    <div class="order-summary-thumbnail">
                                        <img src="${item.picture}"
                                             alt="" class="img-fluid">
                                            <div class="item-quantity">${item.quantity}</div>
                                    </div>
                                    <h5 class="d-block mx-3">${item.name}</h5>
                                    ${priceHtml}
                                </div>
                                `

                        })
                        helperTotalAmountToBeDisplayed = total;
                        $('#total-price').html(`<h1>$${total}</h1>`);
                        $('#items-summary-container').html(html);

                        if(couponIsApplied > 0){
                            $('#total-price').html(`<del><h1>$${helperTotalAmountToBeDisplayed}</h1></del> <h1>$${helperTotalAmountToBeDisplayed * getRemainingPercentageInDecimals(couponIsApplied)}</h1>`);
                        }


                        document.querySelectorAll('.delete_cart_product').forEach(element => {
                            element.addEventListener('click', (event) => {

                                const id = event.target.id;
                                const route = '/cart/' + id

                                $.ajax({
                                    type: "DELETE",
                                    url: route,
                                    data: {
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function (xhr, status, error) {

                                        getItemsSummary()


                                    }
                                })
                            })


                        })
                    },
                    error: function (xhr, status, error) {
                        $('#items-summary-container').html("");
                    },
                });

            }

            getItemsSummary();

        });
    </script>

@endsection

