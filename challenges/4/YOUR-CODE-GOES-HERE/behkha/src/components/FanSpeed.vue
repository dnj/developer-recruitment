<template>
  <div class="container">
    <div class="d-flex flex-row justify-content-end">
      <h4>Speed</h4>
    </div>
    <input dir="rtl" type="range" class="w-100 mt-3"/>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
export default defineComponent({
  name: 'FanSpeed'
})
</script>

<style scoped>
input[type="range"] {
  accent-color: var(--primary-color);
}
</style>