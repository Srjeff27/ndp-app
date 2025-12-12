<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import {
    ChartBarIcon,
    FunnelIcon,
    MapPinIcon,
    BanknotesIcon,
    CalendarIcon,
    CheckCircleIcon,
    XCircleIcon,
    ClockIcon,
    ScaleIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    policies: Array,
    nodes: Array,
});

const selectedCategory = ref('all');
const selectedStatus = ref('all');
const searchQuery = ref('');

const categories = computed(() => {
    const cats = ['all', ...new Set(props.policies.map(p => p.category))];
    return cats;
});

const filteredPolicies = computed(() => {
    return props.policies.filter(policy => {
        const matchCategory = selectedCategory.value === 'all' || policy.category === selectedCategory.value;
        const matchStatus = selectedStatus.value === 'all' || policy.status === selectedStatus.value;
        const matchSearch = searchQuery.value === '' || 
            policy.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            policy.description.toLowerCase().includes(searchQuery.value.toLowerCase());
        
        return matchCategory && matchStatus && matchSearch;
    });
});

const formatCurrency = (amount) => {
    if (!amount) return 'Tidak ada anggaran';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};

const getStatusBadge = (status) => {
    const badges = {
        draft: { text: 'Draft', class: 'bg-gray-100 text-gray-800' },
        proposed: { text: 'Diusulkan', class: 'bg-blue-100 text-blue-800' },
        active: { text: 'Aktif', class: 'bg-green-100 text-green-800' },
        completed: { text: 'Selesai', class: 'bg-purple-100 text-purple-800' },
        rejected: { text: 'Ditolak', class: 'bg-red-100 text-red-800' },
    };
    return badges[status] || badges.draft;
};

const getCategoryIcon = (category) => {
    // Return appropriate icon based on category
    return ChartBarIcon;
};
</script>

<template>
    <Head title="Peta Akuntabilitas" />

    <AuthenticatedLayout>
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-gradient-to-br from-maroon-50 via-white to-gold-50"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
        </div>

        <template #header>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-br from-maroon-100 to-maroon-200 rounded-xl border border-maroon-300">
                        <ScaleIcon class="w-8 h-8 text-maroon-700" />
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-maroon-900 tracking-tight">Peta Akuntabilitas Kebijakan</h2>
                        <p class="text-sm text-maroon-600 mt-1">⚖️ Transparansi & Keadilan Kebijakan Pemerintah</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <div class="px-4 py-2 bg-white rounded-lg border border-slate-200 shadow-sm">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm font-medium text-slate-700">{{ filteredPolicies.filter(p => p.is_approved).length }} Disetujui</span>
                        </div>
                    </div>
                    <div class="px-4 py-2 bg-white rounded-lg border border-slate-200 shadow-sm">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-sm font-medium text-slate-700">{{ filteredPolicies.filter(p => !p.is_approved).length }} Ditolak</span>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="relative z-10 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                
                <!-- Filters -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-200 p-6">
                    <div class="flex items-center space-x-2 mb-4">
                        <FunnelIcon class="w-5 h-5 text-slate-600" />
                        <h3 class="font-bold text-slate-800">Filter Kebijakan</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-2">Cari Kebijakan</label>
                            <input 
                                v-model="searchQuery"
                                type="text"
                                placeholder="Cari judul atau deskripsi..."
                                class="w-full rounded-lg border-maroon-200 bg-maroon-50/50 focus:border-maroon-500 focus:ring-maroon-500 text-sm"
                            />
                        </div>
                        
                        <!-- Category Filter -->
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-2">Kategori</label>
                            <select 
                                v-model="selectedCategory"
                                class="w-full rounded-lg border-maroon-200 bg-maroon-50/50 focus:border-maroon-500 focus:ring-maroon-500 text-sm"
                            >
                                <option value="all">Semua Kategori</option>
                                <option v-for="cat in categories.filter(c => c !== 'all')" :key="cat" :value="cat">
                                    {{ cat }}
                                </option>
                            </select>
                        </div>
                        
                        <!-- Status Filter -->
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-2">Status</label>
                            <select 
                                v-model="selectedStatus"
                                class="w-full rounded-lg border-maroon-200 bg-maroon-50/50 focus:border-maroon-500 focus:ring-maroon-500 text-sm"
                            >
                                <option value="all">Semua Status</option>
                                <option value="proposed">Diusulkan</option>
                                <option value="active">Aktif</option>
                                <option value="completed">Selesai</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-slate-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">Total Kebijakan</p>
                                <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ policies.length }}</h3>
                            </div>
                            <div class="p-3 bg-maroon-100 rounded-xl">
                                <ScaleIcon class="w-8 h-8 text-maroon-700" />
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-slate-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">Disetujui Masyarakat</p>
                                <h3 class="text-3xl font-bold text-green-600 mt-1">{{ policies.filter(p => p.is_approved).length }}</h3>
                            </div>
                            <div class="p-3 bg-green-100 rounded-xl">
                                <CheckCircleIcon class="w-8 h-8 text-green-600" />
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-slate-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">Ditolak Masyarakat</p>
                                <h3 class="text-3xl font-bold text-red-600 mt-1">{{ policies.filter(p => !p.is_approved).length }}</h3>
                            </div>
                            <div class="p-3 bg-red-100 rounded-xl">
                                <XCircleIcon class="w-8 h-8 text-red-600" />
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-slate-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">Total Penilaian</p>
                                <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ policies.reduce((sum, p) => sum + p.reviews_count, 0) }}</h3>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-xl">
                                <ClockIcon class="w-8 h-8 text-purple-600" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Policies List -->
                <div class="space-y-4">
                    <div 
                        v-for="policy in filteredPolicies" 
                        :key="policy.id"
                        class="group bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border-2 hover:shadow-2xl transition-all duration-300"
                        :class="policy.is_approved ? 'border-green-200 hover:border-green-400' : 'border-red-200 hover:border-red-400'"
                    >
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-maroon-700 transition-colors">
                                            {{ policy.title }}
                                        </h3>
                                        <span 
                                            class="px-3 py-1 rounded-full text-xs font-semibold"
                                            :class="getStatusBadge(policy.status).class"
                                        >
                                            {{ getStatusBadge(policy.status).text }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-600 mb-3">{{ policy.description }}</p>
                                    
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500">
                                        <div class="flex items-center space-x-1">
                                            <MapPinIcon class="w-4 h-4" />
                                            <span>{{ policy.node.name }}</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <BanknotesIcon class="w-4 h-4" />
                                            <span>{{ formatCurrency(policy.budget) }}</span>
                                        </div>
                                        <div class="flex items-center space-x-1" v-if="policy.implementation_date">
                                            <CalendarIcon class="w-4 h-4" />
                                            <span>{{ policy.implementation_date }}</span>
                                        </div>
                                        <div class="px-2 py-1 bg-slate-100 rounded text-xs font-medium">
                                            {{ policy.category }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Score Badge -->
                                <div class="ml-6 text-center">
                                    <div 
                                        class="w-24 h-24 rounded-2xl flex flex-col items-center justify-center shadow-lg"
                                        :class="policy.is_approved ? 'bg-gradient-to-br from-green-400 to-green-600' : 'bg-gradient-to-br from-red-400 to-red-600'"
                                    >
                                        <div class="text-3xl font-bold text-white">{{ policy.average_score }}%</div>
                                        <div class="text-xs text-white/90 mt-1">{{ policy.reviews_count }} penilaian</div>
                                    </div>
                                    <div class="mt-2">
                                        <component 
                                            :is="policy.is_approved ? CheckCircleIcon : XCircleIcon"
                                            class="w-6 h-6 mx-auto"
                                            :class="policy.is_approved ? 'text-green-600' : 'text-red-600'"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- AI Recommendation -->
                            <div 
                                v-if="policy.recommendation"
                                class="mt-4 p-4 rounded-xl"
                                :class="policy.is_approved ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'"
                            >
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-maroon-600 to-maroon-800 flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">AI</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-bold text-slate-900 mb-1">Analisis AI</h4>
                                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ policy.recommendation.ai_analysis }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-if="filteredPolicies.length === 0" class="text-center py-20 bg-white/30 backdrop-blur-sm border border-dashed border-slate-300 rounded-3xl">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <ChartBarIcon class="w-10 h-10 text-slate-400" />
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Tidak Ada Kebijakan</h3>
                        <p class="text-slate-500 mt-2 max-w-sm mx-auto">
                            Tidak ada kebijakan yang sesuai dengan filter yang dipilih.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>