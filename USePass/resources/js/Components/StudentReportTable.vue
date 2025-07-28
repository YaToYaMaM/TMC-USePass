<script setup lang="ts">
import axios from "axios";
import { onMounted, onBeforeUnmount, ref, computed, watch } from "vue";

const props = defineProps<{ selectedDate: string }>();

const studentRecords = ref([]);
const attendanceFilter = ref("");
const currentPage = ref(1);
const itemsPerPage = 7;

const fetchStudentRecords = () => {
    axios.get('/student-records', {
        params: { date: props.selectedDate }
    })
        .then((response) => {
            studentRecords.value = response.data.map((record: any) => ({
                id: record.student_id,
                name: record.name,
                program: record.program,
                major: record.major,
                date: record.date,
                time_in: record.record_in,
                time_out: record.record_out,
            }));
        })
        .catch((error) => {
            console.error('Error fetching student records:', error);
        });
};

watch(() => props.selectedDate, fetchStudentRecords, { immediate: true });

let intervalId: number;

onMounted(() => {
    intervalId = setInterval(fetchStudentRecords, 3000); // Refresh every 3 seconds
});

onBeforeUnmount(() => {
    clearInterval(intervalId); // Clean up to avoid memory leaks
});
const filteredRecords = computed(() => {
    let records = studentRecords.value;

    if (attendanceFilter.value === "time_in") {
        records = records.filter((s) => s.record_in);
    } else if (attendanceFilter.value === "time_out") {
        records = records.filter((s) => s.record_out);
    }

    return records;
});

const paginatedRecords = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return filteredRecords.value.slice(start, start + itemsPerPage);
});

const totalPages = computed(() =>
    Math.ceil(filteredRecords.value.length / itemsPerPage)
);

</script>


<template>
    <div class="overflow-x-auto mt-6 rounded-lg shadow border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 bg-white text-sm text-left">
            <thead class="bg-gray-50">
            <tr class="text-gray-600 uppercase text-xs tracking-wider">
                <th class="px-6 py-3 text-left">Name</th>
                <th class="px-6 py-3 text-left">Program</th>
                <th class="px-6 py-3 text-left">Major</th>
                <th class="px-6 py-3 text-left">Time In</th>
                <th class="px-6 py-3 text-left">Time Out</th>
                <th class="px-6 py-3 text-left">Date</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
            <tr
                v-for="(item, index)
                in paginatedRecords"
                :key="index">
                <td class="px-6 py-4 font-semibold whitespace-nowrap">{{ item.name }}</td>
                <td class="px-6 py-4 font-semibold">{{ item.program }}</td>
                <td class="px-6 py-4 font-semibold">{{ item.major }}</td>
                <td class="px-6 py-4 font-semibold">{{ item.time_in}}</td>
                <td class="px-6 py-4 font-semibold">{{ item.time_out}}</td>
                <td class="px-6 py-4 font-semibold">{{ item.date }}</td>
            </tr>

            </tbody>

        </table>
        <div class="flex justify-center mt-1 gap-2">
            <button
                :disabled="currentPage === 1"
                @click="currentPage--"
                class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
            >
                Prev
            </button>

            <span class="px-4 py-1 text-sm text-gray-700">
    Page {{ currentPage }} of {{ totalPages }}
  </span>

            <button
                :disabled="currentPage === totalPages"
                @click="currentPage++"
                class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
            >
                Next
            </button>
        </div>
    </div>

    <!-- View Modal -->


</template>

