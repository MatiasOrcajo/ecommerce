<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    category: Object,
    products: Array,
})

function formatMoneyAR(value) {
    const nf = new Intl.NumberFormat('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
    return `$${nf.format(Number(value))}`
}

function buildVariants(colors) {
    const colorNames = colors.names
    return colorNames.map((_, idx) => {
        const entry = colors[idx]
        const code = Object.keys(entry)[0]
        const pics = entry[code][0]
        return { colorCode: code, pics }
    })
}

function getSwatchesHtml(variants, colorNames) {
    let swatches = ''
    variants.forEach((v, i) => {
        if (!colorNames[i].includes('PACK') && !colorNames[i].includes('Pack')) {
            swatches += `
                <div
                    class="color-box mx-1"
                    data-variant-index="${i}"
                    style="width:24px; height:24px; background:${v.colorCode}; border:1px solid #ccc; cursor:pointer;"
                    title="${colorNames[i]}"
                ></div>
            `
        }
    })
    return swatches
}
</script>

<template>
    <AppLayout :show-reviews="true" :show-footer="true" :title="category.name + ' - Atica'">
        <div class="mt-5 py-5" id="destacados">
            <h2 class="d-block mt-5 text-center" style="font-size: 4rem">{{ category.name }}.</h2>
        </div>

        <div v-if="!products || !products.length" class="text-center my-5">
            <h1 class="display-4 text-muted">No se encontraron resultados para su busqueda.</h1>
        </div>

        <div v-else id="products-container" class="row row-cols-1 row-cols-lg-3 g-4 mx-3 justify-content-center mb-5">
            <div v-for="(item, index) in products" :key="index" class="col">
                <div class="card border-0 p-0 h-100">
                    <div class="ratio image-container" style="--bs-aspect-ratio:120%;">
                        <img
                            :src="buildVariants(item.colors)[0].pics[0]"
                            class="card-img-top img-first"
                            :alt="item.product.name"
                        >
                        <img
                            :src="buildVariants(item.colors)[0].pics[1] || buildVariants(item.colors)[0].pics[0]"
                            class="card-img-top img-second"
                            :alt="item.product.name + ' - Hover'"
                        >

                        <button v-if="item.product.discount != null" class="btn position-absolute top-0 end-0 m-2" style="background-color: #724d3a; color: white;">
                            {{ item.product.discount }}% OFF
                        </button>

                        <Link :href="'/productos/' + item.product.slug">
                            <button class="btn btn-light position-absolute bottom-0 end-0 m-2">
                                <i class="fas fa-shopping-bag"></i>
                            </button>
                        </Link>
                    </div>
                    <div class="card-body d-flex flex-column position-relative">
                        <div class="d-flex justify-content-center mb-3">
                            <div class="color-box-parent d-flex justify-content-center align-items-center" v-html="getSwatchesHtml(buildVariants(item.colors), item.colors.names)"></div>
                        </div>
                        <h5 class="card-title text-center mb-2">{{ item.product.name }}</h5>

                        <p v-if="item.product.discount" class="text-center mb-1 fw-bold">
                            <del>{{ formatMoneyAR(item.product.price) }}</del>
                            {{ formatMoneyAR((item.product.price * (1 - item.product.discount / 100)).toFixed(2)) }}
                        </p>
                        <p v-else class="text-center mb-1 fw-bold">{{ formatMoneyAR(item.product.price) }}</p>

                        <p class="text-center mb-2 text-muted">
                            {{ formatMoneyAR(((item.product.discount ? item.product.price * (1 - item.product.discount / 100) : item.product.price) * 0.9).toFixed(2)) }} con Transferencia bancaria
                        </p>
                        <Link :href="'/productos/' + item.product.slug" class="btn btn-white border-black w-25 mx-auto mt-auto d-block">
                            Ver
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
.image-container {
    position: relative;
    overflow: hidden;
}
.image-container img {
    display: block;
    width: 100%;
    height: 100%;
    transition: opacity 0.5s ease-in-out;
    object-fit: cover;
}
.image-container .img-second {
    position: absolute;
    top: 0;
    left: 0;
    opacity: 0;
}
.image-container:hover .img-first {
    opacity: 0;
}
.image-container:hover .img-second {
    opacity: 1;
}
.color-box {
    width: 1rem;
    height: 1rem;
    background-color: #000;
    border-radius: 100%;
    margin: 0 0.25rem;
    display: inline-block;
}
.color-box-parent {
    position: absolute;
    top: -10%;
    left: 50%;
    z-index: 99;
    width: fit-content;
    padding: 8px 10px;
    transform: translateX(-50%);
    border-radius: 32px;
    background-color: #fff;
}
.btn-white.border-black {
    background-color: #fff;
    color: #000;
    border: 1px solid #000;
    transition: background-color 0.2s ease, color 0.2s ease;
}
.btn-white.border-black:hover {
    background-color: #000;
    color: #fff;
}
</style>
