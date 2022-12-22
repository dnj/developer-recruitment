<template>
    <div class="d-flex justify-center align-center fan-table-oscillating" >
      <div  :class="{'head':rotateSwitch && powerSwitch}">
        <img :class="{'rotate':powerSwitch}" height="150"  src="src/assets/blades.svg" alt="oscillating">
      </div>
    </div>
</template>

<script>
export default {
  name: "FanTableOscillating",
  // data(){
  //   return {
  //     // speed: 1 + 's'
  //     a:'ease-in-out'
  //   }
  // },
  props:{
    powerSwitch:{
      type: Boolean,
      default: false
    },
    rotateSwitch: {
      type: Boolean,
      default: false
    },
    speed:{
      type: String,
      default: '1s'
    },
    state:{
      type: String,
      default:'Simple'
    }
  },
  computed:{
    animationMOde(){
      if(this.state === 'Normal') return 'linear'
      if(this.state === 'Oceanic') return 'ease-in-out'
      if(this.state === 'Tropical') return 'ease'
      if(this.state === 'Woodsy') return 'cubic-bezier(0.5,0.36,0.55,0.84)'
      else return 'linear'
    }
  }
}
</script>

<style lang="scss"  scoped>
.fan-table-oscillating {
  height: 300px;
  width: 100%;
  background-color: #30849E;
}
.rotate{
  animation: rotate v-bind(speed) linear infinite;
}

.head{
  animation:  rotate-head 3s v-bind(animationMOde) infinite ;
}

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
