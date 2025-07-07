<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen bg-white">
        <!-- Left side with background and branding -->
        <div class="w-1/2 hidden lg:flex items-center justify-center relative overflow-hidden">
            <img src="/images/bg.png" class="absolute inset-0 w-full h-full object-cover" alt="Background" />

            <div class="absolute inset-0 bg-black bg-opacity-60"></div>


            <div class="z-10 text-center text-white px-6">
                <img src="/images/Logo1.png" class="mx-auto w-746 mb-4" alt="Logo" />
            </div>
            <p class="mt-2 text-lg italic drop-shadow">"Track Student. Ensure Safety. USePass."</p>
        </div>

        <!-- Right side where the login form appears -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 bg-[#f7f7f7]">
            <slot />
        </div>
    </div>
</template>
