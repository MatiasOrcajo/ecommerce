@extends('layouts.app')

@section('title')
    <title>Órden {{$order->code}} - Ática</title>
@endsection

@section('content')

    <style>

        @media (max-width: 991.98px) {

            body {
                padding-top: 8rem;
            }

            .translate-y-mobile{
                transform: translateY(40px);
            }

        }

    </style>

    <div class="container mt-5 translate-y-mobile">
        <div class="card">
            <div class="card-header text-center text-white" style="background-color: #bc8d8a;">
                <h3 class="card-title">¡Orden Realizada con Éxito!</h3>
            </div>
            <div class="card-body">
                <!-- Información General de la Orden -->
                <div class="mb-3">
                    <h5>Código de Orden: <span class="text-primary">{{ $order->code }}</span></h5>
                </div>
                <div class="mb-3">
                    <h5>Datos del Cliente:</h5>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Costo Total:</strong> ${{ $order->total_amount }}</li>
                        <li class="list-group-item"><strong>Nombre del
                                Cliente:</strong> {{ $order->customer->name.' '.$order->customer->surname }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ $order->customer->email }}</li>
                        <li class="list-group-item"><strong>DNI:</strong> {{ $order->customer->dni }}</li>
                        <li class="list-group-item"><strong>Dirección de envío:</strong> {{ $order->shipping_address }}
                        </li>
                        <li class="list-group-item"><strong>Medio de pago:</strong> {{ $order->payment_method }}</li>

                        @if($order->payment_method == "Transferencia bancaria" || $order->payment_method == "Efectivo")

                            <li class="list-group-item"><strong>Descuento por medio de pago:</strong> 10%
                            </li>

                        @endif
                    </ul>
                </div>

                <!-- Detalle del Pedido -->
                <div class="mb-3">
                    <h5>Detalle del Pedido:</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Color</th>
                                <th>Talle</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Descuento cupón</th>
                                <th>Subtotal</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($order->products as $orderProduct)
                                <tr>
                                    <td>{{ $orderProduct->productVariant->product->name }}</td>
                                    <td>
                                        <div
                                            style="
                                                background: {{ $orderProduct->productVariant->color }};
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
                            <strong>Método de envío:</strong>
                            <span>{{ $order->shipping_method }}</span>
                        </li>

                    </ul>
                </div>

                <!-- Datos para Abonar por Transferencia Bancaria -->
                <div class="mb-3">

                    <h5 class="font-bold mt-5" style="font-size: 18px">Importante: si no recibís ningún email con el detalle de tu compra, revisá la bandeja de correo no deseado, o bien envianos un mensaje por WhatsApp indicando tu código de orden.</h5>

                    @if($order->payment_method == "Transferencia bancaria")

                        <div class="mt-5">
                            <h5>Datos para Abonar por Transferencia Bancaria:</h5>
                            <div>
                                <p style="margin: 0;">Banco Santander</p>
                                <p style="margin: 0;">Tipo y número de cuenta: Cuentas en Pesos  000-199196/7</p>
                                <p style="margin: 0;">Número de CBU: 0720000788000019919672</p>
                                <p style="margin: 0;">Alias de CBU: DEBATE.OFERTA.PETALO</p>
                                <p style="margin: 0;">Titular de la cuenta: Orcajo Matias</p>
                                <p style="margin: 0;">Tipo y número de documento: DNI - 41564192</p>

                                <b><p>Realizá la transferencia y mandanos el comprobante con código de orden por WhatsApp al <a href="https://wa.link/y0c4mg" target="_blank">11 5008-1382</a>.</p></b>
                            </div>
                        </div>

                    @endif
                </div>
            </div>
            <div class="card-footer text-center">
                <small class="text-muted">Gracias por tu compra.</small>
            </div>
        </div>
    </div>
@endsection
