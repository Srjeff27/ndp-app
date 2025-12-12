<script setup>
import { onMounted, ref, watch } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const props = defineProps({
    nodes: {
        type: Array,
        default: () => []
    },
    center: {
        type: Array,
        default: () => [20, 0]
    },
    zoom: {
        type: Number,
        default: 2
    }
});

const mapContainer = ref(null);
const map = ref(null);

onMounted(() => {
    map.value = L.map(mapContainer.value).setView(props.center, props.zoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map.value);

    // Add Markers
    updateMarkers();
});

watch(() => props.nodes, () => {
    updateMarkers();
}, { deep: true });

function updateMarkers() {
    if (!map.value) return;

    // Clear existing layer? Needs a layer group.
    // Simplifying: just add new ones for prototype. Real app needs cleanup.
    
    props.nodes.forEach(node => {
        L.marker([node.lat, node.lng])
            .addTo(map.value)
            .bindPopup(`<b>${node.name}</b><br>${node.institution || ''}<br>${node.country}`);
    });
}
</script>

<template>
    <div ref="mapContainer" class="h-96 w-full rounded-lg shadow-lg z-0"></div>
</template>

<style>
/* Fix Leaflet z-index issues if any */
.leaflet-pane {
    z-index: 10 !important;
}
</style>
