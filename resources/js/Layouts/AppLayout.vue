<script setup>
import { computed, onMounted, ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

defineProps({
    title: String,
});

const page = usePage();
const user = computed(() => page.props.auth?.user);

const sidebarOpen = ref(false);
const isDark = ref(false);

onMounted(() => {
    isDark.value = document.documentElement.classList.contains('dark');
});

const toggleTheme = () => {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
};

const logout = () => {
    router.post(route('logout'));
};

const navItems = [
    { label: 'Dashboard', icon: 'dashboard', route: 'dashboard' },
    { label: 'Events', icon: 'event', route: 'events.index' },
    { label: 'Venues', icon: 'stadium', route: 'venues.index' },
];

const isActive = (name) => {
    try {
        return route().current(name);
    } catch (e) {
        return false;
    }
};
</script>

<template>
    <div>
        <Head :title="title" />
        <Banner />

        <div class="flex h-screen overflow-hidden bg-background text-on-background">
            <!-- Mobile overlay -->
            <div
                v-if="sidebarOpen"
                class="fixed inset-0 z-30 bg-black/50 lg:hidden"
                @click="sidebarOpen = false"
            ></div>

            <!-- Sidebar -->
            <aside
                class="fixed inset-y-0 left-0 z-40 w-64 bg-primary text-on-primary flex flex-col shrink-0 transform transition-transform duration-200 lg:relative lg:translate-x-0"
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            >
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-9 h-9 rounded-xl bg-primary-container flex items-center justify-center">
                            <span class="material-symbols-outlined filled text-on-primary-container">event_available</span>
                        </div>
                        <h1 class="text-xl font-extrabold tracking-tight font-headline">Laravel App</h1>
                    </div>
                    <p class="text-xs text-on-primary-container font-medium opacity-80">Event Management</p>
                </div>

                <nav class="flex-1 px-4 space-y-2 mt-2 overflow-y-auto custom-scrollbar">
                    <Link
                        v-for="item in navItems"
                        :key="item.route"
                        :href="route(item.route)"
                        @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors"
                        :class="isActive(item.route)
                            ? 'bg-primary-container text-white'
                            : 'text-on-primary-container hover:bg-white/5'"
                    >
                        <span
                            class="material-symbols-outlined"
                            :class="{ 'filled': isActive(item.route) }"
                        >{{ item.icon }}</span>
                        <span class="font-medium">{{ item.label }}</span>
                    </Link>
                </nav>

                <div v-if="user" class="p-6 border-t border-white/10 flex items-center gap-3">
                    <img
                        v-if="page.props.jetstream?.managesProfilePhotos && user.profile_photo_url"
                        :src="user.profile_photo_url"
                        :alt="user.name"
                        class="w-10 h-10 rounded-full border-2 border-primary-container object-cover"
                    >
                    <div
                        v-else
                        class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container font-bold"
                    >{{ user.name?.[0]?.toUpperCase() }}</div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold truncate">{{ user.name }}</p>
                        <p class="text-[10px] text-on-primary-container uppercase tracking-wider truncate">{{ user.email }}</p>
                    </div>
                </div>
            </aside>

            <!-- Main column -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Topbar -->
                <header class="h-16 bg-surface-container-lowest flex items-center justify-between px-4 sm:px-8 border-b border-outline-variant/30 shrink-0">
                    <div class="flex items-center gap-3">
                        <button
                            class="lg:hidden p-2 -ml-2 rounded-xl text-on-surface-variant hover:bg-surface-container"
                            @click="sidebarOpen = true"
                            aria-label="Open menu"
                        >
                            <span class="material-symbols-outlined">menu</span>
                        </button>
                        <h2 v-if="title" class="text-sm font-bold text-primary hidden sm:block">{{ title }}</h2>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-4">
                        <button
                            class="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container transition-colors"
                            @click="toggleTheme"
                            :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                        >
                            <span class="material-symbols-outlined">{{ isDark ? 'light_mode' : 'dark_mode' }}</span>
                        </button>

                        <button class="relative p-2 rounded-xl text-on-surface-variant hover:bg-surface-container transition-colors">
                            <span class="material-symbols-outlined">notifications</span>
                        </button>

                        <div class="border-l border-outline-variant/40 pl-2 sm:pl-4">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button class="flex items-center gap-2 p-1 pr-2 rounded-full hover:bg-surface-container transition-colors">
                                        <img
                                            v-if="user && page.props.jetstream?.managesProfilePhotos && user.profile_photo_url"
                                            :src="user.profile_photo_url"
                                            :alt="user.name"
                                            class="w-8 h-8 rounded-full object-cover"
                                        >
                                        <div
                                            v-else-if="user"
                                            class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center text-sm font-bold"
                                        >{{ user.name?.[0]?.toUpperCase() }}</div>
                                        <span v-if="user" class="hidden sm:block text-sm font-medium text-on-surface">{{ user.name }}</span>
                                        <span class="material-symbols-outlined text-on-surface-variant text-base">expand_more</span>
                                    </button>
                                </template>
                                <template #content>
                                    <div class="block px-4 py-2 text-xs text-on-surface-variant">Manage Account</div>
                                    <DropdownLink :href="route('profile.show')">Profile</DropdownLink>
                                    <DropdownLink v-if="page.props.jetstream?.hasApiFeatures" :href="route('api-tokens.index')">API Tokens</DropdownLink>
                                    <div class="border-t border-outline-variant/40"></div>
                                    <form @submit.prevent="logout">
                                        <DropdownLink as="button">Log Out</DropdownLink>
                                    </form>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                </header>

                <!-- Optional page header slot -->
                <div v-if="$slots.header" class="bg-surface-container-lowest border-b border-outline-variant/20 px-4 sm:px-8 py-5">
                    <slot name="header" />
                </div>

                <!-- Scrollable canvas -->
                <main class="flex-1 overflow-y-auto p-4 sm:p-6 custom-scrollbar bg-background">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
