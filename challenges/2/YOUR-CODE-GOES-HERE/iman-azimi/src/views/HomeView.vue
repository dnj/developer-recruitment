<script setup lang="ts">
import { reactive } from 'vue'
import Clock from '../components/AppClock.vue'

const activeHours = reactive([
  { start: 3, end: 6 },
  { start: 15, end: 22 },
])
</script>

<template>
  <main>
    <Clock :activeHours="activeHours" />
  </main>
</template>
