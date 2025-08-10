<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Bienvenido a la familia Ática!</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #ffffff;">
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <!-- Header -->
    <div style="text-align: center;">
        <h1 style="margin: 0; font-family: 'Prata'; font-size: 5rem;">Ática</h1>
        <p style="margin: 0; font-size: 14px; color: #777;">EST 2025</p>
    </div>

    <!-- Greeting -->
    <div style="margin: 30px 0 20px;">
        <h2 style="margin: 0;">¡Hola {{$customer->name}}!</h2>
        <p style="font-size: 16px; color: #555;">
            Gracias por suscribirte a nuestro newsletter. ¡Nos alegra que formes parte de la comunidad Ática!
        </p>
    </div>

    <!-- Coupon Offer -->
    <div style="background-color: #f9f9f9; padding: 20px; border-radius: 8px; text-align: center;">
        <h3 style="margin: 0 0 10px;">Como agradecimiento...</h3>
        <p style="margin: 0 0 15px; font-size: 16px; color: #555;">
            Te regalamos un <strong>{{$coupon->discount}}% de descuento</strong> en tu próxima compra.
        </p>
        <p style="margin: 0 20px; font-size: 18px;">
            Usa el código:
        </p>
        <h2 style="margin: 10px 0; font-size: 2rem; letter-spacing: 1px;">
            <strong>{{$coupon->code}}</strong>
        </h2>
        <p style="margin: 0; font-size: 14px; color: #777;">
            Válido dentro de los próximos 30 días.
        </p>
    </div>

    <!-- Call to Action -->
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('shop') }}" target="_blank"
           style="background-color: #000; color: #fff; text-decoration: none; padding: 12px 24px; display: inline-block; border-radius: 4px; font-size: 16px;">
            EMPEZAR A COMPRAR
        </a>
    </div>

    <!-- Footer -->
    <div style="text-align: center; font-size: 14px; color: #777; line-height: 1.5;">
        <p style="margin: 0 0 10px;">Síguenos en redes sociales:</p>
        <a href="https://www.instagram.com/aticaoficial" style="margin: 0 5px; text-decoration: none;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/1200px-Instagram_icon.png"
                 alt="Instagram" style="width: 30px; vertical-align: middle;">
        </a>
        <a href="https://www.facebook.com/aticaoficial" style="margin: 0 5px; text-decoration: none;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg"
                 alt="Facebook" style="width: 30px; vertical-align: middle;">
        </a>
        <p style="margin: 20px 0 0;">
            © 2025 Ática. Todos los derechos reservados.
        </p>
    </div>
</div>
</body>
</html>
