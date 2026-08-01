<script setup>
import { onMounted } from 'vue'

onMounted(() => {
    const promoData = localStorage.getItem('promoModalData')
    const shouldShow = () => {
        if (!promoData) return true
        const { shown, expiresAt } = JSON.parse(promoData)
        return !shown || new Date().getTime() > expiresAt
    }

    setTimeout(() => {
        if (!shouldShow()) return
        const modal = new bootstrap.Modal(document.getElementById('promoModal'))
        modal.show()
        modal._element.addEventListener('hidden.bs.modal', () => {
            const expirationTime = new Date().getTime() + (60 * 60 * 1000)
            localStorage.setItem('promoModalData', JSON.stringify({ shown: true, expiresAt: expirationTime }))
        })
    }, 60000)
})

async function handleSubmit(e) {
    e.preventDefault()
    const form = e.target
    const formData = new FormData(form)

    try {
        const response = await fetch('/mailing-list-contact', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        })

        if (response.ok) {
            form.parentElement.innerHTML = `
                <div class="text-center p-4">
                    <h5 class="text-success">¡Gracias por suscribirte!</h5>
                    <p class="text-muted">Pronto recibirás noticias y beneficios exclusivos.</p>
                </div>
            `
        }
    } catch (error) {
        console.error('Error:', error)
    }
}
</script>

<template>
    <div class="modal fade" id="promoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="popup-image d-none d-md-block"></div>
                <div class="popup-form">
                    <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    <h5 class="mt-2">Atención 📩 Llevate 10% OFF de regalo</h5>
                    <p>Unite para recibirlo</p>
                    <form id="popup_mailing_list_form" @submit="handleSubmit">
                        <input name="email" type="email" class="form-control" placeholder="Email">
                        <input name="name" type="text" class="form-control" placeholder="Cómo te gusta que te llamen">
                        <button id="subscribe_button" class="btn btn-dark w-100">Suscribirme</button>
                    </form>
                    <small class="d-block text-muted mt-2">Recibirás un correo para validar tu email.</small>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.modal-content { display: flex; flex-direction: row; border-radius: 0; max-width: 700px; margin: auto; }
.popup-image { width: 50%; background: url(/popup.jpg) center center / cover no-repeat; min-height: 100%; }
.popup-form { width: 50%; padding: 2rem; }
.form-control { border-radius: 0; margin-bottom: 1rem; }
.btn-dark { border-radius: 0; }
@media (max-width: 768px) {
    .modal-content { flex-direction: column; }
    .popup-image, .popup-form { width: 100%; }
    .popup-image { height: 300px; }
}
</style>
