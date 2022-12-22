<template>
  <section class="bg-grey-lighten-5 ">
    <!--  <h1>-->
    <!--    {{ $vuetify.locale.t('$vuetify.aaa') }}-->
    <!--  </h1>-->
    <FanTableOscillating
      :speed="speed"
      :power-switch="powerSwitch" :rotate-switch="rotateSwitch"/>
    <FanTableActions :power-switch="powerSwitch" :rotate-switch="rotateSwitch"
                     @togglePower="powerSwitch = $event"
                     @toggleRotate="rotateSwitch = $event"
    />
    <FanTableRotateSpeed
      :fan-speed="fanSpeed"
      @update:modelValue="fanSpeed=$event"
    />
    <FanTableWindState/>


  </section>
</template>

<script>
import FanTableOscillating from "@/components/fan-table/fan-table-oscillating";
import FanTableActions from "@/components/fan-table/fan-table-actions";
import FanTableRotateSpeed from "@/components/fan-table/fan-table-rotate-speed";
import FanTableWindState from "@/components/fan-table/fan-table-wind-state";

export default {
  name: "SectionFanTable",
  components: {FanTableWindState, FanTableRotateSpeed, FanTableActions, FanTableOscillating}
  ,
  data() {
    return {
      powerSwitch: false,
      rotateSwitch: false,
      fanSpeed: 1
    }
  },
  computed:{
    speed(){
      return 1/this.fanSpeed +'s'
    }
  },
  methods: {
    // togglePower(){},
    // toggleRotate(),
    ttt(e) {
      console.log('----', e)
    }
  }
  // watch:{
  //   powerSwitch(val,preVal){
  //     if(!val) this.rotateSwitch = false;
  //   }
  // }
}
</script>

<style scoped>

</style>
