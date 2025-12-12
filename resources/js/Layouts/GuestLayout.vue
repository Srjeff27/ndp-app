<script setup>
import Logo from '@/Components/Logo.vue';
import { Link } from '@inertiajs/vue3';
import { GlobeAltIcon } from '@heroicons/vue/24/outline';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-indigo-50 to-slate-100 flex flex-col sm:justify-center items-center px-4 py-12 relative overflow-hidden">
        <!-- Decorative Background -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
            <div class="absolute top-40 right-10 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-1/2 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <Link href="/">
                <Logo size="xl" />
            </Link>
        </div>

        <!-- Card -->
        <div class="relative z-10 w-full max-w-md">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <slot />
            </div>
        </div>

        <!-- Footer -->
        <div class="relative z-10 mt-8 text-center text-sm text-gray-600">
            <p>&copy; {{ new Date().getFullYear() }} NeuroDemocracy Protocol</p>
        </div>
    </div>
</template>

<style scoped>
@keyframes blob {
    0%, 100% {
        transform: translate(0, 0) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}
</style>
