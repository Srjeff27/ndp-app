<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { 
    UserIcon,
    EnvelopeIcon, 
    LockClosedIcon,
    ArrowRightIcon,
    EyeIcon,
    EyeSlashIcon
} from '@heroicons/vue/24/outline';

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Create Account</h2>
                <p class="mt-2 text-sm text-gray-600">Join the NeuroDemocracy community</p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Name -->
                <div>
                    <InputLabel for="name" value="Full Name" class="mb-2" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <UserIcon class="h-5 w-5 text-gray-400" />
                        </div>
                        <TextInput
                            id="name"
                            type="text"
                            class="block w-full pl-10"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="John Doe"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

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
                            autocomplete="new-password"
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

                <!-- Confirm Password -->
                <div>
                    <InputLabel for="password_confirmation" value="Confirm Password" class="mb-2" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <LockClosedIcon class="h-5 w-5 text-gray-400" />
                        </div>
                        <TextInput
                            id="password_confirmation"
                            :type="showPasswordConfirmation ? 'text' : 'password'"
                            class="block w-full pl-10 pr-10"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                        />
                        <button
                            type="button"
                            @click="showPasswordConfirmation = !showPasswordConfirmation"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        >
                            <EyeIcon v-if="!showPasswordConfirmation" class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                            <EyeSlashIcon v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                        </button>
                    </div>
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>

                <!-- Submit Button -->
                <div>
                    <PrimaryButton
                        class="w-full justify-center"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        <span>Create Account</span>
                        <ArrowRightIcon class="ml-2 w-4 h-4" />
                    </PrimaryButton>
                </div>

                <!-- Login Link -->
                <div class="text-center pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <Link
                            :href="route('login')"
                            class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors"
                        >
                            Sign in
                        </Link>
                    </p>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
