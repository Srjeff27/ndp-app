<script setup>
defineProps({
    title: String,
    value: [String, Number],
    icon: Object,
    trend: String, // 'up', 'down', 'neutral'
    trendValue: String,
    color: {
        type: String,
        default: 'indigo'
    }
});

const colorClasses = {
    indigo: 'bg-indigo-500',
    emerald: 'bg-emerald-500',
    amber: 'bg-amber-500',
    rose: 'bg-rose-500',
    blue: 'bg-blue-500',
    purple: 'bg-purple-500'
};
</script>

<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">{{ title }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ value }}</p>
                <div v-if="trend" class="mt-2 flex items-center text-sm">
                    <span 
                        :class="[
                            'font-medium',
                            trend === 'up' ? 'text-emerald-600 : 
                            trend === 'down' ? 'text-rose-600 : 
                            'text-gray-600
                        ]"
                    >
                        {{ trendValue }}
                    </span>
                </div>
            </div>
            <div 
                v-if="icon"
                :class="[
                    'p-3 rounded-lg',
                    colorClasses[color]
                ]"
            >
                <component :is="icon" class="w-6 h-6 text-white" />
            </div>
        </div>
    </div>
</template>
