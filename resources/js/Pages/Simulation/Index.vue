<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import ChartComponent from '@/Components/ChartComponent.vue';
import { computed, ref } from 'vue';
import {
    BeakerIcon,
    ChartBarIcon,
    ArrowTrendingUpIcon,
    ArrowTrendingDownIcon,
    BuildingLibraryIcon,
    AdjustmentsHorizontalIcon,
    PlayIcon,
    ArrowPathIcon,
    CalculatorIcon,
    ScaleIcon,
    CheckCircleIcon,
    XCircleIcon,
    MapPinIcon,
    ExclamationTriangleIcon,
    SparklesIcon,
    FunnelIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    nodes: Array,
    policies: Array,
});

const page = usePage();
const result = computed(() => page.props.flash.result);

// Tab state
const activeTab = ref('simulation');

// Simulation form
const form = useForm({
    node_id: '',
    indicator: '',
    old_score: 0,
    change_percent: 0
});

const selectedNode = computed(() => {
    if (!form.node_id) return null;
    return props.nodes.find(n => n.id === parseInt(form.node_id));
});

const availableIndicators = computed(() => {
    return selectedNode.value ? selectedNode.value.atlas_entries : [];
});

const onIndicatorChange = () => {
    const entry = availableIndicators.value.find(e => e.indicator === form.indicator);
    if(entry) {
        form.old_score = entry.score;
    }
};

const submit = () => {
    form.post(route('simulation.store'), {
        preserveScroll: true,
    });
};

const chartData = computed(() => {
    if (!result.value) return null;
    return {
        labels: ['Baseline Score', 'Projected Score'],
        datasets: [{
            label: result.value.indicator,
            data: [result.value.old_score, result.value.new_score],
            backgroundColor: [
                'rgba(148, 163, 184, 0.5)',
                result.value.change_percent > 0 ? 'rgba(16, 185, 129, 0.8)' : 'rgba(244, 63, 94, 0.8)'
            ],
            borderColor: [
                'rgb(148, 163, 184)',
                result.value.change_percent > 0 ? 'rgb(16, 185, 129)' : 'rgb(244, 63, 94)'
            ],
            borderWidth: 2,
            borderRadius: 8,
            barThickness: 60
        }]
    };
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: '#1e293b',
            titleFont: { family: 'ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace' },
            bodyFont: { family: 'ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace' }
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            max: 100,
            grid: { color: 'rgba(148, 163, 184, 0.1)' },
            ticks: { font: { family: 'ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace' } }
        },
        x: {
            grid: { display: false },
            ticks: { font: { weight: 'bold' } }
        }
    }
};

// Results filter
const selectedResultNode = ref('');

const filteredPolicies = computed(() => {
    if (!selectedResultNode.value) return props.policies;
    return props.policies.filter(p => p.node_id === parseInt(selectedResultNode.value));
});

const approvedPolicies = computed(() => filteredPolicies.value.filter(p => p.is_approved));
const rejectedPolicies = computed(() => filteredPolicies.value.filter(p => !p.is_approved));
</script>

<template>
    <Head title="Simulasi AI" />

    <AuthenticatedLayout>
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-gradient-to-br from-maroon-50 via-white to-gold-50"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-maroon-500/5 rounded-full blur-[100px]"></div>
        </div>

        <template #header>
            <div class="relative z-10 flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-maroon-100 to-maroon-200 rounded-xl border border-maroon-300">
                    <ScaleIcon class="w-8 h-8 text-maroon-700" />
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-maroon-900 tracking-tight">⚖️ Simulasi AI</h2>
                    <p class="text-sm text-maroon-600">Analisis dampak kebijakan berbasis kecerdasan buatan.</p>
                </div>
            </div>
        </template>

        <div class="relative z-10 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Tab Navigation -->
                <div class="mb-8 bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-200 p-2">
                    <div class="flex space-x-2">
                        <button
                            @click="activeTab = 'simulation'"
                            :class="[
                                'flex-1 flex items-center justify-center px-6 py-3 rounded-xl font-semibold text-sm transition-all',
                                activeTab === 'simulation' 
                                    ? 'bg-gradient-to-r from-maroon-700 to-maroon-800 text-white shadow-lg' 
                                    : 'text-slate-600 hover:bg-slate-100'
                            ]"
                        >
                            <BeakerIcon class="w-5 h-5 mr-2" />
                            Simulasi Kebijakan
                        </button>
                        <button
                            @click="activeTab = 'results'"
                            :class="[
                                'flex-1 flex items-center justify-center px-6 py-3 rounded-xl font-semibold text-sm transition-all',
                                activeTab === 'results' 
                                    ? 'bg-gradient-to-r from-maroon-700 to-maroon-800 text-white shadow-lg' 
                                    : 'text-slate-600 hover:bg-slate-100'
                            ]"
                        >
                            <ChartBarIcon class="w-5 h-5 mr-2" />
                            Hasil Kebijakan
                            <span class="ml-2 px-2 py-0.5 text-xs rounded-full" :class="activeTab === 'results' ? 'bg-white/20' : 'bg-slate-200'">
                                {{ policies.length }}
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Simulation Tab Content -->
                <div v-show="activeTab === 'simulation'" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    <div class="lg:col-span-4 space-y-6">
                        <!-- Info Card -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-4 border border-blue-200">
                            <h4 class="font-bold text-blue-800 text-sm mb-2">💡 Cara Menggunakan Simulasi</h4>
                            <ol class="text-xs text-blue-700 space-y-1 list-decimal list-inside">
                                <li>Pilih <strong>Target Peta</strong> (institusi pemerintah)</li>
                                <li>Pilih <strong>Indikator</strong> yang ingin disimulasikan</li>
                                <li>Masukkan <strong>Variabel Dampak</strong> (persentase perubahan)</li>
                                <li>Klik <strong>Jalankan Simulasi</strong> untuk melihat proyeksi</li>
                            </ol>
                        </div>

                        <div class="bg-white/80 backdrop-blur-xl border border-slate-200 rounded-2xl shadow-xl overflow-hidden">
                            <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                                <h3 class="font-bold text-slate-800 flex items-center text-sm uppercase tracking-wider">
                                    <AdjustmentsHorizontalIcon class="w-4 h-4 mr-2 text-maroon-600" />
                                    Parameter
                                </h3>
                                <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            </div>

                            <form @submit.prevent="submit" class="p-6 space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Target Peta</label>
                                    <p class="text-xs text-slate-400 mb-2">Institusi pemerintah yang akan dianalisis.</p>
                                    <div class="relative">
                                        <select 
                                            v-model="form.node_id" 
                                            class="block w-full pl-10 pr-4 py-3 rounded-xl border-maroon-200 bg-maroon-50/50 focus:border-maroon-500 focus:ring-maroon-500 text-sm transition-shadow shadow-sm appearance-none"
                                            required
                                        >
                                            <option value="" disabled>Pilih Institusi...</option>
                                            <option v-for="node in nodes" :key="node.id" :value="node.id">{{ node.name }}</option>
                                        </select>
                                        <BuildingLibraryIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                                    </div>
                                </div>

                                <div class="relative">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Indikator Metrik</label>
                                    <p class="text-xs text-slate-400 mb-2">Aspek kebijakan yang akan diukur perubahannya.</p>
                                    <div class="relative">
                                        <select 
                                            v-model="form.indicator" 
                                            @change="onIndicatorChange"
                                            class="block w-full pl-10 pr-4 py-3 rounded-xl border-maroon-200 bg-maroon-50/50 focus:border-maroon-500 focus:ring-maroon-500 text-sm transition-shadow shadow-sm appearance-none disabled:opacity-50"
                                            :disabled="!selectedNode"
                                            required
                                        >
                                            <option value="" disabled>Pilih Indikator...</option>
                                            <option v-for="entry in availableIndicators" :key="entry.id" :value="entry.indicator">
                                                {{ entry.indicator }}
                                            </option>
                                        </select>
                                        <ChartBarIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                                    </div>
                                </div>

                                <transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0">
                                    <div v-if="form.indicator" class="bg-slate-100 rounded-xl p-4 border border-slate-200">
                                        <div class="flex justify-between items-end mb-2">
                                            <div>
                                                <span class="text-xs font-medium text-slate-500">Skor Baseline</span>
                                                <p class="text-[10px] text-slate-400">Nilai awal indikator saat ini</p>
                                            </div>
                                            <span class="text-xl font-bold font-mono text-slate-900">{{ form.old_score }}</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-1.5">
                                            <div class="bg-slate-500 h-1.5 rounded-full transition-all duration-1000" :style="{ width: form.old_score + '%' }"></div>
                                        </div>
                                    </div>
                                </transition>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Variabel Dampak (%)</label>
                                    <p class="text-xs text-slate-400 mb-2">Persentase perubahan yang ingin disimulasikan terhadap skor baseline.</p>
                                    <div class="relative flex items-center">
                                        <button 
                                            type="button" 
                                            @click="form.change_percent--"
                                            class="p-3 bg-slate-100 hover:bg-rose-100 hover:text-rose-600 rounded-l-xl border border-r-0 border-slate-200 transition-colors"
                                        >
                                            <span class="sr-only">Kurangi</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                                        </button>
                                        <input 
                                            v-model="form.change_percent" 
                                            type="number" 
                                            class="block w-full text-center border-y border-x-0 border-slate-200 bg-white focus:ring-0 focus:border-slate-200 font-mono font-bold text-lg py-2"
                                            placeholder="0"
                                            required 
                                        />
                                        <button 
                                            type="button" 
                                            @click="form.change_percent++"
                                            class="p-3 bg-slate-100 hover:bg-emerald-100 hover:text-emerald-600 rounded-r-xl border border-l-0 border-slate-200 transition-colors"
                                        >
                                            <span class="sr-only">Tambah</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                        </button>
                                    </div>
                                    <div class="mt-2 p-2 bg-amber-50 border border-amber-200 rounded-lg">
                                        <p class="text-[10px] text-amber-700">
                                            <strong>Contoh:</strong> Jika baseline = 52 dan dampak = +10%, maka proyeksi = 52 + (52 × 10%) = <strong>57.2</strong><br>
                                            Gunakan <strong>nilai negatif</strong> untuk simulasi penurunan (regresi).
                                        </p>
                                    </div>
                                </div>

                                <button 
                                    type="submit" 
                                    :disabled="!form.indicator || form.processing"
                                    class="w-full flex items-center justify-center py-3.5 px-4 bg-gradient-to-r from-maroon-700 to-maroon-800 hover:from-maroon-600 hover:to-maroon-700 text-white font-bold rounded-xl shadow-lg shadow-maroon-500/30 transition-all transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none"
                                >
                                    <ArrowPathIcon v-if="form.processing" class="w-5 h-5 mr-2 animate-spin" />
                                    <PlayIcon v-else class="w-5 h-5 mr-2" />
                                    Jalankan Simulasi
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="lg:col-span-8">
                        <div class="h-full flex flex-col">
                            <div class="bg-white/80 backdrop-blur-xl border border-slate-200 rounded-2xl shadow-xl overflow-hidden flex-1 flex flex-col">
                                
                                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                    <h3 class="font-bold text-slate-800 flex items-center text-sm uppercase tracking-wider">
                                        <CalculatorIcon class="w-4 h-4 mr-2 text-maroon-600" />
                                        Analisis Proyeksi
                                    </h3>
                                    <span v-if="result" class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-1 rounded border border-emerald-200">
                                        KALKULASI SELESAI
                                    </span>
                                </div>

                                <div class="p-6 md:p-8 flex-1 flex flex-col">
                                    <transition mode="out-in" enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100">
                                        
                                        <div v-if="result && chartData" class="flex flex-col h-full space-y-8">
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                                                    <p class="text-xs text-slate-500 uppercase font-bold">Baseline</p>
                                                    <p class="text-3xl font-mono font-bold text-slate-700">{{ result.old_score }}</p>
                                                </div>
                                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 relative overflow-hidden">
                                                    <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-transparent to-slate-200 opacity-20 rounded-bl-full"></div>
                                                    <p class="text-xs text-slate-500 uppercase font-bold">Proyeksi</p>
                                                    <p class="text-3xl font-mono font-bold text-slate-900">{{ result.new_score.toFixed(1) }}</p>
                                                </div>
                                                <div class="p-4 rounded-xl border flex flex-col justify-center relative overflow-hidden" 
                                                    :class="result.change_percent > 0 ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-rose-50 border-rose-100 text-rose-700'">
                                                    <p class="text-xs uppercase font-bold opacity-70">Delta Dampak</p>
                                                    <div class="flex items-center">
                                                        <component :is="result.change_percent > 0 ? ArrowTrendingUpIcon : ArrowTrendingDownIcon" class="w-6 h-6 mr-2" />
                                                        <span class="text-3xl font-mono font-bold">{{ result.change_percent > 0 ? '+' : '' }}{{ result.change_percent }}%</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex-1 min-h-[300px] w-full bg-white rounded-xl border border-slate-100 p-4 relative">
                                                <ChartComponent type="bar" :data="chartData" :options="chartOptions" />
                                            </div>
                                        </div>

                                        <div v-else class="flex-1 flex flex-col items-center justify-center text-center py-12 opacity-60">
                                            <div class="w-32 h-32 bg-slate-100 rounded-full flex items-center justify-center mb-6 animate-pulse">
                                                <CalculatorIcon class="w-12 h-12 text-slate-400" />
                                            </div>
                                            <h4 class="text-xl font-bold text-slate-700">Siap untuk Analisis</h4>
                                            <p class="text-slate-500 max-w-sm mt-2">Konfigurasikan parameter di panel kiri dan jalankan simulasi untuk memvisualisasikan hasil proyeksi.</p>
                                        </div>
                                    </transition>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Results Tab Content -->
                <div v-show="activeTab === 'results'" class="space-y-8">
                    
                    <!-- Info Banner -->
                    <div class="bg-gradient-to-r from-maroon-700 to-maroon-900 rounded-2xl shadow-xl p-6 text-white">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                    <SparklesIcon class="w-6 h-6" />
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold mb-1">Sistem Penilaian Kebijakan</h3>
                                <p class="text-white/90 text-sm">
                                    🟢 <strong>Hijau (91-100%)</strong>: Kebijakan disetujui masyarakat dan dapat diterapkan.<br>
                                    🔴 <strong>Merah (10-90%)</strong>: Kebijakan berisiko dan masyarakat tidak setuju. Perlu perbaikan.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Stats -->
                    <div class="flex items-center space-x-4">
                        <div class="px-4 py-2 bg-green-100 rounded-lg border border-green-300 shadow-sm">
                            <div class="flex items-center space-x-2">
                                <CheckCircleIcon class="w-5 h-5 text-green-600" />
                                <span class="text-sm font-bold text-green-700">{{ approvedPolicies.length }} Disetujui</span>
                            </div>
                        </div>
                        <div class="px-4 py-2 bg-red-100 rounded-lg border border-red-300 shadow-sm">
                            <div class="flex items-center space-x-2">
                                <XCircleIcon class="w-5 h-5 text-red-600" />
                                <span class="text-sm font-bold text-red-700">{{ rejectedPolicies.length }} Ditolak</span>
                            </div>
                        </div>
                    </div>

                    <!-- Filter -->
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-200 p-6">
                        <div class="flex items-center space-x-2 mb-4">
                            <FunnelIcon class="w-5 h-5 text-slate-600" />
                            <h3 class="font-bold text-slate-800">Filter Daerah</h3>
                        </div>
                        <select 
                            v-model="selectedResultNode"
                            class="w-full md:w-64 rounded-xl border-slate-200 bg-slate-50 focus:border-maroon-500 focus:ring-maroon-500"
                        >
                            <option value="">Semua Daerah</option>
                            <option v-for="node in nodes" :key="node.id" :value="node.id">
                                {{ node.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Approved Policies (Green) -->
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border-2 border-green-300 p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                <CheckCircleIcon class="w-6 h-6 text-green-600" />
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-green-800">Kebijakan Disetujui</h3>
                                <p class="text-sm text-green-600">Skor 91-100% • Dapat diterapkan</p>
                            </div>
                            <div class="ml-auto px-4 py-2 bg-green-600 text-white font-bold rounded-xl">
                                {{ approvedPolicies.length }}
                            </div>
                        </div>

                        <div v-if="approvedPolicies.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <Link 
                                v-for="policy in approvedPolicies" 
                                :key="policy.id"
                                :href="route('labs.show', policy.id)"
                                class="group p-4 bg-green-50 hover:bg-green-100 rounded-xl border border-green-200 transition-all"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-slate-900 group-hover:text-green-700 transition-colors">{{ policy.title }}</h4>
                                        <p class="text-sm text-slate-600 mt-1 line-clamp-2">{{ policy.description }}</p>
                                        <div class="flex items-center space-x-3 mt-3 text-xs text-slate-500">
                                            <div class="flex items-center space-x-1">
                                                <MapPinIcon class="w-4 h-4" />
                                                <span>{{ policy.node?.name }}</span>
                                            </div>
                                            <span>•</span>
                                            <span>{{ policy.reviews_count }} penilaian</span>
                                        </div>
                                    </div>
                                    <div class="ml-4 text-center flex-shrink-0">
                                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex flex-col items-center justify-center shadow-lg">
                                            <div class="text-xl font-bold text-white">{{ policy.average_score }}%</div>
                                        </div>
                                    </div>
                                </div>
                            </Link>
                        </div>
                        
                        <div v-else class="text-center py-8 text-green-600">
                            <CheckCircleIcon class="w-12 h-12 mx-auto mb-2 opacity-50" />
                            <p>Belum ada kebijakan yang mencapai persetujuan masyarakat (91%+)</p>
                        </div>
                    </div>

                    <!-- Rejected Policies (Red) -->
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border-2 border-red-300 p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                                <ExclamationTriangleIcon class="w-6 h-6 text-red-600" />
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-red-800">Kebijakan Berisiko</h3>
                                <p class="text-sm text-red-600">Skor 10-90% • Perlu perbaikan</p>
                            </div>
                            <div class="ml-auto px-4 py-2 bg-red-600 text-white font-bold rounded-xl">
                                {{ rejectedPolicies.length }}
                            </div>
                        </div>

                        <div v-if="rejectedPolicies.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <Link 
                                v-for="policy in rejectedPolicies" 
                                :key="policy.id"
                                :href="route('labs.show', policy.id)"
                                class="group p-4 bg-red-50 hover:bg-red-100 rounded-xl border border-red-200 transition-all"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-slate-900 group-hover:text-red-700 transition-colors">{{ policy.title }}</h4>
                                        <p class="text-sm text-slate-600 mt-1 line-clamp-2">{{ policy.description }}</p>
                                        <div class="flex items-center space-x-3 mt-3 text-xs text-slate-500">
                                            <div class="flex items-center space-x-1">
                                                <MapPinIcon class="w-4 h-4" />
                                                <span>{{ policy.node?.name }}</span>
                                            </div>
                                            <span>•</span>
                                            <span>{{ policy.reviews_count }} penilaian</span>
                                        </div>
                                        <!-- AI Recommendation Preview -->
                                        <div v-if="policy.recommendation" class="mt-3 p-2 bg-amber-50 border border-amber-200 rounded-lg">
                                            <p class="text-xs text-amber-800 line-clamp-2">
                                                <strong>💡 Rekomendasi AI:</strong> {{ policy.recommendation.recommendation }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="ml-4 text-center flex-shrink-0">
                                        <div class="w-16 h-16 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex flex-col items-center justify-center shadow-lg">
                                            <div class="text-xl font-bold text-white">{{ policy.average_score }}%</div>
                                        </div>
                                    </div>
                                </div>
                            </Link>
                        </div>
                        
                        <div v-else class="text-center py-8 text-red-600">
                            <XCircleIcon class="w-12 h-12 mx-auto mb-2 opacity-50" />
                            <p>Tidak ada kebijakan berisiko. Semua kebijakan disetujui masyarakat!</p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>