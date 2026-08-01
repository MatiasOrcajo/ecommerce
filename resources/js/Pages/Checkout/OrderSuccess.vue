<script setup>
import { computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    order: Object,
})

const totalDiscount = computed(() => {
    let discount = 0
    if (props.order.coupon) discount += props.order.coupon.discount
    if (props.order.payment_method === 'Transferencia bancaria') discount += 10
    if (props.order.payment_method === 'Efectivo') discount += 10
    return discount
})
</script>

<template>
    <AppLayout :show-reviews="false" :show-footer="true" :title="'Orden ' + order.code + ' - Atica'" body-padding-top="120px">
        <div class="container mt-5 translate-y-mobile">
            <div class="card">
                <div class="card-header text-center text-white" style="background-color: #bc8d8a;">
                    <h3 class="card-title">Gracias por tu compra!</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5>Codigo de Orden: <span class="text-primary">{{ order.code }}</span></h5>
                    </div>
                    <div class="mb-3">
                        <h5>Datos del Cliente:</h5>
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Nombre:</strong> {{ order.customer.name + ' ' + order.customer.surname }}</li>
                            <li class="list-group-item"><strong>Email:</strong> {{ order.customer.email }}</li>
                            <li class="list-group-item"><strong>DNI:</strong> {{ order.customer.dni }}</li>
                            <li class="list-group-item"><strong>Direccion de envio:</strong> {{ order.shipping_address }}</li>
                            <li class="list-group-item"><strong>Medio de pago:</strong> {{ order.payment_method }}</li>
                            <li v-if="order.payment_method === 'Transferencia bancaria' || order.payment_method === 'Efectivo'" class="list-group-item">
                                <strong>Descuento por medio de pago:</strong> 10%
                            </li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h5>Detalle del Pedido:</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Color</th>
                                        <th>Talle</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="orderProduct in order.products" :key="orderProduct.id">
                                        <td>{{ orderProduct.product_variant.product.name }}</td>
                                        <td>
                                            <div
                                                :style="{
                                                    background: orderProduct.product_variant.color,
                                                    width: '32px',
                                                    height: '32px',
                                                    border: '1px solid #ccc',
                                                    borderRadius: '4px',
                                                }"
                                                :title="orderProduct.product_variant.color_name"
                                            ></div>{{ orderProduct.product_variant.color_name }}
                                        </td>
                                        <td>{{ orderProduct.product_variant.size }}</td>
                                        <td>{{ orderProduct.quantity }}</td>
                                        <td>${{ orderProduct.unit_price }}</td>
                                        <td>${{ orderProduct.total }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-end"><strong>Descuentos:</strong></td>
                                        <td><strong>{{ totalDiscount }}%</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-end"><strong>Costo de envio:</strong></td>
                                        <td><strong>${{ order.shipping_cost == 0 ? 'GRATIS' : order.shipping_cost }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-end"><strong>Total:</strong></td>
                                        <td><strong>${{ order.total_amount }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h5>Status de Envio:</h5>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Estado Actual:</strong>
                                <span v-if="order.status === 'Envio realizado'" class="badge bg-success">{{ order.status }}</span>
                                <span v-else class="badge bg-info">{{ order.status }}</span>
                            </li>
                            <li v-if="order.status === 'No pago' || order.status === 'Pago fallido' || order.status === 'Pago pendiente de aprobacion'" class="list-group-item">
                                <strong>Fecha de Vencimiento de Reserva:</strong> {{ order.expiration_date }}
                            </li>
                            <li class="list-group-item">
                                <strong>Metodo de envio:</strong>
                                <span>{{ order.shipping_method }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h5 class="font-bold mt-5" style="font-size: 18px">Importante: si no recibis ningun email con el detalle de tu compra, revisa la bandeja de correo no deseado, o bien envianos un mensaje por WhatsApp indicando tu codigo de orden.</h5>

                        <div v-if="order.payment_method === 'Transferencia bancaria'" class="mt-5">
                            <h5>Datos para Abonar por Transferencia Bancaria:</h5>
                            <div>
                                <p style="margin: 0;">Banco Santander</p>
                                <p style="margin: 0;">Tipo y numero de cuenta: Cuentas en Pesos  000-199196/7</p>
                                <p style="margin: 0;">Numero de CBU: 0720000788000019919672</p>
                                <p style="margin: 0;">Alias de CBU: ATICAOFICIAL</p>
                                <p style="margin: 0;">Titular de la cuenta: Orcajo Matias</p>
                                <p style="margin: 0;">Tipo y numero de documento: DNI - 41564192</p>
                                <b><p>Realiza la transferencia y mandanos el comprobante con codigo de orden por WhatsApp al <a href="https://wa.link/y0c4mg" target="_blank">11 5008-1382</a>.</p></b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <small class="text-muted">Gracias por tu compra.</small>
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
    .translate-y-mobile {
        transform: translateY(40px);
    }
}
</style>
