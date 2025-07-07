<script setup lang="ts">
import { ref } from 'vue';
import  Frontend from "@/Layouts/FrontendLayout.vue";
import { Head } from "@inertiajs/vue3";

const showModal = ref(false);
const selectedImage = ref<File | null>(null);
const imagePreview = ref<string | null>(null);
const importFileInput = ref<HTMLInputElement | null>(null);
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

</script>

<template>
    <Frontend>
        <Head title="Guard Page"/>
        <div >


            <div class="flex justify-between items-center mb-4">
                <button @click="showModal = true" class="px-4 py-2 bg-white text-black border border-black rounded">+ Guard</button>
                <button @click="triggerImport" class="px-4 py-2 bg-green-500 text-white border border-white rounded">Import</button>
                <input
                    type="file"
                    ref="importFileInput"
                    @change="handleImportFile"
                    accept=".csv,.xlsx,.xls,.docx,.pdf,.exel"
                    class="hidden"
                />
            </div>

            <h1 class="text-xl font-bold">Security Guard</h1>


            <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white w-full max-w-2xl p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-bold mb-4">Add Guard</h2>

                    <!-- Form Fields -->
                    <form @submit.prevent="submitForm">
                        <!-- Name Fields in One Row -->
                        <div class="mb-4 flex space-x-4">
                            <!-- Last Name -->
                            <div class="w-1/3">
                                <label class="block text-sm font-medium mb-1">Last Name</label>
                                <input type="text" class="w-full border border-gray-300 p-2 rounded" required />
                            </div>
                            <!-- First Name -->
                            <div class="w-1/3">
                                <label class="block text-sm font-medium mb-1">First Name</label>
                                <input type="text" class="w-full border border-gray-300 p-2 rounded" required />
                            </div>
                            <!-- M.I. -->
                            <div class="w-1/6">
                                <label class="block text-sm font-medium mb-1">M.I</label>
                                <input type="text" class="w-full border border-gray-300 p-2 rounded" required />
                            </div>
                        </div>

                        <!-- Name Fields in One Row -->
                        <div class="mb-4 flex space-x-4">
                            <!-- Last Name -->
                            <div class="w-1/2">
                                <label class="block text-sm font-medium mb-1" for="gender">Gender</label>
                                <select id="gender" class="w-full border border-gray-300 p-2 rounded" required>
                                    <option value="" disabled selected>Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <!-- First Name -->
                            <div class="w-1/3">
                                <label class="block text-sm font-medium mb-1">ID number</label>
                                <input type="text" class="w-full border border-gray-300 p-2 rounded" required />
                            </div>
                            <!-- M.I. -->
                            <div class="w-1/2">
                                <label class="block text-sm font-medium mb-1">Email</label>
                                <input type="text" class="w-full border border-gray-300 p-2 rounded" required />
                            </div>
                        </div>


                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Password</label>
                            <input type="password" class="w-full border border-gray-300 p-2 rounded" required />
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">ConfirmPassword</label>
                            <input type="password" class="w-full border border-gray-300 p-2 rounded" required />
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Upload Image</label>
                            <input
                                type="file"
                                accept="image/*"
                                @change="handleImageUpload"
                                class="w-full border border-gray-300 p-2 rounded"
                                required
                            />

                            <!-- Image Preview -->
                            <div v-if="imagePreview" class="mt-2">
                                <img :src="imagePreview" alt="Preview" class="h-32 rounded border" />
                            </div>
                        </div>


                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                            <button type="submit" class="px-10 py-3 bg-green-500 text-white rounded">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Frontend>
</template>
