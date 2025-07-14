<script setup lang="ts">
import {ref, computed} from "vue";
const reports = ref([
    {
        id: 1,
        guardName: "Froilan Canete",
        findings: "Turn on lights",
        time: "8:25 PM",
        date: "2025-07-09",
        location: "SOM Building",
        actionTaken: "Switch Off the lights",
        teamLeader: "Joan Malintad",
        departmentRepresentative: "Chai",
        Rtype:"Spot Report",

    },



]);

const showViewModal = ref(false);
const selectedReport = ref<any>(null);

function openEditModal(report: any) {
    selectedReport.value = report;
    showViewModal.value = true;
}
function printReport() {
    const query = new URLSearchParams(selectedReport.value).toString();
    window.open(`/spot-report/print?${query}`, '_blank');
}
const currentPage = ref(1);
const itemsPerPage = 7;
const props = defineProps<{
    selectedDate: string;
}>();
const filteredReport = computed(() => {
    if (!props.selectedDate) return reports.value;
    return reports.value.filter((s) => s.date === props.selectedDate);
});

const paginatedIncident = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return filteredReport.value.slice(start, start + itemsPerPage);
});

const totalPages = computed(() =>
    Math.ceil(filteredReport.value.length / itemsPerPage)
);

</script>

<template>
    <div class="overflow-x-auto mt-6 rounded-lg shadow border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 bg-white text-sm text-left">
            <thead class="bg-gray-50">
            <tr class="text-gray-600 uppercase text-xs tracking-wider">
                <th class="px-6 py-3 text-left">Guard on Duty</th>
                <th class="px-6 py-3 text-left">Findings</th>
                <th class="px-6 py-3 text-left">Type of Report</th>
                <th class="px-6 py-3 text-left">Date</th>
                <th class="px-6 py-3 text-center">Action</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
            <tr
                v-for="(item, index)
                in paginatedIncident"
                :key="index">
                <td class="px-6 py-4 font-medium whitespace-nowrap">{{ item.guardName}}</td>
                <td class="px-6 py-4 font-semibold">{{ item.findings }}</td>
                <td class="px-6 py-4 font-semibold">{{ item.Rtype }}</td>
                <td class="px-6 py-4 font-semibold">{{ item.date }}</td>
                <td class="px-6 py-4 text-center">
                    <button
                        @click="openEditModal(item)"
                        class="inline-flex items-center gap-1 bg-blue-500 text-white px-3 py-1.5 rounded hover:bg-blue-600 text-xs transition"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        View
                    </button>
                </td>

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
    <div
        v-if="showViewModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4"
    >
        <div class="bg-white w-full max-w-3xl rounded-xl shadow-2xl p-6 relative">
            <!-- Close Button -->
            <button
                @click="showViewModal = false"
                class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-2xl font-bold focus:outline-none"
                title="Close"
            >
                &times;
            </button>

            <!-- Header -->
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-2">
                Incident Report Details
            </h2>

            <!-- Report Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-700">
                <div>
                    <label class="text-xl font-bold block">Findings:</label>
                    <p class="text-gray-900">{{ selectedReport.findings }}</p>
                </div>
                <div>
                    <label class="text-xl font-bold block">Time:</label>
                    <p class="text-gray-900">{{ selectedReport.time }}</p>
                </div>
                <div>
                    <label class="text-xl font-bold block">Date:</label>
                    <p class="text-gray-900">{{ selectedReport.location }}</p>
                </div>
                <div>
                    <label class="font-bold text-xl block">Guard Name:</label>
                    <p class="text-gray-900">{{ selectedReport.guardName }}</p>
                </div>
                <div>
                    <label class="font-bold text-xl block">Team Leader:</label>
                    <p class="text-gray-900">{{ selectedReport.teamLeader }}</p>
                </div>
                <div>
                    <label class="font-bold text-xl block">Type:</label>
                    <p class="text-gray-900">{{ selectedReport.Rtype }}</p>
                </div>
                <div>
                    <label class="font-bold text-xl block">Action Taken:</label>
                    <p class="text-gray-900">{{ selectedReport.actionTaken }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="font-bold text-xl block">Department Representative:</label>
                    <p class="text-gray-900">{{ selectedReport.departmentRepresentative }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-end gap-2">
                <button
                    @click="showViewModal = false"
                    class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 transition text-sm"
                >
                    Close
                </button>
                <button
                    @click="printReport"
                    class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition text-sm"
                >
                    Print
                </button>
            </div>
        </div>
    </div>


</template>

