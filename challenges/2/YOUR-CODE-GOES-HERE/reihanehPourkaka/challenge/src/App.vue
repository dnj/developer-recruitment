<script setup>
import Clock from './components/Clock.vue'



</script>

<template>
  <div class="container">
    <Clock :activeHours='[{start: 5, end: 13}, {start: 22, end: 24}]'/>
  </div>
</template>

