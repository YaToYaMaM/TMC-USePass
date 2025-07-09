<script setup lang="ts">
import { onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps<{
    report: {
        name: string;
        date: string;
        what: string;
        who: string;
        where: string;
        when: string;
        how: string;
        why: string;
        recommendation: string;
    };
}>();

onMounted(() => {
    setTimeout(() => window.print(), 500);
});
</script>

<template>
    <div v-if="props.report"  class="p-10 text-sm font-sans leading-relaxed text-black max-w-3xl mx-auto bg-white">
        <h1 class="text-lg font-bold text-center mb-4">University of Southeastern Philippines</h1>
        <h2 class="text-center text-sm">Security Service Unit</h2>
        <h3 class="text-center font-bold text-lg mt-2 mb-1">Incident Report</h3>
        <p class="text-right mb-4">Date: {{ report.date }}</p>

        <p class="mb-4">TO WHOM IT MAY CONCERN:</p>
        <p class="mb-4">
            This is to submit my report with regards to the incident reported in my area of responsibility, to wit:
        </p>

        <div class="space-y-2">
            <p><strong>WHAT:</strong> {{ report.what }}</p>
            <p><strong>WHO:</strong> {{ report.who }}</p>
            <p><strong>WHERE:</strong> {{ report.where }}</p>
            <p><strong>WHEN:</strong> {{ report.when }}</p>
            <p><strong>HOW:</strong> {{ report.how }}</p>
            <p><strong>WHY:</strong> {{ report.why }}</p>
            <p><strong>RECOMMENDATION:</strong> {{ report.recommendation }}</p>
        </div>

        <div class="flex justify-between mt-10">
            <div class="text-left">
                <p>Yours truly,</p>
                <br />
                <p class="underline">{{ report.name }}</p>
                <p>(Guard on Duty)</p>
            </div>
            <div class="text-right">
                <p>Information Validated:</p>
                <br />
                <p class="underline">_______________________</p>
                <p>(Complainant)</p>
            </div>
        </div>
    </div>
</template>
