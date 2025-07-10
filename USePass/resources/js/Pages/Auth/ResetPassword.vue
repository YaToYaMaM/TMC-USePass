<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import ForgotPassLayout from "@/Layouts/ForgotPassLayout.vue";

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <ForgotPassLayout>
        <div class="relative bg-white rounded-xl drop-shadow-[0px_4px_34px_rgba(118,0,0,0.06)] w-full max-w-md px-8 pt-28 pb-8 min-h-[500px] mt-16">
            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white w-[120px] max-w-100 h-[120px]  rounded-full shadow-md px-8">
                <img src="/images/ForgotPassword.png" alt="Logo" class="w-50 mt-6 text-center " />
            </div>
        <Head title="Reset Password" />

            <div class="mb-8 text-md text-gray-600 justify-self-center">
                Enter new password
            </div>

        <form @submit.prevent="submit">
            <div class="mt-4">
                <InputLabel for="password" value="" />
                <label class="block text-[17px] font-normal text-customRed mb-0">New Password</label>

                <TextInput
                    id="password"
                    type="password"
                    class="w-full border-0 border-b-2 border-red-900 focus:outline-none focus:border-customRed rounded-sm py-2 pr-10
                            placeholder:text-md placeholder:italic"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-14">
                <InputLabel for="password_confirmation" value=""/>
                <label class="block text-[17px] font-normal text-customRed mb-1">Confirm Password</label>

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="w-full border-0 border-b-2 border-red-900 focus:outline-none focus:border-customRed rounded-sm py-2 pr-10
                            placeholder:text-md placeholder:italic"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-14 flex items-center justify-center">
                <PrimaryButton
                    :class="'block mx-auto bg-red-900 hover:bg-red-700 text-white font-semibold py-2 rounded shadow',
                    { 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Update Password
                </PrimaryButton>
            </div>
        </form>
    </div>
    </ForgotPassLayout>
</template>
