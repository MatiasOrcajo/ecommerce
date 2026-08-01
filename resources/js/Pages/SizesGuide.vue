<script setup>
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const activeModal = ref(null)

function openModal(index) {
    activeModal.value = index
}

function closeModal() {
    activeModal.value = null
}
</script>

<template>
    <AppLayout :show-reviews="false" :show-footer="true" title="Guia de talles - Atica">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <h1 style="font-size: 2rem" class="my-5">Ante cualquier duda, contactanos por WhatsApp!</h1>

                    <img src="/guia-talles/1.jpg" alt="" class="w-100 h-auto my-3" style="cursor: pointer" @click="openModal(1)">
                    <img src="/guia-talles/2.jpg" alt="" class="w-100 h-auto my-3" style="cursor: pointer" @click="openModal(2)">
                    <img src="/guia-talles/3.jpg" alt="" class="w-100 h-auto my-3" style="cursor: pointer" @click="openModal(3)">

                    <div v-if="activeModal" class="modal fade show d-block" tabindex="-1" aria-hidden="true" style="background-color: rgba(0,0,0,0.5);">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content bg-transparent border-0" @click.stop>
                                <div class="modal-body p-0 position-relative">
                                    <img :src="'/guia-talles/' + activeModal + '.jpg'" alt="" class="w-100 h-auto" style="max-height: 90vh; object-fit: contain;">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" style="filter: invert(1);" @click="closeModal"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
