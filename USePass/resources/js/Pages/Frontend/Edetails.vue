<script setup lang="ts">
import {ref, onMounted} from "vue";
import { useRoute } from "vue-router";
import JsBarcode from "jsbarcode"

const route = useRoute();
const guardianEmail = ref("");
const verified = ref(false);

const studentData = ref(route.params.studentData || {});
const parentData = ref(route.params.parentData || {});

const verify = () => {
    if (guardianEmail.value.trim() !== "") {
        verified.value = true;
        generateBarcode();
    } else {
        alert("Please enter guardian email.");
    }
};

const generateBarcode = () => {
    JsBarcode("#barcode", studentData.value.students_id, {
        format: "CODE128",
        lineColor: "#000",
        width: 2,
        height: 80,
        displayValue: true,
    });
};

const downloadBarcode = () => {
    const svg = document.querySelector("#barcode");
    const serializer = new XMLSerializer();
    const source = serializer.serializeToString(svg);
    const blob = new Blob([source], { type: "image/svg+xml;charset=utf-8" });
    const url = URL.createObjectURL(blob);

    const link = document.createElement("a");
    link.href = url;
    link.download = "barcode.svg";
    link.click();
};
</script>

<template>
    <div
        class="relative min-h-screen bg-cover bg-center px-4 py-8"
        :style="{ backgroundImage: 'url(/images/bg_tmc.jpg)' }"
    >
        <div class="absolute top-0 left-0 w-full h-20 bg-red-800 z-20 flex flex-col items-center justify-center text-white"></div>

        <div class="absolute top-14 left-1/2 transform -translate-x-1/2 z-10 flex flex-col items-center mt-2 text-white">
            <img src="/images/Logo1.png" alt="Logo" class="h-10 mb-1" />
            <span class="font-bold text-lg">USePass</span>
            <span class="text-sm italic">Unified Secure Pass • Campus ID System</span>
        </div>

        <div class="absolute inset-0 bg-black bg-opacity-60 z-0"></div>

        <div class="relative z-10 mt-20 bg-white bg-opacity-90 rounded-md shadow-md p-6 max-w-4xl mx-auto space-y-6">
            <!-- User ID -->
            <h2 class="text-black font-semibold text-lg">User ID: {{ studentData.students_id }}</h2>

            <!-- Personal Info -->
            <div>
                <h3 class="font-bold mb-2">Personal Information</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    <div><strong>First Name:</strong> {{ studentData.students_first_name || 'N/A' }}</div>
                    <div><strong>Middle Name:</strong> {{ studentData.students_middle_initial || 'N/A' }}</div>
                    <div><strong>Surname:</strong> {{ studentData.students_last_name || 'N/A' }}</div>
                    <div><strong>Sex:</strong> {{ studentData.students_gender || 'N/A' }}</div>
                    <div><strong>Program:</strong> {{ studentData.students_program || 'N/A' }}</div>
                    <div><strong>Major:</strong> {{ studentData.students_major || 'N/A' }}</div>
                    <div><strong>Contact:</strong> {{ studentData.students_phone_num || 'N/A' }}</div>
                    <div><strong>Unit:</strong> {{ studentData.students_unit || 'N/A' }}</div>
                    <div class="col-span-3"><strong>Email:</strong> {{ studentData.students_email || 'N/A' }}</div>
                </div>
            </div>

            <!-- Guardian Info -->
            <div v-if="parentData">
                <h3 class="font-bold mb-2">Parent/Guardian Information</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    <div><strong>First Name:</strong> {{ parentData.parent_first_name || 'N/A' }}</div>
                    <div><strong>Middle Name:</strong> {{ parentData.parent_middle_initial || 'N/A' }}</div>
                    <div><strong>Surname:</strong> {{ parentData.parent_last_name || 'N/A' }}</div>
                    <div><strong>Relation:</strong> {{ parentData.parent_relation || 'N/A' }}</div>
                    <div><strong>Contact:</strong> {{ parentData.parent_phone_num || 'N/A' }}</div>
                    <div class="md:col-span-2"><strong>Email:</strong>
                        <input
                            type="email"
                            v-model="guardianEmail"
                            :placeholder="parentData.parent_email || 'Enter email account'"
                            class="mt-1 px-2 py-1 border border-gray-400 rounded w-full"
                        />
                    </div>
                </div>

                <div class="mt-4">
                    <button
                        @click="verify"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm"
                    >
                        Verify
                    </button>
                </div>
            </div>

            <!-- Barcode Section -->
            <div v-if="verified" class="pt-4">
                <h3 class="font-bold mb-2">Barcode</h3>
                <div class="bg-white p-6 rounded shadow-md text-center">
                    <svg id="barcode"></svg>
                    <button
                        @click="downloadBarcode"
                        class="mt-4 bg-red-700 hover:bg-red-800 text-white px-4 py-1 text-sm rounded"
                    >
                        Download
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
</style>

