<script setup>
import { computed } from 'vue';

const props = defineProps({
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg', 'xl'].includes(value)
    },
    variant: {
        type: String,
        default: 'full',
        validator: (value) => ['full', 'icon'].includes(value)
    },
    textColor: {
        type: String,
        default: 'text-slate-900'
    }
});

const sizeClasses = computed(() => {
    const sizes = {
        sm: 'w-6 h-6',
        md: 'w-8 h-8',
        lg: 'w-12 h-12',
        xl: 'w-16 h-16'
    };
    return sizes[props.size];
});

const textSizeClasses = computed(() => {
    const sizes = {
        sm: 'text-sm',
        md: 'text-base',
        lg: 'text-xl',
        xl: 'text-2xl'
    };
    return sizes[props.size];
});
</script>

<template>
    <div class="flex items-center space-x-3">
        <div :class="sizeClasses" class="flex-shrink-0 text-indigo-600">
            <img src="/images/logo.svg" alt="NeuroDemocracy Protocol" class="w-full h-full" />
        </div>
        <span 
            v-if="variant === 'full'" 
            :class="[textSizeClasses, textColor]"
            class="font-bold tracking-tight"
        >
            NeuroDemocracy
        </span>
    </div>
</template>
