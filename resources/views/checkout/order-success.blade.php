@extends('layouts.app')

@section('content')

    <div class="container mt-5">
        <div class="card">
            <div class="card-header text-center bg-success text-white">
                <h3 class="card-title">¡Orden Realizada con Éxito!</h3>
            </div>
            <div class="card-body">
                <!-- Información General de la Orden -->
                <div class="mb-3">
                    <h5>Código de Orden: <span class="text-primary">{{ $order->code }}</span></h5>
                </div>
                <div class="mb-3">
                    <h5>Datos del Pedido:</h5>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Costo Total:</strong> ${{ $order->total_amount }}</li>
                        <li class="list-group-item"><strong>Nombre del
                                Cliente:</strong> {{ $order->customer->name.' '.$order->customer->surname }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ $order->customer->email }}</li>
                        <li class="list-group-item"><strong>DNI:</strong> {{ $order->customer->dni }}</li>
                        <li class="list-group-item"><strong>Dirección de envío:</strong> {{ $order->shipping_address }}
                        </li>
                    </ul>
                </div>

                <!-- Detalle del Pedido -->
                <div class="mb-3">
                    <h5>Detalle del Pedido:</h5>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Color</th>
                            <th>Talle</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Descuento %</th>
                            <th>Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($order->products as $orderProduct)
                            <tr>
                                <td>{{ $orderProduct->productVariant->product->name }}</td>
                                <td><div
                                        style="
                                                background-color: {{ $orderProduct->productVariant->color }};
                                                width: 32px;
                                                height: 32px;
                                                border: 1px solid #ccc;
                                                border-radius: 4px;
                                            "
                                        title="{{ $orderProduct->productVariant->color_name }}"
                                    ></div>{{ $orderProduct->productVariant->color_name }}</td>
                                <td>{{ $orderProduct->productVariant->size }}</td>
                                <td>{{ $orderProduct->quantity }}</td>
                                <td>${{ $orderProduct->unit_price }}</td>
                                <td>{{ $orderProduct->discount }}</td>
                                <td>${{ $orderProduct->total }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        @if($order->coupon_id !== null)
                            <tr>
                                <td colspan="6" class="text-end"><strong>Descuento:</strong></td>
                                <td><strong>{{ $order->coupon->discount }}%</strong></td>
                            </tr>
                        @endif

                        <tr>
                            <td colspan="6" class="text-end"><strong>Total:</strong></td>
                            <td><strong>${{ $order->total_amount }}</strong></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Status de Envío -->
                <div class="mb-3">
                    <h5>Status de Envío:</h5>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Estado Actual:</strong>
                            @if ($order->status == 'Envío realizado')
                                <span class="badge bg-success">{{ $order->status }}</span>

                            @else
                                <span class="badge bg-info">{{ $order->status }}</span>

                            @endif
                        </li>

                        @if ($order->status == 'No pago' || $order->status == 'Pago fallido' ||  $order->status == 'Pago pendiente de aprobación' )
                            <li class="list-group-item"><strong>Fecha de Vencimiento de
                                    Reserva:</strong> {{ \Carbon\Carbon::parse($order->expiration_date)->format('d-m-Y') }}
                            </li>

                        @endif

                        <li class="list-group-item">
                            <strong>Número de Seguimiento:</strong>
                            <span>{{ $trackingNumber ?? 'No disponible' }}</span>
                        </li>

                    </ul>
                </div>

                <!-- Datos para Abonar por Transferencia Bancaria -->
                <div class="mb-3">
                    <h5>Datos para Abonar por Transferencia Bancaria:</h5>
                    <div>
                        <p><strong>Banco:</strong> {{ $bankName ?? 'Banco Ejemplo' }}</p>
                        <p><strong>CBU:</strong> {{ $bankCbu ?? '1234567890123456789012' }}</p>
                        <p><strong>Alias:</strong> {{ $bankAlias ?? 'ORDEN.PAGO.EJEMPLO' }}</p>
                        <p><strong>Titular:</strong> {{ $accountHolder ?? 'Juan Perez' }}</p>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <small class="text-muted">Gracias por tu compra.</small>
            </div>
        </div>
    </div>
@endsection
