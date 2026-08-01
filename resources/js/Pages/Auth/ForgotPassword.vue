<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    email: '',
})

function submit() {
    form.post(route('password.email'))
}
</script>

<template>
    <div class="min-vh-100 d-flex flex-column align-items-center justify-content-center pt-5 bg-light">
        <div class="mb-4">
            <a href="/">
                <svg class="w-20 h-20" style="width: 5rem; height: 5rem; fill: currentColor; color: #6b7280;" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M20 40C8.954 40 0 31.046 0 20S8.954 0 20 0s20 8.954 20 20-8.954 20-20 20ZM4 20c0 8.837 7.163 16 16 16s16-7.163 16-16S28.837 4 20 4 4 11.163 4 20Z"/>
                </svg>
            </a>
        </div>

        <div class="w-100" style="max-width: 28rem; margin-top: 1.5rem;">
            <div class="bg-white shadow-sm rounded px-4 py-4">
                <div class="mb-3 text-muted small">
                    Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                </div>

                <form @submit.prevent="submit">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" class="form-control" v-model="form.email" required autofocus>
                        <div v-if="form.errors.email" class="text-danger mt-2 small">{{ form.errors.email }}</div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-dark" :disabled="form.processing">
                            Email Password Reset Link
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
