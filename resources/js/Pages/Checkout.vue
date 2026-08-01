<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import axios from 'axios'

const currentStep = ref('step1')
const selectedShipmentMethod = ref(null)
const selectedPaymentMethod = ref(null)
const cartProducts = ref([])
const cartTotal = ref(0)
const cartFinalTotal = ref(0)
const shippingCost = ref(0)
const couponId = ref(null)
const couponDiscount = ref(0)
const couponApplied = ref(false)
const summaryOpen = ref(false)

const formData = ref({
    firstName: '', lastName: '', phone: '', documentNumber: '', email: '',
    province: '', locality: '', street: '', number: '', apartment: '',
    zip_code: '', observations: '', coupon_id: null
})

const shippingOptions = ref({
    'correo-argentino': { name: 'Correo Argentino', desc: 'De 1 a 5 días hábiles' },
    'take-away': { name: 'Retirar en CABA', desc: 'Bernardo de Irigoyen 630 - Monserrat' },
    'FLEX': { name: '', desc: '' }
})

const AVAILABLE_FOR_ARRIVES_TODAY = [
    'Almirante Brown', 'Avellaneda', 'Berazategui', 'Berisso', 'Campana', 'Cañuelas',
    'Ciudad Autónoma de Buenos Aires', 'Ensenada', 'Escobar', 'Esteban Echeverría',
    'Ezeiza', 'Florencio Varela', 'General Las Heras', 'General Rodríguez',
    'General San Martín', 'Hurlingham', 'Ituzaingó', 'José C. Paz', 'La Matanza',
    'La Plata', 'Lanús', 'Lomas de Zamora', 'Luján', 'Malvinas Argentinas',
    'Marcos Paz', 'Merlo', 'Moreno', 'Morón', 'Pilar', 'Presidente Perón', 'Quilmes',
    'San Fernando', 'San Isidro', 'San Miguel', 'San Vicente', 'Tigre', 'Tres de Febrero',
    'Vicente López', 'Zárate'
]

const BARRIOS_CABA = [
    "Agronomía", "Almagro", "Balvanera", "Barracas", "Belgrano", "Boedo", "Caballito",
    "Chacarita", "Coghlan", "Colegiales", "Constitución", "Flores", "Floresta", "La Boca",
    "La Paternal", "Liniers", "Mataderos", "Monte Castro", "Monserrat", "Nueva Pompeya",
    "Núñez", "Palermo", "Parque Avellaneda", "Parque Chacabuco", "Parque Chas",
    "Parque Patricios", "Puerto Madero", "Recoleta", "Retiro", "Saavedra", "San Cristóbal",
    "San Nicolás", "San Telmo", "Vélez Sársfield", "Versalles", "Villa Crespo",
    "Villa del Parque", "Villa Devoto", "Villa General Mitre", "Villa Lugano", "Villa Luro",
    "Villa Ortúzar", "Villa Pueyrredón", "Villa Real", "Villa Riachuelo", "Villa Santa Rita",
    "Villa Soldati", "Villa Urquiza"
]

function formatMoneyAR(value) {
    const nf = new Intl.NumberFormat('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
    return `$${nf.format(Number(value) || 0)}`
}

function getNowInBuenosAires() {
    try {
        const parts = new Intl.DateTimeFormat('en-CA', {
            timeZone: 'America/Argentina/Buenos_Aires', hour12: false,
            year: 'numeric', month: '2-digit', day: '2-digit',
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        }).formatToParts(new Date()).reduce((acc, p) => {
            if (p.type !== 'literal') acc[p.type] = parseInt(p.value, 10)
            return acc
        }, {})
        const baLikeUTC = new Date(Date.UTC(parts.year, parts.month - 1, parts.day, parts.hour, parts.minute, parts.second))
        return { year: parts.year, month: parts.month, day: parts.day, hour: parts.hour, minute: parts.minute, second: parts.second, weekday: baLikeUTC.getUTCDay(), date: baLikeUTC }
    } catch (_) {
        const now = new Date()
        const baMs = now.getTime() + now.getTimezoneOffset() * 60000 - (3 * 3600000)
        const ba = new Date(baMs)
        return { year: ba.getUTCFullYear(), month: ba.getUTCMonth() + 1, day: ba.getUTCDate(), hour: ba.getUTCHours(), minute: ba.getUTCMinutes(), second: ba.getUTCSeconds(), weekday: ba.getUTCDay(), date: ba }
    }
}

function computeShippingTitleForBA(nowBA) {
    const hour = nowBA.hour
    const weekday = nowBA.weekday
    const isWeekday = weekday >= 1 && weekday <= 5
    const beforeCutoff = hour <= 12

    const parts = new Intl.DateTimeFormat('en-CA', {
        timeZone: 'America/Argentina/Buenos_Aires', hour12: false,
        year: 'numeric', month: '2-digit', day: '2-digit',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    }).formatToParts(new Date()).reduce((acc, p) => {
        if (p.type !== 'literal') acc[p.type] = parseInt(p.value, 10)
        return acc
    }, {})

    const baNow = new Date(Date.UTC(parts.year, parts.month - 1, parts.day, parts.hour, parts.minute, parts.second))
    let title, deadline = new Date(baNow)

    if (!isWeekday) {
        title = '¡Llega el lunes!'
        const daysAhead = (1 - weekday + 7) % 7 || 7
        deadline.setUTCDate(deadline.getUTCDate() + daysAhead)
        deadline.setUTCHours(15, 0, 0, 0)
    } else if (beforeCutoff) {
        title = '¡Llega hoy!'
        deadline.setUTCHours(15, 0, 0, 0)
    } else if (weekday === 5) {
        title = '¡Llega el lunes!'
        const daysAhead = (1 - weekday + 7) % 7 || 7
        deadline.setUTCDate(deadline.getUTCDate() + daysAhead)
        deadline.setUTCHours(15, 0, 0, 0)
    } else {
        title = '¡Llega mañana!'
        deadline.setUTCDate(deadline.getUTCDate() + 1)
        deadline.setUTCHours(15, 0, 0, 0)
    }

    return `${title} con motomensajería`
}

async function loadCartItems() {
    try {
        const { data } = await axios.get('/cart-info')
        if (data.final_total == 0) { location.reload(); return }

        cartProducts.value = Object.values(data.products || {})
        cartTotal.value = data.total
        cartFinalTotal.value = data.final_total
        shippingCost.value = data.shipping_cost

        if (data.is_coupon_applied) {
            couponApplied.value = true
            couponId.value = data.coupon_id
            couponDiscount.value = data.coupon_discount
            cartFinalTotal.value = data.final_total_after_coupon_applied
        }

        document.querySelectorAll('.cart-panel-item-qty').forEach(el => {
            el.style.display = 'inline-flex'
        })
    } catch (e) {
        console.error(e)
    }
}

async function updateQuantity(productVariantId, action) {
    try {
        await axios.put('/carts/products/update-quantity', { productVariantId, action })
        await loadCartItems()
    } catch (e) {
        console.error(e)
    }
}

async function deleteProduct(productVariantId) {
    try {
        await axios.delete('/cart', {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            data: { product_variant_id: productVariantId }
        })
        await loadCartItems()
        toastr.success('Producto eliminado')
    } catch (e) {
        console.error(e)
    }
}

async function validateCoupon() {
    const code = document.getElementById('coupon').value
    try {
        await axios.get(`/validate-coupon?code=${code}`)
        document.getElementById('coupon-validated-success').innerHTML = 'Cupón validado'
        document.getElementById('coupon-validated-failed').innerHTML = ''
        await loadCartItems()
    } catch (error) {
        document.getElementById('coupon-validated-success').innerHTML = ''
        document.getElementById('coupon-validated-failed').innerHTML = error?.response?.data?.message || 'Error'
    }
}

async function submitOrder() {
    const fields = ['firstName', 'lastName', 'phone', 'documentNumber', 'email']
    if (selectedShipmentMethod.value !== 'take-away') {
        fields.push('locality', 'province', 'street', 'number', 'zip_code')
    }

    let valid = true
    fields.forEach(f => {
        const input = document.getElementById(f)
        if (!input || !input.value.trim()) {
            if (input) input.classList.add('is-invalid')
            valid = false
        } else {
            if (input) input.classList.remove('is-invalid')
        }
    })

    if (!valid) { toastr.error('Por favor, complete todos los campos obligatorios.'); return }
    if (!selectedPaymentMethod.value || !selectedShipmentMethod.value) {
        toastr.error('Por favor, seleccione un método de pago y de envío.'); return
    }

    const data = {
        name: document.getElementById('firstName').value,
        surname: document.getElementById('lastName').value,
        phone: document.getElementById('phone').value,
        dni: document.getElementById('documentNumber').value,
        email: document.getElementById('email').value,
        locality: document.getElementById('locality')?.value || '',
        province: document.getElementById('province')?.value || '',
        street: document.getElementById('street')?.value || '',
        number: document.getElementById('number')?.value || '',
        apartment: document.getElementById('apartment')?.value || '',
        zip_code: document.getElementById('zip_code')?.value || '',
        observations: document.getElementById('observations')?.value || '',
        coupon_id: couponId.value,
        payment_method: selectedPaymentMethod.value,
        shipping_method: selectedShipmentMethod.value
    }

    const btn = document.getElementById('submit')
    btn.innerHTML = '<div class="spinner-border text-white" role="status"></div>'

    try {
        const res = await axios.post('/pay', {
            data: JSON.stringify(data),
            _token: document.querySelector('meta[name="csrf-token"]').content
        })
        window.open(res.data.init_point, '_blank')
        btn.innerHTML = 'Finalizar compra'
    } catch (e) {
        btn.innerHTML = 'Finalizar compra'
    }
}

function setStep(step) {
    currentStep.value = step
}

function fillSummary() {
    document.getElementById('summaryEmail').textContent = document.getElementById('email')?.value || ''
    document.getElementById('summaryPhone').textContent = document.getElementById('phone')?.value || ''

    let shippingText = 'A definir'
    if (selectedShipmentMethod.value === 'take-away') shippingText = 'Retiro en CABA'
    else if (selectedShipmentMethod.value === 'correo-argentino') shippingText = 'Correo Argentino "Envío a domicilio"'
    else if (selectedShipmentMethod.value === 'FLEX') shippingText = document.getElementById('shipping-option-title')?.textContent || 'Envío rápido'
    document.getElementById('summaryShippingMethod').textContent = shippingText

    const street = document.getElementById('street')?.value || ''
    const number = document.getElementById('number')?.value || ''
    const apartment = document.getElementById('apartment')?.value || ''
    if (selectedShipmentMethod.value === 'take-away') {
        document.getElementById('summaryAddress').textContent = 'Retiro en persona'
        document.getElementById('summaryCityZip').textContent = ''
    } else {
        document.getElementById('summaryAddress').textContent = `${street} ${number}${apartment ? ', ' + apartment : ''}`
        const provinceEl = document.getElementById('province')
        const localityEl = document.getElementById('locality')
        const zip = document.getElementById('zip_code')?.value || ''
        document.getElementById('summaryCityZip').textContent = `${localityEl?.options[localityEl.selectedIndex]?.text || ''}, ${provinceEl?.options[provinceEl.selectedIndex]?.text || ''}${zip ? ' (CP ' + zip + ')' : ''}`
    }
}

onMounted(async () => {
    await loadCartItems()

    try {
        const { data: provinces } = await axios.get('https://apis.datos.gob.ar/georef/api/provincias?campos=id,nombre')
        const sorted = provinces.provincias.sort((a, b) => a.nombre.localeCompare(b.nombre))
        let html = sorted.map(p => `<option value="${p.nombre}">${p.nombre}</option>`).join('')
        html += '<option selected disabled>Seleccione una opción</option>'
        document.getElementById('province').innerHTML = html

        document.getElementById('province').addEventListener('change', async (e) => {
            const { data: mun } = await axios.get(`https://apis.datos.gob.ar/georef/api/municipios?provincia=${e.target.value}&campos=id,nombre&max=500`)
            const sortedMun = mun.municipios.sort((a, b) => a.nombre.localeCompare(b.nombre))
            let mhtml = sortedMun.filter(l => !l.nombre.includes('Comuna')).map(l => `<option value="${l.nombre}">${l.nombre}</option>`).join('')
            if (e.target.value === 'Ciudad Autónoma de Buenos Aires') {
                mhtml += BARRIOS_CABA.map(b => `<option value="${b}">${b}</option>`).join('')
            }
            mhtml += '<option selected disabled>Seleccione una opción</option>'
            document.getElementById('locality').innerHTML = mhtml
        })
    } catch (e) { console.error(e) }
})
</script>

<template>
    <AppLayout :show-reviews="false" :show-footer="false" title="Carrito - Ática" body-padding-top="3rem">
        <div class="container mt-5 mt-md-4" style="max-width: 100%;">
            <div class="row">
                <div class="col-md-7 steps-container order-2 order-md-1">
                    <ul class="nav nav-pills mb-4 ul-steps">
                        <li :class="['nav-item li-step d-flex align-items-center', { 'grey-background': currentStep === 'step1' }]" @click="setStep('step1')">
                            <div class="li-step-icon"><i class="fa-solid fa-user" style="color: #bc8d8a"></i></div>
                            <div><div class="li-step-title">Cliente</div><div class="li-step-description">Ingresá tus datos</div></div>
                        </li>
                        <li :class="['nav-item li-step d-flex align-items-center', { 'grey-background': currentStep === 'step2' }]" @click="setStep('step2')">
                            <div class="li-step-icon"><i class="fa-regular fa-credit-card" style="color: #bc8d8a"></i></div>
                            <div><div class="li-step-title">Pago</div><div class="li-step-description">Elegí cómo pagar</div></div>
                        </li>
                    </ul>

                    <div v-if="currentStep === 'step1'">
                        <h5 class="mb-3" style="font-weight: bold; font-size: 1.5rem">Datos de facturación</h5>
                        <form>
                            <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" id="email" placeholder="email@gmail.com" required></div>
                            <div class="row">
                                <div class="mb-3 col-md-6"><label class="form-label">Nombre</label><input type="text" class="form-control" id="firstName" placeholder="Nombre" required></div>
                                <div class="mb-3 col-md-6"><label class="form-label">Apellido</label><input type="text" class="form-control" id="lastName" placeholder="Apellido" required></div>
                            </div>
                            <div class="mb-3"><label class="form-label">Teléfono</label><input type="tel" class="form-control" id="phone" placeholder="011 6172-1821" required></div>
                            <div class="row">
                                <div class="mb-3 col-md-4"><label class="form-label">Documento</label><select class="form-select" id="documentType" required><option value="DNI" selected>DNI</option></select></div>
                                <div class="mb-3 col-md-8"><label class="form-label">Número de documento</label><input type="text" class="form-control" id="documentNumber" required></div>
                            </div>

                            <hr class="my-4">
                            <h5 class="mb-3" style="font-weight: bold; font-size: 1.5rem">Entrega</h5>
                            <div class="row mb-3"><div class="col-12 col-md-6"><label class="form-label">Código postal</label><input type="text" class="form-control" id="zip_code" required></div></div>

                            <div id="address-section" class="border rounded-3 p-3 mb-4" :class="{ 'd-none': selectedShipmentMethod === 'take-away' }">
                                <h6 class="mb-3" style="font-weight: bold; font-size: 1.5rem">Datos del destinatario</h6>
                                <div class="row">
                                    <div class="mb-3 col-md-6"><label class="form-label">Provincia</label><select class="form-select" id="province" required></select></div>
                                    <div class="mb-3 col-md-6"><label class="form-label">Localidad</label><select class="form-select" id="locality" required><option value="">Selecciona una opción</option></select></div>
                                </div>
                                <div class="row"><div class="mb-3 col-12"><label class="form-label">Calle</label><input type="text" class="form-control" id="street" required></div></div>
                                <div class="row">
                                    <div class="mb-3 col-md-4"><label class="form-label">Número</label><input type="text" class="form-control" id="number" required></div>
                                    <div class="mb-3 col-md-4"><label class="form-label">Piso/Depto</label><input type="text" class="form-control" id="apartment"></div>
                                </div>
                                <div class="row"><div class="mb-3 col-md-12"><label class="form-label">Observaciones para el envío:</label><label class="form-label">Si preferís que te enviemos a una sucursal de Correo Argentino, dejalo escrito acá</label><textarea class="form-control" id="observations" rows="3" placeholder="Referencias; cosas a tener en cuenta; etc."></textarea></div></div>
                            </div>

                            <div class="shipping-methods mb-4">
                                <label class="shipping-option" @click="selectedShipmentMethod = 'correo-argentino'">
                                    <input type="radio" name="shipping_method" class="shipping-option__radio" value="correo-argentino" :checked="selectedShipmentMethod === 'correo-argentino'">
                                    <span class="shipping-option__circle"></span>
                                    <span class="shipping-option__content">
                                        <img src="https://www.correoargentino.com.ar/sites/default/files/logo-correo.png" alt="Correo Argentino" class="shipping-option__logo">
                                        <span class="shipping-option__title">Envío a domicilio</span>
                                        <small class="shipping-option__subtitle">De 1 a 5 días hábiles</small>
                                    </span>
                                    <span class="shipping-option__price">{{ shippingCost == 0 ? 'GRATIS' : formatMoneyAR(shippingCost) }}</span>
                                </label>
                                <label class="shipping-option" @click="selectedShipmentMethod = 'take-away'">
                                    <input type="radio" name="shipping_method" class="shipping-option__radio" value="take-away" :checked="selectedShipmentMethod === 'take-away'">
                                    <span class="shipping-option__circle"></span>
                                    <span class="shipping-option__content">
                                        <span class="shipping-option__title">Retirar en CABA</span>
                                        <small class="shipping-option__subtitle">Bernardo de Irigoyen 630 - Monserrat</small>
                                        <small class="shipping-option__subtitle">CON CITA PREVIA</small>
                                    </span>
                                </label>
                                <label class="shipping-option shipping-option--highlight" @click="selectedShipmentMethod = 'FLEX'">
                                    <input type="radio" name="shipping_method" class="shipping-option__radio" value="FLEX" :checked="selectedShipmentMethod === 'FLEX'">
                                    <span class="shipping-option__circle"></span>
                                    <span class="shipping-option__content">
                                        <span class="shipping-option__title" id="shipping-option-title">{{ computeShippingTitleForBA(getNowInBuenosAires()) }}</span>
                                        <small class="shipping-option__subtitle" id="shipping-option-subtitle">Comprando antes de las 13:00 hs</small>
                                    </span>
                                    <span class="shipping-option__price">Gratis</span>
                                </label>
                            </div>

                            <button type="button" id="continue-to-payment-step-button" class="btn w-100" @click="fillSummary(); setStep('step2')">Continuar al pago</button>
                        </form>
                    </div>

                    <div v-if="currentStep === 'step2'">
                        <div class="mb-4">
                            <div class="card mb-3"><div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold">Datos de facturación</span>
                                    <button type="button" class="btn btn-link btn-sm p-0" @click="setStep('step1')">Cambiar</button>
                                </div>
                                <p class="mb-0" id="summaryEmail"></p>
                                <p class="mb-0 small text-muted" id="summaryPhone"></p>
                            </div></div>
                            <div class="card mb-3"><div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold">Entrega</span>
                                    <button type="button" class="btn btn-link btn-sm p-0" @click="setStep('step1')">Cambiar</button>
                                </div>
                                <p class="mb-0" id="summaryShippingMethod"></p>
                                <p class="mb-0 small text-muted" id="summaryAddress"></p>
                                <p class="mb-0 small text-muted" id="summaryCityZip"></p>
                            </div></div>
                        </div>

                        <h5 class="mb-3" style="font-weight: bold; font-size: 1.5rem">Método de pago</h5>
                        <div class="payment-methods mb-5">
                            <label class="payment-option" @click="selectedPaymentMethod = 'bank-transfer'">
                                <input type="radio" name="payment_method" class="payment-option__radio" value="bank-transfer" :checked="selectedPaymentMethod === 'bank-transfer'">
                                <span class="payment-option__circle"></span>
                                <span class="payment-option__label">Transferencia bancaria</span>
                                <span class="payment-option__badge">10% de descuento</span>
                            </label>
                            <label class="payment-option" @click="selectedPaymentMethod = 'mercado-pago'">
                                <input type="radio" name="payment_method" class="payment-option__radio" value="mercado-pago" :checked="selectedPaymentMethod === 'mercado-pago'">
                                <span class="payment-option__circle"></span>
                                <span class="payment-option__label"><span>Mercado Pago</span>
                                    <img src="/MP_RGB_HANDSHAKE_color_vertical.png" alt="Mercado Pago" class="payment-option__mp-logo">
                                </span>
                            </label>
                        </div>

                        <button id="submit" class="btn btn-success w-100" @click="submitOrder">Finalizar</button>
                    </div>
                </div>

                <div class="col-md-5 checkout-info-container order-1 order-md-2 px-0 px-md-3">
                    <div class="checkout-summary">
                        <button type="button" class="checkout-summary-toggle" @click="summaryOpen = !summaryOpen">
                            <div class="d-flex align-items-center">
                                <span class="checkout-summary-chevron" :class="{ rotated: summaryOpen }" style="display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:999px; border:1px solid #d1d5db; margin-right:8px; transition:transform .2s ease;">
                                    <i class="fa-solid fa-chevron-down" style="font-size:11px;"></i>
                                </span>
                                <span>Ver detalles de mi compra</span>
                            </div>
                            <span class="checkout-summary-total">{{ formatMoneyAR(cartFinalTotal) }}</span>
                        </button>
                        <div class="checkout-summary-body" :class="{ 'is-open': summaryOpen }" style="display:none;">
                            <div class="p-3 border-top">
                                <div v-for="product in cartProducts" :key="product.product_variant_id" class="cart-item-card p-3 my-3 border rounded w-100 position-relative">
                                    <button class="x-cart-button" @click="deleteProduct(product.product_variant_id)">X</button>
                                    <div class="d-flex gap-3">
                                        <div class="order-summary-thumbnail flex-shrink-0">
                                            <a :href="'/productos/' + product.slug" target="_blank" class="cart-item-link text-decoration-none text-dark flex-grow-1">
                                                <img :src="product.pic" class="img-fluid">
                                                <div class="item-quantity">{{ product.quantity }}</div>
                                            </a>
                                        </div>
                                        <div class="d-flex flex-column flex-md-row flex-grow-1 justify-content-between">
                                            <div>
                                                <h5 class="cart-item-title mb-1">{{ product.product_name }}</h5>
                                                <div class="small text-muted mb-1">Talle: {{ product.size }}</div>
                                                <div class="d-flex align-items-center">
                                                    <div style="width:18px; height:18px; margin-right:0.5rem; border-radius:50%;" :style="{ background: product.color }"></div>
                                                    <span class="small text-muted">{{ product.color_name }}</span>
                                                </div>
                                                <div class="cart-panel-item-qty mt-2" style="display:inline-flex; align-items:center; border-radius:999px; border:1px solid #d1d5db; overflow:hidden;">
                                                    <button type="button" style="border:none; background:#f9fafb; padding:4px 10px; cursor:pointer; font-size:16px; line-height:1;" @click="updateQuantity(product.product_variant_id, 'minus')">−</button>
                                                    <span style="padding:2px 12px; font-size:14px;">{{ product.quantity }}</span>
                                                    <button type="button" style="border:none; background:#f9fafb; padding:4px 10px; cursor:pointer; font-size:16px; line-height:1;" @click="updateQuantity(product.product_variant_id, 'plus')">+</button>
                                                </div>
                                            </div>
                                            <div class="cart-item-price mt-2 mt-md-0 ms-md-3 text-md-end">
                                                <template v-if="product.subtotal > product.total">
                                                    <del><h4 style="font-size:1rem;margin:0;">{{ formatMoneyAR(product.subtotal) }}</h4></del>
                                                    <h4 class="text-success ms-2" style="font-size:1rem;margin:0;">{{ formatMoneyAR(product.total) }}</h4>
                                                </template>
                                                <h4 v-else class="text-success" style="font-size:1rem;margin:0;">{{ formatMoneyAR(product.total) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="px-3 pb-3">
                                <div class="row">
                                    <div class="mt-3 col-md-12">
                                        <label class="form-label">Tengo un cupón de descuento</label>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <input type="text" class="mb-0 me-3 form-control" id="coupon" placeholder="Ingresa tu código" required>
                                            <button type="button" class="btn btn-primary" @click="validateCoupon">Validar</button>
                                        </div>
                                        <div id="coupon-validated-success" class="mt-2" style="color: green; font-weight: bold"></div>
                                        <div id="coupon-validated-failed" class="mt-2" style="color: red; font-weight: bold"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="px-3 pb-3 border-top">
                                <div id="coupon-success-code" class="mb-2 small text-success" v-if="couponApplied">Aplicado {{ couponDiscount }}% OFF</div>
                                <div class="d-flex justify-content-between align-content-center mt-3">
                                    <span class="fw-semibold">Envío (gratis a partir de $35.000):</span>
                                    <span class="text-success fw-semibold">{{ shippingCost == 0 ? 'GRATIS' : formatMoneyAR(shippingCost) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-content-center mt-3">
                                    <span class="fw-bold">Total</span>
                                    <div class="text-success fw-bold">
                                        <template v-if="couponApplied"><del>{{ formatMoneyAR(cartTotal) }}</del> </template>
                                        <span style="font-size:1.5rem;">{{ formatMoneyAR(cartFinalTotal) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
.payment-methods { border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; background: #fff; }
.payment-option { display: flex; align-items: center; gap: 12px; padding: 16px; cursor: pointer; user-select: none; }
.payment-option + .payment-option { border-top: 1px solid #e5e7eb; }
.payment-option__radio { position: absolute; opacity: 0; pointer-events: none; }
.payment-option__circle { width: 18px; height: 18px; border-radius: 999px; border: 2px solid #9ca3af; background: #fff; display: inline-flex; align-items: center; justify-content: center; flex: 0 0 18px; }
.payment-option__radio:checked + .payment-option__circle { border-color: #111827; }
.payment-option__radio:checked + .payment-option__circle::after { content: ""; width: 8px; height: 8px; border-radius: 999px; background: #111827; }
.payment-option__label { font-weight: 600; color: #111827; line-height: 1.1; }
.payment-option__badge { margin-left: 10px; font-size: 12px; font-weight: 700; color: #0f5132; background: #d1e7dd; border-radius: 999px; padding: 4px 10px; white-space: nowrap; }
.payment-option__mp-logo { height: 34px; width: auto; display: block; }
.payment-option:hover { background: #f9fafb; }
.cart-item-card { background: #ffffff; }
.cart-item-title { font-size: 1rem; line-height: 1.2; }
body { background: #f3f4f6; }
.steps-container { background: #ffffff; border-radius: 16px; box-shadow: 0 10px 30px rgba(15,23,42,.12); padding: 24px 24px 120px; }
.checkout-info-container { background: transparent; }
.checkout-summary { border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(15,23,42,.16); background: #ffffff; }
.checkout-summary-toggle { border: none; background: #f9fafb; padding: 12px 16px; font-size: 14px; font-weight: 500; display: flex; align-items: center; justify-content: space-between; width: 100%; }
.checkout-summary-total { font-size: 16px; font-weight: 700; color: #111827; }
.checkout-summary-body.is-open { display: block; }
.shipping-methods { border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; background: #fff; }
.shipping-option { display: flex; align-items: center; gap: 12px; padding: 16px; cursor: pointer; user-select: none; }
.shipping-option + .shipping-option { border-top: 1px solid #e5e7eb; }
.shipping-option:hover { background: #f9fafb; }
.shipping-option__radio { position: absolute; opacity: 0; pointer-events: none; }
.shipping-option__circle { width: 18px; height: 18px; border-radius: 999px; border: 2px solid #9ca3af; background: #fff; display: inline-flex; align-items: center; justify-content: center; flex: 0 0 18px; }
.shipping-option__radio:checked + .shipping-option__circle { border-color: #111827; }
.shipping-option__radio:checked + .shipping-option__circle::after { content: ""; width: 8px; height: 8px; border-radius: 999px; background: #111827; }
.shipping-option__content { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
.shipping-option__logo { width: 160px; max-width: 100%; height: auto; display: block; margin-bottom: 2px; }
.shipping-option__title { font-weight: 700; color: #111827; line-height: 1.1; }
.shipping-option__subtitle { color: #6b7280; line-height: 1.2; }
.shipping-option__price { margin-left: auto; font-weight: 800; color: #16a34a; white-space: nowrap; }
.ul-steps { display: flex; gap: 0; padding: 4px; border-radius: 999px; background: #f5f7fb; border: 1px solid #e5e7eb; }
.li-step { flex: 1; display: flex; align-items: center; gap: 10px; padding: 10px 18px; border-radius: 999px; border: none; background: transparent; cursor: pointer; }
.li-step-icon { width: 32px; height: 32px; border-radius: 999px; background: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 0 1px #e5e7eb; }
.li-step-title { font-size: 14px; font-weight: 700; }
.li-step-description { font-size: 12px; color: #6b7280; }
.li-step.grey-background, .li-step.active { background: #0b1220; color: #ffffff; box-shadow: 0 12px 25px rgba(15,23,42,.35); }
.li-step.grey-background .li-step-description, .li-step.active .li-step-description { color: #e5e7eb; }
#continue-to-payment-step-button, #submit { background: #111827; border-radius: 999px; border: none; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: .08em; padding: 12px 18px; color: #fff; }
#continue-to-payment-step-button:hover, #submit:hover { background: #020617; }
@media (max-width: 991.98px) { footer { display: none; } .steps-container { padding-bottom: 5rem !important; box-shadow: none; border-radius: 0; } body { padding-top: 3rem; } }
.order-summary-thumbnail { width: 110px; height: 110px; border-radius: 8px; overflow: hidden; background: #f9fafb; flex-shrink: 0; display: flex; align-items: center; justify-content: center; position: relative; }
.order-summary-thumbnail img { width: 100%; height: 100%; object-fit: cover; display: block; }
.item-quantity { position: absolute; bottom: 6px; left: 6px; background: rgba(0,0,0,.8); color: #fff; font-size: 0.75rem; padding: 2px 6px; border-radius: 999px; }
.x-cart-button { position: absolute; height: 25px; width: 25px; border-radius: 50%; top: 0; right: 0; background: black; color: white; transform: translateY(-30%); border: none; cursor: pointer; z-index: 2; }
input, option, select { background-color: #ffffff !important; }
</style>
