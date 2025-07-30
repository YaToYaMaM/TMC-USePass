<template xmlns="http://www.w3.org/1999/html">
    <Head title="USePass" />
    <Frontend>
        <div class="min-h-screen bg-gray-50">
            <!-- Header Section with Controls -->
            <div class="bg-white shadow-sm border-b sticky top-0 z-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                        <h1 class="text-2xl font-bold text-gray-900">Activity Logs</h1>

                        <!-- Date Filter Only -->
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-gray-700">Date:</label>
                            <input
                                type="date"
                                v-model="selectedDate"
                                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

                <!-- Logs Table -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Recent Activity Logs</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <div class="max-h-96 overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date | Time</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="(log, index) in paginatedLogs"
                                    :key="index"
                                    class="hover:bg-gray-50 transition-colors duration-150"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ log.user }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                              :class="log.role === 'Admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'">
                                            {{ log.role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ log.action }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ log.description }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ formatTime(log.time) }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination Footer -->
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <button
                                @click="prevPage"
                                :disabled="currentPage === 1"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Previous
                            </button>
                            <button
                                @click="nextPage"
                                :disabled="currentPage === totalPages"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Next
                            </button>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ ((currentPage - 1) * itemsPerPage) + 1 }}</span>
                                    to
                                    <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredLogs.length) }}</span>
                                    of
                                    <span class="font-medium">{{ filteredLogs.length }}</span>
                                    results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    <button
                                        @click="prevPage"
                                        :disabled="currentPage === 1"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                        Page {{ currentPage }} of {{ totalPages }}
                                    </span>
                                    <button
                                        @click="nextPage"
                                        :disabled="currentPage === totalPages"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue'
import { Head } from '@inertiajs/vue3'
</script>

<script>
import { Link, router } from '@inertiajs/vue3'
import { route } from "ziggy-js";

export default {
    components: {
        Link
    },
    data() {
        return {
            menuOpen: false,
            currentPage: 1,
            itemsPerPage: 10,
            selectedDate: '2025-06-27',
            logs: [
                { time: "06/27/2025|11:35 AM", user: "Froilan Canete", role: "Security Guard", action: "Barcode Scan", description: "Scanning Barcode" },
                { time: "06/27/2025|11:36 AM", user: "Maria Santos", role: "Admin", action: "Log In", description: "Logging In" },
                { time: "06/27/2025|11:37 AM", user: "Jose Rivera", role: "Security Guard", action: "Barcode Scan", description: "Scanning Barcode" },
                { time: "06/27/2025|11:38 AM", user: "Anna Cruz", role: "Security Guard", action: "Barcode Scan", description: "Scanning Barcode" },
                { time: "06/27/2025|11:39 AM", user: "Mark Lopez", role: "Admin", action: "Log Out", description: "Logging Out" },
                { time: "06/27/2025|11:40 AM", user: "Lisa Garcia", role: "Security Guard", action: "Barcode Scan", description: "Scanning Barcode" },
                { time: "06/27/2025|11:41 AM", user: "Tom Anderson", role: "Admin", action: "Log Out", description: "Logging Out" },
                { time: "06/27/2025|11:42 AM", user: "Amy Gonzalez", role: "Admin", action: "Log In", description: "Logging In" },
                { time: "06/27/2025|11:43 AM", user: "Chris Martinez", role: "Security Guard", action: "Barcode Scan", description: "Scanning Barcode" }
            ]
        };
    },

    computed: {
        filteredLogs() {
            const date = this.selectedDate;

            return this.logs.filter(log => {
                // Only filter by date if a date is selected
                if (!date) return true;

                // Extract date from log.time (format: "06/27/2025|11:35 AM")
                const logDate = log.time.split('|')[0]; // Gets "06/27/2025"
                const selectedDateFormatted = this.formatDateForComparison(date); // Convert "2025-06-27" to "06/27/2025"

                return logDate === selectedDateFormatted;
            });
        },

        totalPages() {
            return Math.ceil(this.filteredLogs.length / this.itemsPerPage);
        },

        paginatedLogs() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.filteredLogs.slice(start, start + this.itemsPerPage);
        }
    },

    methods: {
        formatTime(timeString) {
            // Convert "06/27/2025|11:35 AM" to "06/27/2025 | 11:35 AM"
            const parts = timeString.split('|');
            if (parts.length > 1) {
                const date = parts[0]; // "06/27/2025"
                const time = parts[1]; // "11:35 AM"
                return `${date} | ${time}`;
            }
            return timeString;
        },

        formatDateForComparison(dateString) {
            // Convert "2025-06-27" to "06/27/2025" for comparison
            const date = new Date(dateString);
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const year = date.getFullYear();
            return `${month}/${day}/${year}`;
        },

        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },

        logout() {
            router.post(route('logout'));
        }
    },

    watch: {
        selectedDate() {
            this.currentPage = 1;
        }
    }
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
