@extends('layouts.app')

@section('title')
    <title>Políticas de devolución - Atica</title>
@endsection

@section('content')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <header class="mb-4">
                    <h1 class="h2 mb-3">Políticas de Devolución</h1>
                    <p class="lead">
                        En Ática creemos que sentirte bien con lo que elegís es tan importante como verte increíble
                        frente al espejo.
                        Por eso, diseñamos nuestras políticas para que tu experiencia sea <strong>transparente, simple y
                            sin complicaciones</strong>.
                    </p>
                </header>

                <!-- Cambios y Devoluciones -->
                <section class="card mb-4">
                    <div class="card-header bg-light">
                        <span class="me-2">📦</span><strong>Política de Cambios y Devoluciones</strong>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">
                            Podés devolver tu compra realizada en nuestra tienda online dentro de los
                            <mark class="px-1 rounded">10 (diez) días</mark>
                            desde la recepción del paquete y
                            te reembolsaremos el <strong>precio total</strong>.
                        </p>

                        <ul class="mb-3">
                            <li>
                                Si la devolución se realiza <strong>fuera de este período</strong>, o si el artículo fue
                                <strong>usado, dañado</strong> o <strong>no se envía en su embalaje original</strong>,
                                no podremos aceptar la devolución ni realizar el reembolso.
                            </li>
                            <li>Los <strong>gastos de envío por devolución</strong> o arrepentimiento de compra serán
                                <strong>descontados del reembolso</strong>.
                            </li>
                            <li>En caso de <strong>cambio por talle u otra razón</strong>, el envío corre por cuenta del
                                cliente.
                            </li>
                            <li>Si el producto tiene una <strong>falla</strong>, el costo de envío queda a cargo de
                                <strong>Ática</strong>.
                            </li>
                        </ul>

                        <div class="alert alert-warning">
                            <strong>Importante:</strong> al recibir tu pedido, <strong>revisá el estado del
                                producto</strong> antes de firmar el remito.
                        </div>

                        <p class="mb-2">
                            Los productos señalados como <strong>“sin cambio ni devolución”</strong> o los de la
                            <strong>sección Outlet</strong> solo podrán devolverse si hubo <strong>error en el
                                envío</strong>
                            (color, talle, artículo) o <strong>falla de fábrica</strong>.
                        </p>

                        <p class="mb-0">
                            Los cambios y devoluciones se aceptan <strong>únicamente dentro de la República
                                Argentina</strong>.
                        </p>
                    </div>
                </section>

                <!-- Motivos válidos -->
                <section class="card mb-4">
                    <div class="card-header bg-light"><strong>Motivos de cambio válidos</strong></div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li><strong>Producto defectuoso</strong>.</li>
                            <li><strong>Recibiste un artículo distinto</strong> al que pediste.</li>
                        </ul>
                    </div>
                </section>

                <!-- Cómo gestionar -->
                <section class="card mb-4">
                    <div class="card-header bg-light"><strong>Cómo gestionar un cambio</strong></div>
                    <div class="card-body">
                        <ol class="mb-3">
                            <li><strong>Contactanos</strong> por mail o WhatsApp para iniciar el trámite.</li>
                            <li>Una vez autorizado, <strong>coordinaremos el retiro</strong> del producto en la
                                dirección que nos indiques.
                            </li>
                            <li>
                                Prepará el artículo en su <strong>embalaje original con etiquetas</strong>, dentro de
                                una bolsa plástica,
                                y pegá la <strong>etiqueta de devolución</strong> (o escribila a mano si no podés
                                imprimirla).
                            </li>
                            <li>
                                Tené a mano: <strong>número de pedido</strong>, fecha, <strong>dirección de
                                    retiro</strong> y un
                                <strong>teléfono de contacto</strong>.
                            </li>
                        </ol>
                    </div>
                </section>

                <!-- Calidad -->
                <section class="card mb-4">
                    <div class="card-header bg-light"><strong>Productos defectuosos o dañados</strong></div>
                    <div class="card-body">
                        <p>
                            Todos los artículos devueltos son evaluados por nuestro <strong>Departamento de Control de
                                Calidad</strong>.
                        </p>
                        <ul>
                            <li>
                                Si el daño es de <strong>fábrica</strong> o no cumple con los estándares de calidad,
                                se hará el <strong>reembolso</strong>.
                            </li>
                            <li>
                                Si el problema fue por <strong>mal uso</strong>, <strong>desgaste normal</strong> o
                                factores externos
                                (calor, químicos, objetos cortantes, etc.), <strong>no</strong> se realizará el cambio
                                ni devolución.
                            </li>
                        </ul>
                        <div class="alert alert-info mb-0">
                            El reintegro se efectiviza en un plazo máximo de <strong>7 días hábiles</strong>, a través
                            del
                            <strong>mismo medio de pago</strong> utilizado en la compra.
                        </div>
                    </div>
                </section>

                <!-- Cierre -->
                <section class="mt-4" style="padding-bottom: 7rem">
                    <p class="mb-2">
                        Esperamos que disfrutes tanto el proceso de compra como el momento de usar nuestras prendas.
                        Si algo no sale como esperabas, estamos para ayudarte con <strong>claridad</strong>,
                        <strong>responsabilidad</strong> y <strong>mucho amor</strong> por lo que hacemos.
                    </p>
                    <p class="fw-semibold">
                        Porque tu comodidad, tu estilo y tu confianza son nuestra prioridad 💕
                    </p>
                </section>

            </div>
        </div>
    </div>

@endsection

