<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { 
    ChatBubbleLeftRightIcon, 
    UserIcon, 
    CalendarIcon, 
    PaperAirplaneIcon, 
    TrashIcon, 
    ArrowLeftIcon,
    TagIcon,
    AcademicCapIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    lab: Object,
    canModerate: Boolean
});

const form = useForm({
    content: ''
});

const submit = () => {
    form.post(route('labs.posts.store', props.lab.id), {
        onSuccess: () => form.reset(),
        preserveScroll: true,
    });
};

const deleteLab = () => {
    if (confirm('Are you sure you want to retract this discussion topic? This action cannot be undone.')) {
        useForm({}).delete(route('labs.destroy', props.lab.id));
    }
};

const deletePost = (postId) => {
    if (confirm('Are you sure you want to delete this comment? This action cannot be undone.')) {
        useForm({}).delete(route('labs.posts.destroy', [props.lab.id, postId]));
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-GB', {
        weekday: 'short',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const getInitials = (name) => {
    return name ? name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase() : '??';
};
</script>

<template>
    <Head :title="lab.title" />

    <AuthenticatedLayout>
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-slate-50"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[400px] bg-gradient-to-b from-amber-500/5 to-transparent blur-3xl"></div>
        </div>

        <div class="relative z-10 py-10">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="flex items-center justify-between mb-8">
                    <Link 
                        :href="route('labs.index')" 
                        class="group inline-flex items-center text-sm font-medium text-slate-500 hover:text-amber-600 transition-colors"
                    >
                        <div class="p-2 mr-2 rounded-full bg-white border border-slate-200 group-hover:border-amber-500/30 transition-colors">
                            <ArrowLeftIcon class="w-4 h-4" />
                        </div>
                        Back to Labs
                    </Link>

                    <button
                        v-if="canModerate || lab.user_id === $page.props.auth.user.id"
                        @click="deleteLab"
                        class="inline-flex items-center px-4 py-2 text-xs font-bold text-rose-600 bg-rose-50 rounded-lg hover:bg-rose-100 border border-rose-200 transition-colors"
                    >
                        <TrashIcon class="w-4 h-4 mr-2" />
                        Delete Discussion
                    </button>
                </div>

                <article class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden mb-10">
                    <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 md:px-8">
                        <div class="flex flex-wrap items-center gap-4 text-xs font-medium text-slate-500 mb-4">
                            <div class="flex items-center bg-white px-3 py-1 rounded-full border border-slate-200 shadow-sm">
                                <UserIcon class="w-3.5 h-3.5 mr-1.5 text-amber-500" />
                                {{ lab.creator?.name }}
                            </div>
                            <div class="flex items-center bg-white px-3 py-1 rounded-full border border-slate-200 shadow-sm">
                                <CalendarIcon class="w-3.5 h-3.5 mr-1.5 text-amber-500" />
                                {{ formatDate(lab.created_at) }}
                            </div>
                            <div class="flex items-center bg-white px-3 py-1 rounded-full border border-slate-200 shadow-sm">
                                <TagIcon class="w-3.5 h-3.5 mr-1.5 text-amber-500" />
                                Topic #{{ lab.id }}
                            </div>
                        </div>
                        
                        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 leading-tight">
                            {{ lab.title }}
                        </h1>
                    </div>

                    <div class="p-6 md:p-8">
                        <div class="prose prose-slate prose-lg max-w-none leading-relaxed">
                            <p class="whitespace-pre-wrap">{{ lab.description }}</p>
                        </div>
                    </div>
                </article>

                <div class="relative">
                    <div class="flex items-center space-x-4 mb-8">
                        <div class="h-px flex-1 bg-slate-200"></div>
                        <h2 class="text-lg font-bold text-slate-900 flex items-center">
                            <ChatBubbleLeftRightIcon class="w-5 h-5 mr-2 text-amber-500" />
                            Deliberation Log
                            <span class="ml-2 px-2 py-0.5 rounded-full bg-slate-100 text-xs text-slate-500">
                                {{ lab.posts?.length || 0 }}
                            </span>
                        </h2>
                        <div class="h-px flex-1 bg-slate-200"></div>
                    </div>

                    <div class="space-y-8 pl-4 md:pl-0">
                        <div 
                            v-for="(post, index) in lab.posts" 
                            :key="post.id"
                            class="relative md:flex md:space-x-6 group"
                        >
                            <div 
                                v-if="index !== lab.posts.length - 1" 
                                class="absolute top-12 left-5 bottom-[-32px] w-0.5 bg-slate-200 md:left-[2.25rem] -z-10"
                            ></div>

                            <div class="flex-shrink-0 mb-3 md:mb-0">
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 border-2 border-white shadow-sm flex items-center justify-center text-sm font-bold text-slate-600">
                                    {{ getInitials(post.author?.name) }}
                                </div>
                            </div>

                            <div class="flex-1">
                                <div class="bg-white backdrop-blur-sm border border-slate-200 rounded-2xl rounded-tl-none p-5 shadow-sm group-hover:shadow-md group-hover:border-amber-500/30 transition-all duration-300">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center">
                                            <span class="font-bold text-slate-900 text-sm mr-2">
                                                {{ post.author?.name }}
                                            </span>
                                            <span v-if="post.author?.id === lab.creator_id" class="px-1.5 py-0.5 rounded bg-amber-100 text-amber-700 text-[10px] font-bold uppercase tracking-wide">
                                                Author
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <time class="text-xs text-slate-400 font-mono">
                                                {{ formatDate(post.created_at) }}
                                            </time>
                                            <button
                                                v-if="canModerate || post.user_id === $page.props.auth.user.id"
                                                @click="deletePost(post.id)"
                                                class="p-1.5 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                title="Delete comment"
                                            >
                                                <TrashIcon class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                    <div class="prose prose-sm prose-slate max-w-none">
                                        <p class="whitespace-pre-wrap">{{ post.content }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="!lab.posts || lab.posts.length === 0" class="text-center py-12 px-6 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/50">
                            <AcademicCapIcon class="w-12 h-12 mx-auto text-slate-300 mb-3" />
                            <p class="text-slate-500 font-medium">No contribution yet.</p>
                            <p class="text-sm text-slate-400">Be the first to provide insight on this topic.</p>
                        </div>
                    </div>

                    <div class="mt-12 sticky bottom-6 z-20">
                        <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl border border-amber-500/20 p-1 md:p-2 ring-1 ring-black/5">
                            <form @submit.prevent="submit" class="relative">
                                <label for="comment" class="sr-only">Your contribution</label>
                                <textarea 
                                    id="comment"
                                    v-model="form.content" 
                                    rows="3" 
                                    class="block w-full rounded-xl border-none bg-transparent py-4 pl-4 pr-16 text-slate-900 placeholder:text-slate-400 focus:ring-0 sm:text-sm sm:leading-6 resize-none" 
                                    placeholder="Type your insight or counter-argument here..." 
                                    required
                                ></textarea>
                                
                                <div class="absolute bottom-2 right-2 flex items-center justify-between">
                                    <button 
                                        type="submit" 
                                        :disabled="form.processing || !form.content"
                                        class="inline-flex items-center justify-center p-3 rounded-xl text-white bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all transform active:scale-95"
                                    >
                                        <PaperAirplaneIcon class="w-5 h-5" />
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="text-center mt-2">
                             <p class="text-[10px] text-slate-400 uppercase tracking-widest">Secure Deliberation Protocol</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>