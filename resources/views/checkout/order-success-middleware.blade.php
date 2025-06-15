@extends('layouts.app')

@section('content')

    <div class="container mt-5">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-gray-400 text-white">
                        <h5 class="mb-0">Verificar email</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">Para proteger tus datos, por favor ingresá el email con el que realizaste el pedido:</p>
                        <form action="{{route('order.success.verify.email', $code)}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="email" class="form-control" id="email" name="email"
                                       placeholder="Ingrese su email" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Validar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
