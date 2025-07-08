<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen bg-[#F5F5F5]">
        <div class="w-2/3 hidden lg:flex items-center justify-center relative overflow-hidden [clip-path:polygon(0%_0%,100%_0%,85%_100%,0%_100%)]">
            <img src="/images/bg.png" class="absolute inset-0 w-full h-full object-cover" alt="Background" />

            <div class="absolute inset-0 bg-black bg-opacity-60"></div>


            <div class="z-10 text-center text-white px-6">
                <img src="/images/withSlogan.png" class="mx-auto w-full max-w-95 mb-4" alt="Logo" />
            </div>
        </div>


        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 bg-[#F5F5F5]">
            <slot />
        </div>
    </div>
</template>
