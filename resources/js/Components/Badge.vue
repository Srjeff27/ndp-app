<script setup>
defineProps({
    variant: {
        type: String,
        default: 'default', // default, success, warning, danger, info
        validator: (value) => ['default', 'success', 'warning', 'danger', 'info'].includes(value)
    },
    size: {
        type: String,
        default: 'md', // sm, md, lg
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    }
});

const variantClasses = {
    default: 'bg-gray-100 text-gray-800
    success: 'bg-emerald-100 text-emerald-800
    warning: 'bg-amber-100 text-amber-800
    danger: 'bg-red-100 text-red-800
    info: 'bg-indigo-100 text-indigo-800
};

const sizeClasses = {
    sm: 'px-2 py-0.5 text-xs',
    md: 'px-2.5 py-1 text-sm',
    lg: 'px-3 py-1.5 text-base'
};
</script>

<template>
    <span 
        :class="[
            'inline-flex items-center font-medium rounded-full',
            variantClasses[variant],
            sizeClasses[size]
        ]"
    >
        <slot />
    </span>
</template>
