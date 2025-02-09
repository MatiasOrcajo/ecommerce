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
                        <li class="nav-item li-step li-steps-form d-flex align-items-center grey-background" id="step-client">
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
                                    <input type="email" class="form-control" id="email" placeholder="matias@gmail.com" required>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="firstName" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="firstName" placeholder="Matias" required>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="lastName" class="form-label">Apellido</label>
                                        <input type="text" class="form-control" id="lastName" placeholder="Orcajo" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="phone" placeholder="011 6172-1821" required>
                                </div>
                                <button type="button" class="btn btn-primary" data-bs-target="#step2" data-bs-toggle="pill">Continuar con el método de pago</button>
                            </form>
                        </div>

                        <!-- Step 2: Datos de facturación -->
                        <div class="tab-pane fade" id="step2">
                            <h5 class="mb-3">Datos de facturación</h5>
                            <form id="billingForm">
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="billingFirstName" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="billingFirstName" placeholder="Matias" required>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="billingLastName" class="form-label">Apellido</label>
                                        <input type="text" class="form-control" id="billingLastName" placeholder="Orcajo" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="billingPhone" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="billingPhone" placeholder="011 6172-1821" required>
                                </div>
                                <div class="mb-3">
                                    <label for="documentType" class="form-label">Tipo de documento</label>
                                    <select class="form-select" id="documentType" required>
                                        <option value="">Selecciona un tipo</option>
                                        <option value="DNI">DNI</option>
                                        <option value="CUIT">CUIT</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="documentNumber" class="form-label">Número de documento</label>
                                    <input type="text" class="form-control" id="documentNumber" required>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="province" class="form-label">Provincia/Estado/Región</label>
                                        <select class="form-select" id="province" required>
                                            <option value="">Selecciona una opción</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="city" class="form-label">Ciudad</label>
                                        <select class="form-select" id="city" required>
                                            <option value="">Selecciona una opción</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">Finalizar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del producto -->
            <div class="col-md-5 checkout-info-container">
                <h3 class="mb-3">Resumen de la compra</h3>
                <div class="p-3 d-flex align-items-center border rounded w-75">
                    <div class="order-summary-thumbnail">
                        <img src="https://sublitextil.com.ar/wp-content/uploads/2019/01/Remera-sublimar-hombre-.jpg" alt="" class="img-fluid">
                        <div class="item-quantity">2</div>
                    </div>
                    <h5 class="d-block mx-3">Curso de Edición de Videos con CapCut</h5>
                    <h4 class="text-success">ARS 22.990</h4>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        document.addEventListener("DOMContentLoaded", () => {
            const steps = document.querySelectorAll("#steps .li-step");
            const tabs = document.querySelectorAll(".tab-pane");
            const btnNext = document.querySelector(".btn-next");

            // Manejador de clics en los pasos (navegación por pestañas)
            steps.forEach((step) => {
                step.addEventListener("click", (event) => {
                    const clickedStep = event.target.closest(".li-step");
                    if (clickedStep) {
                        let targetStep = null;

                        // Activa la pestaña correspondiente
                        if(clickedStep.id === "step-client"){
                            targetStep = "step1";
                            document.getElementById('step-client').classList.add("grey-background")
                            document.getElementById('step-payment').classList.remove("grey-background")

                        }
                        else{
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

            // Manejador para botón "Continuar"
            btnNext.addEventListener("click", () => {
                setActiveTab("step2");
            });

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
        });



        // const btnNext = document.querySelector('.btn-next');
        // const btnPrev = document.querySelector('.btn-prev');
        const btnSubmit = document.getElementById('submit');
        const btnValidateCoupon = document.getElementById('validate-coupon-button');
        let coupon_id = null;

        btnValidateCoupon.addEventListener('click', () => {

            let coupon = document.getElementById('coupon').value;

            $.ajax({
                type: "GET",
                url: '{{route('validate-coupon')}}' + '?code=' + coupon,
                success: function (xhr, status, error) {
                    $('#coupon-validated-success').html(xhr.success);
                    $('#coupon-validated-failed').html("");
                    coupon_id = xhr.coupon_id;
                },
                error: function (xhr, status, error) {
                    $('#coupon-validated-success').html("");
                    $('#coupon-validated-failed').html(xhr.responseJSON.error);

                },
            });

        });

        // Recopilar datos y enviarlos
        btnSubmit.addEventListener('click', () => {
            const data = {
                name: document.getElementById('name').value,
                surname: document.getElementById('surname').value,
                dni: document.getElementById('dni').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                address: document.getElementById('address').value,
                zip_code: document.getElementById('zip_code').value,
                locality: document.getElementById('locality').value,
                province: document.getElementById('province').value,
                country: document.getElementById('country').value,
                coupon: coupon_id,
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

    </script>

@endsection

