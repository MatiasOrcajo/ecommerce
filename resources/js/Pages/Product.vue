<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    product: Object,
})

const selectedColor = ref(null)
const selectedSize = ref(null)
const availableColors = ref([])
const productsVariantsArray = ref([])
const quantity = ref(1)
const sizeHelpOpen = ref(false)
const lightboxImage = ref(null)
const flexShippingTitle = ref('')
const currentCarouselIndex = ref(0)

const currentColorPics = computed(() => {
    const colorObj = availableColors.value.find(c => c.color === selectedColor.value)
    if (!colorObj) return []
    return Array.isArray(colorObj.pics.paths) ? colorObj.pics.paths : Object.values(colorObj.pics.paths)
})

const filteredSizes = computed(() => {
    const variants = productsVariantsArray.value.filter(v => v.color === selectedColor.value)
    const arr = variants.map(v => ({ size: v.size, stock: v.stock }))
    const isLetter = arr.every(s => isNaN(s.size))
    const order = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL']
    arr.sort((a, b) => isLetter ? order.indexOf(a.size) - order.indexOf(b.size) : a.size - b.size)
    return arr
})

const carouselItems = computed(() => {
    return currentColorPics.value.map((path, i) => ({
        path,
        active: i === currentCarouselIndex.value,
        index: i,
    }))
})

function formatMoneyAR(value) {
    const nf = new Intl.NumberFormat('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
    return `$${nf.format(Number(value))}`
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
        title = 'Llega gratis el lunes'
        const daysAhead = (1 - weekday + 7) % 7 || 7
        deadline.setUTCDate(deadline.getUTCDate() + daysAhead)
        deadline.setUTCHours(15, 0, 0, 0)
    } else if (beforeCutoff) {
        title = 'Llega gratis hoy comprando antes de las 12'
        deadline.setUTCHours(15, 0, 0, 0)
    } else if (weekday === 5) {
        title = 'Llega gratis el lunes'
        const daysAhead = (1 - weekday + 7) % 7 || 7
        deadline.setUTCDate(deadline.getUTCDate() + daysAhead)
        deadline.setUTCHours(15, 0, 0, 0)
    } else {
        title = 'Llega gratis mañana comprando antes del mediodia'
        deadline.setUTCDate(deadline.getUTCDate() + 1)
        deadline.setUTCHours(15, 0, 0, 0)
    }

    return title
}

function setShippingTitle() {
    flexShippingTitle.value = computeShippingTitleForBA(getNowInBuenosAires())
}

function toYoutubeEmbed(url) {
    if (!url) return null
    url = String(url).trim()
    if (url.includes('/embed/')) return url
    const shortMatch = url.match(/youtu\.be\/([a-zA-Z0-9_-]{6,})/)
    if (shortMatch && shortMatch[1]) return `https://www.youtube.com/embed/${shortMatch[1]}`
    try {
        const u = new URL(url)
        const v = u.searchParams.get('v')
        if (v) return `https://www.youtube.com/embed/${v}`
    } catch (e) {}
    return null
}

function selectColor(colorHex) {
    selectedColor.value = colorHex
    currentCarouselIndex.value = 0
}

function selectSize(sizeName) {
    selectedSize.value = sizeName
}

function handleCarouselImageClick(img) {
    lightboxImage.value = img
}

async function addToCart() {
    if (!selectedSize.value) {
        toastr.error('Debe seleccionar un talle')
        return
    }
    if (!selectedColor.value) {
        toastr.error('Debe seleccionar un color')
        return
    }

    const btn = document.getElementById('add-product-to-cart')
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Agregando...'
    btn.disabled = true

    try {
        await fetch(`/carts/products/${props.product.id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                size: selectedSize.value,
                color: selectedColor.value,
                product_id: props.product.id,
                quantity: quantity.value,
            })
        })
        btn.innerHTML = 'AGREGAR AL CARRITO'
        btn.disabled = false
        document.getElementById('added-to-cart-succesfully').innerHTML = '<div class="alert alert-success mt-3">¡Producto agregado al carrito!</div>'
        updateCartCounter()
    } catch (e) {
        btn.innerHTML = 'AGREGAR AL CARRITO'
        btn.disabled = false
    }
}

function updateCartCounter() {
    fetch('/calculate-cart-total-items')
        .then(r => r.json())
        .then(data => {
            const totalItems = data.products.reduce((sum, p) => sum + Number(p.quantity || 0), 0)
            const el = document.getElementById('cart_counter')
            const elR = document.getElementById('cart_counter_responsive')
            if (el) el.innerHTML = `<h1>${totalItems}</h1>`
            if (elR) elR.innerHTML = `<h1>${totalItems}</h1>`
        })
}

function scrollThumbs(direction) {
    document.querySelector('.thumbnail-container')?.scrollBy({ left: direction * 100, behavior: 'smooth' })
}

onMounted(async () => {
    try {
        const res = await fetch(`/products/${props.product.id}/get-variants`)
        const data = await res.json()
        availableColors.value = data.availableColors
        productsVariantsArray.value = data.productsVariantsArray

        if (availableColors.value.length > 0) {
            const hasPics = availableColors.value.some(c => (Array.isArray(c.pics.paths) ? c.pics.paths : Object.values(c.pics.paths)).length > 0)
            if (hasPics && !availableColors.value[0].color_name.includes('PACK')) {
                selectedColor.value = availableColors.value[0].color
            } else if (availableColors.value[0]) {
                selectedColor.value = availableColors.value[0].color
            }
        }
    } catch (e) {
        console.error(e)
    }

    setShippingTitle()
    setInterval(setShippingTitle, 1000)
})
</script>

<template>
    <AppLayout :show-reviews="false" :show-footer="false" :title="product.name + ' - Atica'">
        <div class="product-page-container">
            <div class="container my-md-5">
                <div class="row gx-5">
                    <div class="col-md-6" style="margin-top: 3rem">
                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div v-for="(pic, index) in (currentColorPics.length ? currentColorPics : product.product_pictures?.map(p => p.path) || [])" :key="'cp-'+index" :class="['carousel-item', { active: index === currentCarouselIndex }]">
                                    <div class="zoom-container d-flex justify-content-center align-items-center" @click="handleCarouselImageClick(pic)">
                                        <img :src="pic" :alt="'Producto ' + (index + 1)" class="d-block product-image">
                                    </div>
                                </div>
                                <div v-if="product.youtube_link" class="carousel-item">
                                    <div class="ratio ratio-16x9 carousel-video">
                                        <iframe :src="toYoutubeEmbed(product.youtube_link)" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                        </div>

                        <div class="thumbnail-wrapper mt-3">
                            <button class="arrow-btn arrow-left" @click="scrollThumbs(-1)">‹</button>
                            <div class="thumbnail-container">
                                <img v-for="(pic, index) in (currentColorPics.length ? currentColorPics : product.product_pictures?.map(p => p.path) || [])" :key="'t-'+index" :src="pic" :class="['thumbnail-item', { active: index === currentCarouselIndex }]" :data-bs-target="'#productCarousel'" :data-bs-slide-to="index" :alt="'Mini ' + (index + 1)" @click="currentCarouselIndex = index">
                            </div>
                            <button class="arrow-btn arrow-right" @click="scrollThumbs(1)">›</button>
                        </div>

                        <div class="col-12">
                            <div class="my-3 d-none d-md-block">
                                <div class="mb-4">
                                    <div class="bg-light border rounded p-2"><em>Descripción</em></div>
                                    <div class="mt-2" v-html="product.description"></div>
                                </div>
                                <div class="mb-4">
                                    <div class="bg-light border rounded p-2"><em>Medidas</em></div>
                                    <div class="mt-2" v-html="product.sizes_description"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h2 class="text-uppercase my-2" style="font-size: 32px">{{ product.name }}</h2>

                        <p v-if="product.discount" class="h4 text-dark">
                            <small><del>{{ formatMoneyAR(product.price) }}</del> {{ product.discount }}% OFF</small>
                        </p>
                        <p class="text-dark" style="font-size: 3rem;">
                            {{ formatMoneyAR(product.discount ? product.price * (1 - product.discount / 100) : product.price) }}
                        </p>

                        <p class="text-secondary" style="font-size: 1.5rem; color: #785c64 !important">
                            {{ formatMoneyAR((product.discount ? product.price * (1 - product.discount / 100) : product.price) * 0.9) }} con Transferencia
                        </p>
                        <p class="text-secondary" style="font-size: 1.5rem; color: #785c64 !important">
                            {{ formatMoneyAR((product.discount ? product.price * (1 - product.discount / 100) : product.price) * 0.8) }} en efectivo
                        </p>

                        <span style="color: green; font-weight: bold; font-size: 19px">{{ flexShippingTitle }}</span>
                        <br>
                        <span style="text-decoration: underline; cursor: pointer; font-size: 15px">Válido para CABA y GBA</span>

                        <div class="my-3">
                            <span class="me-2"><strong>Medios de pago:</strong></span>
                            <div class="d-flex gap-2">
                                <img src="https://logowik.com/content/uploads/images/mercado-pago3162.logowik.com.webp" alt="MP" width="30" height="25">
                                <img src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/visa@2x.png" alt="Visa" width="30" height="25">
                                <img src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/mastercard@2x.png" alt="MC" width="30" height="25">
                                <img src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/amex@2x.png" alt="Amex" width="30" height="25">
                                <img src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/ar/tarjeta-naranja@2x.png" alt="Naranja" width="30" height="25">
                            </div>
                        </div>
                        <p><strong>10% de descuento</strong> pagando con transferencia</p>
                        <p><strong>20% de descuento</strong> pagando en efectivo</p>

                        <div class="my-3">
                            <label class="d-block mb-1"><strong>Color:</strong></label>
                            <div class="d-flex gap-2">
                                <div v-if="availableColors.length <= 1 || availableColors[0].color_name.includes('PACK')" style="display:none"></div>
                                <div v-for="(c, i) in availableColors" v-else :key="c.color" class="btn btn-outline-secondary" :title="c.color_name" :style="{ background: c.color, width: '32px', height: '32px', borderRadius: '50%', outline: selectedColor === c.color ? '1px solid black' : 'none' }" @click="selectColor(c.color)"></div>
                            </div>
                        </div>

                        <div class="my-4">
                            <label class="d-block mb-1"><strong>Talle: {{ selectedSize }}</strong></label>
                            <div class="d-flex gap-2">
                                <div v-for="s in filteredSizes" :key="s.size" class="btn btn-outline-secondary size-box" :class="{ 'no-stock': s.stock === 0, active: selectedSize === s.size }" :data-size="s.size" :data-stock="s.stock" style="margin-right:0.5rem; cursor:pointer;" @click="selectSize(s.size)">{{ s.size }}</div>
                            </div>
                            <span style="text-decoration: underline; cursor: pointer" class="mt-1" @click="sizeHelpOpen = true">¿Qué talle elijo?</span>👈
                        </div>

                        <div id="size-help-overlay" :style="{ display: sizeHelpOpen ? 'block' : 'none' }" @click="sizeHelpOpen = false"></div>
                        <div id="size-help-panel" class="position-fixed top-0 end-0 vh-100 shadow-lg" :style="{ width: sizeHelpOpen ? (window.innerWidth <= 768 ? '95%' : '40%') : '0' }">
                            <button class="btn btn-link text-dark fw-bold position-absolute top-0 end-0 me-3 mt-3" @click="sizeHelpOpen = false">×</button>
                            <div class="p-4">
                                <h4 style="font-size: 24px; font-weight: bold" class="mb-3">¿Qué talle elijo?</h4>
                                <p>Cada cuerpo es distinto, por eso queremos ayudarte a encontrar el talle que mejor se adapte a vos. Nuestros productos vienen del talle S al XXL, y acá te dejamos una guía de equivalencias y recomendaciones para que elijas con confianza.</p>
                                <small>👉 Todos nuestros productos tienen compresión, así que no hace falta pedir un talle más chico.</small>
                                <img src="/guia-talles-body2.png" alt="Guia talles Body" style="width: 100%; margin-top: 2rem">
                                <img src="/guia-talles-faja3.png" alt="Guia talles Faja" style="width: 100%; margin-top: 2rem">
                                <img src="/guia-talles-camiseta.png" alt="Guia talles Camiseta" style="width: 100%; margin-top: 2rem">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label"><strong>Cantidad</strong></label>
                            <input v-model="quantity" type="number" class="form-control" value="1" min="1" style="max-width:100px;">
                        </div>

                        <div id="added-to-cart-succesfully" class="mb-3"></div>

                        <button id="add-product-to-cart" class="btn btn-lg w-100" style="background-color: #bc8d8a; color: white; font-weight: bold" @click="addToCart">AGREGAR AL CARRITO</button>

                        <div class="col-md-6">
                            <div class="my-3 d-block d-md-none">
                                <div class="mb-4">
                                    <div class="bg-light border rounded p-2"><em>Descripción</em></div>
                                    <div class="mt-2" v-html="product.description"></div>
                                </div>
                                <div class="mb-4">
                                    <div class="bg-light border rounded p-2"><em>Medidas</em></div>
                                    <div class="mt-2" v-html="product.sizes_description"></div>
                                </div>
                                <div class="mb-4">
                                    <div class="bg-light border rounded p-2"><em>Referencia Modelo</em></div>
                                    <div class="mt-2" v-html="product.model_reference"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="lightbox-overlay" :style="{ display: lightboxImage ? 'flex' : 'none' }" @click="lightboxImage = null">
                <span id="lightbox-close">&times;</span>
                <img id="lightbox-img" :src="lightboxImage" alt="Ampliado">
            </div>
        </div>
    </AppLayout>
</template>

<style>
.no-stock { opacity: 0.5; pointer-events: none; position: relative; border: 1px dashed #999 !important; color: #666 !important; }
.no-stock::after { content: "Sin stock"; position: absolute; bottom: 2px; right: 4px; font-size: 0.65rem; color: #999; }
main { overflow-x: hidden; }
.product-page-container { min-height: auto; padding-bottom: 30rem; }
@media (max-width: 991.98px) { .product-page-container { padding-bottom: 0; transform: none; } }
.zoom-container { overflow: hidden; position: relative; cursor: zoom-in; height: 80vh; }
.zoom-container img { transition: transform 0.3s ease; display: block; width: 100%; height: 100%; object-fit: contain; transform-origin: center center; }
.zoom-container:hover img { transform: scale(1.8); cursor: zoom-out; }
@media (max-width: 991.98px) { .zoom-container { height: auto; } .product-image { height: auto; max-height: 60dvh; width: 100% !important; } .zoom-container:hover img { transform: none; cursor: auto; } }
.thumbnail-wrapper { position: relative; }
.thumbnail-container { display: flex; gap: 0.5rem; overflow-x: auto; scroll-behavior: smooth; padding: 0.5rem 2rem; }
.thumbnail-item { flex: 0 0 auto; width: 60px; height: 60px; object-fit: cover; cursor: pointer; border: 2px solid transparent; transition: border-color 0.2s; }
.thumbnail-item.active { border-color: #000; }
.product-image { max-height: 90vh; width: auto !important; max-width: 100%; }
.arrow-btn { position: absolute; top: 50%; transform: translateY(-50%); width: 1.5rem; height: 1.5rem; background: rgba(0,0,0,.5); border: none; color: #fff; z-index: 10; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; cursor: pointer; }
.arrow-left { left: 0.2rem; }
.arrow-right { right: 0.2rem; }
#lightbox-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,.85); justify-content: center; align-items: center; z-index: 2000; cursor: zoom-out; }
#lightbox-overlay img { max-width: 90%; max-height: 90%; box-shadow: 0 0 20px rgba(0,0,0,.5); }
#lightbox-close { position: absolute; top: 1rem; right: 1rem; font-size: 2rem; color: #fff; cursor: pointer; z-index: 2001; }
#size-help-panel { position: fixed !important; top: 0; right: 0; width: 0; height: 100%; overflow-y: auto; transition: all 0.3s ease; z-index: 2147483647 !important; background-color: #f4f5f4; }
#size-help-overlay { display: none; position: fixed !important; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,.5); z-index: 10000; }
body.panel-open { overflow: hidden; height: 100vh; }
.size-box.active { outline: 2px solid black; }
</style>
