<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue'
import { router } from '@inertiajs/vue3'
import Navbar from '@/Layouts/Navbar.vue'
import Footer from '@/Layouts/Footer.vue'
import Popup from '@/Layouts/Popup.vue'
import Reviews from '@/Layouts/Reviews.vue'

const props = defineProps({
    showReviews: { type: Boolean, default: false },
    showFooter: { type: Boolean, default: true },
    title: { type: String, default: 'Atica' },
    bodyClass: { type: String, default: '' },
    bodyPaddingTop: { type: String, default: '120px' },
})

const footerMarginTop = ref('2rem')

onMounted(() => {
    document.title = props.title

    if (window.location.pathname === '/cart' || window.location.pathname.includes('/orden/')) {
        footerMarginTop.value = '22rem'
    }

    const style = document.createElement('style')
    style.id = 'body-padding-style'
    style.textContent = `body { padding-top: ${props.bodyPaddingTop}; } @media (max-width: 991.98px) { body { padding-top: 72px; } }`
    document.head.appendChild(style)
})

onUnmounted(() => {
    const style = document.getElementById('body-padding-style')
    if (style) style.remove()
})
</script>

<template>
    <div class="d-flex flex-column min-vh-100">
        <Navbar />
        <Popup />

        <main class="flex-grow-1">
            <slot />
        </main>

        <Reviews v-if="showReviews" />

        <Footer v-if="showFooter" :margin-top="footerMarginTop" />
    </div>
</template>
