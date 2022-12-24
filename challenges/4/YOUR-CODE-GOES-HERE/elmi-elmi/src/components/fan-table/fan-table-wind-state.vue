<template>
  <v-container class="pt-0">
    <!--    title-->
    <h2 class="text-subtitle-1 font-weight-medium ">
      {{
        $vuetify.locale.t('$vuetify.wind_status.title')
      }}
    </h2>
    <!--    modes: [simple oceanic tropical silvan]-->
    <div class="d-flex flex-row mt-2">
      <v-card
        v-for="state in states"
        class="d-flex rounded-lg flex-column align-center py-2 px-4 fan-table-states  "
        :class="{'active-mode': state.selected}"
        flat
        @click="$emit('windMode',state.mode)"
      >
        <v-icon size="small">
          {{ state.icon }}
        </v-icon>
        <p class="text-subtitle-2 mt-2">
          {{ state.text }}
        </p>
      </v-card>
    </div>
  </v-container>
</template>

<script>
export default {
  name: "FanTableWindState",
  props: {
    states: {
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
