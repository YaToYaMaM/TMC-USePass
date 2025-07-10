<script setup lang="ts">
import { onMounted } from 'vue';
import { Head } from "@inertiajs/vue3";

const props = defineProps<{
    report: {
        date: string;
        findings: string;
        time: string;
        location: string;
        actionTaken: string;
        guardName: string;
        teamLeader: string;
        departmentRepresentative: string;
    };
}>();

onMounted(() => {
    setTimeout(() => window.print(), 500);
});
</script>

<template>
    <Head title="Spot Report" />

    <div class="max-w-4xl mx-auto bg-white p-10 text-sm text-black leading-relaxed">
        <!-- Header with Logo and Titles -->
        <div class="text-center mb-4">
            <img src="/images/usep-logo-small.png" class="mx-auto w-16 mb-2" alt="USEP Logo" />
            <h1 class="text-2xl font-bold font-serif">University of Southeastern Philippines</h1>
            <h2 class="text-sm italic">Security Service Unit</h2>
        </div>


        <!-- Title -->
        <!-- Header Container -->
        <div class="relative text-center mb-4">
            <!-- Centered Title -->
            <div>
                <h3 class="text-lg font-bold uppercase tracking-wide">SECURITY SPOT REPORT</h3>
                <p class="text-xs italic text-gray-600">
                    (For Security and Austerity Measures)<br />
                    Series of 2015
                </p>
            </div>

            <!-- FOR SSU USE ONLY Box positioned absolutely to the right -->
            <div class="absolute top-0 right-0 border border-black text-xs p-2 w-44">
                <p class="font-bold text-center border-b border-black pb-1 mb-2">
                    FOR SSU USE ONLY
                </p>
                <div class="space-y-1">
                    <div>
                        <p>_________________________</p>
                        <p>Log No.</p>
                    </div>
                    <div>
                        <p>_________________________</p>
                        <p>Log Date</p>

                    </div>
                    <div>
                        <p>_________________________</p>
                        <p>Spot Report No.</p>

                    </div>
                    <div>
                        <p>_________________________</p>
                        <p>Department</p>

                    </div>
                </div>
            </div>
        </div>


        <!-- Date -->
        <p class="mb-4"><strong>Date:</strong> <span class="underline">{{ report.date }}</span></p>

        <!-- Body -->
        <p class="mb-4 font-bold">TO WHOM IT MAY CONCERN:</p>
        <p class="mb-4">
            Sir/Madame;
            <br /><br />
            This is to inform your good office that as I conducted clearing inspection in my area of responsibility on or about
            <span class="underline">{{ report.time }}</span> hours dated <span class="underline">{{ report.date }}</span>,
            I found out that <span class="underline">{{ report.findings }}</span> at <span class="underline">{{ report.location }}</span>
            (specific department/office/building).
        </p>

        <!-- Action Taken -->
        <p class="font-bold">ACTION TAKEN:</p>
        <p class="mb-6 underline">{{ report.actionTaken }}</p>

        <p class="mb-6">
            This security spot report is subject for recording of the department or office concerned for further action.
        </p>

        <!-- Signatories -->
        <div class="flex justify-between mt-10">
            <div>
                <p>Yours truly,</p>
                <br />
                <p class="underline text-lg">{{ report.guardName }}</p>
                <p class="font-semibold">(Guard on Duty)</p>
            </div>
            <div class="text-right">
                <p>Verified:</p>
                <br />
                <p class="underline text-lg">{{ report.teamLeader }}</p>
                <p class="font-semibold">(Team Leader)</p>
            </div>
        </div>

        <div class="flex justify-between mt-10">
            <div>
                <p>Noted:</p>
                <br />
                <p class="underline text-lg">Celso Talarde</p>
                <p class="font-semibold">Head, SSU</p>
                <p class="font-semibold">Date:____________________</p>
            </div>
            <div class="text-right">
                <p>Copy Received:</p>
                <br />
                <p class="underline text-lg">{{ report.departmentRepresentative }}</p>
                <p class="font-semibold">Concerned Department/Office/Unit</p>
                <p class="font-semibold">Date:____________________</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-10 border-t pt-4 text-xs text-center">
            <p><strong>VISION:</strong> PREMIER RESEARCH UNIVERSITY TRANSFORMING COMMUNITIES IN THE ASEAN AND BEYOND</p>
            <p>University of Southeastern Philippines | Apokon, Tagum City, Davao del Norte 8100 | www.usep.edu.ph</p>
        </div>
    </div>
</template>

<style scoped>
.font-old {
    font-family: 'OldEnglish', serif;
}
</style>
