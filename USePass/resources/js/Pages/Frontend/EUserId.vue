<template>
    <div
        class="relative min-h-screen bg-cover bg-center flex flex-col items-center justify-center"
        :style="{ backgroundImage: 'url(/images/bg_tmc.jpg)' }"
    >
        <!-- 🔹 Overlay layer -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <!-- 🔹 Foreground content -->
        <div class="relative z-10 p-1 rounded-lg text-center">
            <img
                src="/images/Logo1.png"
                alt="USePass Logo"
                class="w-[700px] max-w-none mx-auto mb-0"
                style="margin-top: -290px; width: 800px;"
            />
<!-- <h1 class="text-4xl font-bold text-white drop-shadow-lg">USePass</h1>-->
            <p class="text-white italic -mt-10 text-lg md:text-xl lg:text-2xl">
                "Track Student. Ensure Safety. <strong class='text-yellow-300 font-bold'>USePass.</strong>"
            </p>

            <input
                v-model="userId"
                type="text"
                placeholder="User ID"
                class="mt-6 px-4 py-2 rounded-md w-64 text-center shadow-lg focus:outline-none"
            />

            <div class="flex justify-center gap-4 mt-4">
                <button @click="selectCampus('Tagum')" class="bg-white hover:bg-gray-100 px-6 py-2 rounded-md font-semibold shadow">
                    Tagum
                </button>
                <button @click="selectCampus('Mabini')" class="bg-white hover:bg-gray-100 px-6 py-2 rounded-md font-semibold shadow">
                    Mabini
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "UserIDView",
    data() {
        return {
            userId: "",
        };
    },
    methods: {
        selectCampus(campus) {
            console.log(`User ID: ${this.userId}, Campus: ${campus}`);
            // Add your login navigation or API logic here
        },
    },
};
</script>

<style scoped>
body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
</style>
