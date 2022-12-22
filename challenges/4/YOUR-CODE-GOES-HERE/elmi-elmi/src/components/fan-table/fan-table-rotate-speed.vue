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
      thumb-label
      color="cyan-darken-2"
    ></v-slider>
  </v-container>
</template>

<script>
export default {
  name: "FanTableSpeed",
  data() {
    return {
      value: 0,
    }
  },
  props: {
    fanSpeed: {
      type: Number,
      default: 1
    }
  }
}
</script>

<style scoped>

</style>
