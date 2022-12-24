<template>
  <v-container>
    <v-row justify="space-around">
      <!--      power off, on switch-->
      <BaseButtonBox :switch-name="switchNameSys"
                     :title="sysTitle"
                     :modelValue="powerSwitch"
                     @update:modelValue="$emit('togglePower', $event)"
                     icon-name="mdi-power"
      />
      <!--      rotate head switch-->
      <BaseButtonBox :switch-name="switchNameRotate"
                     :title="rotateTitle"
                     :modelValue="rotateSwitch"
                     @update:modelValue="$emit('toggleRotation', $event)"
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
    },
    sysTitle() {
      return this.$vuetify.locale.t('$vuetify.panel.system.title')
    },
    rotateTitle() {
      return this.$vuetify.locale.t('$vuetify.panel.rotate.title')
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
