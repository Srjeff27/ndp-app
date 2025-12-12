<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { 
    EnvelopeIcon, 
    LockClosedIcon,
    ArrowRightIcon,
    EyeIcon,
    EyeSlashIcon
} from '@heroicons/vue/24/outline';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const showPassword = ref(false);

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
        <Head title="Log in" />

        <div class="p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Welcome Back</h2>
                <p class="mt-2 text-sm text-gray-600">Sign in to your account to continue</p>
            </div>

            <!-- Status Message -->
            <div v-if="status" class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                <p class="text-sm font-medium text-emerald-800">{{ status }}</p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Email -->
                <div>
                    <InputLabel for="email" value="Email Address" class="mb-2" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <EnvelopeIcon class="h-5 w-5 text-gray-400" />
                        </div>
                        <TextInput
                            id="email"
                            type="email"
                            class="block w-full pl-10"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="you@example.com"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <!-- Password -->
                <div>
                    <InputLabel for="password" value="Password" class="mb-2" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <LockClosedIcon class="h-5 w-5 text-gray-400" />
                        </div>
                        <TextInput
                            id="password"
                            :type="showPassword ? 'text' : 'password'"
                            class="block w-full pl-10 pr-10"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                        />
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        >
                            <EyeIcon v-if="!showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                            <EyeSlashIcon v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                        </button>
                    </div>
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors"
                    >
                        Forgot password?
                    </Link>
                </div>

                <!-- Submit Button -->
                <div>
                    <PrimaryButton
                        class="w-full justify-center"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        <span>Sign In</span>
                        <ArrowRightIcon class="ml-2 w-4 h-4" />
                    </PrimaryButton>
                </div>

                <!-- Register Link -->
                <div class="text-center pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600">
                        Don't have an account?
                        <Link
                            :href="route('register')"
                            class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors"
                        >
                            Sign up
                        </Link>
                    </p>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
