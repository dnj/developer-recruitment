<template>
  <div class="d-flex flex-column align-items-center">
    <Fan />
    <FanController />
  </div>
</template>

<script lang="ts">
import Fan from './components/Fan.vue';
import FanController from './components/FanController.vue';
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'App',
  components: {
    Fan,
    FanController,
  }
})
</script>

<style scoped>
</style>
