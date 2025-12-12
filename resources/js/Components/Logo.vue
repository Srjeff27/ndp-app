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
        sm: 'w-8 h-8',
        md: 'w-10 h-10',
        lg: 'w-14 h-14',
        xl: 'w-20 h-20'
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
        <div :class="sizeClasses" class="flex-shrink-0">
            <img src="/images/logo-dpp.png" alt="Democracy Protocol Policy" class="w-full h-full object-contain rounded-lg" />
        </div>
        <span 
            v-if="variant === 'full'" 
            :class="[textSizeClasses, textColor]"
            class="font-bold tracking-tight"
        >
            DPP
        </span>
    </div>
</template>
