<template>
  <v-container>
    <v-row justify="space-around">
      <BaseButtonBox/>
      <BaseButtonBox/>
    </v-row>
  </v-container>

</template>

<script>
import BaseButtonBox from "@/components/ui/base/base-button-box";
export default {
  name: "FanTableActions",
  components: {BaseButtonBox}

}
</script>

<style scoped>

</style>
