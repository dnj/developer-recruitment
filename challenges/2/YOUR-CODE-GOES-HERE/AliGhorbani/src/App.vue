<template>
  <analog-clock :active-hours="[{start: 5, end: 6}, {start: 17, end: 18}, {start: 20, end: 24}]"/>
</template>

<script>
import AnalogClock from './components/AnalogClock.vue'

export default {
  name: 'App',
  components: {
    AnalogClock
  }
}
</script>