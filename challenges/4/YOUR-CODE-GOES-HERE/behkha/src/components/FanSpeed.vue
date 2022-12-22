<template>
  <div class="container">
    <div class="d-flex flex-row justify-content-end">
      <h4>Speed</h4>
    </div>
    <input dir="rtl" type="range" value="1" min="1" max="3" step="1" class="w-100 mt-3" @change="onSpeedChange"/>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { mapMutations } from 'vuex';

export default defineComponent({
  name: 'FanSpeed',
  methods: {
    ...mapMutations(['updateSpeed']),
    onSpeedChange(event: any) {
      this.updateSpeed(event.target.value);
    }
  }
})
</script>

<style scoped>
input[type="range"] {
  accent-color: var(--primary-color);
}
</style>