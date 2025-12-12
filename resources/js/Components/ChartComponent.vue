<script setup>
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
  LineElement,
  PointElement
} from 'chart.js'
import { Bar, Line } from 'vue-chartjs'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend, LineElement, PointElement)

const props = defineProps({
    type: {
        type: String,
        default: 'bar'
    },
    data: {
        type: Object,
        required: true
    },
    options: {
        type: Object,
        default: () => ({
            responsive: true,
            maintainAspectRatio: false
        })
    }
});
</script>

<template>
    <div class="h-64 w-full">
        <Bar v-if="type === 'bar'" :data="data" :options="options" />
        <Line v-if="type === 'line'" :data="data" :options="options" />
    </div>
</template>
