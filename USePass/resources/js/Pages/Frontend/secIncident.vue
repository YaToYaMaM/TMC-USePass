<script setup lang="ts">
import { ref, computed } from 'vue';
import  Frontend from "@/Layouts/FrontendLayout.vue";
import { Head } from "@inertiajs/vue3";
import IncidentTable from "@/Components/IncidentTable.vue";
import SpotTable from "@/Components/SpotTable.vue";


const showModal = ref(false);
const selectedImage = ref<File | null>(null);
const imagePreview = ref<string | null>(null);
const importFileInput = ref<HTMLInputElement | null>(null);
const selectedLocation = ref('Tagum');
const selectedIncident = ref('Incident');
const showEditModal = ref(false);
const guardToEdit = ref<{ id: number; name: string; title: string } | null>(null);
const selectedDate = ref<string>('');

function submitForm() {
    alert('Guard saved!');
    showModal.value = false;
}
function handleImageUpload(event: Event) {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) {
        selectedImage.value = file;
        imagePreview.value = URL.createObjectURL(file);
    }
}

function triggerImport() {
    importFileInput.value?.click();
}

function handleImportFile(event: Event) {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) {
        alert(`Imported file: ${file.name}`);
        // You can add logic here to upload it or read its content
    }
}
// Mock guard list (you can fetch this from a backend later)
const guards = ref([
    { id: 1, name: "Froilan Canete", title: "Security Guard" },
    { id: 2, name: "Marvin Dela Cruz", title: "Security Guard" },
    { id: 3, name: "Carlos Reyes", title: "Security Guard" },
    { id: 4, name: "Ronald Sison", title: "Security Guard" },
    { id: 5, name: "Elmer Santos", title: "Security Guard" },
    { id: 6, name: "Benjie Ramos", title: "Security Guard" },
    { id: 7, name: "Jake Garcia", title: "Security Guard" },
]);

const currentPage = ref(1);
const guardsPerPage = 4;

const paginatedGuards = computed(() => {
    const start = (currentPage.value - 1) * guardsPerPage;
    return guards.value.slice(start, start + guardsPerPage);
});

const totalPages = computed(() =>
    Math.ceil(guards.value.length / guardsPerPage)
);

function goToPage(page: number) {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
}
function openEditModal(guard: { id: number; name: string; title: string }) {
    guardToEdit.value = { ...guard };
    showEditModal.value = true;
}

function updateGuard() {
    if (guardToEdit.value) {
        const index = guards.value.findIndex(g => g.id === guardToEdit.value?.id);
        if (index !== -1) {
            guards.value[index] = { ...guardToEdit.value };
            alert("Guard updated!");
        }
    }
    showEditModal.value = false;
}
</script>

<template>
    <Frontend>
        <Head title="Incident Report" />
        <div class="flex flex-col p-3 ">
            <!-- Top Control Buttons -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <span></span>
                <div class="flex flex-wrap items-center gap-2">

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
                <!-- Title on the left -->
                <h1 class="text-2xl font-extrabold mb-2 md:mb-0">Incident Report</h1>

                <!-- Date & Dropdown aligned right -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4 ml-auto w-full sm:w-auto">
                    <div class="relative w-full md:w-64">
                        <input
                            type="text"
                            placeholder="Search a Incident..."
                            class="w-full border border-gray-300 pl-10 pr-4 py-2 rounded-3xl focus:outline-none"
                        />
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <!-- Magnifying Glass SVG Icon -->
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    <input
                        type="date"
                        class="border border-gray-300 p-2 rounded w-full sm:w-36 text-sm"
                        v-model="selectedDate"
                    />
                    <div class="inline-flex justify-center text-[0.775rem] bg-gray-100 p-0.5 rounded-md shadow max-w-fit ">
                        <button
                            @click="selectedIncident = 'Incident'"
                            :class="[
        'px-3 py-1 text-sm border-r border-gray-300',
        selectedIncident === 'Incident'
          ? 'bg-white text-black shadow'
          : 'bg-transparent text-black'
      ]"
                        >
                            Incident Report
                        </button>
                        <button
                            @click="selectedIncident = 'Spot'"
                            :class="[
        'px-3 py-1 text-sm',
        selectedIncident === 'Spot'
          ? 'bg-white text-black shadow'
          : 'bg-transparent text-black'
      ]"
                        >
                            Spot Report
                        </button>
                    </div>


                </div>

            </div>
            <div>
                <IncidentTable v-if="selectedIncident === 'Incident'" />
                <SpotTable v-else-if="selectedIncident === 'Spot'" />
            </div>
        </div>
    </Frontend>
</template>


