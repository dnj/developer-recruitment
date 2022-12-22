<template>
  <v-container>
    <v-row justify="space-around">
      <BaseButtonBox switch-name="روشن" title="دستگاه"
                     :modelValue="powerSwitch"
                     @update:modelValue="$emit('togglePower', $event)"
                     icon-name="mdi-power"
      />
      <BaseButtonBox switch-name="غیرفعال" title="چرخش"
                     :modelValue="rotateSwitch"
                     @update:modelValue="$emit('toggleRotate', $event)"
                     icon-name="mdi-code-tags"
      />
    </v-row>
  </v-container>

</template>

<script>
import BaseButtonBox from "@/components/ui/base/base-button-box";
export default {
  name: "FanTableActions",
  components: {BaseButtonBox},
  data(){
    return {
      rotate:false,
      power: true
    }
  },
  props:{
    powerSwitch:{
      type: Boolean,
      default: false
    },
    rotateSwitch:{
      type: Boolean,
      default: false
    }
  },


}
</script>

<style scoped>

</style>
