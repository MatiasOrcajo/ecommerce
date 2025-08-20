<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gracias por tu compra</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #ffffff;">
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="text-align: center;">
        <img
            src="https://atica.com.ar/LOGO_PNG.png"
            class="img-fluid" alt="Logo Ática" style="width: 250px; margin: 0 0 20px;">
        <h2 style="margin: 20px 0;">¡Hola {{$order->customer->name}}, gracias por tu compra!</h2>
        <h3 style="margin: 10px 0;">Orden {{$order->code}}</h3>
    </div>

    <p><strong>Medio de pago:</strong> {{$order->payment_method}} <span style="font-size: 12px; color: #555;"></span>
    </p>

    @if($order->payment_method == "Transferencia bancaria")
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;">

            <p style="margin: 0;">Banco Santander</p>
            <p style="margin: 0;">Tipo y número de cuenta: Cuentas en Pesos  000-199196/7</p>
            <p style="margin: 0;">Número de CBU: 0720000788000019919672</p>
            <p style="margin: 0;">Alias de CBU: DEBATE.OFERTA.PETALO</p>
            <p style="margin: 0;">Titular de la cuenta: Orcajo Matias</p>
            <p style="margin: 0;">Tipo y número de documento: DNI - 41564192</p>

            <b><p>Realizá la transferencia y mandanos el comprobante con código de orden por WhatsApp al <a href="https://wa.link/3v3e29" target="_blank">11 7118 1308</a>.</p></b>
        </div>

    @endif

    <p><strong>Medio de envío:</strong> {{$order->shipping_method}}</p>

    <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;">
        <p style="margin: 0;">{{$order->customer->name}} {{$order->customer->surname}} deberá recibirlo con su DNI en su domicilio. Caso contrario, retirarlo en la sucursal más cercana.</p>
        <p>Te vamos a avisar por email cuando ya esté despachado y te daremos el código de envío.</p>
    </div>

    <h3>Detalle de la orden</h3>
    <div style="border: 1px solid #ddd; padding: 10px;">
        @foreach($order->products as $orderProduct)
            <div style="display: flex; margin-bottom: 10px;">
                <img src="{{$orderProduct->productVariant->findFirstSimilarVariantWithPicture()}}" style="width: 60px; margin-right: 10px;">
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

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{route('order-success', $order->code)}}" target="_blank"
           style="background-color: #000; color: #fff; text-decoration: none; padding: 10px 20px; display: inline-block;">VER
            MI COMPRA</a>
    </div>

    <div style="text-align: center;">
        <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/1200px-Instagram_icon.png" style="width: 30px; margin: 0 5px;"></a>
    </div>
</div>
</body>
</html>
