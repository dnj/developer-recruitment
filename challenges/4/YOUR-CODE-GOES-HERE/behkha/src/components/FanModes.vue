<template>
  <div class="container">
    <div class="d-flex flex-row justify-content-end">
      <h4>Fan Mode</h4>
    </div>
    <div class="mt-3">
      <div class="row">
        <div class="col-3 col-md-12" v-for="mode in modes" :key="mode.id">
          <FanModeItem :title="mode.title" @click="onFanModeSelect(mode.title)"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import FanModeItem from './FanModeItem.vue'
import { defineComponent } from 'vue'
import { mapMutations } from 'vuex'
export default defineComponent({
  name: 'FanModes',
  components: {
    FanModeItem,
  },
  data() {
    return {
      modes: [
        { id: 1, title: 'simple' },
        { id: 2, title: 'oceanic' },
        { id: 3, title: 'tropical' },
        { id: 4, title: 'woodsy' },
      ]
    }
  },
  methods: {
    ...mapMutations(['updateRotationMode']),
    onFanModeSelect(title: string) {
      this.updateRotationMode(title);
    }
  }
})
</script>

<style lang="scss" scoped>

</style>