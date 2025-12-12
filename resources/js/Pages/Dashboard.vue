<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ChartComponent from '@/Components/ChartComponent.vue';
import {
    GlobeAltIcon,
    ChartBarIcon,
    ChatBubbleLeftRightIcon,
    ClockIcon,
    ArrowTrendingUpIcon,
    BeakerIcon,
    AcademicCapIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    stats: Object,
    recent_simulations: Array,
    recent_discussions: Array
});

// Helper untuk format tanggal akademik
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-GB', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

// Konfigurasi Chart yang lebih Clean & Modern
const chartData = {
    labels: ['Governance Nodes', 'Indicators', 'Active Labs'],
    datasets: [{
        label: 'System Metrics',
        data: [props.stats.nodes_count, props.stats.indicators_count, props.stats.discussions_count],
        backgroundColor: [
            'rgba(79, 70, 229, 0.1)', // Indigo
            'rgba(16, 185, 129, 0.1)', // Emerald
            'rgba(245, 158, 11, 0.1)'  // Amber
        ],
        borderColor: [
            '#4f46e5',
            '#10b981',
            '#f59e0b'
        ],
        borderWidth: 2,
        borderRadius: 6,
        borderSkipped: false,
        barThickness: 40
    }]
};

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: '#1e293b',
            padding: 12,
            titleFont: { size: 13 },
            bodyFont: { size: 12 },
            cornerRadius: 8,
            displayColors: false
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                color: 'rgba(148, 163, 184, 0.1)',
                borderDash: [5, 5]
            },
            ticks: {
                font: { size: 11 },
                color: '#64748b'
            }
        },
        x: {
            grid: { display: false },
            ticks: {
                font: { size: 11, weight: '500' },
                color: '#64748b'
            }
        }
    }
};
</script>

<template>
    <Head title="Research Dashboard" />

    <AuthenticatedLayout>
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-slate-50"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[100px]"></div>
        </div>

        <template #header>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900">
                        Dashboard
                    </h2>
                    <p class="mt-1 text-sm text-slate-500 flex items-center gap-2">
                        <AcademicCapIcon class="w-4 h-4" />
                        <span>Overview of research nodes and democratic indicators.</span>
                    </p>
                </div>
                <div class="flex items-center gap-3 text-sm font-medium text-slate-600 bg-white/50 px-4 py-2 rounded-lg border border-slate-200 backdrop-blur-sm">
                    <ClockIcon class="w-4 h-4 text-indigo-500" />
                    {{ new Date().toLocaleDateString('en-GB', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                </div>
            </div>
        </template>

        <div class="relative z-10 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="group relative bg-white/70 backdrop-blur-xl border border-slate-200 rounded-2xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <GlobeAltIcon class="w-16 h-16 text-indigo-600 rotate-12" />
                        </div>
                        <div class="relative">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <GlobeAltIcon class="w-6 h-6 text-indigo-600" />
                            </div>
                            <p class="text-sm font-medium text-slate-500">Total Nodes</p>
                            <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ stats.nodes_count }}</h3>
                            <div class="mt-2 flex items-center text-xs font-medium text-emerald-600 bg-emerald-50 w-fit px-2 py-1 rounded-full">
                                <ArrowTrendingUpIcon class="w-3 h-3 mr-1" />
                                <span>Active</span>
                            </div>
                        </div>
                    </div>

                    <div class="group relative bg-white/70 backdrop-blur-xl border border-slate-200 rounded-2xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <ChartBarIcon class="w-16 h-16 text-emerald-600 rotate-12" />
                        </div>
                        <div class="relative">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <ChartBarIcon class="w-6 h-6 text-emerald-600" />
                            </div>
                            <p class="text-sm font-medium text-slate-500">Governance Indicators</p>
                            <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ stats.indicators_count }}</h3>
                            <div class="mt-2 flex items-center text-xs font-medium text-slate-500">
                                <span>Tracked metrics</span>
                            </div>
                        </div>
                    </div>

                    <div class="group relative bg-white/70 backdrop-blur-xl border border-slate-200 rounded-2xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <ChatBubbleLeftRightIcon class="w-16 h-16 text-amber-600 rotate-12" />
                        </div>
                        <div class="relative">
                            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <ChatBubbleLeftRightIcon class="w-6 h-6 text-amber-600" />
                            </div>
                            <p class="text-sm font-medium text-slate-500">Civic Discussions</p>
                            <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ stats.discussions_count }}</h3>
                            <div class="mt-2 flex items-center text-xs font-medium text-amber-600 bg-amber-50 w-fit px-2 py-1 rounded-full">
                                <span>High Engagement</span>
                            </div>
                        </div>
                    </div>

                    <div class="group relative bg-white/70 backdrop-blur-xl border border-slate-200 rounded-2xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <BeakerIcon class="w-16 h-16 text-purple-600 rotate-12" />
                        </div>
                        <div class="relative">
                            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <BeakerIcon class="w-6 h-6 text-purple-600" />
                            </div>
                            <p class="text-sm font-medium text-slate-500">Avg. Integrity Score</p>
                            <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ parseFloat(stats.avg_score).toFixed(1) }}</h3>
                            <div class="mt-2 flex items-center text-xs font-medium text-purple-600 bg-purple-50 w-fit px-2 py-1 rounded-full">
                                <ArrowTrendingUpIcon class="w-3 h-3 mr-1" />
                                <span>+2.5% vs Last Month</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <div class="lg:col-span-2 bg-white/70 backdrop-blur-xl border border-slate-200 rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Platform Analytics</h3>
                                <p class="text-sm text-slate-500">Real-time data visualization</p>
                            </div>
                            <button class="text-xs font-semibold text-indigo-600 hover:bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors border border-indigo-200">
                                Download Report
                            </button>
                        </div>
                        <div class="h-80 w-full">
                            <ChartComponent type="bar" :data="chartData" :options="chartOptions" />
                        </div>
                    </div>

                    <div class="bg-indigo-900 text-white rounded-2xl p-6 shadow-xl relative overflow-hidden flex flex-col justify-between">
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
                        <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-40 h-40 bg-indigo-500 opacity-20 rounded-full blur-2xl"></div>
                        
                        <div class="relative z-10">
                            <h3 class="text-lg font-bold mb-1">Weekly Highlight</h3>
                            <p class="text-indigo-200 text-sm mb-6">Latest system simulation analysis.</p>
                            
                            <div class="space-y-4">
                                <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/10">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-xs text-indigo-200 uppercase tracking-wider font-semibold">Top Performing Node</p>
                                            <p class="text-lg font-bold mt-1">Jakarta Governance Lab</p>
                                        </div>
                                        <div class="bg-emerald-500/20 text-emerald-300 text-xs px-2 py-1 rounded font-bold">+12%</div>
                                    </div>
                                </div>
                                <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/10">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-xs text-indigo-200 uppercase tracking-wider font-semibold">Active Participants</p>
                                            <p class="text-lg font-bold mt-1">1,240 Researchers</p>
                                        </div>
                                        <div class="bg-indigo-500/20 text-indigo-300 text-xs px-2 py-1 rounded font-bold">New</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="relative z-10 mt-6 pt-6 border-t border-white/10">
                            <button class="w-full py-2 bg-white text-indigo-900 font-bold rounded-lg hover:bg-indigo-50 transition-colors text-sm">
                                View Full Analysis
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <div class="bg-white/70 backdrop-blur-xl border border-slate-200 rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center space-x-2 mb-6">
                            <div class="p-2 bg-indigo-100 rounded-lg">
                                <BeakerIcon class="w-5 h-5 text-indigo-600" />
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">Recent Simulations</h3>
                        </div>

                        <div class="space-y-3">
                            <div v-if="recent_simulations.length === 0" class="text-center py-12 text-slate-400">
                                <BeakerIcon class="w-12 h-12 mx-auto mb-2 opacity-20" />
                                <p class="text-sm">No simulations recorded yet.</p>
                            </div>

                            <div 
                                v-for="sim in recent_simulations" 
                                :key="sim.id"
                                class="group flex items-center justify-between p-4 rounded-xl border border-slate-100 bg-white hover:border-indigo-200 hover:shadow-md transition-all duration-200"
                            >
                                <div class="flex items-center space-x-4">
                                    <div class="w-2 h-2 rounded-full" :class="sim.change_percent > 0 ? 'bg-emerald-500' : 'bg-rose-500'"></div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900 group-hover:text-indigo-600 transition-colors">
                                            {{ sim.node?.name || 'Unknown Node' }}
                                        </p>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            Indicator: {{ sim.indicator }}
                                        </p>
                                    </div>
                                </div>
                                <div 
                                    class="text-xs font-bold px-3 py-1 rounded-full"
                                    :class="sim.change_percent > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'"
                                >
                                    {{ sim.change_percent > 0 ? '+' : '' }}{{ sim.change_percent }}%
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/70 backdrop-blur-xl border border-slate-200 rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center space-x-2 mb-6">
                            <div class="p-2 bg-amber-100 rounded-lg">
                                <ChatBubbleLeftRightIcon class="w-5 h-5 text-amber-600" />
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">Recent Discussions</h3>
                        </div>

                        <div class="space-y-3">
                            <div v-if="recent_discussions.length === 0" class="text-center py-12 text-slate-400">
                                <ChatBubbleLeftRightIcon class="w-12 h-12 mx-auto mb-2 opacity-20" />
                                <p class="text-sm">No discussions started yet.</p>
                            </div>

                            <div 
                                v-for="lab in recent_discussions" 
                                :key="lab.id"
                                class="group flex items-start space-x-4 p-4 rounded-xl border border-slate-100 bg-white hover:border-amber-200 hover:shadow-md transition-all duration-200 cursor-pointer"
                            >
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600 group-hover:bg-amber-100 group-hover:text-amber-600 transition-colors">
                                        {{ lab.title.charAt(0) }}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 truncate group-hover:text-amber-600 transition-colors">
                                        {{ lab.title }}
                                    </p>
                                    <div class="flex items-center mt-1 space-x-3">
                                        <span class="text-xs text-slate-500 flex items-center">
                                            <ClockIcon class="w-3 h-3 mr-1" />
                                            {{ formatDate(lab.created_at) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <ArrowTrendingUpIcon class="w-4 h-4 text-slate-300 group-hover:text-amber-500 transition-colors" />
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>