<script setup lang="ts">
import {ref} from "vue";
const guards = ref([
    {
        id: 1,
        name: "Froilan Canete",
        description: "Burning Computer",
        type: "Incident Report",
        date: "2025-07-08",
        what: "Computer suddenly caught fire.",
        who: "IT personnel",
        where: "Lab 3",
        when: "2:30 PM, July 8, 2025",
        how: "Possibly due to overheating power supply",
        why: "Lack of regular maintenance",
        recommendation: "Install cooling systems and perform regular checkups"
    },
    {
        id: 2,
        name: "Carlos Reyes",
        description: "Unauthorized access",
        type: "Spot Report",
        date: "2025-07-07",
        what: "Individual accessed server room without clearance.",
        who: "Unknown person",
        where: "Main Building Server Room",
        when: "1:00 AM",
        how: "By bypassing security keycard",
        why: "Possible lapse in guard supervision",
        recommendation: "Install CCTV, review access logs"
    },
    {
        id: 2,
        name: "John Robert Paler",
        description: "Unauthorized access",
        type: "Spot Report",
        date: "2025-07-06",
        what: "Suga wa napalong.",
        who: "Unknown person",
        where: "SOM Building",
        when: "9:00 pM",
        how: "By bypassing security keycardbe ",
        why: "Possible lapse in guard supervision",
        recommendation: "Check the room before you leave"
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
    window.open(`/incident-report/print?${query}`, '_blank');
}


</script>

<template>
    <div class="overflow-x-auto mt-6 rounded-lg shadow border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 bg-white text-sm text-left">
            <thead class="bg-gray-50">
            <tr class="text-gray-600 uppercase text-xs tracking-wider">
                <th class="px-6 py-3 text-left">Guard on Duty</th>
                <th class="px-6 py-3 text-left">Description</th>
                <th class="px-6 py-3 text-left">Type of Report</th>
                <th class="px-6 py-3 text-left">Date</th>
                <th class="px-6 py-3 text-center">Action</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
            <tr
                v-for="(item, index) in guards"
                :key="index"
                class="hover:bg-gray-50 transition"
            >
                <td class="px-6 py-4 font-medium whitespace-nowrap">{{ item.name }}</td>
                <td class="px-6 py-4 font-semibold">{{ item.what }}</td>
                <td class="px-6 py-4 font-semibold">{{ item.type }}</td>
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
                    <label class="text-xl font-bold block">What:</label>
                    <p class="text-gray-900">{{ selectedReport.what }}</p>
                </div>
                <div>
                    <label class="text-xl font-bold block">Who:</label>
                    <p class="text-gray-900">{{ selectedReport.who }}</p>
                </div>
                <div>
                    <label class="text-xl font-bold block">Where:</label>
                    <p class="text-gray-900">{{ selectedReport.where }}</p>
                </div>
                <div>
                    <label class="font-bold text-xl block">When:</label>
                    <p class="text-gray-900">{{ selectedReport.when }}</p>
                </div>
                <div>
                    <label class="font-bold text-xl block">How:</label>
                    <p class="text-gray-900">{{ selectedReport.how }}</p>
                </div>
                <div>
                    <label class="font-bold text-xl block">Why:</label>
                    <p class="text-gray-900">{{ selectedReport.why }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="font-bold text-xl block">Recommendation:</label>
                    <p class="text-gray-900">{{ selectedReport.recommendation }}</p>
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

