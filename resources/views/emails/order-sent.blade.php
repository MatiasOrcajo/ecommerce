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
        <img
            src="https://atica.com.ar/LOGO_PNG.png"
            class="img-fluid w-25" alt="Logo Ática">
        <h2 style="margin: 20px 0;">¡Hola {{$order->customer->name}}!</h2>
        <h2 style="margin: 10px 0;">✨ Tu pedido <strong>{{$order->code}}</strong> ya está en camino ✨</h2>
    </div>

    <p>Nos encanta darte buenas noticias: ya preparamos tu pedido y lo despachamos con mucho cuidado.
        Ahora está viajando con Andreani rumbo a tu domicilio.</p>

    <p>Muy pronto vas a recibir un mail de Andreani con el seguimiento del envío, para que puedas ver por dónde está tu paquete en todo momento.</p>

    <h3>Y porque nos encanta mimarte…</h3>
    <p>Te regalamos un cupón con <b>{{$coupon->discount}}% de descuento</b> para que uses en tu próxima compra 💌</p>
    <p>Usá el código <h2><b>{{$coupon->code}}</b></h2> dentro de los próximos 30 días y disfrutá ese gustito extra.</p>
    <p>Gracias por elegir Ática 🤎</p>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('order-success', $order->code) }}" target="_blank"
           style="background-color: #000; color: #fff; text-decoration: none; padding: 10px 20px; display: inline-block;">VER ESTADO DE MI PEDIDO</a>
    </div>

    <div style="text-align: center;">
        <a href="https://www.instagram.com/aticaoficial"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/1200px-Instagram_icon.png" style="width: 30px; margin: 0 5px;" alt="Instagram"></a>
    </div>
</div>
</body>
</html>
