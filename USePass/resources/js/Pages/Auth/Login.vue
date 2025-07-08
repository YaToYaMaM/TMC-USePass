<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-8">
            <div class="border-white flex justify-center mb-6 rounded-lg" >
                <img src="/images/Logo2.png" alt="Logo" class="w-40" />
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <div class="relative">
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            placeholder="Enter your username"
                            class="w-full border-b-2 border-red-900 focus:outline-none focus:border-red-600 py-2 pr-10"
                        />
                        <span class="absolute right-2 top-1/2 transform -translate-y-1/2 text-red-900">
              <i class="fas fa-user"></i>
            </span>
                    </div>
                    <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input
                            v-model="form.password"
                            type="password"
                            required
                            placeholder="Enter your password"
                            class="w-full border-b-2 border-red-900 focus:outline-none focus:border-red-600 py-2 pr-10"
                        />
                        <span class="absolute right-2 top-1/2 transform -translate-y-1/2 text-red-900">
              <i class="fas fa-key"></i>
            </span>
                    </div>
                    <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                </div>

                <div class="flex justify-between items-center text-sm">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" v-model="form.remember" class="rounded border-gray-300" />
                        <span>Remember me</span>
                    </label>
                    <Link href="/forgot-password" class="text-red-900 hover:underline">Forgot password?</Link>
                </div>

                <button
                    type="submit"
                    class="w-full bg-red-900 hover:bg-red-700 text-white font-semibold py-2 rounded shadow"
                >
                    Login
                </button>
            </form>

            <div class="mt-8 text-center text-xs text-gray-500">
                © 2025. All Rights Reserved. <br />
                <Link href="#" class="underline mx-1">Terms of Use</Link> |
                <Link href="#" class="underline mx-1">Privacy Policy</Link>
            </div>
        </div>
    </GuestLayout>
</template>
