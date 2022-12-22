<template>
  <v-container>
    <v-row justify="space-around">
      <BaseButtonBox :switch-name="switchNameSys" title="دستگاه"
                     :modelValue="powerSwitch"
                     @update:modelValue="$emit('togglePower', $event)"
                     icon-name="mdi-power"
      />
      <BaseButtonBox :switch-name="switchNameRotate" title="چرخش"
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
  data() {
    return {
      rotate: false,
      power: true
    }
  },
  computed: {
    switchNameSys() {
      let status;
      this.powerSwitch
        ? status = this.$vuetify.locale.t('$vuetify.panel.system.status.on')
        : status = this.$vuetify.locale.t('$vuetify.panel.system.status.off')
      return status
    },
    switchNameRotate() {
      let status;
      this.rotateSwitch
        ? status = this.$vuetify.locale.t('$vuetify.panel.rotate.status.on')
        : status = this.$vuetify.locale.t('$vuetify.panel.rotate.status.off')
      return status
    }
  },
  props: {
    powerSwitch: {
      type: Boolean,
      default: false
    },
    rotateSwitch: {
      type: Boolean,
      default: false
    }
  },


}
</script>

<style scoped>

</style>
