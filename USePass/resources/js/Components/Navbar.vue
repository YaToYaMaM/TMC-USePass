<script setup lang="ts">
import { ref } from 'vue';
import {router, usePage} from "@inertiajs/vue3";
import { route } from 'ziggy-js';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';

const menuOpen = ref(false);

const logout = () => {
    router.post(route('logout'))
}

const showChangePasswordModal = ref(false);

const passwordForm = ref({
    current_password: '',
    new_password: '',
    confirm_password: '',
});

const passwordErrors = ref<string[]>([]);
const isSubmitting = ref(false);

const submitPasswordChange = () => {
    passwordErrors.value = [];
    isSubmitting.value = true;

    axios.post('/change-password', passwordForm.value)
        .then(() => {
            alert('Password updated successfully!');
            showChangePasswordModal.value = false;
            passwordForm.value = {
                current_password: '',
                new_password: '',
                confirm_password: '',
            };
        })
        .catch((error) => {
            if (error.response?.status === 422) {
                const errors = error.response.data.errors;
                for (const key in errors) {
                    passwordErrors.value.push(...errors[key]);
                }
            } else {
                passwordErrors.value.push('An unexpected error occurred.');
            }
        })
        .then(() => {
            // Mimics the purpose of `.finally()`
            isSubmitting.value = false;
        });
};
const showCurrent = ref(false);
const showNew = ref(false);
const showConfirm = ref(false);

interface User {
    id: number;
    name: string;
    email?: string;
    role: 'admin' | 'guard' | 'user';
    first_name: string;
    last_name: string;
    profile_image?: string | null;

}

const page = usePage();
const user = page.props.auth.user as User;

</script>

<template>
    <nav class="relative flex justify-between items-center px-6 py-4 bg-red-800 h-15 min-h-15 flex-shrink-0 overflow-visible">
        <img src="/images/logo1.png" alt="Logo" class="w-20 sm:w-28 h-auto object-contain" />
        <div class="flex items-center space-x-2 sm:space-x-4">
            <img
                :src="user.profile_image || '/guard_profiles/user.png'"
                @error="(e) => ((e.target as HTMLImageElement).src = '/guard_profiles/user.png')"
                alt="Profile"
                class="h-10 w-10 sm:h-10 sm:w-10 rounded-full object-cover border-2 border-white"
            />
            <div class="relative"> <!-- Ensure this div is relative -->
                <button @click="menuOpen = !menuOpen" class="focus:outline-none" aria-label="Toggle menu">
                    <svg class="h-5 w-5 sm:h-5 sm:w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <transition name="fade">
                    <div v-if="menuOpen" class="absolute right-0 top-full mt-2 w-40 sm:w-48 bg-[#760000] rounded-lg shadow-lg border border-[#a00000] z-[99999]">
                        <div class="flex items-center space-x-2 sm:space-x-3 px-3 py-2 border-b border-[#a00000]">
                            <img
                                :src="user.profile_image || '/guard_profiles/user.png'"
                                @error="(e) => ((e.target as HTMLImageElement).src = '/guard_profiles/user.png')"
                                alt="Profile"
                                class="h-10 w-10 sm:h-10 sm:w-10 rounded-full object-cover border-2 border-white"
                            />
                            <div>
                                <div class="text-white font-extrabold text-xs sm:text-sm">{{ user.first_name }} {{ user.last_name}}</div>
                                <div class="text-white text-opacity-80 italic text-[10px] sm:text-xs -mt-1">{{ user.role }}</div>
                            </div>
                        </div>
                        <nav class="flex flex-col px-3 py-2 space-y-1 sm:space-y-2">
                            <a href="#" @click.prevent="showChangePasswordModal = true"  class="text-white font-bold text-xs sm:text-sm hover:underline">CHANGE PASSWORD</a>
                            <button @click="logout" class="text-white font-bold text-xs sm:text-sm hover:underline text-left">LOGOUT</button>
                        </nav>
                    </div>

                </transition>
            </div>
        </div>


    </nav>
    <transition name="fade">
        <div v-if="showChangePasswordModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-[90%] sm:w-[400px] relative">
                <h2 class="text-lg font-bold mb-4">Change Password</h2>
                <form @submit.prevent="submitPasswordChange" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium">Current Password</label>
                        <div class="relative">
                            <input
                                :type="showCurrent ? 'text' : 'password'"
                                v-model="passwordForm.current_password"
                                class="w-full border px-3 py-2 rounded pr-10"
                                required
                            />
                            <span
                                @click="showCurrent = !showCurrent"
                                class="absolute right-2 top-2 cursor-pointer text-gray-600"
                            >
                            <svg v-if="showCurrent" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10 0-1.095.178-2.15.506-3.143M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c1.558 0 3.037.338 4.374.938m3.443 2.564C21.42 9.755 22 10.836 22 12c0 4.418-4.03 8-9 8-1.886 0-3.626-.534-5.082-1.458M4.59 4.59l14.82 14.82" />
                            </svg>
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">New Password</label>
                        <div class="relative">
                            <input
                                :type="showNew ? 'text' : 'password'"
                                v-model="passwordForm.new_password"
                                class="w-full border px-3 py-2 rounded pr-10"
                                required
                            />
                            <span
                                @click="showNew = !showNew"
                                class="absolute right-2 top-2 cursor-pointer text-gray-600"
                            >
                                <svg v-if="showNew" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10 0-1.095.178-2.15.506-3.143M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c1.558 0 3.037.338 4.374.938m3.443 2.564C21.42 9.755 22 10.836 22 12c0 4.418-4.03 8-9 8-1.886 0-3.626-.534-5.082-1.458M4.59 4.59l14.82 14.82" />
                            </svg>
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Confirm New Password</label>
                        <div class="relative">
                            <input
                                :type="showConfirm ? 'text' : 'password'"
                                v-model="passwordForm.confirm_password"
                                class="w-full border px-3 py-2 rounded pr-10"
                                required
                            />
                            <span
                                @click="showConfirm = !showConfirm"
                                class="absolute right-2 top-2 cursor-pointer text-gray-600"
                            >
                                 <svg v-if="showConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                      viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10 0-1.095.178-2.15.506-3.143M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c1.558 0 3.037.338 4.374.938m3.443 2.564C21.42 9.755 22 10.836 22 12c0 4.418-4.03 8-9 8-1.886 0-3.626-.534-5.082-1.458M4.59 4.59l14.82 14.82" />
                            </svg>

                            </span>
                        </div>
                    </div>

                    <div v-if="passwordErrors.length" class="text-sm text-red-600 space-y-1">
                        <div v-for="(err, i) in passwordErrors" :key="i">{{ err }}</div>
                    </div>

                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="showChangePasswordModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit" :disabled="isSubmitting" class="px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800">
                            {{ isSubmitting ? 'Saving...' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </transition>
</template>
