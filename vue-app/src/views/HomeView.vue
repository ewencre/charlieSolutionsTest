<script setup>
import "leaflet/dist/leaflet.css";
import { LMap, LTileLayer, LMarker, LCircle } from '@vue-leaflet/vue-leaflet';
import useInventory from '../composables/inventory';
import { onMounted } from 'vue';

const latitude = 50.6337848;
const longitude = 3.0217842;
const radius = 500;
const date = '2023-03-14';

const charlieCenter = [latitude, longitude];

const { inventory, getInventory } = useInventory();

onMounted(() => getInventory(latitude, longitude, radius));
</script>

<template>
  <main>
    <div class="w-full aspect-video">
      <l-map ref="map" :zoom="15" :center="[charlieCenter]">
        <l-tile-layer
          url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
          layer-type="base"
          name="OpenStreetMap"
        ></l-tile-layer>
        <l-marker v-for="sensor in inventory.filter(function(i) {return i.date === date})" v-bind:key="sensor" :lat-lng="[sensor.latitude, sensor.longitude]"></l-marker>
        <l-circle :lat-lng="charlieCenter" :radius="radius" :color="blue"></l-circle>
      </l-map>
    </div>
    <div>
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Capteur
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nombre de sorties
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="sensor in inventory" :key="sensor" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="px-6 py-4">{{ sensor.sensor_name }}</td>
                    <td class="px-6 py-4">{{ sensor.date }}</td>
                    <td class="px-6 py-4">{{ sensor.amount }}</td>
                </tr>
            </tbody>
        </table>
    </div>
  </main>
</template>
