<template>
  <div class="card mt-3 p-3">
    <div class="d-flex flex-row-reverse">
      <h5>{{title}}</h5>
    </div>
    <div class="d-flex flex-row justify-content-between mt-3">
      <SwitchButton class="my-auto" @toggle="onSwitchToggle"/>
      <h5 class="mt-1">{{(status ? 'روشن' : 'خاموش')}}</h5>
    </div>
  </div>
</template>

<script lang="ts">
import SwitchButton from './SwitchButton.vue';
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'Switch',
  components: {
    SwitchButton
  },
  props: {
    title: {
      type: String,
      default: 'Title'
    },
  },
  data() {
    return {
      status: false,
    }
  },
  methods: {
    onSwitchToggle() {
      this.status = !this.status;
      this.$emit('toggle', this.status)
    }
  }
})
</script>

<style lang="scss" scoped>
.card {
  background-color: white;
  border-radius: 12px;
  border: none;
}
</style>