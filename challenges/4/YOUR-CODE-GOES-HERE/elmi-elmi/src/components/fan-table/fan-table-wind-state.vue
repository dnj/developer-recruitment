<template>
<v-container>
  حالت باد
  <BaseList/>

      <div  class="d-flex flex-row  ">
        <v-card
          flat
          v-for="state in states"
          class="d-flex rounded-lg flex-column align-center py-2 px-4 fan-table-states  "
          :class="{'active-mode': state.selected}">
          <v-icon>{{state.icon}}</v-icon>
          <p class="text-body-1">{{ state.text }}</p>
        </v-card>
      </div>

</v-container>
</template>

<script>
import BaseList from "@/components/ui/base/base-list";
export default {
  name: "FanTableWindState",
  components: {BaseList},
  data(){
    return {
      toggle_exclusive: 0,
      states: [
        {
          icon: 'mdi-tailwind',
          text: 'ساده',
          selected:true
        },
        {
          icon: 'mdi-waves',
          text: 'اقیانوسی',
          selected:false
        },
        {
          icon: 'mdi-white-balance-sunny',
          text: 'استوایی'
        },
        {
          icon: 'mdi-pine-tree',
          text: 'جنگلی',
          selected:false
        },
      ]
    }
  }
}
</script>

<style scoped>
.fan-table-states {
  margin: 4px;
  width: 25%;
}
.active-mode {
  background-color: #30849E;
  color: white;
}
</style>
