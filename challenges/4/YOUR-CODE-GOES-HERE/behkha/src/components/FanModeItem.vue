<template>
  <div 
    :class="{ selected: selected }"
    class="card p-2 mb-md-3 d-flex flex-column align-items-center justify-content-center"
    >
    <h6 class="regular-font my-auto">{{getTitle}}</h6>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
export default defineComponent({
  name: 'FanModeItem',
  props: {
    title: {
      type: String,
      default: 'Title'
    },
    selected: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    getTitle(): string {
      return this.title;
    }
  }
})
</script>

<style lang="scss" scoped>
.card {
  cursor: pointer;
  min-width: 80px;
  min-height: 80px;
  background-color: white;
  border-radius: 12px;
  border: none;
  transition: all 0.3s ease;

  &.selected {
    background-color: var(--primary-color);
    color: white;
    border: 2px solid black;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px;
  }
}
</style>