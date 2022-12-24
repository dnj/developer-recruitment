<template>
  <v-container class="pb-0">
    <!--    title-->
    <h2 class="text-subtitle-1 font-weight-medium mb-6">
      {{
        $vuetify.locale.t('$vuetify.rotate_speed.title')
      }}
    </h2>
    <!--    speed control-->
    <v-slider
      v-model="value"
      :model-value="fanSpeed"
      @update:modelValue="$emit('update:modelValue',$event)"
      :min="0.2"
      :max="5"
      :step="0.2"
      thumb-label
      track-color="grey"
      color="#30849E"
      :disabled="!isPowerOn"
    >
      <template v-slot:thumb-label="{ modelValue }">
        {{ velocity[Math.min(Math.floor(modelValue / 2), 2)] }}
      </template>
    </v-slider>
  </v-container>
</template>

<script>
export default {
  name: "FanTableSpeed",
  data() {
    return {
      value: 0,
      velocity: ['کم', 'متوسط', 'زیاد']
    }
  },
  props: {
    fanSpeed: {
      type: Number,
      default: 3
    },
    isPowerOn: {
      type: Boolean,
      default: false
    }
  }
}
</script>

<style scoped>

</style>
