<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    ChatBubbleLeftRightIcon,
    StarIcon,
    UserIcon,
    CheckCircleIcon,
    XCircleIcon,
    SparklesIcon,
    ScaleIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    policy: Object,
});

const showReviewForm = ref(!props.policy.user_has_reviewed);

const form = useForm({
    score: props.policy.user_review?.score || 50,
    comment: props.policy.user_review?.comment || '',
});

const submit = () => {
    form.post(route('policy-reviews.store', props.policy.id), {
        onSuccess: () => {
            showReviewForm.value = false;
        },
    });
};

const formatCurrency = (amount) => {
    if (!amount) return 'Tidak ada anggaran';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};

const getScoreColor = (score) => {
    if (score >= 91) return 'text-green-600';
    if (score >= 75) return 'text-yellow-600';
    if (score >= 50) return 'text-orange-600';
    return 'text-red-600';
};

const getScoreGradient = (score) => {
    if (score >= 91) return 'from-green-400 to-green-600';
    if (score >= 75) return 'from-yellow-400 to-yellow-600';
    if (score >= 50) return 'from-orange-400 to-orange-600';
    return 'from-red-400 to-red-600';
};
</script>

<template>
    <Head :title="policy.title" />

    <AuthenticatedLayout>
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-gradient-to-br from-maroon-50 via-white to-gold-50"></div>
        </div>

        <template #header>
            <div class="relative z-10">
                <div class="flex items-center space-x-3 mb-2">
                    <ScaleIcon class="w-6 h-6 text-maroon-700" />
                    <h2 class="text-2xl font-bold text-maroon-900">⚖️ Detail Kebijakan</h2>
                </div>
            </div>
        </template>

        <div class="relative z-10 py-8">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                
                <!-- Policy Header -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-200 p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-slate-900 mb-3">{{ policy.title }}</h1>
                            <p class="text-slate-600 text-lg">{{ policy.description }}</p>
                        </div>

                        <!-- Overall Score -->
                        <div class="ml-6 text-center">
                            <div 
                                class="w-32 h-32 rounded-2xl flex flex-col items-center justify-center shadow-2xl"
                                :class="'bg-gradient-to-br ' + getScoreGradient(policy.average_score)"
                            >
                                <div class="text-4xl font-bold text-white">{{ policy.average_score }}%</div>
                                <div class="text-sm text-white/90 mt-1">{{ policy.reviews.length }} penilaian</div>
                            </div>
                            <div class="mt-3 flex items-center justify-center space-x-2">
                                <component 
                                    :is="policy.status_color === 'green' ? CheckCircleIcon : XCircleIcon"
                                    class="w-6 h-6"
                                    :class="policy.status_color === 'green' ? 'text-green-600' : 'text-red-600'"
                                />
                                <span class="text-sm font-bold" :class="policy.status_color === 'green' ? 'text-green-600' : 'text-red-600'">
                                    {{ policy.status_color === 'green' ? 'DISETUJUI' : 'DITOLAK' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Policy Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-6 border-t border-slate-200">
                        <div>
                            <p class="text-xs font-medium text-slate-500 mb-1">Kategori</p>
                            <p class="text-sm font-semibold text-slate-900">{{ policy.category }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500 mb-1">Anggaran</p>
                            <p class="text-sm font-semibold text-slate-900">{{ formatCurrency(policy.budget) }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500 mb-1">Implementasi</p>
                            <p class="text-sm font-semibold text-slate-900">{{ policy.implementation_date || 'Belum ditentukan' }}</p>
                        </div>
                    </div>
                </div>

                <!-- AI Recommendation -->
                <div 
                    v-if="policy.recommendation"
                    class="bg-gradient-to-r from-maroon-700 to-maroon-900 rounded-2xl shadow-xl p-6 text-white"
                >
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <SparklesIcon class="w-6 h-6" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold mb-2 flex items-center">
                                <span>Analisis & Rekomendasi AI</span>
                            </h3>
                            <p class="text-white/95 text-sm whitespace-pre-line leading-relaxed">{{ policy.recommendation.ai_analysis }}</p>
                            
                            <!-- Sentiment Breakdown -->
                            <div class="mt-4 grid grid-cols-3 gap-3">
                                <div class="bg-white/10 rounded-lg p-3">
                                    <p class="text-xs text-white/70">Positif</p>
                                    <p class="text-xl font-bold">{{ policy.recommendation.sentiment_breakdown.positive }}</p>
                                </div>
                                <div class="bg-white/10 rounded-lg p-3">
                                    <p class="text-xs text-white/70">Netral</p>
                                    <p class="text-xl font-bold">{{ policy.recommendation.sentiment_breakdown.neutral }}</p>
                                </div>
                                <div class="bg-white/10 rounded-lg p-3">
                                    <p class="text-xs text-white/70">Negatif</p>
                                    <p class="text-xl font-bold">{{ policy.recommendation.sentiment_breakdown.negative }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Review Form -->
                <div v-if="showReviewForm" class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border-2 border-purple-200 p-8">
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                        <StarIcon class="w-6 h-6 mr-2 text-purple-600" />
                        Berikan Penilaian Anda
                    </h3>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Score Slider -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="text-sm font-bold text-slate-700">Skor Penilaian</label>
                                <div 
                                    class="text-4xl font-bold px-6 py-2 rounded-xl"
                                    :class="getScoreColor(form.score)"
                                >
                                    {{ form.score }}%
                                </div>
                            </div>
                            
                            <input 
                                v-model.number="form.score"
                                type="range"
                                min="0"
                                max="100"
                                class="w-full h-3 rounded-lg appearance-none cursor-pointer"
                                :class="form.score >= 91 ? 'accent-green-600' : 'accent-red-600'"
                            />
                            
                            <div class="flex justify-between text-xs text-slate-500 mt-2">
                                <span>0% (Sangat Buruk)</span>
                                <span class="font-bold">91% = Batas Persetujuan</span>
                                <span>100% (Sangat Baik)</span>
                            </div>

                            <div class="mt-4 p-4 rounded-xl" :class="form.score >= 91 ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
                                <p class="text-sm font-semibold" :class="form.score >= 91 ? 'text-green-700' : 'text-red-700'">
                                    {{ form.score >= 91 ? '✅ Anda MENYETUJUI kebijakan ini' : '❌ Anda MENOLAK kebijakan ini' }}
                                </p>
                            </div>
                        </div>

                        <!-- Comment -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kritik & Saran (Opsional)</label>
                            <textarea 
                                v-model="form.comment"
                                rows="4"
                                class="w-full rounded-xl border-slate-200 bg-slate-50 focus:border-purple-500 focus:ring-purple-500"
                                placeholder="Berikan kritik, saran, atau alasan penilaian Anda..."
                            ></textarea>
                            <p class="text-xs text-slate-500 mt-2">Komentar Anda akan membantu AI memberikan rekomendasi yang lebih baik</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-4">
                            <button
                                v-if="policy.user_has_reviewed"
                                type="button"
                                @click="showReviewForm = false"
                                class="px-6 py-3 border border-slate-300 rounded-xl font-semibold text-slate-700 hover:bg-slate-50 transition-colors"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-8 py-3 bg-gradient-to-r from-maroon-700 to-maroon-800 hover:from-maroon-600 hover:to-maroon-700 text-white font-bold rounded-xl shadow-lg transition-all disabled:opacity-50"
                            >
                                {{ policy.user_has_reviewed ? 'Update Penilaian' : 'Kirim Penilaian' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- User's Current Review -->
                <div v-else class="bg-maroon-50 border-2 border-maroon-200 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <CheckCircleIcon class="w-8 h-8 text-maroon-700" />
                            <div>
                                <h3 class="font-bold text-slate-900">Penilaian Anda: {{ policy.user_review.score }}%</h3>
                                <p class="text-sm text-slate-600">{{ policy.user_review.comment || 'Tidak ada komentar' }}</p>
                            </div>
                        </div>
                        <button
                            @click="showReviewForm = true"
                            class="px-4 py-2 bg-maroon-700 hover:bg-maroon-800 text-white font-semibold rounded-lg transition-colors"
                        >
                            Edit Penilaian
                        </button>
                    </div>
                </div>

                <!-- All Reviews -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-200 p-8">
                    <h3 class="text-xl font-bold text-slate-900 mb-6">Penilaian Masyarakat ({{ policy.reviews.length }})</h3>
                    
                    <div class="space-y-4">
                        <div 
                            v-for="review in policy.reviews" 
                            :key="review.id"
                            class="p-4 rounded-xl border"
                            :class="review.score >= 91 ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'"
                        >
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center">
                                        <UserIcon class="w-5 h-5 text-slate-600" />
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ review.user.name }}</p>
                                        <p class="text-xs text-slate-500">{{ review.created_at }}</p>
                                    </div>
                                </div>
                                <div 
                                    class="px-4 py-2 rounded-lg font-bold text-white"
                                    :class="'bg-gradient-to-r ' + getScoreGradient(review.score)"
                                >
                                    {{ review.score }}%
                                </div>
                            </div>
                            <p v-if="review.comment" class="text-sm text-slate-700 mt-2">{{ review.comment }}</p>
                        </div>

                        <div v-if="policy.reviews.length === 0" class="text-center py-8 text-slate-500">
                            Belum ada penilaian. Jadilah yang pertama!
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>