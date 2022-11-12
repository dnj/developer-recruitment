<script setup lang="ts">
import { ref } from 'vue'

const errors = ref('')

export interface Props {
  activeHours: { start: Number; end: Number }[]
}
withDefaults(defineProps<Props>(), {
  activeHours: () => [{ start: 10, end: 15 }],
})
const getQuarterHourStyle = (i) => {
  return `transform: rotate(${15 * i}deg)`
}
const getTimesStyle = (hours) => {
  if (hours.start < 1 || hours.end > 24) {
    errors.value = 'زمان انتخابی صحیح نمی باشد'
    return
  }
  const start = 15 * hours.start
  const end = (hours.end - hours.start) * 15
  return `transform: rotate(${start}deg); background: conic-gradient(orange, ${end}deg, transparent calc(${end}deg + 0.5deg) 100%);`
}
</script>
<template>
  <section class="app-timer">
    <div
      class="app-timer__hours"
      v-for="hours in activeHours"
      :key="hours.i"
      :style="getTimesStyle(hours)"
    ></div>

    <div class="app-clock">
      <div>
        <span class="h6">۶</span>
        <span class="h12">۱۲</span>
        <span class="h18">۱۸</span>
        <span class="h24">۰۰</span>
      </div>
      <div
        v-for="i in 24"
        :key="i"
        :style="getQuarterHourStyle(i)"
        :class="[i == 24 || i == 6 || i == 12 || i == 18 ? '' : 'quarterhour']"
      ></div>
    </div>
    <p class="error">{{ errors }}</p>
  </section>
</template>

<style scoped></style>
