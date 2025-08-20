<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu pedido está en preparación</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #ffffff;">
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="text-align: center;">
        <img
            src="https://atica.com.ar/LOGO_PNG.png"
            class="img-fluid" alt="Logo Ática" style="width: 250px; margin: 0 0 20px;">
        <h2 style="margin: 20px 0;">¡Hola {{$order->customer->name}}!</h2>
        <h3 style="margin: 10px 0;">Tu pedido <strong>{{$order->code}}</strong> está en preparación</h3>
    </div>

    <p>Estamos preparando cuidadosamente tu pedido. En breve te notificaremos cuando comience el envío.</p>

    <p><strong>Medio de pago:</strong> {{$order->payment_method}}</p>

    <p><strong>Medio de envío:</strong> {{$order->shipping_method}}</p>

    <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;">
        <p style="margin: 0;">{{$order->customer->name}} {{$order->customer->surname}} recibirá el pedido con su DNI en el domicilio indicado. Si no estás, podrás retirarlo en la sucursal correspondiente.</p>
        <p>Te enviaremos otro correo cuando el pedido haya sido despachado, con el número de seguimiento.</p>
    </div>

    <h3>Detalle de tu pedido</h3>
    <div style="border: 1px solid #ddd; padding: 10px;">
        @foreach($order->products as $orderProduct)
            <div style="display: flex; margin-bottom: 10px;">
                <img src="{{$orderProduct->productVariant->findFirstSimilarVariantWithPicture()}}" style="width: 60px; margin-right: 10px;" alt="Imagen producto">
                <div style="flex: 1;">
                    <p style="margin: 0;">{{$orderProduct->quantity}}× {{$orderProduct->productVariant->product->name}}</p>
                    <p style="margin: 0; font-size: 13px; color: #555; display: flex; align-items: center;">
                        Color: {{$orderProduct->productVariant->color_name}} <span style="display: inline-block; width: 10px; height: 10px; background: {{$orderProduct->productVariant->color}}; margin-left: 5px;"></span>
                        &nbsp;|&nbsp;Talle: {{$orderProduct->productVariant->size}}
                    </p>
                </div>
                <p style="margin: 0; align-self: center;">${{$orderProduct->total}}</p>
            </div>
        @endforeach
        <hr>
        <p style="text-align: right; margin: 0;">Total: <strong>${{$order->total_amount}}</strong></p>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('order-success', $order->code) }}" target="_blank"
           style="background-color: #000; color: #fff; text-decoration: none; padding: 10px 20px; display: inline-block;">VER ESTADO DE MI PEDIDO</a>
    </div>

    <div style="text-align: center;">
        <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/1200px-Instagram_icon.png" style="width: 30px; margin: 0 5px;" alt="Instagram"></a>
    </div>
</div>
</body>
</html>
