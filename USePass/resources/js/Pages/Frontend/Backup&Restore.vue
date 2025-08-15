<script setup lang="ts">
import Frontend from "@/Layouts/FrontendLayout.vue";
import axios from "axios";
import { ref } from 'vue';
import Swal from 'sweetalert2';

const isBackingUp = ref(false);
const isRestoring = ref(false);
const progress = ref(0);
const progressMessage = ref('');

const backupDatabase = async () => {
    isBackingUp.value = true;
    progress.value = 0;
    progressMessage.value = 'Preparing backup...';

    try {
        const response = await axios.get("/backupData", {
            responseType: "blob",
            onDownloadProgress: (progressEvent) => {
                if (progressEvent.total) {
                    progress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    progressMessage.value = `Downloading backup: ${progress.value}%`;
                }
            }
        });

        progress.value = 95;
        progressMessage.value = 'Creating download...';

        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", `database-backup-${new Date().toISOString().split('T')[0]}.sql`);
        document.body.appendChild(link);
        link.click();

        // Clean up
        window.URL.revokeObjectURL(url);
        document.body.removeChild(link);

        progress.value = 100;
        progressMessage.value = 'Backup complete!';

        await Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Database backup downloaded successfully!',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top'
        });

    } catch (error: any) {
        console.error("Backup error:", error);
        const errorMsg = error.response?.data?.error || "Backup failed!";
        await Swal.fire({
            icon: 'error',
            title: 'Backup Failed',
            text: errorMsg,
            timer: 5000
        });
    } finally {
        isBackingUp.value = false;
        setTimeout(() => {
            progress.value = 0;
            progressMessage.value = '';
        }, 2000);
    }
};

const restoreDatabase = async (event: Event) => {
    const fileInput = event.target as HTMLInputElement;
    if (!fileInput.files?.length) return;

    const file = fileInput.files[0];

    // Validate file type
    if (!file.name.toLowerCase().endsWith('.sql')) {
        await Swal.fire({
            icon: 'error',
            title: 'Invalid File',
            text: 'Please select a valid SQL file',
            timer: 3000
        });
        fileInput.value = '';
        return;
    }

    // Confirm restoration
    const confirmation = await Swal.fire({
        icon: 'warning',
        title: 'Are you sure?',
        text: 'This will overwrite all current data!',
        showCancelButton: true,
        confirmButtonText: 'Yes, restore it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
    });

    if (!confirmation.isConfirmed) {
        fileInput.value = '';
        return;
    }

    isRestoring.value = true;
    progress.value = 0;
    progressMessage.value = 'Preparing restore...';

    try {
        const formData = new FormData();
        formData.append("backup_file", file);

        await axios.post("/restoreData", formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            onUploadProgress: (progressEvent) => {
                if (progressEvent.total) {
                    progress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    progressMessage.value = `Uploading backup: ${progress.value}%`;
                }
            }
        });

        progress.value = 100;
        progressMessage.value = 'Restoring database...';

        await Swal.fire({
            icon: 'success',
            title: 'Restored!',
            text: 'Database restored successfully!',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top'
        });

    } catch (error: any) {
        console.error("Restore error:", error);
        const errorMsg = error.response?.data?.error || "Restore failed!";

        await Swal.fire({
            icon: 'error',
            title: 'Restore Failed',
            html: `<div>${errorMsg}</div>${
                error.response?.data?.output
                    ? `<div class="mt-2 p-2 bg-gray-100 text-xs text-left">${error.response.data.output}</div>`
                    : ''
            }`,
            width: '600px'
        });
    } finally {
        isRestoring.value = false;
        fileInput.value = '';
        setTimeout(() => {
            progress.value = 0;
            progressMessage.value = '';
        }, 2000);
    }
};
</script>

<template>
    <Frontend>
        <div class="flex gap-4 justify-center mt-10">
            <!-- Your existing buttons remain the same -->
            <button
                @click="backupDatabase"
                :disabled="isBackingUp || isRestoring"
                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-semibold rounded-xl shadow-lg transition transform hover:scale-105 active:scale-95 disabled:transform-none"
            >
                <span v-if="isBackingUp">⏳ Backing up...</span>
                <span v-else>📦 Back Up</span>
            </button>

            <label
                :class="{
                    'px-6 py-3 font-semibold rounded-xl shadow-lg transition transform cursor-pointer': true,
                    'bg-orange-600 hover:bg-orange-700 text-white hover:scale-105 active:scale-95': !isRestoring && !isBackingUp,
                    'bg-gray-400 text-white cursor-not-allowed': isRestoring || isBackingUp
                }"
            >
                <span v-if="isRestoring">⏳ Restoring...</span>
                <span v-else>♻️ Restore</span>
                <input
                    type="file"
                    class="hidden"
                    accept=".sql"
                    :disabled="isRestoring || isBackingUp"
                    @change="restoreDatabase"
                />
            </label>
        </div>

        <!-- Bigger Progress Indicator -->
        <div v-if="isBackingUp || isRestoring" class="mt-8 mx-auto w-full max-w-2xl">
            <div class="text-center mb-4">
                <span class="text-lg font-semibold text-gray-800 block mb-1">{{ progressMessage }}</span>
                <span class="text-3xl font-bold"
                      :class="{
                          'text-blue-600': isBackingUp,
                          'text-orange-600': isRestoring
                      }">
                    {{ progress }}%
                </span>
            </div>

            <div class="relative pt-1">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full"
                              :class="{
                                  'text-blue-600 bg-blue-200': isBackingUp,
                                  'text-orange-600 bg-orange-200': isRestoring
                              }">
                            {{ isBackingUp ? 'BACKUP IN PROGRESS' : 'RESTORE IN PROGRESS' }}
                        </span>
                    </div>
                </div>
                <div class="overflow-hidden h-6 mb-4 text-xs flex rounded-full bg-gray-200 mt-2">
                    <div :style="{ width: progress + '%' }"
                         class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center transition-all duration-500 ease-in-out"
                         :class="{
                             'bg-blue-600': isBackingUp,
                             'bg-orange-600': isRestoring
                         }">
                    </div>
                </div>
            </div>

            <div class="text-center mt-2">
                <span class="text-sm text-gray-600">
                    {{ isBackingUp ? 'Please wait while we prepare your database backup...' :
                    'Restoring your database, this may take a few moments...' }}
                </span>
            </div>
        </div>
    </Frontend>
</template>
