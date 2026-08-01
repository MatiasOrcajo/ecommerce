<script setup>
import { onMounted } from 'vue'

onMounted(() => {
    new Swiper('.customers-swiper', {
        slidesPerView: 1,
        spaceBetween: 10,
        loop: true,
        autoplay: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        },
        scrollbar: {
            el: '.swiper-scrollbar',
        },
        breakpoints: {
            576: { slidesPerView: 2 },
            992: { slidesPerView: 3, autoplay: false }
        }
    })
})
</script>

<template>
    <section>
        <div id="destacados" style="margin-bottom: 3rem">
            <h2 class="d-block mt-5 text-center" style="font-size: 2rem; font-weight: bold">Lo que dicen nuestras clientas 💞</h2>
        </div>
        <div class="swiper customers-swiper mx-3">
            <div class="swiper-wrapper pb-3 justify-content-md-center" id="customers-container">
                <div class="swiper-slide"><img src="/testimonial/1.jpg" alt="Testimonial 1" class="img-fluid"></div>
                <div class="swiper-slide"><img src="/testimonial/2.jpg" alt="Testimonial 2" class="img-fluid"></div>
                <div class="swiper-slide"><img src="/testimonial/3.jpg" alt="Testimonial 3" class="img-fluid"></div>
                <div class="swiper-slide"><img src="/testimonial/4.jpg" alt="Testimonial 4" class="img-fluid"></div>
                <div class="swiper-slide"><img src="/testimonial/5.jpg" alt="Testimonial 5" class="img-fluid"></div>
                <div class="swiper-slide"><img src="/testimonial/6.jpg" alt="Testimonial 6" class="img-fluid"></div>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-scrollbar"></div>
        </div>
    </section>
</template>
