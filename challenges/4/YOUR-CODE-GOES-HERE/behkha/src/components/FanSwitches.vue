<template>
  <div class="container">
    <div class="row">
      <div class="col-6">
        <Switch title="Oscillation"/>
      </div>
      <div class="col-6">
        <Switch title="Power"/>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import Switch from './base/Switch.vue';
import { defineComponent } from "vue";

export default defineComponent({
  name: "FanSwitches",
  components: {
    Switch
  }
});
</script>

<style lang="scss" scoped>
</style>