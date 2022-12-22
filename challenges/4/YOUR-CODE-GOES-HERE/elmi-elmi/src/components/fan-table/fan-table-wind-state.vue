<template>
<v-container>
  حالت باد
  <BaseList/>
  <v-btn-toggle v-model="toggle_exclusive" color="cyan-darken-2">
    <v-btn rounded="lg" class=" ml-1">
      <div class="d-flex flex-column align-center">
        <v-icon>mdi-tailwind</v-icon>
        <p class="text-body-1">ساده</p>
      </div>

    </v-btn>
    <v-btn rounded="lg" class=" ml-1">
      <div class="d-flex flex-column align-center">
        <v-icon>mdi-waves</v-icon>
        <p class="text-body-1">اقیانوسی</p>
      </div>

    </v-btn>
    <v-btn rounded="lg" class=" ml-1">
      <div class="d-flex flex-column align-center">
        <v-icon>mdi-white-balance-sunny</v-icon>
        <p class="text-body-1">استوایی</p>
      </div>

    </v-btn>
    <v-btn rounded="lg" class="">
      <div class="d-flex flex-column align-center">
        <v-icon>mdi-pine-tree</v-icon>
        <p class="text-body-1">جنگلی</p>
      </div>

    </v-btn>

  </v-btn-toggle>
</v-container>
</template>

<script>
import BaseList from "@/components/ui/base/base-list";
export default {
  name: "FanTableWindState",
  components: {BaseList},
  data(){
    return { toggle_exclusive: 0}
  }
}
</script>

<style scoped>

</style>
