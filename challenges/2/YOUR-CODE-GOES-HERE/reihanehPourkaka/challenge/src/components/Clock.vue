<script setup>
import { ref} from "vue";

const timeRange = ref({
  start: Number,
  end:Number
});

const props = defineProps(['activeHours'])
const checkValid = () => {
  timeRange.value = props.activeHours;
  if( timeRange.value.start < 0  )  timeRange.value.start = 0;
  if( timeRange.value.start > 24)  timeRange.value.end = 24;
};
checkValid();

</script>

<template>

    <div class="timeRange"
         v-for="(activeHour,index) in timeRange"
         :key="index"
         :style="{backgroundImage : 'conic-gradient(rgb(255 255 255 / 0%)  00deg  , rgb(255 255 255 / 0%) '+activeHour.start *15+'deg ,#ee7e25  '+activeHour.start *15+'deg  , #ee7e25  '+activeHour.end *15+'deg,rgb(255 255 255 / 0%)'+activeHour.end *15+'deg  , rgb(255 255 255 / 0%) 360deg)' }"
    >

    </div>
    <div class="clock">
      <div
          class="degreeClock"
          v-for="i in 24"
          :key="i"
          :style="{ transform: 'rotate('+15*i + 'deg)'}"
      >
        <div
            class="lines"
            v-show="i % 6 !== 0"
        ></div>
        <span
            class="number"
            v-show="i% 6 === 0"
            :style="{ transform: 'rotate('+ -15*i + 'deg)'}"
        >
          {{ i === 24 ? "00" : i < 10 ? "0" + i : i }}
          </span>
      </div>

    </div>

</template>


<style scoped>

.timeRange {
  display: inline-block;
  width: 320px;
  height: 320px;
  border-radius: 50%;
  position: absolute;
  z-index: 0;
  top: calc(50% - 164px);
  left: calc(50% - 160px);
}

.clock {
  display: inline-block;
  background: #ffffff;
  width: 300px;
  height: 300px;
  border-radius: 50%;
  box-shadow: 0 0 2vw -1vw rgba(197, 185, 185, 0.8);
}

.degreeClock {
  top: 4%;
  transform-origin: 50% 138px;
}

.lines {
  position: absolute;
  left: 47%;
  z-index: 2;
  width: 3px;
  height: 7px;
  background: #333;
  margin-left: 10px;

}

.number {
  position: absolute;
  left: 47%;
  z-index: 2;
  margin-top: -11px;
  margin-left: -1px;
  color: #333;
  font-size: 22px;
  font-weight: 400;
}

</style>