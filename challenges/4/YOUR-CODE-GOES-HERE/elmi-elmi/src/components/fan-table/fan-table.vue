<template>
  <section class="bg-grey-lighten-5 ">
    <!--  <h1>-->
    <!--    {{ $vuetify.locale.t('$vuetify.aaa') }}-->
    <!--  </h1>-->
    <FanTableOscillating :power-switch="powerSwitch" :rotate-switch="rotateSwitch"/>
    <FanTableActions :power-switch="powerSwitch" :rotate-switch="rotateSwitch"
                     @togglePower="powerSwitch = $event"
                     @toggleRotate="rotateSwitch = $event"
    />
    <FanTableRotateSpeed/>
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
    }
  },
  // methods:{
  //   togglePower(){},
  //   toggleRotate(),
  // }
  // watch:{
  //   powerSwitch(val,preVal){
  //     if(!val) this.rotateSwitch = false;
  //   }
  // }
}
</script>

<style scoped>

</style>
