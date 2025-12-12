<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import MapComponent from '@/Components/MapComponent.vue';
import { computed, ref } from 'vue';
import {
    GlobeAltIcon,
    PlusIcon,
    MapPinIcon,
    BuildingLibraryIcon,
    XMarkIcon,
    SignalIcon,
    ArrowTopRightOnSquareIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    nodes: Array
});

const page = usePage();
const permissions = computed(() => page.props.auth.permissions);
const canManageNodes = computed(() => permissions.value.includes('manage nodes'));

const showForm = ref(false);

const form = useForm({
    name: '',
    institution: '',
    country: '',
    lat: '',
    lng: ''
});

const submit = () => {
    form.post(route('nodes.store'), {
        onSuccess: () => {
            form.reset();
            showForm.value = false;
        },
    });
};
</script>

<template>
    <Head title="Governance Nodes" />

    <AuthenticatedLayout>
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-slate-50"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-purple-500/10 rounded-full blur-[100px]"></div>
        </div>

        <template #header>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-white rounded-xl shadow-sm border border-slate-200">
                        <GlobeAltIcon class="w-8 h-8 text-indigo-600" />
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Governance Nodes</h2>
                        <div class="flex items-center space-x-2 text-sm text-slate-500">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span>Global Institutional Network Tracker</span>
                        </div>
                    </div>
                </div>
                
                <button
                    v-if="canManageNodes"
                    @click="showForm = !showForm"
                    class="group inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-indigo-500/30 transition-all duration-300"
                >
                    <component :is="showForm ? XMarkIcon : PlusIcon" class="w-5 h-5 mr-2 transition-transform group-hover:rotate-90" />
                    {{ showForm ? 'Cancel Entry' : 'Register Node' }}
                </button>
            </div>
        </template>

        <div class="relative z-10 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                
                <transition
                    enter-active-class="transition ease-out duration-300"
                    enter-from-class="transform opacity-0 -translate-y-4 scale-95"
                    enter-to-class="transform opacity-100 translate-y-0 scale-100"
                    leave-active-class="transition ease-in duration-200"
                    leave-from-class="transform opacity-100 translate-y-0 scale-100"
                    leave-to-class="transform opacity-0 -translate-y-4 scale-95"
                >
                    <div v-if="showForm && canManageNodes" class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-indigo-500/20 overflow-hidden ring-1 ring-indigo-500/10">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="font-bold text-slate-800 flex items-center">
                                <BuildingLibraryIcon class="w-5 h-5 mr-2 text-indigo-500" />
                                Institutional Data Entry
                            </h3>
                            <span class="text-[10px] font-mono text-slate-400 uppercase tracking-widest">Geo-Tagging Protocol</span>
                        </div>
                        
                        <form @submit.prevent="submit" class="p-6 md:p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-5">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Institution Name</label>
                                        <input 
                                            v-model="form.name" 
                                            type="text" 
                                            class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 transition-colors" 
                                            placeholder="e.g. Center for Digital Democracy"
                                            required 
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Department / Unit</label>
                                        <input 
                                            v-model="form.institution" 
                                            type="text" 
                                            class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 transition-colors" 
                                            placeholder="e.g. Faculty of Social Sciences"
                                        />
                                    </div>
                                </div>

                                <div class="space-y-5">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Country / Region</label>
                                        <div class="relative">
                                            <input 
                                                v-model="form.country" 
                                                type="text" 
                                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 transition-colors pl-10" 
                                                placeholder="e.g. Indonesia"
                                                required 
                                            />
                                            <GlobeAltIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Geospatial Coordinates</label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="relative">
                                                <input 
                                                    v-model="form.lat" 
                                                    type="number" 
                                                    step="any"
                                                    class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 transition-colors font-mono text-sm pl-8" 
                                                    placeholder="Lat"
                                                    required 
                                                />
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-serif italic text-xs">φ</span>
                                            </div>
                                            <div class="relative">
                                                <input 
                                                    v-model="form.lng" 
                                                    type="number" 
                                                    step="any"
                                                    class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 transition-colors font-mono text-sm pl-8" 
                                                    placeholder="Lng"
                                                    required 
                                                />
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-serif italic text-xs">λ</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-8 flex justify-end">
                                <button 
                                    type="submit" 
                                    :disabled="form.processing"
                                    class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-xl shadow-md transition-all disabled:opacity-50"
                                >
                                    <MapPinIcon class="w-5 h-5 mr-2" />
                                    Initialize Node
                                </button>
                            </div>
                        </form>
                    </div>
                </transition>

                <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl overflow-hidden relative group">
                    <div class="absolute top-0 left-0 right-0 z-10 bg-gradient-to-b from-slate-900/80 to-transparent p-4 flex justify-between items-start pointer-events-none">
                        <div>
                            <h3 class="text-white font-bold text-lg flex items-center shadow-black drop-shadow-md">
                                <SignalIcon class="w-5 h-5 mr-2 text-emerald-400 animate-pulse" />
                                Network Topology
                            </h3>
                            <p class="text-slate-300 text-xs shadow-black drop-shadow-md pl-7">{{ nodes.length }} active nodes connected</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md px-3 py-1 rounded-full border border-white/20 text-xs font-mono text-white">
                            LIVE VIEW
                        </div>
                    </div>

                    <div class="h-[450px] w-full bg-slate-100 relative">
                        <MapComponent :nodes="nodes" class="h-full w-full" />
                    </div>
                </div>

                <div class="pt-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-slate-900">Registered Nodes</h3>
                        <div class="text-sm text-slate-500">Sorted by recent activity</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div 
                            v-for="node in nodes" 
                            :key="node.id"
                            class="group bg-white/50 backdrop-blur-sm border border-slate-200 rounded-2xl p-5 hover:bg-white hover:shadow-xl hover:border-indigo-500/30 transition-all duration-300 relative overflow-hidden"
                        >
                            <BuildingLibraryIcon class="absolute -right-6 -bottom-6 w-32 h-32 text-slate-100 group-hover:text-indigo-50 transition-colors pointer-events-none rotate-12" />

                            <div class="relative z-10">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="p-2.5 bg-indigo-50 rounded-xl group-hover:bg-indigo-600 group-hover:text-white text-indigo-600 transition-colors duration-300">
                                        <BuildingLibraryIcon class="w-6 h-6" />
                                    </div>
                                    <div class="text-[10px] font-mono text-slate-400 bg-slate-100 px-2 py-1 rounded border border-slate-200">
                                        ID-{{ node.id.toString().padStart(4, '0') }}
                                    </div>
                                </div>
                                
                                <h4 class="text-lg font-bold text-slate-900 mb-1 group-hover:text-indigo-600 transition-colors">
                                    {{ node.name }}
                                </h4>
                                <p class="text-sm text-slate-500 mb-4 h-10 line-clamp-2">
                                    {{ node.institution || 'Institutional affiliation not specified' }}
                                </p>

                                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                    <div class="flex items-center text-xs text-slate-500 font-medium">
                                        <MapPinIcon class="w-3.5 h-3.5 mr-1 text-rose-500" />
                                        {{ node.country }}
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-[10px] font-mono text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded">
                                            {{ parseFloat(node.lat).toFixed(2) }}, {{ parseFloat(node.lng).toFixed(2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="nodes.length === 0" class="mt-10 text-center py-20 bg-white/30 backdrop-blur-sm border border-dashed border-slate-300 rounded-3xl">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <GlobeAltIcon class="w-10 h-10 text-slate-400" />
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Network Offline</h3>
                        <p class="text-slate-500 mt-2 max-w-sm mx-auto mb-8">
                            No governance nodes have been registered in the system yet. Initialize the first node to begin mapping.
                        </p>
                        <button
                            v-if="canManageNodes"
                            @click="showForm = true"
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-all shadow-lg hover:shadow-indigo-500/25"
                        >
                            <PlusIcon class="w-5 h-5 mr-2" />
                            Initialize First Node
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>