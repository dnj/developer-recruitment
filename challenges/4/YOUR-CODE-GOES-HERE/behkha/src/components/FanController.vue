<template>
  <div class="d-flex flex-column align-items-center w-100">
    <FanSwitches />
  </div>
</template>

<script lang="ts">
import FanSwitches from './FanSwitches.vue';
import { defineComponent } from 'vue';
export default defineComponent({
  name: 'FanController',
  components: {
    FanSwitches
  }
})
</script>

<style lang="scss" scoped>

</style>