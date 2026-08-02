<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const featuredProducts = ref([])
const mainProducts = ref([])
let productsSwiper = null

function formatMoneyAR(value) {
    const nf = new Intl.NumberFormat('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
    return `$${nf.format(Number(value))}`
}

function renderProductCard(product, colors, isFeatured = false) {
    const variants = colors.map(c => ({
        colorCode: c.hex,
        colorName: c.name,
        pics: c.paths,
    }))

    const firstImg = variants[0].pics[0]
    const secondImg = variants[0].pics[1] || variants[0].pics[0]

    let swatches = ''
    variants.forEach((v, i) => {
        if (!v.colorName.includes('PACK') && !v.colorName.includes('Pack')) {
            swatches += `<div class="color-box mx-1" data-variant-index="${i}" style="background:${v.colorCode};" title="${v.colorName}"></div>`
        }
    })

    const productPrice = product.discount ? product.price * (1 - product.discount / 100) : product.price
    const priceWithTransfer = (productPrice * 0.9).toFixed(2)

    return {
        html: `
            <div class="${isFeatured ? 'swiper-slide' : 'col'}">
                <div class="card border-0 p-0 ${isFeatured ? 'h-50' : 'h-100'}">
                    <div class="ratio image-container" style="--bs-aspect-ratio:120%;" data-variants='${JSON.stringify(variants)}'>
                        <img src="${firstImg}" class="card-img-top img-first" alt="${product.name}">
                        <img src="${secondImg}" class="card-img-top img-second" alt="${product.name} - Hover">
                        ${product.discount != null ? `<button class="btn position-absolute top-0 end-0 m-2" style="background-color: #724d3a; color: white;">${product.discount}% OFF</button>` : ''}
                        <a href="/productos/${product.slug}">
                            <button class="btn btn-light position-absolute bottom-0 end-0 m-2"><i class="fas fa-shopping-bag"></i></button>
                        </a>
                    </div>
                    <div class="card-body d-flex flex-column position-relative">
                        <div class="d-flex justify-content-center mb-3">
                            <div class="color-box-parent d-flex justify-content-center align-items-center">${swatches}</div>
                        </div>
                        <h5 class="card-title text-center mb-2">${product.name}</h5>
                        ${product.discount
                            ? `<p class="text-center mb-1 fw-bold"><del>${formatMoneyAR(product.price)}</del> ${formatMoneyAR((product.price * (1 - product.discount / 100)).toFixed(2))}</p>`
                            : `<p class="text-center mb-1 fw-bold">${formatMoneyAR(product.price)}</p>`
                        }
                        <p class="text-center mb-2 text-muted">${formatMoneyAR(priceWithTransfer)} con Transferencia bancaria</p>
                        <a href="/productos/${product.slug}" class="btn btn-white border-black w-25 mx-auto mt-auto d-block">Ver</a>
                    </div>
                </div>
            </div>
        `,
        variants
    }
}

function bindSwatchEvents(container) {
    container.querySelectorAll('.image-container').forEach(imgContainer => {
        const variants = JSON.parse(imgContainer.dataset.variants)
        const imgFirst = imgContainer.querySelector('.img-first')
        const imgSecond = imgContainer.querySelector('.img-second')
        const card = imgContainer.closest('.card')

        card.querySelectorAll('.color-box').forEach(box => {
            box.addEventListener('click', () => {
                const i = parseInt(box.dataset.variantIndex, 10)
                const pics = variants[i].pics || []
                if (!pics.length) return
                imgFirst.src = pics[0]
                imgSecond.src = pics[1] || pics[0]
            })
        })
    })
}

async function loadFeaturedProducts() {
    try {
        const res = await fetch('/featured-products')
        const data = await res.json()
        const container = document.getElementById('products-container')
        let html = ''
        const sortedData = Object.values(data).sort((a, b) => b.product.price - a.product.price)

        sortedData.forEach(item => {
            const card = renderProductCard(item.product, item.colors, true)
            html += card.html
        })

        container.innerHTML = html
        if (productsSwiper) {
            productsSwiper.update()
            productsSwiper.slideTo(0)
        }
        nextTick(() => bindSwatchEvents(container))
    } catch (e) {
        console.error(e)
    }
}

async function loadMainProducts() {
    try {
        const res = await fetch('/main-products')
        const data = await res.json()
        const container = document.getElementById('main-products-container')
        let html = ''
        const sortedData = Object.values(data).sort((a, b) => b.product.price - a.product.price)

        sortedData.forEach(item => {
            const card = renderProductCard(item.product, item.colors, false)
            html += card.html
        })

        container.innerHTML = html
        nextTick(() => bindSwatchEvents(container))
    } catch (e) {
        console.error(e)
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

onMounted(() => {
    productsSwiper = new Swiper('.products-swiper', {
        slidesPerView: 1, spaceBetween: 20, loop: true,
        scrollbar: { el: '.swiper-scrollbar' },
        breakpoints: {
            576: { slidesPerView: 2 },
            992: { slidesPerView: 3 },
            1200: { slidesPerView: 4, scrollbar: false }
        }
    })

    loadFeaturedProducts()
    loadMainProducts()
    updateCartCounter()
})
</script>

<template>
    <AppLayout :show-reviews="true" :show-footer="true" title="Atica">
        <div class="container-fluid mt-4 px-0 d-none d-lg-block">
            <a href="/search?q=SUMMER SALE">
                <img src="/banner.png" alt="Banner principal" width="1920" height="960" fetchpriority="high" decoding="async" class="img-fluid w-100">
            </a>
        </div>
        <div class="container-fluid mt-4 px-0 d-block d-lg-none">
            <a href="/search?q=Todos los productos">
                <img src="/banner_mobile.png" alt="Banner principal móvil" width="608" height="1080" fetchpriority="high" decoding="async" class="img-fluid w-100">
            </a>
        </div>

        <div class="d-none d-lg-flex row justify-content-center align-items-center bg-black text-white" style="padding: 3rem 0">
            <div class="col-lg-4 d-flex justify-content-center align-items-center border-end flex-column text-center">
                <div class="mb-3"><i style="font-size: 2rem; color: white;" class="fa-solid fa-truck"></i></div>
                <div><b><h3 class="mb-2" style="font-size: 1.5rem">ENVÍOS A TODO EL PAÍS</h3><p>Por Correo Argentino o motomensajería</p></b></div>
            </div>
            <div class="col-lg-4 d-flex justify-content-center align-items-center border-end flex-column text-center">
                <div class="mb-3"><i style="font-size: 2rem; color: white;" class="fa-solid fa-cart-shopping"></i></div>
                <div><b><h3 class="mb-2" style="font-size: 1.5rem">ENVÍOS RÁPIDOS</h3><p>Tu pedido llega de 1 a 5 días hábiles</p></b></div>
            </div>
            <div class="col-lg-4 d-flex justify-content-center align-items-center flex-column text-center">
                <div class="mb-3"><i style="font-size: 2rem; color: white;" class="fa-solid fa-shop"></i></div>
                <div><b><h3 class="mb-2" style="font-size: 1.5rem">PUNTO DE RETIRO</h3><p>Retirá tu compra en Monserrat, CABA</p></b></div>
            </div>
        </div>

        <div class="d-block d-lg-none bg-black text-white" style="padding: 3rem 0">
            <div id="mobileInfoSlider" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="col-12 my-4 d-flex justify-content-center align-items-center flex-column text-center">
                            <div class="mb-3"><i style="font-size: 2rem; color: white;" class="mb-2 mt-3 fa-solid fa-truck"></i></div>
                            <div><b><h3 class="mb-2" style="font-size: 1.5rem">ENVÍOS A TODO EL PAÍS</h3><p>Por Correo Argentino o motomensajería</p></b></div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-12 my-4 d-flex justify-content-center align-items-center flex-column text-center">
                            <div class="mb-3"><i style="font-size: 2rem; color: white;" class="mb-2 mt-3 fa-solid fa-cart-shopping"></i></div>
                            <div><b><h3 class="mb-2" style="font-size: 1.5rem">ENVÍOS RÁPIDOS</h3><p>Tu pedido llega de 1 a 5 días hábiles</p></b></div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-12 my-4 d-flex justify-content-center align-items-center flex-column text-center">
                            <div class="mb-3"><i style="font-size: 2rem; color: white;" class="mb-2 mt-3 fa-solid fa-shop"></i></div>
                            <div><b><h3 class="mb-2" style="font-size: 1.5rem">PUNTO DE RETIRO</h3><p>Retirá tu compra en Monserrat, CABA</p></b></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section>
            <div class="container-fluid my-md-5 py-md-5 px-0">
                <div class="categories-container">
                    <div class="row g-0 d-none d-lg-flex justify-content-center">
                        <div class="col-auto">
                            <div class="row g-0">
                                <div class="col-3 position-relative category-square m-2">
                                    <a href="/search?q=SUMMER SALE">
                                        <img src="/50_off.jpg" alt="Categoría 1" class="category-img">
                                        <div class="category-content"></div>
                                    </a>
                                </div>
                                <div class="col-3 position-relative category-square m-2">
                                    <a href="/categorias/bodys-reductores">
                                        <img src="/bodys_moldeadores.jpg" alt="Categoría 2" class="category-img">
                                        <div class="category-content"></div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="row g-0">
                                <div class="col-3 position-relative category-square m-2">
                                    <a href="/categorias/fajas-modeladoras">
                                        <img src="/trusas_moldeadoras.jpg" alt="Categoría 3" class="category-img">
                                        <div class="category-content"></div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column d-lg-none">
                        <div class="position-relative category-square">
                            <a href="/search?q=SUMMER SALE">
                                <img src="/50_off.jpg" alt="Categoría 1" class="category-img">
                                <div class="category-content"></div>
                            </a>
                        </div>
                        <div class="position-relative category-square">
                            <a href="/categorias/bodys-reductores">
                                <img src="/bodys_moldeadores.jpg" alt="Categoría 2" class="category-img">
                                <div class="category-content"></div>
                            </a>
                        </div>
                        <div class="position-relative category-square">
                            <a href="/categorias/fajas-modeladoras">
                                <img src="/trusas_moldeadoras.jpg" alt="Categoría 3" class="category-img">
                                <div class="category-content"></div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="mt-5" id="destacados" style="margin-top: 7rem; margin-bottom: 3rem">
                <h2 class="d-block mt-5 text-center" style="font-size: 2rem; font-weight: bold">Los más pedidos ❤️</h2>
            </div>
            <div class="swiper products-swiper mx-3">
                <div class="swiper-wrapper pb-3 justify-content-md-center" id="products-container"></div>
                <div class="swiper-pagination"></div>
                <div class="swiper-scrollbar"></div>
            </div>
        </section>

        <section>
            <div class="announcement-bar mt-5">
                <div class="announcement-container">
                    <div class="announcement-track">
                        <div class="announcement-item">
                            <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                            <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                            <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                            <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                            <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                            <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                            <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                            <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                        </div>
                        <div class="announcement-item">
                            <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                            <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                            <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                            <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                            <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                            <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                            <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                            <span class="announcement-text">🔥 50% OFF EN TODA LA WEB</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="mt-5" style="margin-top: 7rem; margin-bottom: 3rem">
                <h2 class="d-block mt-5 text-center" style="font-size: 2rem; font-weight: bold">¡Los infaltables para modelar tu figura!</h2>
            </div>
            <div id="main-products-container" class="row row-cols-1 row-cols-lg-2 g-4 justify-content-center col-lg-8 col-12 mx-auto"></div>
        </section>
    </AppLayout>
</template>

<style>
@media (max-width: 991.98px) { body { padding-top: 60px; } }
.image-container { position: relative; overflow: hidden; }
.image-container img { display: block; width: 100%; height: 100%; transition: opacity 0.5s ease-in-out; object-fit: cover; }
.image-container .img-second { position: absolute; top: 0; left: 0; opacity: 0; }
.image-container:hover .img-first { opacity: 0; }
.image-container:hover .img-second { opacity: 1; }
.color-box { width: 24px; height: 24px; border-radius: 100%; margin: 0 0.25rem; border: 1px solid #ccc; cursor: pointer; display: inline-block; }
.color-box-parent { position: absolute; top: -10%; left: 50%; z-index: 99; width: fit-content; padding: 8px 10px; transform: translateX(-50%); border-radius: 32px; background-color: #fff; }
.categories-container { width: 100%; }
@media (min-width: 992px) { .categories-container { max-width: 100%; margin: 0 auto; } .category-square { width: 400px; height: auto; } }
.category-square { aspect-ratio: 1 / 1; overflow: hidden; cursor: pointer; transition: all 0.3s ease; }
.category-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
.category-square:hover .category-img { transform: scale(1.05); }
.category-content { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); z-index: 2; text-align: center; width: 100%; padding: 0 1rem; }
.announcement-bar { background-color: black; overflow: hidden; padding: 12px 0; position: relative; }
.announcement-container { width: 100%; overflow: hidden; }
.announcement-track { display: flex; white-space: nowrap; animation: scrollLeft 10s linear infinite; }
.announcement-item { display: flex; flex-shrink: 0; }
.announcement-text { display: inline-block; color: white; font-weight: bold; font-size: 18px; margin: 0 25px; padding: 5px 15px; text-transform: uppercase; letter-spacing: 0.5px; text-shadow: 1px 1px 2px rgba(0,0,0,.3); }
@keyframes scrollLeft { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
.announcement-bar:hover .announcement-track { animation-play-state: paused; }
@media (max-width: 768px) { .announcement-text { font-size: 16px; margin: 0 15px; padding: 3px 10px; } }
@media (max-width: 480px) { .announcement-text { font-size: 14px; margin: 0 10px; padding: 2px 8px; } }
.btn-white.border-black { background-color: #fff; color: #000; border: 1px solid #000; transition: background-color .2s ease, color .2s ease; }
.btn-white.border-black:hover { background-color: #000; color: #fff; }
</style>
