<template>
    <div class="min-h-screen bg-gray-50 p-4">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-blue-600 text-white p-6">
                    <h1 class="text-3xl font-bold text-center">Visitor Registration</h1>
                    <p class="text-center mt-2 text-blue-100">Please scan your ID and fill out the information</p>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Camera/Image Section -->
                        <div class="space-y-6">
                            <h3 class="text-2xl font-bold flex items-center gap-3 text-gray-800">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Scan Your ID
                            </h3>

                            <!-- Enhanced Camera Interface -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border-2 border-blue-200">
                                <!-- Initial State - Large, Prominent Buttons -->
                                <div v-if="!capturedImage && !isStreaming" class="text-center space-y-6">
                                    <div class="bg-white p-8 rounded-full w-32 h-32 mx-auto flex items-center justify-center shadow-lg">
                                        <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-xl font-semibold text-gray-800">📋 Take a Photo of Your ID</h4>
                                    <p class="text-gray-600">Choose one option below to scan your identification document</p>

                                    <div class="space-y-4">
                                        <!-- Large Camera Button -->
                                        <button
                                            @click="startCamera"
                                            class="w-full bg-green-600 hover:bg-green-700 text-white text-xl font-bold py-6 px-8 rounded-xl flex items-center justify-center gap-4 shadow-lg transform hover:scale-105 transition-all duration-200"
                                        >
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Use Camera
                                        </button>

                                        <!-- Large Upload Button -->
<!--                                        <button-->
<!--                                            @click="triggerFileUpload"-->
<!--                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xl font-bold py-6 px-8 rounded-xl flex items-center justify-center gap-4 shadow-lg transform hover:scale-105 transition-all duration-200"-->
<!--                                        >-->
<!--                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>-->
<!--                                            </svg>-->
<!--                                             Upload Photo-->
<!--                                        </button>-->
                                    </div>

                                    <input
                                        ref="fileInputRef"
                                        type="file"
                                        accept="image/*"
                                        @change="handleFileUpload"
                                        class="hidden"
                                    />
                                </div>

                                <!-- Streaming State - Large Preview -->
                                <div v-if="isStreaming" class="text-center space-y-6">
                                    <div class="relative bg-black rounded-lg overflow-hidden">
                                        <video
                                            ref="videoRef"
                                            autoplay
                                            playsinline
                                            muted
                                            class="w-full h-80 object-cover rounded-lg shadow-lg"
                                            style="min-height: 320px;"
                                        ></video>
                                        <div class="absolute inset-4 border-4 border-dashed border-yellow-400 rounded-lg pointer-events-none"></div>

                                    </div>

                                    <div class="bg-yellow-100 p-4 rounded-lg">
                                        <p class="text-lg font-semibold text-yellow-800">📋 Position your ID inside the yellow frame</p>
                                        <p class="text-yellow-700">Make sure the text is clear and readable</p>
                                    </div>

                                    <div class="flex flex-col sm:flex-row gap-4 justify-center w-full px-4">
                                        <button
                                            @click="captureImage"
                                            class="bg-red-600 hover:bg-red-700 text-white text-lg sm:text-xl font-bold py-4 px-6 sm:px-8
               rounded-xl flex items-center justify-center gap-3 shadow-lg
               w-full sm:w-auto"
                                        >
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Take Photo
                                        </button>

                                        <button
                                            @click="stopCamera"
                                            class="bg-gray-600 hover:bg-gray-700 text-white text-lg sm:text-xl font-bold py-4 px-6 sm:px-8
               rounded-xl flex items-center justify-center gap-3 shadow-lg
               w-full sm:w-auto"
                                        >
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Cancel
                                        </button>
                                    </div>

                                </div>

                                <!-- Captured Image State -->
                                <div v-if="capturedImage" class="text-center space-y-6">
                                    <div class="relative">
                                        <img
                                            :src="capturedImage"
                                            alt="Captured ID"
                                            class="w-full max-w-lg mx-auto rounded-lg shadow-lg border-4 border-white"
                                        />
                                        <div v-if="isProcessing" class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center">
                                            <div class="bg-white p-4 rounded-lg">
                                                <div class="animate-spin w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full mx-auto mb-2"></div>
                                                <p class="text-blue-600 font-semibold">🔍 Reading ID...</p>
                                                <p class="text-sm text-gray-600 mt-1">{{ ocrProgress }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="isProcessing" class="bg-blue-100 p-4 rounded-lg">
                                        <p class="text-lg font-semibold text-blue-800">🔍 Extracting information from your ID...</p>
                                        <p class="text-blue-700">Please wait while we read the text</p>
                                        <div class="w-full bg-blue-200 rounded-full h-2 mt-2">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" :style="`width: ${ocrProgressPercent}%`"></div>
                                        </div>
                                    </div>

                                    <div v-if="ocrError" class="bg-red-100 p-4 rounded-lg">
                                        <p class="text-red-800 font-semibold">❌ {{ ocrError }}</p>
                                    </div>

                                    <button
                                        v-if="!isProcessing"
                                        @click="retakeImage"
                                        class="bg-gray-600 hover:bg-gray-700 text-white text-lg font-bold py-3 px-6 rounded-xl flex items-center gap-2 mx-auto"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                        </svg>
                                         Take Another Photo
                                    </button>
                                </div>
                            </div>

                            <!-- Hidden canvas for image capture -->
                            <canvas ref="canvasRef" class="hidden"></canvas>

                            <!-- Extracted Text Display -->
<!--                            <div v-if="extractedText" class="bg-green-50 border-2 border-green-200 rounded-lg p-4">-->
<!--                                <h4 class="font-bold text-green-800 mb-2 text-lg">✅ Information Successfully Extracted:</h4>-->
<!--                                <pre class="text-sm text-green-700 whitespace-pre-wrap bg-white p-3 rounded border">{{ extractedText }}</pre>-->
<!--                                <p class="text-green-600 text-sm mt-2">📝 The form below has been automatically filled with this information.</p>-->
<!--                            </div>-->
                        </div>

                        <!-- Form Section -->
                        <div class="space-y-6">
                            <h3 class="text-2xl font-bold flex items-center gap-3 text-gray-800">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                 Your Information
                            </h3>

                            <div class="bg-gray-50 rounded-xl p-6 space-y-6">
                                <!-- Personal Information -->
                                <div class="space-y-4">
                                    <h4 class="text-lg font-semibold text-gray-700 border-b pb-2">📋 Personal Details</h4>
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">👤 First Name *</label>
                                            <input
                                                v-model="visitorForm.firstName"
                                                type="text"
                                                class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Enter your first name"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">👤 Middle Name</label>
                                            <input
                                                v-model="visitorForm.middleName"
                                                type="text"
                                                class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Enter your middle name (optional)"
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">👤 Last Name *</label>
                                            <input
                                                v-model="visitorForm.surname"
                                                type="text"
                                                class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Enter your last name"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">🆔 Type of ID</label>
                                            <input
                                                v-model="visitorForm.idType"
                                                type="text"
                                                class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="e.g., National ID, Driver's License"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <!-- Vehicle Information -->
                                <div class="space-y-4 border-t pt-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <input
                                            v-model="visitorForm.usedVehicle"
                                            type="checkbox"
                                            id="usedVehicle"
                                            class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                        />
                                        <label for="usedVehicle" class="text-lg font-bold text-gray-700 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                            </svg>
                                              Came with a vehicle
                                        </label>
                                    </div>

                                    <div v-if="visitorForm.usedVehicle" class="ml-8 space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-2">🚗 Vehicle Type</label>
                                                <select
                                                    v-model="visitorForm.vehicleType"
                                                    class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                >
                                                    <option value="">Select type</option>
                                                    <option value="car">🚗 Car</option>
                                                    <option value="motorcycle">🏍️ Motorcycle</option>
                                                    <option value="truck">🚚 Truck</option>
                                                    <option value="van">🚐 Van</option>
                                                    <option value="bus">🚌 Bus</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-2">🔢 Plate Number</label>
                                                <input
                                                    v-model="visitorForm.plateNumber"
                                                    type="text"
                                                    class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                    placeholder="ABC-1234"
                                                />
                                            </div>
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-2">👥 Passengers</label>
                                                <input
                                                    v-model="visitorForm.passengers"
                                                    type="number"
                                                    min="1"
                                                    class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                    placeholder="1"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- QR Visitor Selection -->
                                <div class="space-y-4 border-t pt-6">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">👥 Select QR Visitor</label>
                                    <select
                                        v-model="visitorForm.selectedVisitor"
                                        class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                        <option value="">Select a visitor</option>
                                        <option v-for="visitor in availableVisitors" :key="visitor" :value="visitor">{{ visitor }}</option>
                                    </select>
                                </div>

                                <!-- Submit Button -->
                                <div class="border-t pt-6">
                                    <button
                                        @click="submitRegistration"
                                        :disabled="!visitorForm.firstName || !visitorForm.surname"
                                        class="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white text-xl font-bold py-4 px-6 rounded-xl flex items-center justify-center gap-3 shadow-lg transform hover:scale-105 transition-all duration-200 disabled:transform-none"
                                    >
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                         Submit Registration
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, reactive, onUnmounted } from 'vue'
import Tesseract from 'tesseract.js'

export default {
    name: 'VisitorForm',
    setup() {
        // Camera and image processing states
        const cameraStream = ref(null)
        const isStreaming = ref(false)
        const capturedImage = ref(null)
        const extractedText = ref('')
        const isProcessing = ref(false)
        const ocrError = ref('')
        const ocrProgress = ref('')
        const ocrProgressPercent = ref(0)

        // Form data
        const visitorForm = reactive({
            firstName: '',
            middleName: '',
            surname: '',
            idType: '',
            usedVehicle: false,
            vehicleType: '',
            plateNumber: '',
            passengers: 1,
            selectedVisitor: ''
        })

        const availableVisitors = [
            'Visitor 1',
            'Visitor 2',
            'Visitor 3',
            'Visitor 4',
            'Visitor 5'
        ]

        // Template refs
        const videoRef = ref(null)
        const canvasRef = ref(null)
        const fileInputRef = ref(null)

        // Start camera
        const startCamera = async () => {
            try {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    throw new Error('Camera not supported in this browser')
                }

                isStreaming.value = true
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: { ideal: 1280 },
                        height: { ideal: 720 },
                        facingMode: 'environment'
                    }
                })

                if (videoRef.value) {
                    videoRef.value.srcObject = stream
                    cameraStream.value = stream
                    videoRef.value.onloadedmetadata = () => videoRef.value.play()
                }
            } catch (error) {
                console.error('Error accessing camera:', error)
                isStreaming.value = false
                alert(`Camera error: ${error.message}`)
            }
        }

        // Stop camera
        const stopCamera = () => {
            if (cameraStream.value) {
                cameraStream.value.getTracks().forEach(track => track.stop())
                cameraStream.value = null
            }
            if (videoRef.value) videoRef.value.srcObject = null
            isStreaming.value = false
        }

        // Capture image
        const captureImage = () => {
            if (videoRef.value && canvasRef.value) {
                const context = canvasRef.value.getContext('2d')
                canvasRef.value.width = videoRef.value.videoWidth
                canvasRef.value.height = videoRef.value.videoHeight
                context.drawImage(videoRef.value, 0, 0)
                capturedImage.value = canvasRef.value.toDataURL('image/jpeg', 0.9)
                stopCamera()
                processImage(capturedImage.value)
            }
        }

        // File upload
        const handleFileUpload = (event) => {
            const file = event.target.files[0]
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader()
                reader.onload = (e) => {
                    capturedImage.value = e.target.result
                    processImage(capturedImage.value)
                }
                reader.readAsDataURL(file)
            }
        }

        // Process image with OCR
        const processImage = async (imageDataURL) => {
            isProcessing.value = true
            ocrError.value = ''
            ocrProgress.value = 'Processing image...'

            try {
                const { data } = await Tesseract.recognize(
                    imageDataURL,
                    'eng+fil',
                    {
                        logger: m => {
                            if (m.status === 'recognizing text') {
                                ocrProgressPercent.value = Math.round(m.progress * 100)
                                ocrProgress.value = `Recognizing text... ${ocrProgressPercent.value}%`
                            }
                        },
                        tessedit_char_whitelist: 'ABCDEFGHIJKLMNOPQRSTUVWXYZÑabcdefghijklmnopqrstuvwxyzñ0123456789 -.,/',
                        preserve_interword_spaces: '1'
                    }
                )

                extractedText.value = data.text
                autoFillForm(data.text)

            } catch (error) {
                ocrError.value = 'Failed to extract text. Please try again with a clearer image.'
                console.error('OCR error:', error)
            } finally {
                isProcessing.value = false
            }
        }

        // Extract line after label
        const extractAfterLabel = (lines, regex) => {
            for (let i = 0; i < lines.length; i++) {
                if (regex.test(lines[i])) {
                    // If value is in the same line after a colon
                    if (lines[i].includes(':')) {
                        return lines[i].split(':')[1].trim();
                    }
                    // Otherwise, assume next line is the value
                    if (i + 1 < lines.length) {
                        return lines[i + 1].trim();
                    }
                }
            }
            return '';
        };

        // Detect ID type
        // Enhanced detectIdType function
        const detectIdType = (text) => {
            const upperText = text.toUpperCase()

            if (upperText.includes('PAMBANSANG PAGKAKAKILANLAN') ||
                upperText.includes('PHILSYS') ||
                upperText.includes('PHILIPPINE IDENTIFICATION')) {
                return "PhilSys"
            }

            if (upperText.includes("DRIVER'S LICENSE") ||
                upperText.includes('DRIVERS LICENSE') ||
                upperText.includes('LICENSE TO OPERATE')) {
                return "Driver's License"
            }

            if (upperText.includes('PASSPORT') ||
                upperText.includes('REPUBLIC OF THE PHILIPPINES') && upperText.includes('PASAPORTE')) {
                return "Passport"
            }

            if (upperText.includes('VOTER') || upperText.includes('COMELEC')) {
                return "Voter's ID"
            }

            if (upperText.includes('SSS') || upperText.includes('SOCIAL SECURITY')) {
                return "SSS ID"
            }

            if (upperText.includes('UMID') || upperText.includes('UNIFIED')) {
                return "UMID"
            }

            if (upperText.includes('TIN') || upperText.includes('TAXPAYER')) {
                return "TIN ID"
            }

            return "Government ID"
        }

        // Sanitize name
        const sanitizeName = (name) => {
            return name
                .replace(/[^A-ZÑ\s-]/gi, '')
                .replace(/\s+/g, ' ')
                .trim()
                .toUpperCase()
        }
        // Enhanced cleanNamePart function
        const cleanNamePart = (name) => {
            if (!name) return ''

            return name
                .split(/\s+/)
                .filter(word => {
                    // Remove very short words that are likely OCR noise
                    if (word.length <= 2 && !/^[A-ZÑ]\.?$/i.test(word)) return false

                    // Remove common OCR noise words
                    const noiseWords = ['AS', 'NA', 'NG', 'SA', 'SI', 'NI', 'KA', 'KO', 'MO', 'PO', '%']
                    if (noiseWords.includes(word.toUpperCase())) return false

                    return true
                })
                .join(' ')
                .replace(/[^A-ZÑ\s-]/gi, '') // Keep only letters, spaces, and hyphens
                .replace(/\s+/g, ' ') // Collapse multiple spaces
                .trim()
                .toUpperCase()
        }
        // Enhanced auto-fill form function
        const autoFillForm = (text) => {
            const lines = text.split('\n')
                .map(l => l.trim())
                .filter(l => l.length > 0)

            // Reset form fields
            visitorForm.firstName = ''
            visitorForm.middleName = ''
            visitorForm.surname = ''

            const idType = detectIdType(text)
            visitorForm.idType = idType

            // Enhanced name extraction with multiple strategies
            const extractedNames = extractNamesFromText(text, lines, idType)

            visitorForm.surname = extractedNames.surname
            visitorForm.firstName = extractedNames.firstName
            visitorForm.middleName = extractedNames.middleName

            console.log('Extracted:', visitorForm)
        }

// Main name extraction function with multiple strategies
        const extractNamesFromText = (fullText, lines, idType) => {
            // Strategy 1: Look for explicit labels (works for most IDs)
            let result = extractByLabels(lines)

            // Strategy 2: If labels failed, try pattern-based extraction
            if (!result.surname && !result.firstName) {
                result = extractByPatterns(fullText, lines)
            }

            // Strategy 3: If still no luck, try positional extraction for PhilSys
            if (!result.surname && !result.firstName && idType === "PhilSys") {
                result = extractByPosition(lines)
            }

            // Clean and format the extracted names
            return {
                surname: cleanNamePart(result.surname || ''),
                firstName: cleanNamePart(result.firstName || ''),
                middleName: cleanNamePart(result.middleName || '')
            }
        }

// Strategy 1: Extract by looking for specific labels
        const extractByLabels = (lines) => {
            let surname = ''
            let firstName = ''
            let middleName = ''

            // Define label patterns for different languages/formats
            const labelPatterns = {
                surname: [
                    /^(apelyido|apelido|surname|last\s*name|family\s*name)/i,
                    /^(.*apelyido|.*surname)/i
                ],
                firstName: [
                    /^(mga\s*pangalan|pangalan|given\s*names?|first\s*name)/i,
                    /^(.*pangalan|.*given)/i
                ],
                middleName: [
                    /^(gitnang\s*apelyido|middle\s*name|mn)/i,
                    /^(.*gitnang|.*middle)/i
                ]
            }

            for (let i = 0; i < lines.length; i++) {
                const line = lines[i]
                const nextLine = i + 1 < lines.length ? lines[i + 1] : ''

                // Check for surname patterns
                if (!surname && labelPatterns.surname.some(pattern => pattern.test(line))) {
                    if (line.includes(':')) {
                        surname = line.split(':').pop().trim()
                    } else if (nextLine && isValidName(nextLine)) {
                        surname = nextLine
                    }
                }

                // Check for first name patterns
                if (!firstName && labelPatterns.firstName.some(pattern => pattern.test(line))) {
                    if (line.includes(':')) {
                        firstName = line.split(':').pop().trim()
                    } else if (nextLine && isValidName(nextLine)) {
                        firstName = nextLine
                    }
                }

                // Check for middle name patterns
                if (!middleName && labelPatterns.middleName.some(pattern => pattern.test(line))) {
                    if (line.includes(':')) {
                        middleName = line.split(':').pop().trim()
                    } else if (nextLine && isValidName(nextLine)) {
                        middleName = nextLine
                    }
                }
            }

            return { surname, firstName, middleName }
        }

// Strategy 2: Extract by text patterns and context
        const extractByPatterns = (fullText, lines) => {
            let surname = ''
            let firstName = ''
            let middleName = ''

            // Look for comma-separated format (common in passports)
            const commaMatch = fullText.match(/([A-ZÑ\s]{2,30}),\s*([A-ZÑ\s]{2,30})/i)
            if (commaMatch) {
                surname = commaMatch[1].trim()
                const givenNames = commaMatch[2].trim().split(/\s+/)
                firstName = givenNames[0] || ''
                middleName = givenNames.slice(1).join(' ')
                return { surname, firstName, middleName }
            }

            // Look for name blocks (consecutive lines with valid names)
            const nameBlocks = findNameBlocks(lines)
            if (nameBlocks.length >= 2) {
                // Most common pattern: surname first, then given names
                surname = nameBlocks[0]

                if (nameBlocks.length >= 3) {
                    firstName = nameBlocks[1]
                    middleName = nameBlocks[2]
                } else {
                    // Split second block into first and middle names
                    const givenParts = nameBlocks[1].split(/\s+/)
                    firstName = givenParts[0] || ''
                    middleName = givenParts.slice(1).join(' ')
                }
            }

            return { surname, firstName, middleName }
        }

// Strategy 3: Extract by position (for PhilSys cards)
        const extractByPosition = (lines) => {
            // For PhilSys, names usually appear in the first few lines after filtering
            const nameLines = lines.filter(line =>
                isValidName(line) &&
                !line.match(/^(republika|republic|pilipinas|philippines|pambansang|identification)/i) &&
                !line.match(/^\d{4}-\d{4}-\d{4}-\d{4}/) // Exclude ID numbers
            )

            if (nameLines.length >= 3) {
                return {
                    surname: nameLines[0],
                    firstName: nameLines[1],
                    middleName: nameLines[2]
                }
            } else if (nameLines.length === 2) {
                // Try to split the second line
                const givenParts = nameLines[1].split(/\s+/)
                return {
                    surname: nameLines[0],
                    firstName: givenParts[0] || '',
                    middleName: givenParts.slice(1).join(' ')
                }
            }

            return { surname: '', firstName: '', middleName: '' }
        }

// Helper function to find blocks of text that look like names
        const findNameBlocks = (lines) => {
            return lines.filter(line => {
                return isValidName(line) &&
                    !line.match(/^(republika|republic|pilipinas|philippines)/i) &&
                    !line.match(/^\d/) && // Skip lines starting with numbers
                    !line.match(/^(phl|cav|male|female|m|f)$/i) && // Skip common non-name tokens
                    line.length > 2 &&
                    line.length < 50
            })
        }

// Enhanced helper function to check if a string looks like a valid name
        const isValidName = (str) => {
            if (!str || str.length < 2) return false

            // Should contain mostly letters
            const letterCount = (str.match(/[a-zA-ZÑñ]/g) || []).length
            const totalCount = str.replace(/\s/g, '').length

            if (letterCount / totalCount < 0.7) return false

            // Should not be common OCR noise or non-name words
            const blacklist = [
                /^(republika|republic|pilipinas|philippines|pambansang|identification|card|id|phl|cav|male|female|m|f|date|birth|address|pin|sig|signature)$/i,
                /^\d+$/, // Pure numbers
                /^[^a-zA-ZÑñ\s-]+$/, // No letters at all
                /^(as|na|ng|sa|si|ni|ka|ko|mo|po|oo|ah|eh|oh)$/i // Common Filipino particles/noise
            ]

            return !blacklist.some(pattern => pattern.test(str.trim()))
        }

        const extractNameField = (lines, keywords) => {
            for (let i = 0; i < lines.length; i++) {
                const line = lines[i].toLowerCase();
                if (keywords.some(k => line.includes(k))) {
                    // if same line has name after colon, take it
                    if (lines[i].includes(':')) {
                        return lines[i].split(':')[1].trim();
                    }
                    // otherwise use next non-empty line
                    for (let j = i + 1; j < lines.length; j++) {
                        if (lines[j].trim().length > 1) return lines[j].trim();
                    }
                }
            }
            return '';
        };



        // Retake image
        const retakeImage = () => {
            capturedImage.value = null
            extractedText.value = ''
            ocrError.value = ''
        }

        // Submit registration
        const submitRegistration = () => {
            if (!visitorForm.firstName || !visitorForm.surname) {
                alert('Please provide at least first name and last name')
                return
            }
            console.log('Submitting:', JSON.parse(JSON.stringify(visitorForm)))
            alert('Registration submitted successfully!')
            Object.assign(visitorForm, {
                firstName: '',
                middleName: '',
                surname: '',
                idType: '',
                usedVehicle: false,
                vehicleType: '',
                plateNumber: '',
                passengers: 1,
                selectedVisitor: ''
            })
            capturedImage.value = null
            extractedText.value = ''
        }

        // Cleanup
        onUnmounted(() => stopCamera())

        return {
            visitorForm,
            availableVisitors,
            isStreaming,
            capturedImage,
            extractedText,
            isProcessing,
            ocrError,
            ocrProgress,
            ocrProgressPercent,
            videoRef,
            canvasRef,
            fileInputRef,
            startCamera,
            stopCamera,
            captureImage,
            triggerFileUpload: () => fileInputRef.value?.click(),
            handleFileUpload,
            retakeImage,
            submitRegistration,
            cleanNamePart,
            extractAfterLabel
        }
    }
}
</script>


<style scoped>
/* Add any additional styles here if needed */
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}
</style>
