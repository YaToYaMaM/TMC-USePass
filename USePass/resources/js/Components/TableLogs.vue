<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Main Content Area -->
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <!-- Logs Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Access Logs</h3>
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
                                          :class="getRoleBadgeClass(log.role)">
                                        {{ log.role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                          :class="getActionBadgeClass(log.action)">
                                        {{ log.action }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ log.description }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ log.time }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- No Data Message -->
                    <div v-if="filteredLogs.length === 0" class="flex flex-col items-center justify-center py-12">
                        <div class="text-6xl mb-4">📅</div>
                        <p class="text-gray-500 text-lg">No logs found for the selected date</p>
                    </div>
                </div>

                <!-- Pagination Footer -->
                <div v-if="filteredLogs.length > 0" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
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
                        <div class="flex items-center space-x-4">
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium">{{ ((currentPage - 1) * itemsPerPage) + 1 }}</span>
                                to
                                <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredLogs.length) }}</span>
                                of
                                <span class="font-medium">{{ filteredLogs.length }}</span>
                                results
                            </p>
                            <div class="flex items-center space-x-2">
                                <label class="text-sm text-gray-700">Show:</label>
                                <select
                                    v-model="itemsPerPage"
                                    @change="resetToFirstPage"
                                    class="border border-gray-300 rounded pl-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                <button
                                    @click="goToPage(1)"
                                    :disabled="currentPage === 1"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                    title="First page"
                                >
                                    ⏮
                                </button>
                                <button
                                    @click="prevPage"
                                    :disabled="currentPage === 1"
                                    class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Page Numbers -->
                                <button
                                    v-for="page in visiblePages"
                                    :key="page"
                                    @click="goToPage(page)"
                                    :class="[
                                        'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                        page === currentPage
                                            ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                                    ]"
                                >
                                    {{ page }}
                                </button>

                                <button
                                    @click="nextPage"
                                    :disabled="currentPage === totalPages"
                                    class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button
                                    @click="goToPage(totalPages)"
                                    :disabled="currentPage === totalPages"
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                    title="Last page"
                                >
                                    ⏭
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "LogTable",
    data() {
        return {
            selectedDate: "2025-06-27",
            currentPage: 1,
            itemsPerPage: 10,
            logs: [
                { time: "06/27/2025 | 11:35 AM", user: "John Doe Supranes", role: "Security Guard", action: "QR Scan", description: "Froilan Canete Enter" },
                { time: "06/27/2025 | 11:36 AM", user: "John Doe Vaughn Rhommer Theodore R. Gucor", role: "Admin", action: "Submit Report", description: "John Doe Submit Report" },
                { time: "06/27/2025 | 11:37 AM", user: "John Doe", role: "Security Guard", action: "Validate Report", description: "John Doe Validate Report" },
                { time: "06/27/2025 | 11:38 AM", user: "John Doe", role: "Admin", action: "QR Scan", description: "Froilan Canete Enter" },
                { time: "06/27/2025 | 11:39 AM", user: "John Doe", role: "Security Guard", action: "QR Scan", description: "Froilan Canete Enter" },
                { time: "06/27/2025 | 11:40 AM", user: "John Doe", role: "Admin", action: "Submit Report", description: "John Doe Submit Report" },
                { time: "06/27/2025 | 11:41 AM", user: "John Doe", role: "Security Guard", action: "Validate Report", description: "John Doe Validate Report" },
                { time: "06/27/2025 | 11:42 AM", user: "John Doe", role: "Admin", action: "QR Scan", description: "Froilan Canete Enter" },
                { time: "06/27/2025 | 11:43 AM", user: "John Doe", role: "Security Guard", action: "QR Scan", description: "Froilan Canete Enter" },
                { time: "06/27/2025 | 11:44 AM", user: "John Doe", role: "Admin", action: "QR Scan", description: "Froilan Canete Enter" },
                { time: "06/28/2025 | 02:15 PM", user: "Jane Smith", role: "Admin", action: "Login", description: "User logged into system" },
                { time: "06/28/2025 | 02:20 PM", user: "Mike Johnson", role: "Security Guard", action: "Export Data", description: "Downloaded monthly report" },
                { time: "06/27/2025 | 09:15 AM", user: "Alice Williams", role: "Security Guard", action: "QR Scan", description: "Employee check-in" },
                { time: "06/27/2025 | 09:30 AM", user: "Bob Brown", role: "Admin", action: "Submit Report", description: "Weekly status report" },
                { time: "06/27/2025 | 10:00 AM", user: "Carol Davis", role: "Security Guard", action: "Validate Report", description: "Approved timesheet" },
                { time: "06/27/2025 | 10:15 AM", user: "David Wilson", role: "Admin", action: "QR Scan", description: "Meeting room access" },
                { time: "06/27/2025 | 10:30 AM", user: "Eva Miller", role: "Security Guard", action: "Export Data", description: "Client report export" },
                { time: "06/27/2025 | 11:00 AM", user: "Frank Garcia", role: "Admin", action: "Login", description: "System access" },
                { time: "06/27/2025 | 11:15 AM", user: "Grace Lee", role: "Security Guard", action: "QR Scan", description: "Parking access" },
                { time: "06/27/2025 | 12:00 PM", user: "Henry Taylor", role: "Admin", action: "Submit Report", description: "Expense report" },
            ],
        };
    },
    computed: {
        filteredLogs() {
            return this.logs.filter(log =>
                log.time.startsWith(this.formatDate(this.selectedDate))
            );
        },
        totalPages() {
            return Math.ceil(this.filteredLogs.length / this.itemsPerPage);
        },
        paginatedLogs() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.filteredLogs.slice(start, end);
        },
        visiblePages() {
            const pages = [];
            const maxVisible = 5;

            if (this.totalPages <= maxVisible) {
                for (let i = 1; i <= this.totalPages; i++) {
                    pages.push(i);
                }
            } else {
                let start = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
                let end = Math.min(this.totalPages, start + maxVisible - 1);

                if (end - start + 1 < maxVisible) {
                    start = Math.max(1, end - maxVisible + 1);
                }

                for (let i = start; i <= end; i++) {
                    pages.push(i);
                }
            }

            return pages;
        },
    },
    watch: {
        selectedDate() {
            this.resetToFirstPage();
        },
    },
    methods: {
        formatDate(dateStr) {
            const date = new Date(dateStr);
            const mm = String(date.getMonth() + 1).padStart(2, "0");
            const dd = String(date.getDate()).padStart(2, "0");
            const yyyy = date.getFullYear();
            return `${mm}/${dd}/${yyyy}`;
        },
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        resetToFirstPage() {
            this.currentPage = 1;
        },
        getRoleBadgeClass(role) {
            return role === 'Admin'
                ? 'bg-purple-100 text-purple-800'
                : 'bg-blue-100 text-blue-800';
        },
        getActionBadgeClass(action) {
            const baseClass = 'bg-green-100 text-green-800';
            switch (action.toLowerCase()) {
                case 'qr scan':
                    return 'bg-blue-100 text-blue-800';
                case 'submit report':
                    return 'bg-yellow-100 text-yellow-800';
                case 'validate report':
                    return 'bg-green-100 text-green-800';
                case 'login':
                    return 'bg-indigo-100 text-indigo-800';
                case 'export data':
                    return 'bg-orange-100 text-orange-800';
                default:
                    return baseClass;
            }
        },
    },
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
