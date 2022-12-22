<template>
  <div class="container">
    <div class="row">
      <div class="col-6">
        <Switch title="Oscillation" @toggle="onOscillationToggle"/>
      </div>
      <div class="col-6">
        <Switch title="Power" @toggle="onPowerToggle"/>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import Switch from './base/Switch.vue';
import { defineComponent } from "vue";
import { mapMutations } from 'vuex';

export default defineComponent({
  name: "FanSwitches",
  components: {
    Switch
  },
  methods: {
    ...mapMutations(['updatePower', 'updateOscillation']),
    onOscillationToggle(status: boolean) {
      this.updateOscillation(status)
    },

    onPowerToggle(status: boolean) {
      this.updatePower(status)
    }
  }
});
</script>

<style lang="scss" scoped>
</style>