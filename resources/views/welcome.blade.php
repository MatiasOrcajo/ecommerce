@extends('layouts.app')

@section('content')

    <style>
        .step-container {
            display: none;
        }

        .step-container.active {
            display: block;
        }

        .form-step-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .btn-next, .btn-prev {
            width: 100px;
        }
    </style>


    <div class="position-relative container d-flex justify-content-center mt-5">

        <div class="mt-5 position-absolute" style="top: 0; right: 0;">
            <form id="form-validate-coupon" class="d-flex flex-column">
                @csrf
                <label for="coupon" class="form-label">Cupón de descuento</label>
                <input type="tel" class="form-control" id="coupon" placeholder="Ingresa tu cupón"
                       required>
                <p id="coupon-validated-success" style="color: green"></p>
                <p id="coupon-validated-failed" style="color: red"></p>
                <button type="button" id="validate-coupon-button" class="btn btn-primary mt-3">Validar</button>
            </form>
        </div>

        <div class="card shadow-sm col-md-6">
            <div class="card-header bg-primary text-white text-center">
                <h2>Formulario de Registro</h2>
            </div>
            <div class="card-body">
                <!-- Paso 1 -->
                <div id="step-1" class="step-container active">
                    <div class="form-step-header">
                        <h5>Paso 1: Información Personal</h5>
                        <p>Completa tus datos personales para continuar.</p>
                    </div>
                    <form id="form-step-1">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="name" placeholder="Ingresa tu nombre"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="surname" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="surname" placeholder="Ingresa tu apellido"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="dni" class="form-label">DNI/CUIT</label>
                            <input type="text" class="form-control" id="dni" placeholder="Ingresa tu DNI/CUIT"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" placeholder="Ingresa tu correo"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="phone" placeholder="Ingresa tu teléfono"
                                   required>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-primary btn-next">Siguiente</button>
                        </div>
                    </form>
                </div>
                <!-- Paso 2 -->
                <div id="step-2" class="step-container">
                    <div class="form-step-header">
                        <h5>Paso 2: Datos de Envío</h5>
                        <p>Completa tu dirección para finalizar.</p>
                    </div>
                    <form id="form-step-2">
                        <div class="mb-3">
                            <label for="address" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="address" placeholder="Ingresa tu dirección"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="zip_code" class="form-label">Código Postal</label>
                            <input type="text" class="form-control" id="zip_code"
                                   placeholder="Ingresa tu código postal" required>
                        </div>
                        <div class="mb-3">
                            <label for="locality" class="form-label">Localidad</label>
                            <input type="text" class="form-control" id="locality" placeholder="Ingresa tu localidad"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="province" class="form-label">Provincia</label>
                            <input type="text" class="form-control" id="province" placeholder="Ingresa tu provincia"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="country" class="form-label">País</label>
                            <input type="text" class="form-control" id="country" placeholder="Ingresa tu país" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary btn-prev">Atrás</button>
                            <button id="submit" type="button" class="btn btn-primary">Finalizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const step1 = document.getElementById('step-1');
        const step2 = document.getElementById('step-2');
        const btnNext = document.querySelector('.btn-next');
        const btnPrev = document.querySelector('.btn-prev');
        const btnSubmit = document.getElementById('submit');
        const btnValidateCoupon = document.getElementById('validate-coupon-button');
        let coupon_id = null;

        btnNext.addEventListener('click', () => {
            step1.classList.remove('active');
            step2.classList.add('active');
        });

        btnPrev.addEventListener('click', () => {
            step2.classList.remove('active');
            step1.classList.add('active');
        });


        btnValidateCoupon.addEventListener('click', () => {

            let coupon = document.getElementById('coupon').value;

            $.ajax({
                type: "GET",
                url: '{{route('validate-coupon')}}'+ '?code='+ coupon,
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

