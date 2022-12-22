<template>
  <v-container>
    <h2 class="text-h6">
      {{
        $vuetify.locale.t('$vuetify.rotate_speed.title')
      }}
    </h2>
    <v-slider
      v-model="value"
      :model-value="fanSpeed"
      @update:modelValue="$emit('update:modelValue',$event)"
      :min="0"
      :max="5"
      :step="0.2"
      thumb-label="always"
      color="cyan-darken-2"
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
      velocity:['کم','متوسط','زیاد']
    }
  },
  props: {
    fanSpeed: {
      type: Number,
      default: 1
    },
    isPowerOn:{
      type: Boolean,
      default: false
    }
  }
}
</script>

<style scoped>

</style>
