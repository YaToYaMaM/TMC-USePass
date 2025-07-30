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

// Get current URL path for active state
const currentPath = computed(() => page.url)

// Helper function to check if a route is active
const isActiveRoute = (path: string) => {
    const current = currentPath.value || ''
    return current === path ||
        (path === '/' && current === '/') ||
        current.indexOf(path + '/') === 0 ||
        current.indexOf(path + '?') === 0
}
</script>

<template>
    <div class="flex h-full">
        <aside
            :class="[
                'bg-gray-100 text-black p-4 transition-all duration-300 ease-in-out h-full shadow-lg',
                isCollapsed ? 'w-16' : 'w-52'
            ]"
        >
            <!-- Toggle Button -->
            <button
                @click="isCollapsed = !isCollapsed"
                class="text-black mb-6 focus:outline-none hover:text-red-600 transition-colors duration-200 transform hover:scale-110"
            >
                <svg
                    :class="[
                        'w-5 h-5 transition-transform duration-300',
                        isCollapsed ? 'rotate-180' : 'rotate-0'
                    ]"
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
                <div v-if="!isCollapsed" class="mb-4 text-sm font-medium text-gray-600 tracking-wide">Menu</div>
                <ul class="space-y-2">
                    <!-- Dashboard - Admin only -->
                    <li v-if="isAdmin" class="relative">
                        <a
                            href="/dashboard"
                            :class="[
                                'flex items-center space-x-3 p-3 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105 group relative overflow-hidden',
                                isCollapsed ? 'w-10 justify-center' : 'w-full',
                                isActiveRoute('/dashboard')
                                    ? 'bg-[#760000] text-white shadow-lg scale-105'
                                    : 'hover:bg-red-50 hover:text-red-700 hover:shadow-md'
                            ]"
                        >
                            <!-- Active indicator -->
                            <div v-if="isActiveRoute('/dashboard')" class="absolute left-0 top-0 w-1 h-full bg-white rounded-r-full"></div>

                            <img
                                src="@/Icons/dashboard.png"
                                alt="Dashboard Icon"
                                width="20"
                                height="20"
                                :class="[
                                    'transition-transform duration-300',
                                    isActiveRoute('/dashboard') ? 'scale-110' : 'group-hover:scale-110'
                                ]"
                            />
                            <span
                                v-if="!isCollapsed"
                                :class="[
                                    'whitespace-nowrap font-medium transition-all duration-300',
                                    isActiveRoute('/dashboard') ? 'translate-x-1' : ''
                                ]"
                            >
                                Dashboard
                            </span>

                            <!-- Hover effect background -->
                            <div class="absolute inset-0 bg-gradient-to-r from-[#760000] to-red-700 opacity-0 group-hover:opacity-10 transition-opacity duration-300 rounded-lg"></div>
                        </a>
                    </li>

                    <!-- Guard Home - Guard only -->
                    <li v-if="isGuard" class="relative">
                        <a
                            href="/"
                            :class="[
                                'flex items-center space-x-3 p-3 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105 group relative overflow-hidden',
                                isCollapsed ? 'w-10 justify-center' : 'w-full',
                                isActiveRoute('/')
                                    ? 'bg-[#760000] text-white shadow-lg scale-105'
                                    : 'hover:bg-red-50 hover:text-red-700 hover:shadow-md'
                            ]"
                        >
                            <div v-if="isActiveRoute('/')" class="absolute left-0 top-0 w-1 h-full bg-white rounded-r-full"></div>

                            <img
                                src="@/Icons/dashboard.png"
                                alt="Dashboard Icon"
                                width="20"
                                height="20"
                                :class="[
                                    'transition-transform duration-300',
                                    isActiveRoute('/') ? 'scale-110' : 'group-hover:scale-110'
                                ]"
                            />
                            <span
                                v-if="!isCollapsed"
                                :class="[
                                    'whitespace-nowrap font-medium transition-all duration-300',
                                    isActiveRoute('/') ? 'translate-x-1' : ''
                                ]"
                            >
                                Guard Home
                            </span>

                            <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-700 opacity-0 group-hover:opacity-10 transition-opacity duration-300 rounded-lg"></div>
                        </a>
                    </li>

                    <!-- Scanner Guard Only -->
                    <li v-if="isGuard" class="relative">
                        <a
                            href="/scan"
                            :class="[
                                'flex items-center space-x-3 p-3 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105 group relative overflow-hidden',
                                isCollapsed ? 'w-10 justify-center' : 'w-full',
                                isActiveRoute('/scan')
                                    ? 'bg-[#760000] text-white shadow-lg scale-105'
                                    : 'hover:bg-red-50 hover:text-red-700 hover:shadow-md'
                            ]"
                        >
                            <div v-if="isActiveRoute('/scan')" class="absolute left-0 top-0 w-1 h-full bg-white rounded-r-full"></div>

                            <img
                                src="@/Icons/scan.png"
                                alt="Scanner Icon"
                                width="20"
                                height="20"
                                :class="[
                                    'transition-transform duration-300',
                                    isActiveRoute('/scan') ? 'scale-110' : 'group-hover:scale-110'
                                ]"
                            />
                            <span
                                v-if="!isCollapsed"
                                :class="[
                                    'whitespace-nowrap font-medium transition-all duration-300',
                                    isActiveRoute('/scan') ? 'translate-x-1' : ''
                                ]"
                            >
                                Scanner
                            </span>

                            <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-700 opacity-0 group-hover:opacity-10 transition-opacity duration-300 rounded-lg"></div>
                        </a>
                    </li>

                    <!-- Security Guard Management - Admin only -->
                    <li v-if="isAdmin" class="relative">
                        <a
                            href="/guard"
                            :class="[
                                'flex items-center space-x-3 p-3 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105 group relative overflow-hidden',
                                isCollapsed ? 'w-10 justify-center' : 'w-full',
                                isActiveRoute('/guard')
                                    ? 'bg-[#760000] text-white shadow-lg scale-105'
                                    : 'hover:bg-red-50 hover:text-red-700 hover:shadow-md'
                            ]"
                        >
                            <div v-if="isActiveRoute('/guard')" class="absolute left-0 top-0 w-1 h-full bg-white rounded-r-full"></div>

                            <img
                                src="@/Icons/secguard.png"
                                alt="Security Guard Icon"
                                width="20"
                                height="20"
                                :class="[
                                    'transition-transform duration-300',
                                    isActiveRoute('/guard') ? 'scale-110' : 'group-hover:scale-110'
                                ]"
                            />
                            <span
                                v-if="!isCollapsed"
                                :class="[
                                    'whitespace-nowrap font-medium transition-all duration-300',
                                    isActiveRoute('/guard') ? 'translate-x-1' : ''
                                ]"
                            >
                                Security Guard
                            </span>

                            <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-700 opacity-0 group-hover:opacity-10 transition-opacity duration-300 rounded-lg"></div>
                        </a>
                    </li>

                    <!-- Students Management - Admin only -->
                    <li v-if="isAdmin" class="relative">
                        <a
                            href="/students"
                            :class="[
                                'flex items-center space-x-3 p-3 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105 group relative overflow-hidden',
                                isCollapsed ? 'w-10 justify-center' : 'w-full',
                                isActiveRoute('/students')
                                    ? 'bg-[#760000] text-white shadow-lg scale-105'
                                    : 'hover:bg-red-50 hover:text-red-700 hover:shadow-md'
                            ]"
                        >
                            <div v-if="isActiveRoute('/students')" class="absolute left-0 top-0 w-1 h-full bg-white rounded-r-full"></div>

                            <img
                                src="@/Icons/students.png"
                                alt="Students Icon"
                                width="20"
                                height="20"
                                :class="[
                                    'transition-transform duration-300',
                                    isActiveRoute('/students') ? 'scale-110' : 'group-hover:scale-110'
                                ]"
                            />
                            <span
                                v-if="!isCollapsed"
                                :class="[
                                    'whitespace-nowrap font-medium transition-all duration-300',
                                    isActiveRoute('/students') ? 'translate-x-1' : ''
                                ]"
                            >
                                Students
                            </span>

                            <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-700 opacity-0 group-hover:opacity-10 transition-opacity duration-300 rounded-lg"></div>
                        </a>
                    </li>

                    <!-- Statistics - Admin only -->
                    <li v-if="isAdmin" class="relative">
                        <a
                            href="/statistics"
                            :class="[
                                'flex items-center space-x-3 p-3 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105 group relative overflow-hidden',
                                isCollapsed ? 'w-10 justify-center' : 'w-full',
                                isActiveRoute('/statistics')
                                    ? 'bg-[#760000] text-white shadow-lg scale-105'
                                    : 'hover:bg-red-50 hover:text-red-700 hover:shadow-md'
                            ]"
                        >
                            <div v-if="isActiveRoute('/statistics')" class="absolute left-0 top-0 w-1 h-full bg-white rounded-r-full"></div>

                            <img
                                src="@/Icons/statistics.png"
                                alt="Statistics Icon"
                                width="20"
                                height="20"
                                :class="[
                                    'transition-transform duration-300',
                                    isActiveRoute('/statistics') ? 'scale-110' : 'group-hover:scale-110'
                                ]"
                            />
                            <span
                                v-if="!isCollapsed"
                                :class="[
                                    'whitespace-nowrap font-medium transition-all duration-300',
                                    isActiveRoute('/statistics') ? 'translate-x-1' : ''
                                ]"
                            >
                                Statistics
                            </span>

                            <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-700 opacity-0 group-hover:opacity-10 transition-opacity duration-300 rounded-lg"></div>
                        </a>
                    </li>

                    <!-- Reports - Admin only -->
                    <li v-if="isAdmin" class="relative">
                        <a
                            href="/reports"
                            :class="[
                                'flex items-center space-x-3 p-3 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105 group relative overflow-hidden',
                                isCollapsed ? 'w-10 justify-center' : 'w-full',
                                isActiveRoute('/reports')
                                    ? 'bg-[#760000] text-white shadow-lg scale-105'
                                    : 'hover:bg-red-50 hover:text-red-700 hover:shadow-md'
                            ]"
                        >
                            <div v-if="isActiveRoute('/reports')" class="absolute left-0 top-0 w-1 h-full bg-white rounded-r-full"></div>

                            <img
                                src="@/Icons/statistics.png"
                                alt="Reports Icon"
                                width="20"
                                height="20"
                                :class="[
                                    'transition-transform duration-300',
                                    isActiveRoute('/reports') ? 'scale-110' : 'group-hover:scale-110'
                                ]"
                            />
                            <span
                                v-if="!isCollapsed"
                                :class="[
                                    'whitespace-nowrap font-medium transition-all duration-300',
                                    isActiveRoute('/reports') ? 'translate-x-1' : ''
                                ]"
                            >
                                Attendance Reports
                            </span>

                            <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-700 opacity-0 group-hover:opacity-10 transition-opacity duration-300 rounded-lg"></div>
                        </a>
                    </li>

                    <!-- Incident Reports - Available for both Admin and Guard -->
                    <li v-if="isAdmin || isGuard" class="relative">
                        <a
                            href="/incident"
                            :class="[
                                'flex items-center space-x-3 p-3 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105 group relative overflow-hidden',
                                isCollapsed ? 'w-10 justify-center' : 'w-full',
                                isActiveRoute('/incident')
                                    ? 'bg-[#760000] text-white shadow-lg scale-105'
                                    : 'hover:bg-red-50 hover:text-red-700 hover:shadow-md'
                            ]"
                        >
                            <div v-if="isActiveRoute('/incident')" class="absolute left-0 top-0 w-1 h-full bg-white rounded-r-full"></div>

                            <img
                                src="@/Icons/statistics.png"
                                alt="Incident Icon"
                                width="20"
                                height="20"
                                :class="[
                                    'transition-transform duration-300',
                                    isActiveRoute('/incident') ? 'scale-110' : 'group-hover:scale-110'
                                ]"
                            />
                            <span
                                v-if="!isCollapsed"
                                :class="[
                                    'whitespace-nowrap font-medium transition-all duration-300',
                                    isActiveRoute('/incident') ? 'translate-x-1' : ''
                                ]"
                            >
                                {{ isGuard ? 'Report Incident' : 'Incident Reports' }}
                            </span>

                            <div class="absolute inset-0 bg-gradient-to-r from-[#760000] to-red-700 opacity-0 group-hover:opacity-10 transition-opacity duration-300 rounded-lg"></div>
                        </a>
                    </li>

                    <!-- Logs - Admin only -->
                    <li v-if="isAdmin" class="relative">
                        <a
                            href="/logs"
                            :class="[
                                'flex items-center space-x-3 p-3 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105 group relative overflow-hidden',
                                isCollapsed ? 'w-10 justify-center' : 'w-full',
                                isActiveRoute('/logs')
                                    ? 'bg-[#760000] text-white shadow-lg scale-105'
                                    : 'hover:bg-red-50 hover:text-red-700 hover:shadow-md'
                            ]"
                        >
                            <div v-if="isActiveRoute('/logs')" class="absolute left-0 top-0 w-1 h-full bg-white rounded-r-full"></div>

                            <img
                                src="@/Icons/log.png"
                                alt="Logs Icon"
                                width="20"
                                height="20"
                                :class="[
                                    'transition-transform duration-300',
                                    isActiveRoute('/logs') ? 'scale-110' : 'group-hover:scale-110'
                                ]"
                            />
                            <span
                                v-if="!isCollapsed"
                                :class="[
                                    'whitespace-nowrap font-medium transition-all duration-300',
                                    isActiveRoute('/logs') ? 'translate-x-1' : ''
                                ]"
                            >
                                Logs
                            </span>

                            <div class="absolute inset-0 bg-gradient-to-r from-[#760000] to-red-700 opacity-0 group-hover:opacity-10 transition-opacity duration-300 rounded-lg"></div>
                        </a>
                    </li>

                    <!-- Guard only logs -->
                    <li v-if="isGuard" class="relative">
                        <a
                            href="/glog"
                            :class="[
                                'flex items-center space-x-3 p-3 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105 group relative overflow-hidden',
                                isCollapsed ? 'w-10 justify-center' : 'w-full',
                                isActiveRoute('/glog')
                                    ? 'bg-[#760000] text-white shadow-lg scale-105'
                                    : 'hover:bg-red-50 hover:text-red-700 hover:shadow-md'
                            ]"
                        >
                            <div v-if="isActiveRoute('/glog')" class="absolute left-0 top-0 w-1 h-full bg-white rounded-r-full"></div>

                            <img
                                src="@/Icons/log.png"
                                alt="Logs Icon"
                                width="20"
                                height="20"
                                :class="[
                                    'transition-transform duration-300',
                                    isActiveRoute('/glog') ? 'scale-110' : 'group-hover:scale-110'
                                ]"
                            />
                            <span
                                v-if="!isCollapsed"
                                :class="[
                                    'whitespace-nowrap font-medium transition-all duration-300',
                                    isActiveRoute('/glog') ? 'translate-x-1' : ''
                                ]"
                            >
                                Logs
                            </span>

                            <div class="absolute inset-0 bg-gradient-to-r from-[#760000] to-red-700 opacity-0 group-hover:opacity-10 transition-opacity duration-300 rounded-lg"></div>
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
