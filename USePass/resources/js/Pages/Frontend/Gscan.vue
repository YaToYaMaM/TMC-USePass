<template>
    <div
        class="relative min-h-screen bg-cover bg-center flex flex-col items-center justify-center px-4"
        :style="{ backgroundImage: 'url(/images/bg_tmc.jpg)' }"
    >
        <div class="absolute top-0 left-0 w-full h-12 bg-[#760000] z-20"></div>
        <!-- Overlay layer -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <!-- Foreground content -->
        <div class="relative z-10 p-4 rounded-lg text-center max-w-lg w-full">
            <div class="relative inline-block mx-auto max-w-[600px] w-full">
                <img
                    src="/images/logo1.png"
                    alt="USePass Logo"
                    class="mx-auto w-full h-auto"
                />
                <!-- Scanner bar -->
                <div class="absolute bottom-0 left-0 w-full h-1 overflow-hidden">
                    <div class="scanner-bar"></div>
                </div>
            </div>

            <!-- Barcode Scanner Camera -->
            <div class="mt-8 max-w-[600px] mx-auto rounded-lg overflow-hidden shadow-lg">
                <StreamBarcodeReader
                    @decode="onDecode"
                    @init="onInit"
                    class="w-full h-64 rounded-lg object-cover"
                />
                <p class="mt-2 text-yellow-300 font-semibold select-text break-words">
                    Scanned Code: {{ scannedCode || 'No code detected yet' }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { StreamBarcodeReader } from "vue-barcode-reader";

export default {
    name: "UserIDView",
    components: { StreamBarcodeReader },
    data() {
        return {
            userId: ["", "", "", "", "", ""],
            timer: 120,
            timerInterval: null,
            timeoutMessageVisible: false,
            isResending: false,
            scannedCode: null,
        };
    },
    computed: {
        formattedTime() {
            const minutes = Math.floor(this.timer / 60)
                .toString()
                .padStart(2, "0");
            const seconds = (this.timer % 60).toString().padStart(2, "0");
            return `${minutes}:${seconds}`;
        },
    },
    methods: {
        onInput(index) {
            this.userId[index] = this.userId[index].replace(/\D/g, "");
            if (this.userId[index].length > 1) {
                this.userId[index] = this.userId[index].slice(0, 1);
            }
            if (this.userId[index] && index < this.userId.length - 1) {
                const nextInput = this.$refs["input" + (index + 1)];
                if (nextInput && nextInput[0]) {
                    nextInput[0].focus();
                }
            }
        },
        onBackspace(index, event) {
            if (this.userId[index] === "" && index > 0) {
                const prevInput = this.$refs["input" + (index - 1)];
                if (prevInput && prevInput[0]) {
                    prevInput[0].focus();
                    event.preventDefault();
                }
            }
        },
        getUserIdString() {
            return this.userId.join("");
        },
        startTimer() {
            if (this.timerInterval) clearInterval(this.timerInterval);
            this.timer = 120;
            this.timeoutMessageVisible = false;
            this.isResending = false;
            this.timerInterval = setInterval(() => {
                if (this.timer > 0) {
                    this.timer--;
                } else {
                    clearInterval(this.timerInterval);
                    this.timerInterval = null;
                    this.timeoutMessageVisible = true;
                    // Do NOT reload automatically here
                }
            }, 1000);
        },
        resendOtp() {
            this.isResending = true;
            // TODO: Add your OTP resend logic here (e.g., API call)
            console.log("Resending OTP...");

            setTimeout(() => {
                window.location.reload();
            }, 3000);
        },
        onDecode(result) {
            this.scannedCode = result;
            console.log("Decoded barcode:", result);
            // Optional: You can auto-fill or trigger actions here
        },
        onInit(promise) {
            promise.catch((error) => {
                console.error("Camera initialization failed:", error);
            });
        },
    },
    mounted() {
        this.startTimer();
    },
    beforeUnmount() {
        if (this.timerInterval) clearInterval(this.timerInterval);
    },
};
</script>

<style scoped>
body {
    margin: 0;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

/* Scanner bar animation */
.scanner-bar {
    height: 4px;
    width: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.7),
        transparent
    );
    animation: scan 2s linear infinite;
}

@keyframes scan {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(100%);
    }
}
</style>
