<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import {
    ChartBarIcon,
    PlusIcon,
    DocumentTextIcon,
    XMarkIcon,
    GlobeAltIcon,
    MagnifyingGlassIcon,
    ArrowPathIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    entries: Array
});

const page = usePage();
const permissions = computed(() => page.props.auth.permissions);
const canManageAtlas = computed(() => permissions.value.includes('manage atlas'));

const showForm = ref(false);
const searchQuery = ref('');

const form = useForm({
    node_id: '',
    indicator: '',
    score: '',
    source: ''
});

// Filter entries based on search
const filteredEntries = computed(() => {
    if (!searchQuery.value) return props.entries;
    const lowerQuery = searchQuery.value.toLowerCase();
    return props.entries.filter(entry => 
        entry.indicator.toLowerCase().includes(lowerQuery) ||
        entry.node?.name.toLowerCase().includes(lowerQuery) ||
        entry.source?.toLowerCase().includes(lowerQuery)
    );
});

const submit = () => {
    form.post(route('atlas.store'), {
        onSuccess: () => {
            form.reset();
            showForm.value = false;
        },
    });
};

const getScoreColor = (score) => {
    if (score >= 80) return 'bg-emerald-500 text-emerald-700 bg-emerald-100';
    if (score >= 60) return 'bg-indigo-500 text-indigo-700 bg-indigo-100';
    if (score >= 40) return 'bg-amber-500 text-amber-700 bg-amber-100';
    return 'bg-rose-500 text-rose-700 bg-rose-100';
};

const getProgressBarColor = (score) => {
    if (score >= 80) return 'bg-gradient-to-r from-emerald-400 to-emerald-600';
    if (score >= 60) return 'bg-gradient-to-r from-indigo-400 to-indigo-600';
    if (score >= 40) return 'bg-gradient-to-r from-amber-400 to-amber-600';
    return 'bg-gradient-to-r from-rose-400 to-rose-600';
};
</script>

<template>
    <Head title="Governance Atlas" />

    <AuthenticatedLayout>
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-slate-50"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
            <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-[100px]"></div>
        </div>

        <template #header>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-white rounded-lg shadow-sm border border-slate-200">
                        <GlobeAltIcon class="w-6 h-6 text-emerald-600" />
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Governance Atlas</h2>
                        <p class="text-sm text-slate-500">Global accountability indicators & metrics</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="relative hidden sm:block">
                        <MagnifyingGlassIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                        <input 
                            v-model="searchQuery"
                            type="text" 
                            placeholder="Search data..." 
                            class="pl-9 pr-4 py-2 w-64 bg-white/50 backdrop-blur-sm border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                        >
                    </div>

                    <button
                        v-if="canManageAtlas"
                        @click="showForm = !showForm"
                        class="inline-flex items-center px-4 py-2 bg-slate-900 text-white font-semibold rounded-lg hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 text-sm"
                    >
                        <component :is="showForm ? XMarkIcon : PlusIcon" class="w-4 h-4 mr-2" />
                        {{ showForm ? 'Close Panel' : 'New Indicator' }}
                    </button>
                </div>
            </div>
        </template>

        <div class="relative z-10 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <transition
                    enter-active-class="transition ease-out duration-300"
                    enter-from-class="transform opacity-0 -translate-y-4 scale-95"
                    enter-to-class="transform opacity-100 translate-y-0 scale-100"
                    leave-active-class="transition ease-in duration-200"
                    leave-from-class="transform opacity-100 translate-y-0 scale-100"
                    leave-to-class="transform opacity-0 -translate-y-4 scale-95"
                >
                    <div v-if="showForm && canManageAtlas" class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-emerald-500/20 overflow-hidden ring-1 ring-emerald-500/10">
                        <div class="border-b border-slate-200 bg-slate-50/50 px-6 py-4 flex items-center justify-between">
                            <h3 class="font-semibold text-slate-900 flex items-center">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 text-xs mr-2">1</span>
                                Data Entry Protocol
                            </h3>
                            <span class="text-xs font-mono text-slate-500 bg-slate-200 px-2 py-1 rounded">SECURE INPUT</span>
                        </div>
                        
                        <form @submit.prevent="submit" class="p-6 md:p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Node Identification</label>
                                        <div class="relative">
                                            <input 
                                                v-model="form.node_id" 
                                                type="number" 
                                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-emerald-500 focus:ring-emerald-500 transition-colors" 
                                                placeholder="Enter Node ID Reference"
                                                required 
                                            />
                                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400">#ID</div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Metric Score</label>
                                        <div class="relative">
                                            <input 
                                                v-model="form.score" 
                                                type="number" 
                                                step="0.1" 
                                                min="0" 
                                                max="100"
                                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-emerald-500 focus:ring-emerald-500 transition-colors pl-4 pr-12 text-lg font-mono font-medium" 
                                                placeholder="0.0"
                                                required 
                                            />
                                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-bold text-emerald-600">/ 100</div>
                                        </div>
                                        <div class="mt-2 h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full transition-all duration-500" :class="getProgressBarColor(form.score || 0)" :style="{ width: `${form.score || 0}%` }"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Indicator Name</label>
                                        <input 
                                            v-model="form.indicator" 
                                            type="text" 
                                            class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-emerald-500 focus:ring-emerald-500 transition-colors" 
                                            placeholder="e.g. Political Transparency Index"
                                            required 
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Data Source Verification</label>
                                        <div class="relative">
                                            <input 
                                                v-model="form.source" 
                                                type="text" 
                                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-emerald-500 focus:ring-emerald-500 transition-colors pl-10" 
                                                placeholder="e.g. World Bank Open Data 2024"
                                            />
                                            <DocumentTextIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end items-center gap-3 pt-6 border-t border-slate-100">
                                <button 
                                    type="button" 
                                    @click="showForm = false"
                                    class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors"
                                >
                                    Cancel Operation
                                </button>
                                <button 
                                    type="submit" 
                                    :disabled="form.processing"
                                    class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-sm font-semibold rounded-lg shadow-md shadow-emerald-500/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <ArrowPathIcon v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                                    <span v-else>Confirm Entry</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </transition>

                <div class="bg-white/70 backdrop-blur-xl border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    
                    <div v-if="searchQuery" class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-emerald-50/50">
                        <p class="text-sm text-emerald-700 font-medium">
                            Found {{ filteredEntries.length }} matching results
                        </p>
                        <button @click="searchQuery = ''" class="text-xs text-emerald-600 hover:underline">Clear Search</button>
                    </div>

                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Node / Region</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Indicator Metric</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-1/4">Integrity Score</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Source Reference</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="entry in filteredEntries" :key="entry.id" class="group hover:bg-white transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-9 w-9 bg-slate-100 rounded-lg flex items-center justify-center text-slate-500 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                                <GlobeAltIcon class="h-5 w-5" />
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-slate-900">{{ entry.node?.name || 'Unknown Node' }}</div>
                                                <div class="text-xs text-slate-500">ID: {{ entry.node_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-700 font-medium">{{ entry.indicator }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <span 
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold"
                                                :class="getScoreColor(entry.score)"
                                            >
                                                {{ entry.score }}
                                            </span>
                                            <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                                <div 
                                                    class="h-full rounded-full shadow-sm"
                                                    :class="getProgressBarColor(entry.score)"
                                                    :style="{ width: `${entry.score}%` }"
                                                ></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center text-xs text-slate-500 bg-slate-50 px-3 py-1.5 rounded-md w-fit border border-slate-100">
                                            <DocumentTextIcon class="flex-shrink-0 h-3.5 w-3.5 mr-1.5" />
                                            <span class="truncate max-w-[150px]">{{ entry.source || 'Unverified Source' }}</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="md:hidden">
                        <div class="p-4 border-b border-slate-100">
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Search indicators..." 
                                class="w-full bg-slate-50 border-none rounded-lg text-sm py-3 px-4 focus:ring-2 focus:ring-emerald-500"
                            >
                        </div>

                        <ul class="divide-y divide-slate-100">
                            <li v-for="entry in filteredEntries" :key="entry.id" class="p-5 bg-white hover:bg-slate-50 transition-colors">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-indigo-50 rounded-lg">
                                            <ChartBarIcon class="w-5 h-5 text-indigo-600" />
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-900">{{ entry.node?.name }}</h4>
                                            <p class="text-xs text-slate-500">{{ entry.indicator }}</p>
                                        </div>
                                    </div>
                                    <span 
                                        class="px-2.5 py-1 rounded-md text-xs font-bold border"
                                        :class="[getScoreColor(entry.score), 'border-transparent bg-opacity-10']"
                                    >
                                        {{ entry.score }}
                                    </span>
                                </div>
                                
                                <div class="mt-4 space-y-3">
                                    <div class="relative h-2 bg-slate-100 rounded-full overflow-hidden">
                                        <div 
                                            class="absolute top-0 left-0 h-full rounded-full"
                                            :class="getProgressBarColor(entry.score)"
                                            :style="{ width: `${entry.score}%` }"
                                        ></div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <span class="flex items-center">
                                            <DocumentTextIcon class="w-3.5 h-3.5 mr-1" />
                                            {{ entry.source || 'N/A' }}
                                        </span>
                                        <span class="font-mono opacity-50">ID:{{ entry.id }}</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div v-if="filteredEntries.length === 0" class="flex flex-col items-center justify-center py-16 px-4 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 ring-8 ring-slate-50/50">
                            <MagnifyingGlassIcon class="w-10 h-10 text-slate-300" />
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">No indicators found</h3>
                        <p class="text-slate-500 mt-1 max-w-sm mx-auto">
                            {{ searchQuery ? `We couldn't find any data matching "${searchQuery}".` : 'The atlas is currently empty. Start by adding your first indicator.' }}
                        </p>
                        <button 
                            v-if="!searchQuery && canManageAtlas"
                            @click="showForm = true"
                            class="mt-6 text-emerald-600 hover:text-emerald-700 font-medium text-sm flex items-center"
                        >
                            <PlusIcon class="w-4 h-4 mr-1" />
                            Add Entry Now
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>