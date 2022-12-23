<template>
  <!--  change name to panel-->
  <v-card class="mt-4 pa-4 bg-white rounded-lg" width="45%" flat>
    <v-row justify="start" align="center">
      <v-btn
        flat
        size="x-small"
        class="ma-2 text-white button-box-icon"
        color="#30859F"
        :icon="iconName"
      ></v-btn>
      <h3 class="text-subtitle-1 ">
        {{ title }}
      </h3>
    </v-row>
    <v-row justify="space-between" align="center">
      <p class="text-subtitle-1 font-weight-bold text-grey-darken-2 ma-2">{{ switchName }}</p>
      <div>
        <v-switch
          :model-value="modelValue"
          @update:modelValue="$emit('update:modelValue', $event)"
          class="ma-2"
          color="#30849E"
          hide-details
        ></v-switch>
      </div>

    </v-row>

  </v-card>
</template>

<script>
export default {
  name: "BaseButtonBox",
  props: {
    title: {
      type: String,
      default: 'نام'
    },
    switchName: {
      type: String,
      default: 'غیرفعال'
    },
    modelValue: {
      type: Boolean,
      default: false
    },
    iconName: {
      type: String,
    }
  },
  data() {
    return {
      active: true
    }
  }
}
</script>

<style lang="scss" scoped>
.button-box-icon {
  cursor: default;

}
</style>
