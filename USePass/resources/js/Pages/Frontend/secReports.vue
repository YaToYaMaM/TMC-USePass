<script setup lang="ts">
import { ref } from 'vue';
import  Frontend from "@/Layouts/FrontendLayout.vue";
import { Head } from "@inertiajs/vue3";
import StudentReportTable from "@/Components/StudentReportTable.vue";


const selectedLocation = ref('Tagum');
const selectedDate = ref<string>('');



</script>

<template>
    <Frontend>
        <Head title="student Page" />
        <div class="flex flex-col p-3 ">

            <!-- Top Control Buttons -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
               <span></span>
                <div class="flex flex-wrap items-center gap-2">
                    <button @click="" class="px-3 py-1 text-sm bg-green-500 text-white rounded">Download as PDF</button>

                    <!-- Button Group -->
                    <div class="inline-flex justify-center text-[0.775rem] bg-gray-100 p-0.5 rounded-md shadow max-w-fit ">
                        <button
                            @click="selectedLocation = 'Tagum'"
                            :class="[
        'px-3 py-1 text-sm border-r border-gray-300',
        selectedLocation === 'Tagum'
          ? 'bg-white text-black shadow'
          : 'bg-transparent text-black'
      ]"
                        >
                            Tagum
                        </button>
                        <button
                            @click="selectedLocation = 'Mabini'"
                            :class="[
        'px-3 py-1 text-sm',
        selectedLocation === 'Mabini'
          ? 'bg-white text-black shadow'
          : 'bg-transparent text-black'
      ]"
                        >
                            Mabini
                        </button>
                    </div>
                </div>

            </div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <h1 class="text-xl font-bold">Reports</h1>

                </div>
                <div class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
                    <input
                        type="date"
                        class="border border-gray-300 p-2 rounded w-full sm:w-36 text-sm"
                        v-model="selectedDate"
                    />
                    <select class="border border-gray-300 p-2 w-fit min-w-[350px] text-sm rounded-lg">
                        <option class="whitespace-nowrap">Bachelor of Science in Information Technology</option>
                        <option class="whitespace-nowrap">Bachelor of Science in Early Childhood Education</option>
                    </select>
                </div>

            </div>
            <StudentReportTable :selectedDate="selectedDate" />
        </div>
    </Frontend>
</template>

