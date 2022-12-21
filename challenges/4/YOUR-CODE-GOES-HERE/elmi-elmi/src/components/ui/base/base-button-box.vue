<template>
<v-card class="pa-4" width="140" height="140">
  <v-row justify="start" align="center">
    <v-btn
      size="x-small"
      class="ma-2"
      color="cyan-darken-2"
      icon="mdi-code-tags"
    ></v-btn>
    چرخش
  </v-row>
  <v-row justify="space-between" align="center">
    <p class="ma-2">روشن</p>
    <div>
      <v-switch
        v-model="active"
        :disabled="!active"
        class="ma-2"
        color="cyan-darken-2"
        hide-details
      ></v-switch>
    </div>

  </v-row>

</v-card>
</template>

<script>
export default {
  name: "BaseButtonBox",
  data(){
    return {
      active:true
    }
  }
}
</script>

<style scoped>

</style>
