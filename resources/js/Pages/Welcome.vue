<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
    GlobeAltIcon, 
    ChartBarIcon, 
    ChatBubbleLeftRightIcon, 
    BeakerIcon,
    ArrowRightIcon,
    AcademicCapIcon,
    ShieldCheckIcon,
    SparklesIcon,
    Bars3Icon,
    XMarkIcon
} from '@heroicons/vue/24/outline';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

const showingNavigationDropdown = ref(false);
const isLoaded = ref(false);

const features = [
    {
        name: 'Node Pemerintahan',
        description: 'Petakan dan lacak akuntabilitas di seluruh institusi di seluruh dunia dengan visualisasi interaktif.',
        icon: GlobeAltIcon,
        color: 'text-blue-500',
        bg: 'bg-blue-500/10',
        border: 'group-hover:border-blue-500/50'
    },
    {
        name: 'Atlas Pemerintahan',
        description: 'Indikator dan metrik komprehensif untuk mengukur akuntabilitas publik.',
        icon: ChartBarIcon,
        color: 'text-emerald-500',
        bg: 'bg-emerald-500/10',
        border: 'group-hover:border-emerald-500/50'
    },
    {
        name: 'Lab Sipil',
        description: 'Ruang kolaboratif untuk diskusi kebijakan dan deliberasi demokratis.',
        icon: ChatBubbleLeftRightIcon,
        color: 'text-amber-500',
        bg: 'bg-amber-500/10',
        border: 'group-hover:border-amber-500/50'
    },
    {
        name: 'Mesin Simulasi',
        description: 'Modelkan perubahan kebijakan dan prediksi dampaknya terhadap indikator pemerintahan.',
        icon: BeakerIcon,
        color: 'text-rose-500',
        bg: 'bg-rose-500/10',
        border: 'group-hover:border-rose-500/50'
    }
];

const benefits = [
    { icon: AcademicCapIcon, text: 'Metodologi Berbasis Riset' },
    { icon: ShieldCheckIcon, text: 'Transparan & Akuntabel' },
    { icon: SparklesIcon, text: 'Wawasan Berbasis Data' }
];

onMounted(() => {
    setTimeout(() => {
        isLoaded.value = true;
    }, 100);
});
</script>

<template>
    <Head title="Democracy Protocol Policy" />

    <div class="min-h-screen bg-slate-50 text-slate-900 selection:bg-indigo-500 selection:text-white font-sans overflow-x-hidden">
        
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 contrast-150"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-slate-50/50 to-slate-50"></div>
            
            <div class="absolute top-0 -left-4 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-32 left-20 w-96 h-96 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <nav class="fixed w-full z-50 border-b border-slate-200/60 bg-white/70 backdrop-blur-md transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center space-x-3 cursor-pointer group">
                        <div class="relative w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-300">
                            <GlobeAltIcon class="w-6 h-6 text-white" />
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold tracking-tight text-slate-900">
                                DPP
                            </span>
                            <span class="text-[10px] uppercase tracking-widest font-semibold text-indigo-600">
                                Democracy Protocol Policy
                            </span>
                        </div>
                    </div>

                    <div class="hidden md:flex items-center space-x-8">
                        <template v-if="canLogin">
                            <Link :href="route('login')" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">
                                Masuk
                            </Link>
                            <Link v-if="canRegister" :href="route('register')" class="group relative inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold text-white transition-all duration-200 bg-slate-900 rounded-full hover:bg-indigo-600 hover:shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                                <span>Mulai</span>
                                <ArrowRightIcon class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" />
                            </Link>
                        </template>
                    </div>

                    <div class="flex items-center md:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none transition duration-150 ease-in-out">
                            <Bars3Icon v-if="!showingNavigationDropdown" class="h-6 w-6" />
                            <XMarkIcon v-else class="h-6 w-6" />
                        </button>
                    </div>
                </div>
            </div>

            <transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-100 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
            >
                <div v-show="showingNavigationDropdown" class="md:hidden border-t border-slate-200 bg-white/90 backdrop-blur-xl">
                    <div class="pt-4 pb-6 px-4 space-y-3">
                        <Link :href="route('login')" class="block w-full text-center py-3 text-slate-600 font-medium hover:bg-slate-50 rounded-lg">
                            Masuk
                        </Link>
                        <Link v-if="canRegister" :href="route('register')" class="block w-full text-center py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:bg-indigo-700">
                            Daftar Sekarang
                        </Link>
                    </div>
                </div>
            </transition>
        </nav>

        <main class="relative z-10 pt-32 pb-16">
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-4xl mx-auto">
                    <div 
                        class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium leading-6 text-indigo-600 ring-1 ring-inset ring-indigo-600/20 bg-indigo-50/50 mb-8 transition-all duration-700 transform"
                        :class="isLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    >
                        <span>Platform Terverifikasi Akademis</span>
                    </div>

                    <h1 
                        class="text-5xl sm:text-6xl lg:text-7xl font-bold tracking-tight text-slate-900 mb-8 transition-all duration-700 delay-100 transform"
                        :class="isLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                    >
                        Platform Pengawasan Kebijakan <br class="hidden sm:block" />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 animate-gradient-x">
                            Pemerintah Berbasis AI
                        </span>
                    </h1>

                    <p 
                        class="text-lg sm:text-xl text-slate-600 mb-10 leading-relaxed max-w-2xl mx-auto transition-all duration-700 delay-200 transform"
                        :class="isLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                    >
                        Platform kolaboratif yang memanfaatkan struktur data canggih untuk melacak, menganalisis, dan meningkatkan tata kelola pemerintahan melalui wawasan yang ketat dan keterlibatan sipil.
                    </p>

                    <div 
                        class="flex flex-col sm:flex-row gap-4 justify-center items-center transition-all duration-700 delay-300 transform"
                        :class="isLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                    >
                        <Link v-if="canRegister" :href="route('register')" class="w-full sm:w-auto px-8 py-4 bg-slate-900 text-white rounded-xl font-bold text-lg hover:shadow-2xl hover:shadow-indigo-500/20 hover:-translate-y-1 transition-all duration-300">
                            Mulai Jelajahi
                        </Link>
                        <a href="#features" class="w-full sm:w-auto px-8 py-4 bg-white border border-slate-200 text-slate-700 rounded-xl font-bold text-lg hover:bg-slate-50 hover:border-slate-300 transition-all duration-300">
                            Pelajari Metodologi
                        </a>
                    </div>

                    <div 
                        class="mt-12 flex flex-wrap justify-center gap-4 sm:gap-8 transition-all duration-700 delay-500 transform"
                        :class="isLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                    >
                        <div v-for="benefit in benefits" :key="benefit.text" class="flex items-center space-x-2 text-slate-600">
                            <component :is="benefit.icon" class="w-5 h-5 text-indigo-500" />
                            <span class="text-sm font-medium">{{ benefit.text }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div id="features" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div 
                        v-for="(feature, index) in features" 
                        :key="feature.name"
                        class="group relative p-8 bg-white/50 backdrop-blur-sm border border-slate-200 rounded-3xl hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-500 hover:-translate-y-2 overflow-hidden"
                        :class="feature.border"
                    >
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-current to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500" :class="feature.color"></div>
                        
                        <div class="relative z-10">
                            <div class="inline-flex p-3 rounded-2xl mb-6 transition-colors duration-300" :class="[feature.bg, feature.color]">
                                <component :is="feature.icon" class="w-8 h-8" />
                            </div>
                            
                            <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors">
                                {{ feature.name }}
                            </h3>
                            
                            <p class="text-slate-600 leading-relaxed text-sm">
                                {{ feature.description }}
                            </p>
                        </div>

                        <component 
                            :is="feature.icon" 
                            class="absolute -bottom-4 -right-4 w-32 h-32 opacity-[0.03] transform rotate-12 group-hover:scale-110 transition-transform duration-700 pointer-events-none" 
                            :class="feature.color"
                        />
                    </div>
                </div>
            </div>
        </main>

        <footer class="relative z-10 border-t border-slate-200 bg-white/50 backdrop-blur-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center space-x-2">
                    <GlobeAltIcon class="w-5 h-5 text-indigo-500" />
                    <span class="text-sm font-semibold text-slate-900">Democracy Protocol Policy</span>
                </div>
                
                <div class="text-sm text-slate-500">
                    &copy; {{ new Date().getFullYear() }} Inisiatif Riset DPP.
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* Custom Animations not in Tailwind Default */
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}

@keyframes gradient-x {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animate-gradient-x {
    background-size: 200% 200%;
    animation: gradient-x 15s ease infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}
</style>