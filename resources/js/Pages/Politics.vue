<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
</script>

<template>
    <AppLayout :show-reviews="false" :show-footer="true" title="Politicas de devolucion - Atica">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-9">

                    <header class="mb-4">
                        <h1 class="h2 mb-3">Politicas de Devolucion</h1>
                        <p class="lead">
                            En Atica creemos que sentirte bien con lo que elegis es tan importante como verte increible
                            frente al espejo.
                            Por eso, disenamos nuestras politicas para que tu experiencia sea <strong>transparente, simple y
                                sin complicaciones</strong>.
                        </p>
                    </header>

                    <section class="card mb-4">
                        <div class="card-header bg-light">
                            <span class="me-2">&#x1F4E6;</span><strong>Politica de Cambios y Devoluciones</strong>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">
                                Podes devolver tu compra realizada en nuestra tienda online dentro de los
                                <mark class="px-1 rounded">10 (diez) dias</mark>
                                desde la recepcion del paquete y
                                te reembolsaremos el <strong>precio total</strong>.
                            </p>

                            <ul class="mb-3">
                                <li>
                                    Si la devolucion se realiza <strong>fuera de este periodo</strong>, o si el articulo fue
                                    <strong>usado, danado</strong> o <strong>no se envia en su embalaje original</strong>,
                                    no podremos aceptar la devolucion ni realizar el reembolso.
                                </li>
                                <li>Los <strong>gastos de envio por devolucion</strong> o arrepentimiento de compra seran
                                    <strong>descontados del reembolso</strong>.
                                </li>
                                <li>En caso de <strong>cambio por talle u otra razon</strong>, el envio corre por cuenta del
                                    cliente.
                                </li>
                                <li>Si el producto tiene una <strong>falla</strong>, el costo de envio queda a cargo de
                                    <strong>Atica</strong>.
                                </li>
                            </ul>

                            <div class="alert alert-warning">
                                <strong>Importante:</strong> al recibir tu pedido, <strong>revisa el estado del
                                    producto</strong> antes de firmar el remito.
                            </div>

                            <p class="mb-2">
                                Los productos senalados como <strong>"sin cambio ni devolucion"</strong> o los de la
                                <strong>seccion Outlet</strong> solo podran devolverse si hubo <strong>error en el
                                    envio</strong>
                                (color, talle, articulo) o <strong>falla de fabrica</strong>.
                            </p>

                            <p class="mb-0">
                                Los cambios y devoluciones se aceptan <strong>unicamente dentro de la Republica
                                    Argentina</strong>.
                            </p>
                        </div>
                    </section>

                    <section class="card mb-4">
                        <div class="card-header bg-light"><strong>Motivos de cambio validos</strong></div>
                        <div class="card-body">
                            <ul class="mb-0">
                                <li><strong>Producto defectuoso</strong>.</li>
                                <li><strong>Recibiste un articulo distinto</strong> al que pediste.</li>
                            </ul>
                        </div>
                    </section>

                    <section class="card mb-4">
                        <div class="card-header bg-light"><strong>Como gestionar un cambio</strong></div>
                        <div class="card-body">
                            <ol class="mb-3">
                                <li><strong>Contactanos</strong> por mail o WhatsApp para iniciar el tramite.</li>
                                <li>Una vez autorizado, <strong>coordinaremos el retiro</strong> del producto en la
                                    direccion que nos indiques.
                                </li>
                                <li>
                                    Prepara el articulo en su <strong>embalaje original con etiquetas</strong>, dentro de
                                    una bolsa plastica,
                                    y pega la <strong>etiqueta de devolucion</strong> (o escribila a mano si no podes
                                    imprimirla).
                                </li>
                                <li>
                                    Tene a mano: <strong>numero de pedido</strong>, fecha, <strong>direccion de
                                        retiro</strong> y un
                                    <strong>telefono de contacto</strong>.
                                </li>
                            </ol>
                        </div>
                    </section>

                    <section class="card mb-4">
                        <div class="card-header bg-light"><strong>Productos defectuosos o danados</strong></div>
                        <div class="card-body">
                            <p>
                                Todos los articulos devueltos son evaluados por nuestro <strong>Departamento de Control de
                                    Calidad</strong>.
                            </p>
                            <ul>
                                <li>
                                    Si el dano es de <strong>fabrica</strong> o no cumple con los estandares de calidad,
                                    se hara el <strong>reembolso</strong>.
                                </li>
                                <li>
                                    Si el problema fue por <strong>mal uso</strong>, <strong>desgaste normal</strong> o
                                    factores externos
                                    (calor, quimicos, objetos cortantes, etc.), <strong>no</strong> se realizara el cambio
                                    ni devolucion.
                                </li>
                            </ul>
                            <div class="alert alert-info mb-0">
                                El reintegro se efectiviza en un plazo maximo de <strong>7 dias habiles</strong>, a traves
                                del
                                <strong>mismo medio de pago</strong> utilizado en la compra.
                            </div>
                        </div>
                    </section>

                    <section class="mt-4" style="padding-bottom: 7rem">
                        <p class="mb-2">
                            Esperamos que disfrutes tanto el proceso de compra como el momento de usar nuestras prendas.
                            Si algo no sale como esperabas, estamos para ayudarte con <strong>claridad</strong>,
                            <strong>responsabilidad</strong> y <strong>mucho amor</strong> por lo que hacemos.
                        </p>
                        <p class="fw-semibold">
                            Porque tu comodidad, tu estilo y tu confianza son nuestra prioridad &#x1F495;
                        </p>
                    </section>

                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
    @media (max-width: 991.98px) {
        body {
            padding-top: 8rem;
        }
    }
</style>
