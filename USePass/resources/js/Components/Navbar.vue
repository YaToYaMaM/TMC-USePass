
<template>
    <nav class="relative flex justify-between items-center px-6 py-4 bg-red-800 h-15 min-h-15 flex-shrink-0 overflow-hidden">
        <div class="absolute left-6 top-1/2 -translate-y-1/2">
            <img src="/images/Logo1.png" alt="Logo" class="w-28 h-auto object-contain" />
        </div>

        <div class="flex-1"></div>

        <div class="ml-36">
            <ul class="flex gap-x-4">
                <li class="nav-item">
                    <button
                        @click="logout"
                        class="text-white px-4 py-2 rounded transition duration-200 ease-in-out
                 hover:bg-white hover:text-red-800 hover:font-semibold"
                    >
                        Logout
                    </button>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script setup lang="ts">
import {router} from "@inertiajs/vue3";
import { route } from 'ziggy-js';

const logout = () => {
    router.post(route('logout'))
}
</script>

<style scoped>

</style>
