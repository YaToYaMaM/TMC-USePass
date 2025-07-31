<template>
    <Head title="USePass" />
    <div
        class="relative min-h-screen bg-cover bg-center flex flex-col items-center justify-center px-4"
        :style="{ backgroundImage: 'url(/images/bg_tmc.jpg)' }"
    >
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <div class="relative z-10 p-4 rounded-lg text-center max-w-lg w-full">
            <!-- Logo + Motto -->
            <div class="relative inline-block mx-auto max-w-[600px] w-full">
                <img src="/images/logo4.png" alt="USePass Logo" class="mx-auto w-full h-auto" />
                <p class="text-white italic text-base md:text-lg lg:text-xl mb-6">
                    "Track Student. Ensure Safety. USePass."
                </p>
                <div class="corner-border relative">
                    <div class="absolute origin-center w-full h-full">
                        <div class="scanner-bar"></div>
                        <div class="scan-line"></div>
                    </div>
                </div>
            </div>

            <!-- User ID input -->
            <div class="flex items-center justify-center pt-5">
                <div class="relative w-full max-w-xs text-center">
                    <input
                        type="text"
                        v-model="userIdInput"
                        @keyup.enter="checkStudent(false)"
                        class="max-w-100 px-4 py-1 text-center rounded-[10px] bg-black bg-opacity-40 border border-white border-opacity-40 text-white placeholder-white placeholder-opacity-60 focus:outline-none"
                        placeholder=" "
                    />
                     </div>
            </div>

            <!-- Check button -->
            <div class="fixed bottom-10 left-20 z-50">
                <button
                    :disabled="checking"
                    @click="checkStudent(true)"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow cursor-not-allowed"
                >
                    {{ checking ? "Checking..." : "Check" }}
                </button>
            </div>

            <!-- Back button -->
            <div class="fixed bottom-10 right-20 z-50">
                <Link
                    :href="route('guard.ghome')"
                    class="text-white p-3 rounded-full hover:bg-white hover:bg-opacity-10 transition inline-block"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                        class="w-8 h-8"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l-7 7 7 7M22 12H3" />
                    </svg>
                </Link>
            </div>
        </div>
    </div>

    <transition name="fade"  v-if="!triggeredByButton">
        <div
            v-if="showProfileModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 px-4"
        >
            <div
                class="relative z-10 flex flex-col items-center
                px-4 sm:px-8 md:px-12 lg:px-20
                py-6 md:py-10
                w-full
                max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl 2xl:max-w-2xl
                space-y-6 md:space-y-8
                bg-white rounded-xl shadow-lg text-center"
            >
                <!-- Close Button -->
                <button
                    @click="showProfileModal = false"
                    class="absolute top-3 right-3 text-gray-600 hover:text-red-500 text-xl"
                >cd
                </button>

                <!-- Logo -->
                <img
                    src="/images/Logo2.png"
                    alt="USePass Logo"
                    class="mb-2 w-48 sm:w-56 md:w-64 lg:w-80 h-auto"
                />

                <!-- If student found -->
                <div v-if="studentFound" class="flex flex-col items-center space-y-4">
                    <div
                        class="rounded-lg overflow-hidden shadow border-4 border-white
                        w-40 h-40 sm:w-48 sm:h-48 md:w-56 md:h-56 lg:w-64 lg:h-64"
                    >
                        <img
                            :src="getProfileImageUrl(studentProfile.profileImage)"
                            alt="Profile"
                            class="object-cover w-full h-full"
                        />
                    </div>
                    <div class="text-gray-800 text-center">
                        <div class="text-lg sm:text-xl md:text-2xl font-bold tracking-wide">
                            {{ studentProfile.fullName }}
                        </div>
                        <div class="text-sm md:text-base lg:text-lg font-medium italic">
                            {{ studentProfile.course }}
                        </div>
                        <div class="text-base md:text-lg mt-2 font-mono tracking-widest">
                            {{ studentProfile.idNumber }}
                        </div>
                    </div>
                </div>

                <!-- If student not found -->
                <div v-else class="text-red-600 text-xl font-semibold">
                    No Student ID Found.
                </div>
            </div>
        </div>
    </transition>
    <div
        v-else-if="showProfileModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 px-4"
    >
        <div
            class="relative z-10 flex flex-col items-center
                px-4 sm:px-8 md:px-12 lg:px-20
                py-6 md:py-10
                w-full
                max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl 2xl:max-w-2xl
                space-y-6 md:space-y-8
                bg-white rounded-xl shadow-lg text-center"
        >
            <!-- Close Button -->
            <button
                @click="showProfileModal = false"
                class="absolute top-3 right-3 text-gray-600 hover:text-red-500 text-xl"
            >
                ×
            </button>

            <!-- Logo -->
            <img
                src="/images/Logo2.png"
                alt="USePass Logo"
                class="mb-2 w-48 sm:w-56 md:w-64 lg:w-80 h-auto"
            />

            <!-- If student found -->
            <div v-if="studentFound" class="flex flex-col items-center space-y-4">
                <div
                    class="rounded-lg overflow-hidden shadow border-4 border-white
                        w-40 h-40 sm:w-48 sm:h-48 md:w-56 md:h-56 lg:w-64 lg:h-64"
                >
                    <img
                        :src="getProfileImageUrl(studentProfile.profileImage)"
                        alt="Profile"
                        class="object-cover w-full h-full"
                    />
                </div>
                <div class="text-gray-800 text-center">
                    <div class="text-lg sm:text-xl md:text-2xl font-bold tracking-wide">
                        {{ studentProfile.fullName }}
                    </div>
                    <div class="text-sm md:text-base lg:text-lg font-medium italic">
                        {{ studentProfile.course }}
                    </div>
                    <div class="text-base md:text-lg mt-2 font-mono tracking-widest">
                        {{ studentProfile.idNumber }}
                    </div>
                </div>
            </div>

            <!-- If student not found -->
            <div v-else class="text-red-600 text-xl font-semibold">
                No Student ID Found.
            </div>
        </div>
    </div>

    <router-view />
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import axios from "axios";

export default {
    name: "Gscan",
    components: { Head, Link },
    data() {
        return {
            userIdInput: "",
            studentFound: null,
            checking: false,
            showProfileModal: false,
            triggeredByButton: false,
            studentProfile: {
                fullName: "",
                course: "",
                idNumber: "",
                profileImage: "",
            },
        };
    },
    methods: {
        getProfileImageUrl(filename) {
            if (!filename) {
                return '/images/profile.png';
            }
            if (filename.startsWith('http') || filename.startsWith('/')) {
                return filename;
            }
            return `/profile_pictures/${filename}`;
        },


        async checkStudent(fromButton = false) {
            this.triggeredByButton = fromButton;
            const trimmedId = this.userIdInput.trim();

            if (!trimmedId) {
                this.studentFound = false;
                this.showModalTemporary();
                return;
            }

            this.checking = true;
            this.studentFound = null;

            try {
                const response = await axios.get(`/students/${trimmedId}`);
                if (response.data.exists) {
                    this.studentFound = true;
                    const student = response.data.student;
                    this.studentProfile.fullName = student.full_name;
                    this.studentProfile.course = student.course;
                    this.studentProfile.idNumber = student.id_number;
                    this.studentProfile.profileImage = student.profile_image;
                } else {
                    this.studentFound = false;
                }
            } catch (error) {
                console.error("Error fetching student data:", error);
                this.studentFound = false;
            } finally {
                this.checking = false;
                this.showModalTemporary();
            }
        },

        showModalTemporary() {
            this.showProfileModal = true;
            setTimeout(() => {
                this.showProfileModal = false;
            }, 3000);
        },
    },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
