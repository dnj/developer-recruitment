<template>
  <div class="d-flex justify-center align-center  fan-table-oscillating" >
    <div class="head">
    <img class=" rotate" height="150"  src="src/assets/blades.svg" alt="oscillating">

    </div>
  </div>

</template>

<script>
export default {
  name: "FanTableOscillating",
  data(){
    return {
      speed: 1 + 's'
    }
  }
}
</script>

<style scoped>
.fan-table-oscillating {
  height: 300px;
  width: 100%;
  background-color: #30849E;
}
.rotate{
  animation: rotate v-bind(speed) linear infinite;
}

.head{
  animation: rotate v-bind(speed) linear infinite, rotate-head 3s infinite linear;
}

/*@keyframes rotation {*/
/*  50%{ transform:rotatey(180deg) }*/
/*  100%{ transform:rotatey(0) }*/
/*}*/
@keyframes rotate-head {
  0% {
    transform:  rotate3d(0, 0, 0, 0deg);
  }
  25% {
    transform: rotate3d(0, 1, 0, -70deg);
  }
  50% {
    transform: rotate3d(0, 0, 0, 0deg);
  }
  75% {
    transform: rotate3d(0, 1, 0, 70deg);
  }
  100% {
    transform:  rotate3d(0, 0, 0, 0deg);
  }
}
@keyframes rotate{
  to{ transform: rotate(360deg); }
}


</style>
