<template>
<v-container>
سرعت چرخش
  <v-slider
    v-model="value"
    :min="0"
    :max="1"
    :step="0.2"
    thumb-label
    color="cyan-darken-2"
  ></v-slider>
</v-container>
</template>

<script>
export default {
  name: "FanTableSpeed",
  data () {
    return {
      value: 0,
    }
  },
}
</script>

<style scoped>

</style>
