<script setup>
defineProps({
    hover: {
        type: Boolean,
        default: false
    },
    padding: {
        type: String,
        default: 'p-6'
    }
});
</script>

<template>
    <div 
        :class="[
            'bg-white rounded-xl shadow-sm border border-gray-200
            padding,
            hover ? 'transition-all duration-200 hover:shadow-lg hover:border-indigo-300 : ''
        ]"
    >
        <slot />
    </div>
</template>
