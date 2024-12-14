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


    <div class="container d-flex justify-content-center mt-5">
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
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" placeholder="Ingresa tu nombre"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" placeholder="Ingresa tu apellido"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" placeholder="Ingresa tu correo"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" placeholder="Ingresa tu teléfono"
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
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" placeholder="Ingresa tu dirección"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="codigoPostal" class="form-label">Código Postal</label>
                            <input type="text" class="form-control" id="codigoPostal"
                                   placeholder="Ingresa tu código postal" required>
                        </div>
                        <div class="mb-3">
                            <label for="localidad" class="form-label">Localidad</label>
                            <input type="text" class="form-control" id="localidad" placeholder="Ingresa tu localidad"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="provincia" class="form-label">Provincia</label>
                            <input type="text" class="form-control" id="provincia" placeholder="Ingresa tu provincia"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="pais" class="form-label">País</label>
                            <input type="text" class="form-control" id="pais" placeholder="Ingresa tu país" required>
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

        btnNext.addEventListener('click', () => {
            step1.classList.remove('active');
            step2.classList.add('active');
        });

        btnPrev.addEventListener('click', () => {
            step2.classList.remove('active');
            step1.classList.add('active');
        });

        // Recopilar datos y enviarlos
        btnSubmit.addEventListener('click', () => {
            const data = {
                nombre: document.getElementById('nombre').value,
                apellido: document.getElementById('apellido').value,
                email: document.getElementById('email').value,
                telefono: document.getElementById('telefono').value,
                direccion: document.getElementById('direccion').value,
                codigoPostal: document.getElementById('codigoPostal').value,
                localidad: document.getElementById('localidad').value,
                provincia: document.getElementById('provincia').value,
                pais: document.getElementById('pais').value,
            };


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $.ajax({
                type: "POST",
                url: '{{route('pagar')}}',
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

