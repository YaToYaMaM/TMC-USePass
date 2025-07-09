<script setup lang="ts">
import { ref, computed } from 'vue';
import  Frontend from "@/Layouts/FrontendLayout.vue";
import { Head } from "@inertiajs/vue3";
import IncidentTable from "@/Components/IncidentTable.vue";


const showModal = ref(false);
const selectedImage = ref<File | null>(null);
const imagePreview = ref<string | null>(null);
const importFileInput = ref<HTMLInputElement | null>(null);
const selectedLocation = ref('Tagum');
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
                    <button @click="triggerImport" class="px-3 py-1 text-sm bg-green-500 text-white rounded">Download as PDF</button>

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
                    <input
                        type="date"
                        class="border border-gray-300 p-2 rounded w-full sm:w-36 text-sm"
                        v-model="selectedDate"
                    />

                    <div class="relative w-full sm:w-36">
                        <select class="border border-gray-300 p-2 rounded w-full text-sm">
                            <option>All</option>
                            <option>Incident Report</option>
                            <option>Spot Report</option>
                        </select>
                    </div>

                </div>

            </div>
            <div>
                <IncidentTable />

            </div>
        </div>
    </Frontend>
</template>


