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
      @togglePower="togglePower($event)"
      @toggleRotate="toggleRotate($event)"
    />
    <FanTableRotateSpeed
      :fan-speed="fanSpeed"
      @update:modelValue="fanSpeed=$event"
      :is-power-on="powerSwitch"
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
          selected: false,
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
    state:{
      get(){
        const state = this.states.find(state => state.selected)
        return state?.mode
      },
      set(val){
        const state = this.states[0]
        state.selected = val;
      }

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
      if(preState?.selected) preState.selected = false;
      const state = this.states.find(state=>state.mode===e)
      state.selected = true
      this.toggleRotate(true)
    },
    togglePower(e){
      this.powerSwitch = e;
      if(this.powerSwitch === false) {
        this.rotateSwitch = false
        const preState = this.states.find(state=>state.selected === true)
        if(preState?.selected) preState.selected = false;
      }
    },
    toggleRotate(e){
      this.rotateSwitch = e;
      if(this.rotateSwitch === true) {
        this.powerSwitch = true
        if(!this.state) this.state = true;
      }
      if(this.rotateSwitch === false){
        const preState = this.states.find(state=>state.selected === true)
        if(preState?.selected) preState.selected = false;
      }
    },
  },
  watch:{
    // rotateSwitch(val,preVal){
    //   if(val) this.powerSwitch = true;
    // },
    // powerSwitch(val,preVal){
    //   if(!val) {
    //     this.rotateSwitch = false
    //     const preState = this.states.find(state=>state.selected === true)
    //     if(preState?.selected) preState.selected = false;
    //   }
    // },
    // state(val){
    //   this.rotateSwitch =  true;
    // }
  }
}
</script>

<style scoped>

</style>
