<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu envío ha sido despachado!</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #ffffff;">
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="text-align: center;">
        <h1 style="margin: 0; font-family: 'Prata'; font-size: 5rem">Atica</h1>
        <p style="margin: 0; font-size: 14px; color: #777;">EST 2025</p>
        <h2 style="margin: 20px 0;">¡Hola {{$order->customer->name}}!</h2>
        <h3 style="margin: 10px 0;">Tu pedido <strong>{{$order->code}}</strong> ha sido despachado</h3>
    </div>

    <p>Buenas noticias! Tu pedido ha sido enviado y ya está en camino.</p>

    <p><strong>Medio de envío:</strong> {{$order->shipping_method}}</p>

    <p>Pronto recibirás un correo de Andreani con la información de seguimiento de tu paquete.</p>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('order-status', $order->code) }}" target="_blank"
           style="background-color: #000; color: #fff; text-decoration: none; padding: 10px 20px; display: inline-block;">VER ESTADO DE MI PEDIDO</a>
    </div>

    <div style="text-align: center;">
        <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/1200px-Instagram_icon.png" style="width: 30px; margin: 0 5px;" alt="Instagram"></a>
    </div>
</div>
</body>
</html>
