<template>
  <div class="container">
    <div class="d-flex flex-row justify-content-end">
      <h4 class="black-font">سرعت</h4>
    </div>
    <input
      dir="rtl"
      type="range"
      name="speed"
      value="20"
      min="10"
      max="100"
      step="10"
      class="w-100 mt-3"
      @change="onSpeedChange"
    />
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { mapMutations } from "vuex";

export default defineComponent({
  name: "FanSpeed",
  methods: {
    ...mapMutations(["updateSpeed"]),
    onSpeedChange(event: any) {
      this.updateSpeed(event.target.value);
    },
  },
});
</script>

<style lang="scss" scoped>
input {
  &[type="range"] {
    accent-color: var(--primary-color);
    cursor: pointer;
  }
}
</style>