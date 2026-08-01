<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
</script>

<template>
    <AppLayout :show-reviews="false" :show-footer="true" title="Preguntas Frecuentes - Atica">
        <div class="container py-5" id="top">
            <div class="row justify-content-center">
                <div class="col-lg-9">

                    <header class="mb-4">
                        <h1 class="h2 mb-2">Preguntas Frecuentes</h1>
                    </header>

                    <section class="mb-4">
                        <div class="p-3 bg-light border rounded quick-box">
                            <strong class="d-block mb-2">Resumen rapido</strong>
                            <ul class="mb-0">
                                <li>Enviamos con <strong>Correo Argentino</strong> a todo el pais.</li>
                                <li>El costo aparece en el <strong>checkout</strong> antes de pagar.</li>
                                <li>Si compras antes de <strong>12:00</strong>, despachamos <strong>hoy</strong>.</li>
                                <li>Rastreo con tu numero en <strong>correoargentino.com.ar</strong>.</li>
                                <li><strong>No</strong> hacemos cambios por talle en bodys y bragas.</li>
                            </ul>
                        </div>
                    </section>

                    <nav class="mb-4">
                        <div class="p-3 bg-light border rounded">
                            <strong class="d-block mb-2">En esta pagina</strong>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-1"><a href="#faq-1" class="link-dark text-decoration-none">Donde recibo mi pedido?</a></li>
                                <li class="mb-1"><a href="#faq-2" class="link-dark text-decoration-none">Cuanto cuesta el envio?</a></li>
                                <li class="mb-1"><a href="#faq-3" class="link-dark text-decoration-none">Como enviamos?</a></li>
                                <li class="mb-1"><a href="#faq-4" class="link-dark text-decoration-none">Como sigo mi pedido?</a></li>
                                <li class="mb-1"><a href="#faq-5" class="link-dark text-decoration-none">Cuanto tarda?</a></li>
                                <li class="mb-1"><a href="#faq-6" class="link-dark text-decoration-none">Cambios y devoluciones</a></li>
                                <li><a href="#faq-7" class="link-dark text-decoration-none">Producto en mal estado</a></li>
                            </ul>
                        </div>
                    </nav>

                    <section id="faq-1" class="mb-4">
                        <div class="card border-0 shadow-sm faq-card">
                            <div class="card-body">
                                <h2 class="h5 mb-2">Donde recibo mi pedido?</h2>
                                <p class="mb-0">
                                    Enviamos a tu domicilio con <strong>Correo Argentino</strong>.
                                </p>
                            </div>
                        </div>
                    </section>

                    <section id="faq-2" class="mb-4">
                        <div class="card border-0 shadow-sm faq-card">
                            <div class="card-body">
                                <h2 class="h5 mb-2">Cuanto cuesta el envio?</h2>
                                <p class="mb-0">
                                    El envio es <strong>GRATIS</strong> en todas tus compras.
                                </p>
                            </div>
                        </div>
                    </section>

                    <section id="faq-3" class="mb-4">
                        <div class="card border-0 shadow-sm faq-card">
                            <div class="card-body">
                                <h2 class="h5 mb-3">Como enviamos?</h2>
                                <ul class="mb-0">
                                    <li class="mb-1">
                                        <strong>A domicilio:</strong> Correo Argentino hace <strong>2 visitas</strong>. Si no te encuentran, dejan el paquete en la sucursal mas cercana por <strong>48 hs</strong>. Despues vuelve al remitente.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <section id="faq-4" class="mb-4">
                        <div class="card border-0 shadow-sm faq-card">
                            <div class="card-body">
                                <h2 class="h5 mb-2">Como sigo mi pedido?</h2>
                                <p class="mb-0">
                                    Cuando despachamos, te enviamos un mail con tu <strong>numero de seguimiento</strong>.
                                    Entra a <a href="https://www.correoargentino.com.ar" target="_blank" rel="noopener">correoargentino.com.ar</a> y pegalo ahi.
                                </p>
                            </div>
                        </div>
                    </section>

                    <section id="faq-5" class="mb-4">
                        <div class="card border-0 shadow-sm faq-card">
                            <div class="card-body">
                                <h2 class="h5 mb-2">Cuanto tarda?</h2>
                                <ul class="mb-2">
                                    <li class="mb-1"><strong>Compra antes de 12:00:</strong> despachamos <strong>hoy</strong>.</li>
                                    <li class="mb-1"><strong>Despues de 12:00:</strong> despachamos el <strong>dia habil siguiente</strong>.</li>
                                    <li><strong>Entrega:</strong> segun tu ubicacion, suele ser entre <strong>2 y 7 dias habiles</strong>.</li>
                                </ul>
                                <p class="text-muted mb-0">
                                    En eventos (p. ej. <em>Hot Sale</em>, <em>Cyber Monday</em>) puede demorar un poco mas.
                                </p>
                            </div>
                        </div>
                    </section>

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
                                    <li class="mb-1"><strong>Demora fuerte:</strong> escribinos; buscamos solucion.</li>
                                    <li class="mb-1"><strong>Direccion mal cargada:</strong> si <em>no</em> se despacho, la corregimos sin costo; si ya salio, el reenvio va a cargo del cliente.</li>
                                    <li class="mb-1"><strong>Paquete perdido/robado:</strong> lo verificamos con Correo Argentino y te enviamos <strong>otro</strong> o un <strong>cupon</strong> por el mismo valor.</li>
                                    <li><strong>Paquete devuelto al remitente:</strong> si vuelve por no retiro/entrega, <strong>no</strong> hay reembolso; podemos <strong>re-enviar</strong> abonando nuevamente el envio.</li>
                                </ul>

                                <p class="mb-0">
                                    Tenes <strong>10 dias corridos</strong> desde que recibis tu pedido para escribirnos si necesitas ayuda.
                                    Lee los detalles en nuestra
                                    <Link href="/politicas">Politica de Cambios y Devoluciones</Link>.
                                </p>
                            </div>
                        </div>
                    </section>

                    <section id="faq-7" class="mb-4">
                        <div class="card border-0 shadow-sm faq-card">
                            <div class="card-body">
                                <h2 class="h5 mb-2">Producto en mal estado</h2>
                                <p class="mb-0">
                                    Si llega con <strong>falla de fabrica</strong> o distinto a lo que compraste, escribinos por WhatsApp con tu numero de pedido y fotos. Lo resolvemos <strong>rapido</strong>.
                                </p>
                            </div>
                        </div>
                    </section>

                    <div class="alert alert-light border mt-4" role="alert">
                        Queremos que tu compra sea simple. Si algo no te quedo claro,
                        <Link href="/contacto">escribinos</Link> y te ayudamos.
                    </div>

                    <div class="text-end mt-3" style="padding-bottom: 7rem">
                        <a href="#top" class="small text-decoration-none">Volver arriba</a>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
    @media (max-width: 991.98px) {
        body { padding-top: 8rem; }
    }
    .faq-card p, .faq-card li {
        line-height: 1.55;
    }
    .quick-box li::marker {
        content: "\2022 ";
    }
</style>
