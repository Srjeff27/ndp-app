<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import {
    ChatBubbleLeftRightIcon,
    StarIcon,
    ArrowRightIcon,
    CheckCircleIcon,
    XCircleIcon,
    ClockIcon,
    ScaleIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    policies: Array,
});

const searchQuery = ref('');
const selectedCategory = ref('all');

const categories = computed(() => {
    const cats = ['all', ...new Set(props.policies.map(p => p.category))];
    return cats;
});

const filteredPolicies = computed(() => {
    return props.policies.filter(policy => {
        const matchCategory = selectedCategory.value === 'all' || policy.category === selectedCategory.value;
        const matchSearch = searchQuery.value === '' || 
            policy.title.toLowerCase().includes(searchQuery.value.toLowerCase());
        
        return matchCategory && matchSearch;
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
</script>

<template>
    <Head title="Forum Masyarakat" />

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
                        <h2 class="text-2xl font-bold text-maroon-900 tracking-tight">Forum Masyarakat</h2>
                        <p class="text-sm text-maroon-600 mt-1">⚖️ Berikan Penilaian & Kritik untuk Kebijakan Pemerintah</p>
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
                                <StarIcon class="w-6 h-6" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold mb-2">Suara Anda Penting!</h3>
                            <p class="text-white/90 text-sm">
                                Berikan penilaian 0-100% untuk setiap kebijakan. Penilaian Anda akan dianalisis oleh AI untuk memberikan rekomendasi kepada pemerintah.
                                <strong class="block mt-2">91-100% = Kebijakan Disetujui ✅ | 0-90% = Kebijakan Ditolak ❌</strong>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-200 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-2">Cari Kebijakan</label>
                            <input 
                                v-model="searchQuery"
                                type="text"
                                placeholder="Cari judul kebijakan..."
                                class="w-full rounded-lg border-slate-200 bg-slate-50 focus:border-purple-500 focus:ring-purple-500 text-sm"
                            />
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-2">Kategori</label>
                            <select 
                                v-model="selectedCategory"
                                class="w-full rounded-lg border-slate-200 bg-slate-50 focus:border-purple-500 focus:ring-purple-500 text-sm"
                            >
                                <option value="all">Semua Kategori</option>
                                <option v-for="cat in categories.filter(c => c !== 'all')" :key="cat" :value="cat">
                                    {{ cat }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Policies Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <Link 
                        v-for="policy in filteredPolicies" 
                        :key="policy.id"
                        :href="route('labs.show', policy.id)"
                        class="group bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border-2 hover:shadow-2xl transition-all duration-300 overflow-hidden"
                        :class="policy.user_has_reviewed ? 'border-purple-200' : 'border-slate-200 hover:border-purple-300'"
                    >
                        <!-- Header with Score -->
                        <div class="p-6 pb-4">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-purple-600 transition-colors">
                                            {{ policy.title }}
                                        </h3>
                                        <CheckCircleIcon v-if="policy.user_has_reviewed" class="w-5 h-5 text-purple-600 flex-shrink-0" />
                                    </div>
                                    <p class="text-sm text-slate-600 line-clamp-2">{{ policy.description }}</p>
                                </div>

                                <!-- Score Badge -->
                                <div class="ml-4 text-center flex-shrink-0">
                                    <div 
                                        class="w-20 h-20 rounded-xl flex flex-col items-center justify-center shadow-lg"
                                        :class="policy.status_color === 'green' ? 'bg-gradient-to-br from-green-400 to-green-600' : 'bg-gradient-to-br from-red-400 to-red-600'"
                                    >
                                        <div class="text-2xl font-bold text-white">{{ policy.average_score }}%</div>
                                        <div class="text-[10px] text-white/90">{{ policy.reviews_count }} suara</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Meta Info -->
                            <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 mb-4">
                                <div class="px-2 py-1 bg-slate-100 rounded font-medium">
                                    {{ policy.category }}
                                </div>
                                <div>{{ policy.node.name }}</div>
                                <div>{{ formatCurrency(policy.budget) }}</div>
                            </div>

                            <!-- Status Indicator -->
                            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                <div class="flex items-center space-x-2">
                                    <component 
                                        :is="policy.status_color === 'green' ? CheckCircleIcon : XCircleIcon"
                                        class="w-5 h-5"
                                        :class="policy.status_color === 'green' ? 'text-green-600' : 'text-red-600'"
                                    />
                                    <span class="text-sm font-semibold" :class="policy.status_color === 'green' ? 'text-green-600' : 'text-red-600'">
                                        {{ policy.status_color === 'green' ? 'Disetujui Masyarakat' : 'Ditolak Masyarakat' }}
                                    </span>
                                </div>

                                <div class="flex items-center space-x-2 text-purple-600 font-medium text-sm">
                                    <span>{{ policy.user_has_reviewed ? 'Lihat Penilaian' : 'Beri Penilaian' }}</span>
                                    <ArrowRightIcon class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                                </div>
                            </div>
                        </div>

                        <!-- User Review Status -->
                        <div v-if="policy.user_has_reviewed" class="px-6 py-3 bg-purple-50 border-t border-purple-100">
                            <div class="flex items-center space-x-2 text-sm">
                                <CheckCircleIcon class="w-4 h-4 text-purple-600" />
                                <span class="text-purple-700 font-medium">Anda telah memberikan penilaian: {{ policy.user_review.score }}%</span>
                            </div>
                        </div>
                    </Link>

                    <!-- Empty State -->
                    <div v-if="filteredPolicies.length === 0" class="col-span-2 text-center py-20 bg-white/30 backdrop-blur-sm border border-dashed border-slate-300 rounded-3xl">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <ChatBubbleLeftRightIcon class="w-10 h-10 text-slate-400" />
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Tidak Ada Kebijakan</h3>
                        <p class="text-slate-500 mt-2 max-w-sm mx-auto">
                            Tidak ada kebijakan yang tersedia untuk dinilai saat ini.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>