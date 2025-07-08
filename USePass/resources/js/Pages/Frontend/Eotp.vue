<template>
    <div
        class="relative min-h-screen bg-cover bg-center flex flex-col items-center justify-center"
        :style="{ backgroundImage: 'url(/images/bg_tmc.jpg)' }"
    >
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <!-- Foreground content -->
        <div class="relative z-10 p-1 rounded-lg text-center">
            <img
                src="/images/Logo1.png"
                alt="USePass Logo"
                class="w-[700px] max-w-none mx-auto mb-0"
                style="margin-top: -290px; width: 800px;"
            />
            <p class="text-white italic -mt-10 text-lg md:text-xl lg:text-2xl">
                "Track Student. Ensure Safety.
                <strong class="text-yellow-300 font-bold">USePass.</strong>"
            </p>

            <!-- Six inputs side by side -->
            <div class="mt-6 flex justify-center space-x-3">
                <input
                    v-for="(digit, index) in code"
                    :key="index"
                    v-model="code[index]"
                    type="text"
                    maxlength="1"
                    ref="inputs"
                    class="w-12 h-12 text-center rounded-md shadow-lg focus:outline-none text-xl"
                    @input="onInput(index, $event)"
                    @keydown.backspace="onBackspace(index, $event)"
                />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "UserIDView",
    data() {
        return {
            code: ["", "", "", "", "", ""],
        };
    },
    methods: {
        onInput(index, event) {
            const input = event.target;
            let value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, ""); // Allow only alphanumeric uppercase
            this.$set(this.code, index, value);
            input.value = value; // sanitize input

            if (value && index < this.code.length - 1) {
                // Move focus to next input
                this.$refs.inputs[index + 1].focus();
            }
        },
        onBackspace(index, event) {
            if (this.code[index] === "" && index > 0) {
                this.$refs.inputs[index - 1].focus();
            }
        },
    },
};
</script>
