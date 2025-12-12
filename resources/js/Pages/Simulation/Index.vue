<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import ChartComponent from '@/Components/ChartComponent.vue';
import { computed } from 'vue';
import {
    BeakerIcon,
    ChartBarIcon,
    ArrowTrendingUpIcon,
    ArrowTrendingDownIcon,
    BuildingLibraryIcon,
    AdjustmentsHorizontalIcon,
    PlayIcon,
    ArrowPathIcon,
    CalculatorIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    nodes: Array
});

const page = usePage();
const result = computed(() => page.props.flash.result);

const form = useForm({
    node_id: '',
    indicator: '',
    old_score: 0,
    change_percent: 0
});

const selectedNode = computed(() => {
    return props.nodes.find(n => n.id === form.node_id);
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
                'rgba(148, 163, 184, 0.5)', // Slate for baseline
                result.value.change_percent > 0 ? 'rgba(16, 185, 129, 0.8)' : 'rgba(244, 63, 94, 0.8)' // Emerald/Rose for result
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
</script>

<template>
    <Head title="Simulation Engine" />

    <AuthenticatedLayout>
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-slate-50"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-rose-500/5 rounded-full blur-[100px]"></div>
        </div>

        <template #header>
            <div class="relative z-10 flex items-center space-x-4">
                <div class="p-3 bg-white rounded-xl shadow-sm border border-slate-200">
                    <BeakerIcon class="w-8 h-8 text-rose-500" />
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Simulation Engine</h2>
                    <p class="text-sm text-slate-500">Predictive modeling for governance impact analysis.</p>
                </div>
            </div>
        </template>

        <div class="relative z-10 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    <div class="lg:col-span-4 space-y-6">
                        <div class="bg-white/80 backdrop-blur-xl border border-slate-200 rounded-2xl shadow-xl overflow-hidden">
                            <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                                <h3 class="font-bold text-slate-800 flex items-center text-sm uppercase tracking-wider">
                                    <AdjustmentsHorizontalIcon class="w-4 h-4 mr-2 text-rose-500" />
                                    Parameters
                                </h3>
                                <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            </div>

                            <form @submit.prevent="submit" class="p-6 space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Target Node</label>
                                    <div class="relative">
                                        <select 
                                            v-model="form.node_id" 
                                            class="block w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:border-rose-500 focus:ring-rose-500 text-sm transition-shadow shadow-sm appearance-none"
                                            required
                                        >
                                            <option value="" disabled>Select Institution...</option>
                                            <option v-for="node in nodes" :key="node.id" :value="node.id">{{ node.name }}</option>
                                        </select>
                                        <BuildingLibraryIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                                    </div>
                                </div>

                                <div class="relative">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Metric Indicator</label>
                                    <div class="relative">
                                        <select 
                                            v-model="form.indicator" 
                                            @change="onIndicatorChange"
                                            class="block w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:border-rose-500 focus:ring-rose-500 text-sm transition-shadow shadow-sm appearance-none disabled:opacity-50"
                                            :disabled="!selectedNode"
                                            required
                                        >
                                            <option value="" disabled>Select Indicator...</option>
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
                                            <span class="text-xs font-medium text-slate-500">Baseline Score</span>
                                            <span class="text-xl font-bold font-mono text-slate-900">{{ form.old_score }}</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-1.5">
                                            <div class="bg-slate-500 h-1.5 rounded-full transition-all duration-1000" :style="{ width: form.old_score + '%' }"></div>
                                        </div>
                                    </div>
                                </transition>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Impact Variable (%)</label>
                                    <div class="relative flex items-center">
                                        <button 
                                            type="button" 
                                            @click="form.change_percent--"
                                            class="p-3 bg-slate-100 hover:bg-rose-100 hover:text-rose-600 rounded-l-xl border border-r-0 border-slate-200 transition-colors"
                                        >
                                            <span class="sr-only">Decrease</span>
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
                                            <span class="sr-only">Increase</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                        </button>
                                    </div>
                                    <p class="text-xs text-center text-slate-400 mt-2">Use negative values for regression scenarios.</p>
                                </div>

                                <button 
                                    type="submit" 
                                    :disabled="!form.indicator || form.processing"
                                    class="w-full flex items-center justify-center py-3.5 px-4 bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-500 hover:to-pink-500 text-white font-bold rounded-xl shadow-lg shadow-rose-500/30 transition-all transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none"
                                >
                                    <ArrowPathIcon v-if="form.processing" class="w-5 h-5 mr-2 animate-spin" />
                                    <PlayIcon v-else class="w-5 h-5 mr-2" />
                                    Run Simulation
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="lg:col-span-8">
                        <div class="h-full flex flex-col">
                            <div class="bg-white/80 backdrop-blur-xl border border-slate-200 rounded-2xl shadow-xl overflow-hidden flex-1 flex flex-col">
                                
                                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                    <h3 class="font-bold text-slate-800 flex items-center text-sm uppercase tracking-wider">
                                        <CalculatorIcon class="w-4 h-4 mr-2 text-rose-500" />
                                        Projection Analysis
                                    </h3>
                                    <span v-if="result" class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-1 rounded border border-emerald-200">
                                        CALCULATION COMPLETE
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
                                                    <p class="text-xs text-slate-500 uppercase font-bold">Projected</p>
                                                    <p class="text-3xl font-mono font-bold text-slate-900">{{ result.new_score.toFixed(1) }}</p>
                                                </div>
                                                <div class="p-4 rounded-xl border flex flex-col justify-center relative overflow-hidden" 
                                                    :class="result.change_percent > 0 ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-rose-50 border-rose-100 text-rose-700'">
                                                    <p class="text-xs uppercase font-bold opacity-70">Impact Delta</p>
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
                                            <h4 class="text-xl font-bold text-slate-700">Ready for Analysis</h4>
                                            <p class="text-slate-500 max-w-sm mt-2">Configure the parameters on the left panel and execute the simulation to visualize projected outcomes.</p>
                                        </div>
                                    </transition>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>