@extends('layouts.app')

@section('content')
    <style>
        /* Estilos adicionales para personalizar algunos elementos */
        .container-custom {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            display: flex;
            align-items: center;
        }

        .progress-bar {
            width: 100%;
            height: 1rem;
        }
    </style>

    <!-- Formulario estilizado con Bootstrap 5 -->
    <form id="form-checkout" class="container my-4 p-4 border rounded shadow-sm">
        <!-- Número de tarjeta -->
        <div class="mb-3">
            <label for="form-checkout__cardNumber" class="form-label">Número de tarjeta</label>
            <div id="form-checkout__cardNumber" class="container-custom"></div>
        </div>

        <!-- Fecha de expiración -->
        <div class="mb-3">
            <label for="form-checkout__expirationDate" class="form-label">Fecha de expiración</label>
            <div id="form-checkout__expirationDate" class="container-custom"></div>
        </div>

        <!-- Código de seguridad -->
        <div class="mb-3">
            <label for="form-checkout__securityCode" class="form-label">Código de seguridad</label>
            <div id="form-checkout__securityCode" class="container-custom"></div>
        </div>

        <!-- Nombre del titular -->
        <div class="mb-3">
            <label for="form-checkout__cardholderName" class="form-label">Nombre del titular</label>
            <input type="text" id="form-checkout__cardholderName" class="form-control" placeholder="Nombre en la tarjeta" />
        </div>

        <!-- Emisor de la tarjeta -->
        <div class="mb-3">
            <label for="form-checkout__issuer" class="form-label">Emisor</label>
            <select id="form-checkout__issuer" class="form-select">
                <option value="">Seleccione el emisor</option>
                <!-- Opciones dinámicas aquí -->
            </select>
        </div>

        <!-- Cuotas -->
        <div class="mb-3">
            <label for="form-checkout__installments" class="form-label">Cuotas</label>
            <select id="form-checkout__installments" class="form-select">
                <option value="">Seleccione el número de cuotas</option>
                <!-- Opciones dinámicas aquí -->
            </select>
        </div>

        <!-- Tipo de identificación -->
        <div class="mb-3">
            <label for="form-checkout__identificationType" class="form-label">Tipo de identificación</label>
            <select id="form-checkout__identificationType" class="form-select">
                <option value="">Seleccione el tipo de identificación</option>
                <!-- Opciones dinámicas aquí -->
            </select>
        </div>

        <!-- Número de identificación -->
        <div class="mb-3">
            <label for="form-checkout__identificationNumber" class="form-label">Número de identificación</label>
            <input type="text" id="form-checkout__identificationNumber" class="form-control" placeholder="Número de identificación" />
        </div>

        <!-- Correo electrónico -->
        <div class="mb-3">
            <label for="form-checkout__cardholderEmail" class="form-label">Correo electrónico</label>
            <input type="email" id="form-checkout__cardholderEmail" class="form-control" placeholder="correo@ejemplo.com" />
        </div>

        <!-- Botón de pago y barra de progreso -->
        <div class="d-grid mb-3">
            <button type="submit" id="form-checkout__submit" class="btn btn-primary">Pagar</button>
        </div>
        <progress value="0" class="progress-bar mb-3">Cargando...</progress>
    </form>


@endsection

