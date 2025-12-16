<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import {
    CheckCircleIcon,
    XCircleIcon,
    ChartBarIcon,
    FunnelIcon,
    MapPinIcon,
    ExclamationTriangleIcon,
    SparklesIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    policies: Array,
    nodes: Array,
    stats: Object,
});

const selectedNode = ref('');

const filteredPolicies = computed(() => {
    if (!selectedNode.value) return props.policies;
    return props.policies.filter(p => p.node_id === parseInt(selectedNode.value));
});

const approvedPolicies = computed(() => filteredPolicies.value.filter(p => p.is_approved));
const rejectedPolicies = computed(() => filteredPolicies.value.filter(p => !p.is_approved));

const formatCurrency = (amount) => {
    if (!amount) return 'Tidak ada anggaran';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};
</script>

<template>
    <Head title="Hasil Kebijakan" />

    <AuthenticatedLayout>
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-gradient-to-br from-maroon-50 via-white to-gold-50"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
        </div>

        <template #header>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-maroon-700 to-maroon-900 rounded-2xl flex items-center justify-center shadow-xl">
                        <ChartBarIcon class="w-7 h-7 text-white" />
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-maroon-900">📊 Hasil Kebijakan</h2>
                        <p class="text-sm text-maroon-600 mt-1">Ringkasan Status Persetujuan Kebijakan Publik</p>
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
            </div>
        </template>

        <div class="relative z-10 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                
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

                <!-- Filter -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-200 p-6">
                    <div class="flex items-center space-x-2 mb-4">
                        <FunnelIcon class="w-5 h-5 text-slate-600" />
                        <h3 class="font-bold text-slate-800">Filter Daerah</h3>
                    </div>
                    <select 
                        v-model="selectedNode"
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
    </AuthenticatedLayout>
</template>
