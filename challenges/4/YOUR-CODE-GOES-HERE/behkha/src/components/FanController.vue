<template>
  <div class="w-100">
    <FanSwitches />
    <FanSpeed class="mt-3"/>
    <FanModes class="mt-3"/>
  </div>
</template>

<script lang="ts">
import FanSwitches from './FanSwitches.vue';
import FanSpeed from './FanSpeed.vue';
import FanModes from './FanModes.vue';
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'FanController',
  components: {
    FanSwitches,
    FanSpeed,
    FanModes
  }
})
</script>

<style lang="scss" scoped>

</style>