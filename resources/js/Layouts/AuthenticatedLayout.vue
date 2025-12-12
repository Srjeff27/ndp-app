<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import Logo from '@/Components/Logo.vue';
import {
    GlobeAltIcon,
    ChartBarIcon,
    ChatBubbleLeftRightIcon,
    BeakerIcon,
    Squares2X2Icon,
    Bars3Icon,
    XMarkIcon,
    UserCircleIcon,
    Cog6ToothIcon,
    ArrowRightOnRectangleIcon
} from '@heroicons/vue/24/outline';

const showingNavigationDropdown = ref(false);

const navigation = [
    { name: 'Dashboard', route: 'dashboard', icon: Squares2X2Icon },
    { name: 'Node Pemerintahan', route: 'nodes.index', icon: GlobeAltIcon },
    { name: 'Peta Akuntabilitas', route: 'atlas.index', icon: ChartBarIcon },
    { name: 'Forum Masyarakat', route: 'labs.index', icon: ChatBubbleLeftRightIcon },
    { name: 'Simulasi AI', route: 'simulation.index', icon: BeakerIcon }
];

const page = usePage();
</script>

<template>
    <div>
        <div class="min-h-screen bg-slate-50">
            
            <!-- Desktop Sidebar -->
            <aside class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
                <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-slate-200 bg-white px-6 pb-4">
                    <!-- Logo -->
                    <div class="flex h-20 shrink-0 items-center border-b border-slate-100">
                        <Link :href="route('dashboard')">
                            <Logo size="lg" />
                        </Link>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="flex flex-1 flex-col">
                        <ul role="list" class="flex flex-1 flex-col gap-y-7">
                            <li>
                                <ul role="list" class="-mx-2 space-y-1">
                                    <li v-for="item in navigation" :key="item.name">
                                        <Link
                                            :href="route(item.route)"
                                            :class="[
                                                route().current(item.route + '*')
                                                    ? 'bg-indigo-50 text-indigo-600'
                                                    : 'text-slate-700 hover:bg-slate-50',
                                                'group flex gap-x-3 rounded-lg p-3 text-sm font-semibold leading-6 transition-colors'
                                            ]"
                                        >
                                            <component
                                                :is="item.icon"
                                                :class="[
                                                    route().current(item.route + '*')
                                                        ? 'text-indigo-600'
                                                        : 'text-slate-400 group-hover:text-indigo-600',
                                                    'h-6 w-6 shrink-0'
                                                ]"
                                            />
                                            {{ item.name }}
                                        </Link>
                                    </li>
                                </ul>
                            </li>
                            
                            <!-- User Profile Section -->
                            <li class="mt-auto">
                                <div class="border-t border-slate-200 pt-4 space-y-2">
                                    <!-- User Info -->
                                    <div class="flex items-center gap-x-3 px-3 py-2">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                            {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-slate-900 truncate">{{ $page.props.auth.user.name }}</p>
                                            <p class="text-xs text-slate-500 truncate">{{ $page.props.auth.user.email }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Profile Link -->
                                    <Link
                                        :href="route('profile.edit')"
                                        class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors"
                                    >
                                        <UserCircleIcon class="h-5 w-5 text-slate-400 group-hover:text-indigo-600" />
                                        <span>Pengaturan Profil</span>
                                    </Link>
                                    
                                    <!-- Logout Link -->
                                    <Link
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                        class="w-full group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition-colors"
                                    >
                                        <ArrowRightOnRectangleIcon class="h-5 w-5 text-slate-400 group-hover:text-rose-600" />
                                        <span>Keluar</span>
                                    </Link>
                                </div>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>

            <!-- Mobile Top Navbar -->
            <div class="sticky top-0 z-40 flex items-center justify-between bg-white border-b border-slate-200 px-4 py-3 lg:hidden shadow-sm">
                <!-- Logo & App Name -->
                <Link :href="route('dashboard')" class="flex items-center">
                    <Logo size="md" />
                </Link>
                
                <!-- Profile Dropdown -->
                <Dropdown align="right" width="48">
                    <template #trigger>
                        <button class="flex items-center space-x-2 rounded-lg px-2 py-1.5 hover:bg-slate-50 transition-colors">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                            </div>
                        </button>
                    </template>

                    <template #content>
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-sm font-semibold text-slate-900">{{ $page.props.auth.user.name }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ $page.props.auth.user.email }}</p>
                        </div>
                        <DropdownLink :href="route('profile.edit')" class="flex items-center space-x-2">
                            <UserCircleIcon class="w-4 h-4" />
                            <span>Pengaturan Profil</span>
                        </DropdownLink>
                        <DropdownLink :href="route('logout')" method="post" as="button" class="flex items-center space-x-2 text-rose-600">
                            <ArrowRightOnRectangleIcon class="w-4 h-4" />
                            <span>Keluar</span>
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>

            <!-- Main Content -->
            <div class="lg:pl-72 pb-16 lg:pb-0">
                <!-- Page Header -->
                <header v-if="$slots.header" class="bg-white border-b border-slate-200">
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>

                <!-- Page Content -->
                <main>
                    <slot />
                </main>
            </div>

            <!-- Mobile Bottom Navigation -->
            <nav class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-200 lg:hidden shadow-lg">
                <div class="grid grid-cols-5 h-16">
                    <Link
                        v-for="item in navigation"
                        :key="item.route"
                        :href="route(item.route)"
                        :class="[
                            route().current(item.route + '*')
                                ? 'text-indigo-600'
                                : 'text-slate-500',
                            'flex flex-col items-center justify-center space-y-1 transition-colors hover:text-indigo-600'
                        ]"
                    >
                        <component
                            :is="item.icon"
                            :class="[
                                route().current(item.route + '*') ? 'text-indigo-600' : '',
                                'w-6 h-6'
                            ]"
                        />
                        <span class="text-[10px] font-medium">{{ item.name.split(' ')[0] }}</span>
                    </Link>
                </div>
            </nav>
        </div>
    </div>
</template>
