<script setup>
import Clock from './components/Clock.vue'



</script>

<template>
  <div class="container">
    <Clock :activeHours='[{start: 2, end: 4}, {start: 15, end: 22} , {start: 6, end: 9} ]'/>
  </div>
</template>

