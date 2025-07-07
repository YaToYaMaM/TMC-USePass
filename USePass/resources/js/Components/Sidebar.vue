<script setup lang="ts">
import { ref } from 'vue';
const isCollapsed = ref(false);
import {Link} from "@inertiajs/vue3";
</script>

<template>
    <aside
        :class="[
          'bg-gray-100 text-black p-4 transition-all duration-300 ease-in-out overflow-y-auto h-full',
          isCollapsed ? 'w-16' : 'w-44'
        ]"

    >
        <!-- Toggle Button -->
        <button
            @click="isCollapsed = !isCollapsed"
            class="text-black mb-6 focus:outline-none"
        >
            <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M4 6h16M4 12h16M4 18h16"
                />
            </svg>
        </button>

        <!-- Nav Items -->
        <nav>
            Menu
            <ul class="space-y-4">

                <li class="flex items-center space-x-3 text-sm">
                    <a
                        href="/"
                        class="flex items-center space-x-3 p-2 pe-14 rounded-md hover:bg-red-800 hover:text-white transition"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
                        </svg>
                        <span v-if="!isCollapsed" class="whitespace-nowrap">Dashboard</span>
                    </a>
                </li>
                <li class="flex items-center space-x-3">
                    <a
                        href="/contact"
                        class="flex items-center space-x-3 p-2 pe-20 rounded-md hover:bg-red-800 hover:text-white transition"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c3.038 0 5.824 1.004 7.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span v-if="!isCollapsed" class="whitespace-nowrap">Users</span>
                    </a>
                </li>
                <li class="flex items-center space-x-3">
                    <a
                        href="/about"
                        class="flex items-center space-x-3 p-2 pe-14 rounded-md hover:bg-red-800 hover:text-white transition"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span v-if="!isCollapsed" class="whitespace-nowrap">Settings</span>
                    </a>
                </li>
                <li class="flex items-center space-x-3">
                    <a
                        href="/about"
                        class="flex items-center space-x-3 p-2 pe-14 rounded-md hover:bg-red-800 hover:text-white transition"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span v-if="!isCollapsed" class="whitespace-nowrap">Settings</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
</template>

<style scoped>

</style>
