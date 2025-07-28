<template>
    <div
        class="relative min-h-screen bg-cover bg-center flex flex-col items-center justify-center px-4"
        :style="{ backgroundImage: 'url(/images/bg_tmc.jpg)' }"
    >
        <!-- Red top bar -->
        <div class="absolute top-0 left-0 w-full h-12 bg-[#760000] z-20"></div>

        <!-- Dark overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <div class="min-h-screen flex items-center justify-center">
            <div class="relative z-10 p-2 rounded-lg text-center max-w-lg w-full mb-20">
                <img
                    src="/images/logo3.png"
                    alt="USePass Logo"
                    class="mx-auto mb-7 w-[1000px] min-w-64 h-auto"
                />

                <!-- Student ID input -->
                <input
                    v-model="userId"
                    type="text"
                    placeholder="User ID"
                    class="w-full max-w-xs mx-auto px-4 py-2 rounded-md text-center shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                    @keyup.enter="fetchStudentData"
                />

                <button
                    @click="fetchStudentData"
                    :disabled="loading || !userId.trim()"
                    class="mt-4 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-6 py-2 rounded-md font-semibold shadow w-full max-w-xs"
                >
                    {{ loading ? 'Loading...' : 'Continue' }}
                </button>

                <div v-if="error" class="mt-4 text-red-300 text-sm">
                    {{ error }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "UserIDView",
    data() {
        return {
            userId: "",
            loading: false,
            error: null
        };
    },
    methods: {
        async fetchStudentData() {
            if (!this.userId.trim()) {
                this.error = 'Please enter a User ID';
                return;
            }

            this.loading = true;
            this.error = null;

            try {
                const response = await axios.post('/student/get-data', {
                    students_id: this.userId.trim()
                });

                if (response.data.success) {
                    // Navigate to UserDetails with student data
                    this.$router.push({
                        name: 'UserDetails',
                        params: {
                            studentData: response.data.data.student,
                            parentData: response.data.data.parent
                        }
                    });
                }
            } catch (error) {
                if (error.response?.status === 404) {
                    this.error = 'Student not found';
                } else {
                    this.error = 'An error occurred. Please try again.';
                }
                console.error('Error:', error);
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>

<style scoped>
body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

</style>
