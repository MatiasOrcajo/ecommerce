<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

function submit() {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <div class="min-vh-100 d-flex flex-column align-items-center justify-content-center pt-5 bg-light">
        <div class="mb-4">
            <Link href="/">
                <svg class="w-20 h-20" style="width: 5rem; height: 5rem; fill: currentColor; color: #6b7280;" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M20 40C8.954 40 0 31.046 0 20S8.954 0 20 0s20 8.954 20 20-8.954 20-20 20ZM4 20c0 8.837 7.163 16 16 16s16-7.163 16-16S28.837 4 20 4 4 11.163 4 20Z"/>
                </svg>
            </Link>
        </div>

        <div class="w-100" style="max-width: 28rem; margin-top: 1.5rem;">
            <div class="bg-white shadow-sm rounded px-4 py-4">
                <div v-if="form.errors.email || form.errors.password" class="mb-3">
                    <div v-if="form.errors.email" class="alert alert-danger">{{ form.errors.email }}</div>
                    <div v-if="form.errors.password" class="alert alert-danger">{{ form.errors.password }}</div>
                </div>

                <form @submit.prevent="submit">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" class="form-control" v-model="form.email" required autofocus autocomplete="username">
                        <div v-if="form.errors.email" class="text-danger mt-2 small">{{ form.errors.email }}</div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" class="form-control" v-model="form.password" required autocomplete="current-password">
                        <div v-if="form.errors.password" class="text-danger mt-2 small">{{ form.errors.password }}</div>
                    </div>

                    <div class="mb-3 form-check">
                        <input id="remember_me" type="checkbox" class="form-check-input" v-model="form.remember">
                        <label for="remember_me" class="form-check-label">Remember me</label>
                    </div>

                    <div class="d-flex align-items-center justify-content-between">
                        <Link v-if="$page.props?.hasPasswordRequest !== false" href="/forgot-password" class="text-decoration-none small text-muted">
                            Forgot your password?
                        </Link>

                        <button type="submit" class="btn btn-dark ms-auto" :disabled="form.processing">
                            Log in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
