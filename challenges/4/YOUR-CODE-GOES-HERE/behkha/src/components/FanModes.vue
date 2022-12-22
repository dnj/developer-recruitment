<template>
  <div class="container">
    <div class="d-flex flex-row justify-content-end">
      <h4>حالت باد</h4>
    </div>
    <div class="mt-3">
      <div class="row">
        <div class="col-3 col-md-12" v-for="mode in modes" :key="mode.id">
          <FanModeItem :title="mode.title_fa" @click="onFanModeSelect(mode.title)" :selected="getRotationMode === mode.title"/>
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
        { id: 4, title: 'woodsy', title_fa: 'جنگلی' },
        { id: 3, title: 'tropical', title_fa: 'استوایی' },
        { id: 2, title: 'oceanic', title_fa: 'اقیانوسی' },
        { id: 1, title: 'simple', title_fa: 'ساده' },
      ]
    }
  },
  methods: {
    ...mapMutations(['updateRotationMode']),
    onFanModeSelect(title: string) {
      this.updateRotationMode(title);
    }
  },
  computed: {
    ...mapGetters(['getRotationMode'])
  }
})
</script>

<style lang="scss" scoped>

</style>