<template>
  <section class="fan-table-bg ">
    <FanTableBlade
      :speed="speed"
      :power-switch="powerSwitch"
      :rotate-switch="rotateSwitch"
      :state="state"
    />
    <FanTableActions
      :power-switch="powerSwitch"
      :rotate-switch="rotateSwitch"
      @togglePower="togglePower($event)"
      @toggleRotation="toggleRotation($event)"
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
import FanTableBlade from "@/components/fan-table/fan-table-blade";
import FanTableActions from "@/components/fan-table/fan-table-actions";
import FanTableRotateSpeed from "@/components/fan-table/fan-table-rotate-speed";
import FanTableWindState from "@/components/fan-table/fan-table-wind-state";

export default {
  name: "SectionFanTable",
  components: {
    FanTableWindState,
    FanTableRotateSpeed,
    FanTableActions,
    FanTableBlade
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
          mode: 'Normal'
        },
        {
          icon: 'mdi-waves',
          text: 'اقیانوسی',
          selected: false,
          mode: 'Oceanic'
        },
        {
          icon: 'mdi-white-balance-sunny',
          text: 'استوایی',
          mode: 'Tropical'
        },
        {
          icon: 'mdi-pine-tree',
          text: 'جنگلی',
          selected: false,
          mode: 'Woodsy'
        },
      ]

    }
  },
  computed: {
    //blade speed computed here
    speed() {
      return 1 / this.fanSpeed + 's'
    },
    // state: [Normal, Oceanic Tropical Woodsy}
    state: {
      get() {
        const state = this.states.find(state => state.selected)
        return state?.mode
      },
      set(val) {
        const state = this.states[0]
        state.selected = val;
      }

    }
  },
  methods: {
    changeWindMode(e) {
      this.turnOffWindMode()
      const state = this.states.find(state => state.mode === e)
      state.selected = true
      this.toggleRotation(true)
    },
    togglePower(e) {
      this.powerSwitch = e;
      // if user power off the fan => head rotate should be stopped
      if (this.powerSwitch === false) {
        this.rotateSwitch = false
        this.turnOffWindMode()
      }
    },
    toggleRotation(e) {
      this.rotateSwitch = e;
      if (this.rotateSwitch === true) {
        this.powerSwitch = true
        if (!this.state) this.state = true;
      }
      if (this.rotateSwitch === false) {
        this.turnOffWindMode()
      }
    },
    turnOffWindMode() {
      const preState = this.states.find(state => state.selected === true)
      if (preState?.selected) preState.selected = false;
    }
  },
}
</script>

<style scoped>
.fan-table-bg {
  background-color: #F5F5F5;
}
</style>
