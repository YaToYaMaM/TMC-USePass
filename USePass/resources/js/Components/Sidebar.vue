<!-- Sidebar.vue -->
<script setup lang="ts">
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3'

const isCollapsed = ref(false);

// Define the expected shape of the user
interface User {
    id: number
    name: string
    email: string
    role: string
}

const page = usePage<{ auth: { user: User | null } }>()

const user = computed(() => page.props.auth.user)
const isAdmin = computed(() => user.value?.role === 'admin')
const isGuard = computed(() => user.value?.role === 'guard')
</script>

<template>
    <div class="flex h-full">
        <aside
            :class="[
                'bg-gray-100 text-black p-4 transition-all duration-300 ease-in-out h-full',
                isCollapsed ? 'w-16' : 'w-52'
            ]"
        >
            <!-- Toggle Button -->
            <button
                @click="isCollapsed = !isCollapsed"
                class="text-black mb-6 focus:outline-none"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>
            </button>

            <!-- Nav Items -->
            <nav>
                <div v-if="!isCollapsed" class="mb-4 text-sm font-medium">Menu</div>
                <ul class="space-y-4">
                    <!-- Dashboard - Admin only -->
                    <li v-if="isAdmin" class="flex items-center space-x-3 text-sm">
                        <a
                            href="/dashboard"
                            :class="[
                                'flex items-center space-x-3 p-2 rounded-md hover:bg-red-800 hover:text-white transition',
                                isCollapsed ? 'w-8 justify-center' : 'w-48'
                            ]"
                        >
                            <img src="@/Icons/dashboard.png" alt="Dashboard Icon" width="24" height="24" />
                            <span v-if="!isCollapsed" class="whitespace-nowrap">Dashboard</span>
                        </a>
                    </li>

                    <!-- Guard Home - Guard only -->
                    <li v-if="isGuard" class="flex items-center space-x-3 text-sm">
                        <a
                            href="/"
                            :class="[
                                'flex items-center space-x-3 p-2 rounded-md hover:bg-red-800 hover:text-white transition',
                                isCollapsed ? 'w-8 justify-center' : 'w-48'
                            ]"
                        >
                            <img src="@/Icons/dashboard.png" alt="Dashboard Icon" width="24" height="24" />
                            <span v-if="!isCollapsed" class="whitespace-nowrap">Guard Home</span>
                        </a>
                    </li>


                    <!-- Scanner Guard Only -->
                    <li v-if="isGuard" class="flex items-center space-x-3">
                        <a
                            href="/scan"
                            :class="[
                                'flex items-center space-x-3 p-2 rounded-md hover:bg-red-800 hover:text-white transition',
                                isCollapsed ? 'w-8 justify-center' : 'w-48'
                            ]"
                        >
                            <img src="@/Icons/scan.png" alt="Dashboard Icon" width="24" height="24" />
                            <span v-if="!isCollapsed" class="whitespace-nowrap">Scanner</span>
                        </a>
                    </li>

                    <!-- Security Guard Management - Admin only -->
                    <li v-if="isAdmin" class="flex items-center space-x-3">
                        <a
                            href="/guard"
                            :class="[
                                'flex items-center space-x-3 p-2 rounded-md hover:bg-red-800 hover:text-white transition',
                                isCollapsed ? 'w-8 justify-center' : 'w-48'
                            ]"
                        >
                            <img src="@/Icons/secguard.png" alt="Security Guard Icon" width="24" height="24" />
                            <span v-if="!isCollapsed" class="whitespace-nowrap">Security Guard</span>
                        </a>
                    </li>

                    <!-- Students Management - Admin only -->
                    <li v-if="isAdmin" class="flex items-center space-x-3">
                        <a
                            href="/students"
                            :class="[
                                'flex items-center space-x-3 p-2 rounded-md hover:bg-red-800 hover:text-white transition',
                                isCollapsed ? 'w-8 justify-center' : 'w-48'
                            ]"
                        >
                            <img src="@/Icons/students.png" alt="Students Icon" width="24" height="24" />
                            <span v-if="!isCollapsed" class="whitespace-nowrap">Students</span>
                        </a>
                    </li>

                    <!-- Statistics - Admin only -->
                    <li v-if="isAdmin" class="flex items-center space-x-3">
                        <a
                            href="/statistics"
                            :class="[
                                'flex items-center space-x-3 p-2 rounded-md hover:bg-red-800 hover:text-white transition',
                                isCollapsed ? 'w-8 justify-center' : 'w-48'
                            ]"
                        >
                            <img src="@/Icons/statistics.png" alt="Statistics Icon" width="24" height="24" />
                            <span v-if="!isCollapsed" class="whitespace-nowrap">Statistics</span>
                        </a>
                    </li>

                    <!-- Reports - Admin only -->
                    <li v-if="isAdmin" class="flex items-center space-x-3">
                        <a
                            href="/reports"
                            :class="[
                                'flex items-center space-x-3 p-2 rounded-md hover:bg-red-800 hover:text-white transition',
                                isCollapsed ? 'w-8 justify-center' : 'w-48'
                            ]"
                        >
                            <img src="@/Icons/statistics.png" alt="Statistics Icon" width="24" height="24" />
                            <span v-if="!isCollapsed" class="whitespace-nowrap">Attendance Reports</span>
                        </a>
                    </li>

                    <!-- Incident Reports - Available for both Admin and Guard -->
                    <li v-if="isAdmin || isGuard" class="flex items-center space-x-3">
                        <a
                            href="/incident"
                            :class="[
                                'flex items-center space-x-3 p-2 rounded-md hover:bg-red-800 hover:text-white transition',
                                isCollapsed ? 'w-8 justify-center' : 'w-48'
                            ]"
                        >
                            <img src="@/Icons/statistics.png" alt="Statistics Icon" width="24" height="24" />
                            <span v-if="!isCollapsed" class="whitespace-nowrap">
                                {{ isGuard ? 'Report Incident' : 'Incident Reports' }}
                            </span>
                        </a>
                    </li>


                    <!-- Logs - Available for both Admin and Guard -->
                    <li v-if="isAdmin" class="flex items-center space-x-3">
                        <a
                            href="/logs"
                            :class="[
                                'flex items-center space-x-3 p-2 rounded-md hover:bg-red-800 hover:text-white transition',
                                isCollapsed ? 'w-8 justify-center' : 'w-48'
                            ]"
                        >
                            <img src="@/Icons/log.png" alt="Dashboard Icon" width="24" height="24" />
                            <span v-if="!isCollapsed" class="whitespace-nowrap">Logs</span>
                        </a>
                    </li>
<!--                    Guard only logs-->
                    <li v-if="isGuard" class="flex items-center space-x-3">
                        <a
                            href="/glog"
                            :class="[
                                'flex items-center space-x-3 p-2 rounded-md hover:bg-red-800 hover:text-white transition',
                                isCollapsed ? 'w-8 justify-center' : 'w-48'
                            ]"
                        >
                            <img src="@/Icons/log.png" alt="Dashboard Icon" width="24" height="24" />
                            <span v-if="!isCollapsed" class="whitespace-nowrap">Logs</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="px-10 w-full overflow-y-auto">
            <slot />
        </div>
    </div>
</template>
