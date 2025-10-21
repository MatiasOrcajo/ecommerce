@extends('layouts.app')

@section('title')
    <title>Preguntas Frecuentes - Ática</title>
@endsection

@section('content')

    <style>
        @media (max-width: 991.98px) {
            body { padding-top: 8rem; }
        }
        /* Mejora de legibilidad general */
        .faq-card p, .faq-card li {
            line-height: 1.55;
        }
        .quick-box li::marker {
            content: "• ";
        }
    </style>

    <div class="container py-5" id="top">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <header class="mb-4">
                    <h1 class="h2 mb-2">Preguntas Frecuentes</h1>
                </header>

                {{-- RESUMEN RÁPIDO --}}
                <section class="mb-4">
                    <div class="p-3 bg-light border rounded quick-box">
                        <strong class="d-block mb-2">Resumen rápido</strong>
                        <ul class="mb-0">
                            <li>Enviamos con <strong>Andreani</strong> a todo el país.</li>
                            <li>El costo aparece en el <strong>checkout</strong> antes de pagar.</li>
                            <li>Si comprás antes de <strong>12:00</strong>, despachamos <strong>hoy</strong>.</li>
                            <li>Rastreo con tu número en <strong>andreani.com</strong>.</li>
                            <li><strong>No</strong> hacemos cambios por talle en bodys y bragas.</li>
                        </ul>
                    </div>
                </section>

                {{-- Índice --}}
                <nav class="mb-4">
                    <div class="p-3 bg-light border rounded">
                        <strong class="d-block mb-2">En esta página</strong>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-1"><a href="#faq-1" class="link-dark text-decoration-none">¿Dónde recibo mi pedido?</a></li>
                            <li class="mb-1"><a href="#faq-2" class="link-dark text-decoration-none">¿Cuánto cuesta el envío?</a></li>
                            <li class="mb-1"><a href="#faq-3" class="link-dark text-decoration-none">¿Cómo enviamos?</a></li>
                            <li class="mb-1"><a href="#faq-4" class="link-dark text-decoration-none">¿Cómo sigo mi pedido?</a></li>
                            <li class="mb-1"><a href="#faq-5" class="link-dark text-decoration-none">¿Cuánto tarda?</a></li>
                            <li class="mb-1"><a href="#faq-6" class="link-dark text-decoration-none">Cambios y devoluciones</a></li>
                            <li><a href="#faq-7" class="link-dark text-decoration-none">Producto en mal estado</a></li>
                        </ul>
                    </div>
                </nav>

                {{-- FAQ 1 --}}
                <section id="faq-1" class="mb-4">
                    <div class="card border-0 shadow-sm faq-card">
                        <div class="card-body">
                            <h2 class="h5 mb-2">¿Dónde recibo mi pedido?</h2>
                            <p class="mb-0">
                                Enviamos a tu domicilio con <strong>Andreani</strong>.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- FAQ 2 --}}
                <section id="faq-2" class="mb-4">
                    <div class="card border-0 shadow-sm faq-card">
                        <div class="card-body">
                            <h2 class="h5 mb-2">¿Cuánto cuesta el envío?</h2>
                            <p class="mb-0">
                                El envío es <strong>GRATIS</strong> en todas tus compras.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- FAQ 3 --}}
                <section id="faq-3" class="mb-4">
                    <div class="card border-0 shadow-sm faq-card">
                        <div class="card-body">
                            <h2 class="h5 mb-3">¿Cómo enviamos?</h2>
                            <ul class="mb-0">
                                <li class="mb-1">
                                    <strong>A domicilio:</strong> Andreani hace <strong>2 visitas</strong>. Si no te encuentran, dejan el paquete en la sucursal más cercana por <strong>48&nbsp;hs</strong>. Después vuelve al remitente.
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>

                {{-- FAQ 4 --}}
                <section id="faq-4" class="mb-4">
                    <div class="card border-0 shadow-sm faq-card">
                        <div class="card-body">
                            <h2 class="h5 mb-2">¿Cómo sigo mi pedido?</h2>
                            <p class="mb-0">
                                Cuando despachamos, te enviamos un mail con tu <strong>número de seguimiento</strong>.
                                Entrá a <a href="https://www.andreani.com" target="_blank" rel="noopener">andreani.com</a> y pegalo ahí.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- FAQ 5 --}}
                <section id="faq-5" class="mb-4">
                    <div class="card border-0 shadow-sm faq-card">
                        <div class="card-body">
                            <h2 class="h5 mb-2">¿Cuánto tarda?</h2>
                            <ul class="mb-2">
                                <li class="mb-1"><strong>Compra antes de 12:00:</strong> despachamos <strong>hoy</strong>.</li>
                                <li class="mb-1"><strong>Después de 12:00:</strong> despachamos el <strong>día hábil siguiente</strong>.</li>
                                <li><strong>Entrega:</strong> según tu ubicación, suele ser entre <strong>2 y 7 días hábiles</strong>.</li>
                            </ul>
                            <p class="text-muted mb-0">
                                En eventos (p. ej. <em>Hot Sale</em>, <em>Cyber Monday</em>) puede demorar un poco más.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- FAQ 6 --}}
                <section id="faq-6" class="mb-4">
                    <div class="card border-0 shadow-sm faq-card">
                        <div class="card-body">
                            <h2 class="h5 mb-2">Cambios y devoluciones</h2>

                            <p class="mb-2">
                                <strong>No</strong> hacemos cambios ni devoluciones por <strong>talle</strong> en productos de ropa interior
                                (<strong>bodys</strong> y <strong>bragas</strong>).
                            </p>

                            <p class="mb-2"><strong>Si hubo un problema real, te ayudamos:</strong></p>
                            <ul class="mb-2">
                                <li class="mb-1"><strong>Demora fuerte:</strong> escribinos; buscamos solución.</li>
                                <li class="mb-1"><strong>Dirección mal cargada:</strong> si <em>no</em> se despachó, la corregimos sin costo; si ya salió, el reenvío va a cargo del cliente.</li>
                                <li class="mb-1"><strong>Paquete perdido/robado:</strong> lo verificamos con Andreani y te enviamos <strong>otro</strong> o un <strong>cupón</strong> por el mismo valor.</li>
                                <li><strong>Paquete devuelto al remitente:</strong> si vuelve por no retiro/entrega, <strong>no</strong> hay reembolso; podemos <strong>re-enviar</strong> abonando nuevamente el envío.</li>
                            </ul>

                            <p class="mb-0">
                                Tenés <strong>10 días corridos</strong> desde que recibís tu pedido para escribirnos si necesitás ayuda.
                                Leé los detalles en nuestra
                                <a href="{{ url('/politicas') }}">Política de Cambios y Devoluciones</a>.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- FAQ 7 --}}
                <section id="faq-7" class="mb-4">
                    <div class="card border-0 shadow-sm faq-card">
                        <div class="card-body">
                            <h2 class="h5 mb-2">Producto en mal estado</h2>
                            <p class="mb-0">
                                Si llega con <strong>falla de fábrica</strong> o distinto a lo que compraste, escribinos por WhatsApp con tu número de pedido y fotos. Lo resolvemos <strong>rápido</strong>.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- CTA final --}}
                <div class="alert alert-light border mt-4" role="alert">
                    Queremos que tu compra sea simple. Si algo no te quedó claro,
                    <a href="{{ url('/contacto') }}">escribinos</a> y te ayudamos.
                </div>

                <div class="text-end mt-3" style="padding-bottom: 7rem">
                    <a href="#top" class="small text-decoration-none">↑ Volver arriba</a>
                </div>

            </div>
        </div>
    </div>
@endsection
