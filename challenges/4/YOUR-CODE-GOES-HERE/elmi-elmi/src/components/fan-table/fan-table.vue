<template>
  <section class="bg-grey-lighten-5 ">
    <!--  <h1>-->
    <!--    {{ $vuetify.locale.t('$vuetify.aaa') }}-->
    <!--  </h1>-->
    <FanTableOscillating
      :speed="speed"
      :power-switch="powerSwitch"
      :rotate-switch="rotateSwitch"
      :state="state"
    />
    <FanTableActions
      :power-switch="powerSwitch" :rotate-switch="rotateSwitch"
      @togglePower="powerSwitch = $event"
      @toggleRotate="rotateSwitch = $event"
    />
    <FanTableRotateSpeed
      :fan-speed="fanSpeed"
      @update:modelValue="fanSpeed=$event"
    />
    <FanTableWindState
      @windMode="changeWindMode"
      :states="states"
    />


  </section>
</template>

<script>
import FanTableOscillating from "@/components/fan-table/fan-table-oscillating";
import FanTableActions from "@/components/fan-table/fan-table-actions";
import FanTableRotateSpeed from "@/components/fan-table/fan-table-rotate-speed";
import FanTableWindState from "@/components/fan-table/fan-table-wind-state";

export default {
  name: "SectionFanTable",
  components: {
    FanTableWindState,
    FanTableRotateSpeed,
    FanTableActions,
    FanTableOscillating
  }
  ,
  data() {
    return {
      powerSwitch: false,
      rotateSwitch: false,
      fanSpeed: 1,
      states: [
        {
          icon: 'mdi-tailwind',
          text: 'ساده',
          selected: true,
          mode:'Normal'
        },
        {
          icon: 'mdi-waves',
          text: 'اقیانوسی',
          selected: false,
          mode:'Oceanic'
        },
        {
          icon: 'mdi-white-balance-sunny',
          text: 'استوایی',
          mode:'Tropical'
        },
        {
          icon: 'mdi-pine-tree',
          text: 'جنگلی',
          selected: false,
          mode:'Woodsy'
        },
      ]

    }
  },
  computed: {
    speed() {
      return 1 / this.fanSpeed + 's'
    },
    state(){
      const state = this.states.find(state => state.selected)
      return state.mode
    }
  },
  methods: {
    // togglePower(){},
    // toggleRotate(),
    ttt(e) {
      console.log('----', e)
    },
    changeWindMode(e) {
      const preState = this.states.find(state=>state.selected === true)
      preState.selected = false;
      const state = this.states.find(state=>state.mode===e)
      state.selected = true
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
