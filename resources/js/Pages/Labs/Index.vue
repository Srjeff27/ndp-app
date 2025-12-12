<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    ChatBubbleLeftRightIcon,
    PlusIcon,
    UserIcon,
    CalendarIcon,
    ArrowLongRightIcon,
    XMarkIcon,
    TrashIcon,
    BeakerIcon,
    HashtagIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    labs: Array
});

const showForm = ref(false);

const form = useForm({
    title: '',
    description: '',
});

const submit = () => {
    form.post(route('labs.store'), {
        onSuccess: () => {
            form.reset();
            showForm.value = false;
        },
    });
};

const deleteLab = (labId) => {
    if (confirm('Are you sure you want to retract this discussion topic? This cannot be undone.')) {
        useForm({}).delete(route('labs.destroy', labId));
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-GB', {
        day: 'numeric', 
        month: 'short', 
        year: 'numeric'
    });
};

const getInitials = (name) => {
    return name ? name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase() : '??';
};
</script>

<template>
    <Head title="Civic Labs" />

    <AuthenticatedLayout>
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-slate-50"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-amber-500/5 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-orange-500/5 rounded-full blur-[100px]"></div>
        </div>

        <template #header>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-start space-x-4">
                    <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg shadow-amber-500/30 text-white">
                        <BeakerIcon class="w-8 h-8" />
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Civic Labs</h2>
                        <p class="text-sm text-slate-500 mt-1">Collaborative space for democratic deliberation & policy experiments.</p>
                    </div>
                </div>
                
                <button
                    @click="showForm = !showForm"
                    class="group relative inline-flex items-center justify-center px-6 py-3 bg-slate-900 text-white font-semibold rounded-xl hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 overflow-hidden"
                >
                    <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-amber-500 to-orange-500 opacity-0 group-hover:opacity-10 transition-opacity"></span>
                    <PlusIcon class="w-5 h-5 mr-2" />
                    <span>Initiate Discussion</span>
                </button>
            </div>
        </template>

        <div class="relative z-10 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <transition
                    enter-active-class="transition ease-out duration-300"
                    enter-from-class="transform opacity-0 -translate-y-4"
                    enter-to-class="transform opacity-100 translate-y-0"
                    leave-active-class="transition ease-in duration-200"
                    leave-from-class="transform opacity-100 translate-y-0"
                    leave-to-class="transform opacity-0 -translate-y-4"
                >
                    <div v-if="showForm" class="mb-12 bg-white/80 backdrop-blur-xl border border-amber-500/20 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-amber-500/10">
                        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="font-bold text-slate-800 flex items-center">
                                <ChatBubbleLeftRightIcon class="w-5 h-5 mr-2 text-amber-500" />
                                New Lab Topic
                            </h3>
                            <button @click="showForm = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <XMarkIcon class="w-6 h-6" />
                            </button>
                        </div>
                        
                        <form @submit.prevent="submit" class="p-6 md:p-8 space-y-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Research Question / Title</label>
                                <input 
                                    v-model="form.title" 
                                    type="text" 
                                    class="block w-full text-lg font-semibold rounded-xl border-slate-200 bg-slate-50 focus:border-amber-500 focus:ring-amber-500 transition-colors placeholder:font-normal" 
                                    placeholder="e.g. How can we improve local budget transparency?"
                                    required 
                                />
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Context & Hypothesis</label>
                                <textarea 
                                    v-model="form.description" 
                                    rows="5"
                                    class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-amber-500 focus:ring-amber-500 transition-colors leading-relaxed" 
                                    placeholder="Provide background context and the goal of this discussion..."
                                    required
                                ></textarea>
                            </div>
                            <div class="flex justify-end pt-4">
                                <button 
                                    type="submit" 
                                    :disabled="form.processing"
                                    class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-bold rounded-xl shadow-lg shadow-amber-500/20 transform hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    Launch Discussion
                                </button>
                            </div>
                        </form>
                    </div>
                </transition>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    
                    <div 
                        v-for="lab in labs" 
                        :key="lab.id"
                        class="group relative flex flex-col h-full bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-xl hover:border-amber-500/30 transition-all duration-300"
                    >
                        <div class="p-6 pb-0 flex-1">
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                    <HashtagIcon class="w-3 h-3 mr-1" />
                                    Issue #{{ lab.id }}
                                </span>
                                <span class="text-xs text-slate-400 font-mono">
                                    {{ formatDate(lab.created_at) }}
                                </span>
                            </div>

                            <Link :href="route('labs.show', lab.id)" class="block">
                                <h3 class="text-xl font-bold text-slate-900 mb-3 leading-tight group-hover:text-amber-600 transition-colors">
                                    {{ lab.title }}
                                </h3>
                            </Link>
                            
                            <p class="text-slate-600 text-sm leading-relaxed line-clamp-3 mb-6">
                                {{ lab.description }}
                            </p>
                        </div>

                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-tr from-slate-200 to-slate-300 flex items-center justify-center text-xs font-bold text-slate-600">
                                    {{ getInitials(lab.creator?.name) }}
                                </div>
                                <div class="text-xs">
                                    <p class="font-semibold text-slate-900">{{ lab.creator?.name || 'Anonymous' }}</p>
                                    <p class="text-slate-500">Researcher</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <button
                                    v-if="$page.props.auth.permissions.includes('manage labs')"
                                    @click.prevent="deleteLab(lab.id)"
                                    class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-full transition-colors"
                                    title="Delete Topic"
                                >
                                    <TrashIcon class="w-4 h-4" />
                                </button>
                                
                                <Link 
                                    :href="route('labs.show', lab.id)" 
                                    class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-full transition-colors"
                                >
                                    <ArrowLongRightIcon class="w-5 h-5" />
                                </Link>
                            </div>
                        </div>
                    </div>

                    </div>

                <div v-if="labs.length === 0" class="flex flex-col items-center justify-center py-20 bg-white/50 backdrop-blur-sm border border-dashed border-slate-300 rounded-3xl">
                    <div class="w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center mb-6">
                        <ChatBubbleLeftRightIcon class="w-10 h-10 text-amber-500" />
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">No active labs yet</h3>
                    <p class="text-slate-500 max-w-md text-center mb-8">
                        The discussion floor is empty. Be the first to propose a topic for democratic deliberation.
                    </p>
                    <button
                        @click="showForm = true"
                        class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg shadow-md transition-colors"
                    >
                        Start First Discussion
                    </button>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>