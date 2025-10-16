@extends('layouts.app')

@section('title')
    <title>Preguntas Frecuentes - Ática</title>
@endsection

@section('content')


    <style>
        @media (max-width: 991.98px) {

            body {
                padding-top: 8rem;
            }
        }
    </style>


    <div class="container py-5" id="top" >
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <header class="mb-4">
                    <h1 class="h2 mb-2">Preguntas Frecuentes</h1>
                    <p class="text-muted mb-0">Encontrá respuestas rápidas sobre envíos, cambios y tiempos de entrega.</p>
                </header>

                {{-- Índice (anclas) --}}
                <nav class="mb-4">
                    <div class="p-3 bg-light border rounded">
                        <strong class="d-block mb-2">En esta página</strong>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-1"><a href="#faq-1" class="link-dark text-decoration-none">¿Dónde puedo recibir mi pedido?</a></li>
                            <li class="mb-1"><a href="#faq-2" class="link-dark text-decoration-none">¿Cuál es el costo de envío?</a></li>
                            <li class="mb-1"><a href="#faq-3" class="link-dark text-decoration-none">¿Cómo se realizan los envíos?</a></li>
                            <li class="mb-1"><a href="#faq-4" class="link-dark text-decoration-none">¿Cómo puedo hacer el seguimiento de mi envío?</a></li>
                            <li class="mb-1"><a href="#faq-5" class="link-dark text-decoration-none">¿Cuánto tarda en llegar el pedido?</a></li>
                            <li class="mb-1"><a href="#faq-6" class="link-dark text-decoration-none">¿Cuál es el plazo para realizar un cambio?</a></li>
                            <li><a href="#faq-7" class="link-dark text-decoration-none">¿Qué hago si el producto no llega en buen estado?</a></li>
                        </ul>
                    </div>
                </nav>

                {{-- FAQ 1 --}}
                <section id="faq-1" class="mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="h5 mb-2">¿Dónde puedo recibir mi pedido?</h2>
                            <p class="mb-0">
                                Realizamos envíos a todo el territorio de la República Argentina, ya sea
                                <strong>a domicilio</strong> o a <strong>sucursal Andreani</strong>.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- FAQ 2 --}}
                <section id="faq-2" class="mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="h5 mb-2">¿Cuál es el costo de envío?</h2>
                            <p class="mb-0">
                                El costo de envío se calcula automáticamente en el <strong>checkout</strong>, según el total de tu compra y la ubicación de entrega.
                                Lo verás <strong>antes de confirmar</strong> tu pedido.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- FAQ 3 --}}
                <section id="faq-3" class="mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="h5 mb-3">¿Cómo se realizan los envíos?</h2>
                            <p class="mb-2">En Ática realizamos todos nuestros envíos a través de <strong>Andreani</strong>, para garantizar seguridad y seguimiento:</p>
                            <ul class="mb-0">
                                <li class="mb-1">
                                    <strong>A domicilio:</strong> el correo realiza 2 visitas. Si no logran entregarlo, el pedido queda en la sucursal más cercana durante 48&nbsp;horas.
                                    Pasado ese plazo, vuelve al remitente y deberás abonar un nuevo envío.
                                </li>
                                <li>
                                    <strong>A sucursal Andreani:</strong> el pedido permanece 5 días hábiles en la sucursal elegida.
                                    Si no lo retirás en ese tiempo, vuelve al remitente y deberás abonar nuevamente el costo del envío.
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>

                {{-- FAQ 4 --}}
                <section id="faq-4" class="mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="h5 mb-2">¿Cómo puedo hacer el seguimiento de mi envío?</h2>
                            <p class="mb-0">
                                Una vez despachado tu pedido, recibirás un mail con tu <strong>número de seguimiento</strong>.<br>
                                Podés rastrearlo en 👉
                                <a href="https://www.andreani.com" target="_blank" rel="noopener">www.andreani.com</a>.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- FAQ 5 --}}
                <section id="faq-5" class="mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="h5 mb-2">¿Cuánto tarda en llegar el pedido?</h2>
                            <p class="mb-1">
                                Comprando antes de las 12:00 del mediodía el despacho se realiza <strong>el mismo día</strong>.
                                Caso contrario se realizará <strong>al siguiente día hábil después de la compra</strong>.
                                El tiempo de entrega dependerá del método seleccionado y de tu ubicación.
                            </p>
                            <p class="text-muted mb-0">
                                👉 En fechas especiales como <em>Hot Sale</em> o <em>Cyber Monday</em>, los envíos pueden demorar un poco más.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- FAQ 6 --}}
                <section id="faq-6" class="mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="h5 mb-2">¿Cuál es el plazo para realizar un cambio?</h2>
                            <p class="mb-0">
                                Podés solicitar un cambio dentro de los <strong>10 días corridos</strong> desde que recibís tu pedido,
                                siempre que la prenda cumpla con las condiciones detalladas en nuestra
                                <a href="{{ url('/politicas') }}">Política de Cambios y Devoluciones</a>.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- FAQ 7 --}}
                <section id="faq-7" class="mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="h5 mb-2">¿Qué hago si el producto no llega en buen estado?</h2>
                            <p class="mb-0">
                                No te preocupes, estamos para ayudarte 💕. Si tu producto llega con una <strong>falla de fábrica</strong> o en condiciones distintas a las que compraste,
                                escribinos a <a href="mailto:soporte@aticashapewear.com">soporte@aticashapewear.com</a> para iniciar el reclamo y solucionarlo cuanto antes.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- CTA final --}}
                <div class="alert alert-light border mt-4" role="alert">
                    Queremos que disfrutes la experiencia de compra tanto como de usar nuestras prendas.
                    Si todavía tenés dudas, <a href="{{ url('/contacto') }}">escribinos</a> y con gusto te vamos a responder.
                </div>

                <div class="text-end mt-3" style="padding-bottom: 7rem">
                    <a href="#top" class="small text-decoration-none">↑ Volver arriba</a>
                </div>

            </div>
        </div>
    </div>
@endsection
