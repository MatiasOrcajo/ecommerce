<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva venta en Atica</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f9f9f9;">
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="text-align: center;">
        <img
            src="https://atica.com.ar/LOGO_PNG.png"
            class="img-fluid w-25" alt="Logo Ática">
        <p style="margin: 0; font-size: 14px; color: #777;">Notificación de nueva venta</p>
        <h2 style="margin: 20px 0;">Orden #{{$order->code}}</h2>
    </div>

    <p><strong>Cliente:</strong> {{$order->customer->name}} {{$order->customer->surname}}</p>
    <p><strong>Email:</strong> {{$order->customer->email}}</p>
    <p><strong>Teléfono:</strong> {{$order->customer->phone}}</p>

    <p><strong>Medio de pago:</strong> {{$order->payment_method}}</p>
    <p><strong>Medio de envío:</strong> {{$order->shipping_method}}</p>

    <p><strong>Dirección:</strong><br>
        {{$order->customer->address}}<br>
        {{$order->customer->postal_code}}, {{$order->customer->city}}, {{$order->customer->province}}
    </p>

    <h3>Detalle de la orden</h3>
    <div style="border: 1px solid #ddd; padding: 10px;">
        @foreach($order->products as $orderProduct)
            <div style="display: flex; margin-bottom: 10px;">
                <img src="{{$orderProduct->productVariant->pictures->first()->path}}" style="width: 60px; margin-right: 10px;">
                <div style="flex: 1;">
                    <p style="margin: 0;">{{$orderProduct->quantity}}x {{$orderProduct->productVariant->product->name}}</p>
                    <p style="margin: 0; font-size: 13px; color: #555;">
                    <div style="display: flex; align-items: center">
                        Color: {{$orderProduct->productVariant->color_name}} <span style="margin-left: 5px; display: block; width: 10px; height: 10px; background: {{$orderProduct->productVariant->color}}"></span>
                    </div>
                    Talle: {{$orderProduct->productVariant->size}}</p>
                </div>
                <p style="margin: 0;">${{$orderProduct->total}}</p>
            </div>
        @endforeach
        <hr>
        <p style="text-align: right; margin: 0;">Total: <strong>${{$order->total_amount}}</strong></p>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{route('order-success', $order->code)}}" target="_blank"
           style="background-color: #000; color: #fff; text-decoration: none; padding: 10px 20px; display: inline-block;">VER DETALLE COMPLETO</a>
    </div>
</div>
</body>
</html>
