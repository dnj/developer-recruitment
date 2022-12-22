<template>
  <div class="container">
    <div class="d-flex flex-row justify-content-end">
      <h4>Fan Mode</h4>
    </div>
    <div class="mt-3">
      <div class="row">
        <div class="col-3 col-md-12" v-for="mode in modes" :key="mode.id">
          <FanModeItem :title="mode.title" @click="onFanModeSelect(mode.title, mode.animation)" :selected="getRotationMode === mode.title"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import FanModeItem from './FanModeItem.vue'
import { defineComponent } from 'vue'
import { mapGetters, mapMutations } from 'vuex'
export default defineComponent({
  name: 'FanModes',
  components: {
    FanModeItem,
  },
  data() {
    return {
      modes: [
        { id: 1, title: 'simple', animation: 'linear' },
        { id: 2, title: 'oceanic', animation: 'ease-in-out' },
        { id: 3, title: 'tropical', animation: 'ease' },
        { id: 4, title: 'woodsy', animation: 'cubic-bezier(0.5, 0.36, 0.55, 0.84)' },
      ]
    }
  },
  methods: {
    ...mapMutations(['updateRotationMode', 'updateRotationAnimation']),
    onFanModeSelect(title: string, animation: string) {
      this.updateRotationMode(title);
      this.updateRotationAnimation(animation);
    }
  },
  computed: {
    ...mapGetters(['getRotationMode'])
  }
})
</script>

<style lang="scss" scoped>

</style>