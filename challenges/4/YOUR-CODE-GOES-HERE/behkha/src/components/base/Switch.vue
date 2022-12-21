<template>
  <div class="card mt-3 p-3">
    <div class="d-flex flex-row-reverse">
      <h5>{{title}}</h5>
    </div>
    <div class="d-flex flex-row justify-content-between mt-3">
      <SwitchButton class="my-auto" @toggle="status = !status"/>
      <h5 class="mt-1">{{(status ? 'On' : 'Off')}}</h5>
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