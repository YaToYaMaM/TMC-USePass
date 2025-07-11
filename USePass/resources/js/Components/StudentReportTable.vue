<script setup lang="ts">
import { ref, computed } from "vue";

const props = defineProps<{
    selectedDate: string;
}>();

const student = ref([
    {
        id: "2022-00381",
        name: "Froilan Canete",
        program: "BSIT",
        major: "Information Security",
        date: "2025-07-08",
    },
    {
        id: "2022-00280",
        name: "Joan Malintad",
        program: "BSIT",
        major: "Information Security",
        date: "2025-07-07",
    },
    {
        id: "2022-00291",
        name: "Miralie Lyka Borjal",
        program: "BSIT",
        major: "Information Security",
        date: "2025-07-08",
    },
]);

const currentPage = ref(1);
const itemsPerPage = 7;

const filteredStudents = computed(() => {
    if (!props.selectedDate) return student.value;
    return student.value.filter((s) => s.date === props.selectedDate);
});

const paginatedIncident = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return filteredStudents.value.slice(start, start + itemsPerPage);
});

const totalPages = computed(() =>
    Math.ceil(filteredStudents.value.length / itemsPerPage)
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
                <th class="px-6 py-3 text-left">Date</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
            <tr
                v-for="(item, index)
                in paginatedIncident"
                :key="index">
                <td class="px-6 py-4 font-semibold whitespace-nowrap">{{ item.name }}</td>
                <td class="px-6 py-4 font-semibold">{{ item.program }}</td>
                <td class="px-6 py-4 font-semibold">{{ item.major }}</td>
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

