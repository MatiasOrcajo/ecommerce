<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import axios from 'axios'

const cartPanelOpen = ref(false)
const cartProducts = ref([])
const cartTotal = ref(0)
const cartCounter = ref(0)
const headerHidden = ref(false)

let lastScroll = 0

function formatMoneyAR(value) {
    const nf = new Intl.NumberFormat('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
    return `$${nf.format(Number(value) || 0)}`
}

async function loadCartPanel() {
    try {
        const { data } = await axios.get('/cart-info')
        cartProducts.value = data.products || []
        cartTotal.value = data.order_total_after_coupon_applied ?? data.order_total ?? 0
    } catch {
        cartProducts.value = []
        cartTotal.value = 0
    }
}

async function loadCartCounter() {
    try {
        const { data } = await axios.get('/calculate-cart-total-items')
        cartCounter.value = data.products.reduce((sum, p) => sum + Number(p.quantity || 0), 0)
    } catch {
        cartCounter.value = 0
    }
}

async function updateQuantity(productVariantId, action) {
    try {
        await axios.put('/carts/products/update-quantity', { productVariantId, action })
        await loadCartPanel()
        await loadCartCounter()
    } catch (e) {
        console.error(e)
    }
}

function openCart() {
    cartPanelOpen.value = true
    loadCartPanel()
}

function closeCart() {
    cartPanelOpen.value = false
}

function onScroll() {
    const currentScroll = window.pageYOffset
    if (currentScroll <= 0) {
        headerHidden.value = false
        return
    }
    if (currentScroll > lastScroll && !headerHidden.value) {
        headerHidden.value = true
    } else if (currentScroll < lastScroll && headerHidden.value) {
        headerHidden.value = false
    }
    lastScroll = currentScroll
}

onMounted(() => {
    loadCartCounter()
    window.addEventListener('scroll', onScroll)
})

onUnmounted(() => {
    window.removeEventListener('scroll', onScroll)
})
</script>

<template>
    <div :class="['cart-panel-overlay', { 'is-open': cartPanelOpen }]" @click="closeCart"></div>

    <div :class="['cart-panel', { 'is-open': cartPanelOpen }]">
        <div class="cart-panel-header">
            <h5 class="mb-0">Carrito de Compras</h5>
            <button type="button" class="btn-close" @click="closeCart" aria-label="Cerrar"></button>
        </div>
        <div class="cart-panel-body">
            <div v-for="p in cartProducts" :key="p.product_variant_id" class="cart-panel-item">
                <div class="cart-panel-item-img">
                    <img :src="p.pic" :alt="p.product_name">
                </div>
                <div class="cart-panel-item-info">
                    <div class="cart-panel-item-name p-0">{{ p.product_name }}</div>
                    <div class="d-flex align-items-center">
                        <div class="color-box" :style="{ background: p.color, width: '18px', height: '18px', marginRight: '0.5rem' }" :title="p.color_name"></div>
                        <span class="small text-muted">{{ p.color_name }}</span>
                    </div>
                    <div class="cart-panel-item-qty mt-1">
                        <button type="button" @click="updateQuantity(p.product_variant_id, 'minus')">−</button>
                        <span>{{ p.quantity }}</span>
                        <button type="button" @click="updateQuantity(p.product_variant_id, 'plus')">+</button>
                    </div>
                </div>
                <div class="cart-panel-item-price">
                    {{ formatMoneyAR(p.quantity ? (p.subtotal / p.quantity) : p.subtotal) }}
                </div>
            </div>
        </div>
        <div class="cart-panel-footer">
            <div class="d-flex justify-content-between mt-2">
                <span class="fw-bold">Subtotal:</span>
                <span class="fw-bold fs-5">{{ formatMoneyAR(cartTotal) }}</span>
            </div>
            <button class="cart-panel-btn-primary mt-3 w-100" @click="window.location.href='/cart'">INICIAR COMPRA</button>
            <Link href="/cart" class="d-block text-center mt-3 small text-decoration-underline">Ver más productos</Link>
        </div>
    </div>

    <header :class="['', { 'nav-up': headerHidden }]">
        <div class="d-none d-lg-block fixed-top">
            <div class="bg-dark text-white text-center" style="height:36px; line-height:36px; overflow:hidden;">
                <span class="animate-marquee d-inline-block" style="padding-left:100%; animation-duration: 20s;">
                    ENVÍOS GRATIS A TODO EL PAÍS A PARTIR DE $35.000. 10% OFF PAGANDO CON TRANSFERENCIA. 20% OFF EN EFECTIVO.
                </span>
            </div>
            <nav class="navbar navbar-expand-lg navbar-light pb-0 px-3" style="background:#ffffff">
                <div class="container-fluid d-flex align-items-center">
                    <div class="col-lg-4 d-none d-lg-flex justify-content-start">
                        <form class="d-flex align-items-center w-100" style="max-width: 220px;" method="GET" action="/search">
                            <input class="form-control border-0 border-bottom rounded-0 w-100" type="search" placeholder="Buscar" style="background-color:#ffffff" name="q">
                            <button class="btn p-0 ms-2" type="submit"><i class="bi bi-search"></i></button>
                        </form>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="d-flex justify-content-center">
                            <Link class="navbar-brand d-flex justify-content-center align-items-center" href="/" style="font-size: 2.5rem; letter-spacing: 0.1rem;">
                                <img src="/LOGO_PNG.png" alt="Logo Ática" width="118" height="40" decoding="async" style="height:auto">
                            </Link>
                        </div>
                    </div>
                    <div class="col-lg-4 d-none d-lg-flex justify-content-end align-items-center">
                        <a class="position-relative text-dark" @click.prevent="openCart" style="cursor:pointer">
                            <i class="fa-solid fa-cart-shopping fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">{{ cartCounter }}</span>
                        </a>
                    </div>
                </div>
            </nav>
            <nav class="navbar navbar-expand-lg navbar-light shadow-sm p-0" style="background:#ffffff">
                <div class="container-fluid">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item"><Link class="nav-link active" href="/">Inicio</Link></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="collectionsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Productos</a>
                            <ul class="dropdown-menu" aria-labelledby="collectionsDropdown">
                                <li><Link class="dropdown-item" href="/search?q=SUMMER SALE">SUMMER SALE</Link></li>
                                <li><Link class="dropdown-item" href="/categorias/bodys-reductores">Bodys reductores</Link></li>
                                <li><Link class="dropdown-item" href="/categorias/camisetas-reductoras">Camisetas reductoras</Link></li>
                                <li><Link class="dropdown-item" href="/categorias/fajas-modeladoras">Fajas modeladoras</Link></li>
                                <li><Link class="dropdown-item" href="/search?q=Todos los productos">Todos los productos</Link></li>
                            </ul>
                        </li>
                        <li class="nav-item"><Link class="nav-link" href="/guia-de-talles">Guía de talles</Link></li>
                        <li class="nav-item"><Link class="nav-link" href="/faqs">Preguntas frecuentes</Link></li>
                        <li class="nav-item"><a class="nav-link" href="https://wa.link/n5il16" target="_blank" style="font-weight: bold; text-decoration: underline">Necesito ayuda</a></li>
                    </ul>
                </div>
            </nav>
        </div>

        <div class="d-lg-none fixed-top">
            <div class="bg-dark text-white text-center" style="height:36px; line-height:36px; overflow:hidden;">
                <span class="animate-marquee d-inline-block" style="padding-left:100%">
                    ENVÍOS GRATIS A TODO EL PAÍS A PARTIR DE $35.000. 10% OFF PAGANDO CON TRANSFERENCIA. 20% OFF EN EFECTIVO.
                </span>
            </div>
            <nav class="navbar navbar-light shadow-sm" style="background:#fff; position:relative; min-height:64px;">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mainOffcanvas" aria-controls="mainOffcanvas"
                        style="position:absolute; left:12px; top:10px; z-index:2;">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <Link class="navbar-brand m-0 p-0" href="/" style="position:absolute; left:50%; top:8px; transform:translateX(-50%); z-index:1;">
                        <img src="/LOGO_PNG.png" alt="Logo Ática" width="118" height="40" decoding="async" style="height:auto;">
                    </Link>
                    <a class="d-flex align-items-center text-dark" @click.prevent="openCart" style="position:absolute; right:12px; top:12px; z-index:2; cursor:pointer">
                        <i class="fa-solid fa-cart-shopping fs-4"></i>
                        <span class="badge bg-dark text-white ms-2">{{ cartCounter }}</span>
                    </a>

                    <div class="offcanvas offcanvas-start" tabindex="-1" id="mainOffcanvas" aria-labelledby="offcanvasLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasLabel">Menú</h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
                        </div>
                        <div class="offcanvas-body">
                            <form class="d-flex mb-4" method="GET" action="/search">
                                <input class="form-control rounded-0 border-bottom" type="search" placeholder="Buscar" name="q" aria-label="Buscar">
                                <button class="btn ms-2" type="submit"><i class="bi bi-search"></i></button>
                            </form>
                            <ul class="navbar-nav mb-4">
                                <li class="nav-item"><Link class="nav-link active" href="/">Inicio</Link></li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" id="productDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Productos</a>
                                    <ul class="dropdown-menu" aria-labelledby="productDropdown">
                                        <li><Link class="dropdown-item" href="/search?q=SUMMER SALE">SUMMER SALE</Link></li>
                                        <li><Link class="dropdown-item" href="/categorias/bodys-reductores">Bodys reductores</Link></li>
                                        <li><Link class="dropdown-item" href="/categorias/camisetas-reductoras">Camisetas reductoras</Link></li>
                                        <li><Link class="dropdown-item" href="/categorias/fajas-modeladoras">Fajas modeladoras</Link></li>
                                        <li><Link class="dropdown-item" href="/search?q=Todos los productos">Todos los productos</Link></li>
                                    </ul>
                                </li>
                                <li class="nav-item"><Link class="nav-link" href="/guia-de-talles">Guía de talles</Link></li>
                                <li class="nav-item"><Link class="nav-link" href="/faqs">Preguntas frecuentes</Link></li>
                                <li class="nav-item"><a class="nav-link" href="https://wa.link/n5il16" target="_blank" style="font-weight: bold; text-decoration: underline">Necesito ayuda</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
</template>

<style>
.cart-panel-overlay {
    position: fixed; inset: 0; background: rgba(0,0,0,.35); opacity: 0; pointer-events: none;
    transition: opacity .25s ease; z-index: 1040;
}
.cart-panel-overlay.is-open { opacity: 1; pointer-events: auto; }
.cart-panel {
    position: fixed; top: 0; right: -420px; width: 420px; max-width: 100%; height: 100vh;
    background: #ffffff; box-shadow: -4px 0 20px rgba(15,23,42,.25); z-index: 1041;
    display: flex; flex-direction: column; transition: right .35s ease;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
}
.cart-panel.is-open { right: 0; }
.cart-panel-header { padding: 16px 20px; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; }
.cart-panel-body { padding: 16px 20px; overflow-y: auto; flex: 1; }
.cart-panel-footer { padding: 16px 20px 24px; border-top: 1px solid #e5e7eb; background: #ffffff; }
.cart-panel-item { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px; }
.cart-panel-item-img { width: 64px; height: 64px; border-radius: 4px; overflow: hidden; background: #f3f4f6; flex-shrink: 0; }
.cart-panel-item-img img { width: 100%; height: 100%; object-fit: cover; }
.cart-panel-item-info { flex: 1; }
.cart-panel-item-name { font-size: 14px; font-weight: 500; color: #111827; margin-bottom: 4px; }
.cart-panel-item-qty { display: inline-flex; align-items: center; border-radius: 999px; border: 1px solid #d1d5db; overflow: hidden; }
.cart-panel-item-qty button { border: none; background: #f9fafb; padding: 4px 10px; cursor: pointer; font-size: 16px; line-height: 1; }
.cart-panel-item-qty span { padding: 2px 12px; font-size: 14px; }
.cart-panel-item-price { font-size: 14px; font-weight: 600; color: #111827; white-space: nowrap; }
.cart-panel-btn-primary { background: #1f2933; color: #ffffff; border-radius: 999px; padding: 10px 16px; border: none; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: .08em; cursor: pointer; }
.cart-panel-btn-primary:hover { filter: brightness(1.05); }
@media (max-width: 991.98px) { #mainOffcanvas { --bs-offcanvas-width: 80vw; } }
header { transition: transform 0.3s ease-in-out; }
header.nav-up { transform: translateY(-100%); }
@keyframes marquee { 0% { transform: translateX(100%); } 100% { transform: translateX(-100%); } }
.animate-marquee { white-space: nowrap; animation: marquee 20s linear infinite; }
</style>
