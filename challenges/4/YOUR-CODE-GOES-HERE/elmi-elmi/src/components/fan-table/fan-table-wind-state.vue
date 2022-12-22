<template>
  <v-container>
<!--    title-->
    <h2 class="text-h6">
      {{
        $vuetify.locale.t('$vuetify.wind_status.title')
      }}
    </h2>
<!--    modes: [simple oceanic tropical silvan]-->
    <div class="d-flex flex-row">
      <v-card
        v-for="state in states"
        class="d-flex rounded-lg flex-column align-center py-2 px-4 fan-table-states  "
        :class="{'active-mode': state.selected}"
        flat
        @click="$emit('windMode',state.mode)"
      >
        <v-icon>
          {{ state.icon }}
        </v-icon>
        <p class="text-body-1">
          {{ state.text }}
        </p>
      </v-card>
    </div>

  </v-container>
</template>

<script>
import BaseList from "@/components/ui/base/base-list";

export default {
  name: "FanTableWindState",
  components: {BaseList},
  data() {
    return {
      toggle_exclusive: 0,
    }
  },
  props:{
    states:{
      type: Array,
      default: []
    }
  }
}
</script>

<style scoped>
.fan-table-states {
  margin: 4px;
  width: 25%;
  cursor: pointer;
}

.active-mode {
  background-color: #30849E;
  color: white;
}
</style>
