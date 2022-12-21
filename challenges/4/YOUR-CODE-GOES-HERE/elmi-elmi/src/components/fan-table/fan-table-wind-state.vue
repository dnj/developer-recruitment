<template>
<v-container>
  حالت باد
  <BaseList/>
  <v-btn-toggle v-model="toggle_exclusive" color="cyan-darken-2">
    <v-btn rounded="lg" class=" ml-4">
      <div class="d-flex flex-column align-center">
        <v-icon>mdi-tailwind</v-icon>
        <div>ساده</div>
      </div>

    </v-btn>
    <v-btn rounded="lg" class=" ml-4">
      <div class="d-flex flex-column align-center">
        <v-icon>mdi-waves</v-icon>
        <div>اقیانوسی</div>
      </div>

    </v-btn>
    <v-btn rounded="lg" class=" ml-4">
      <div class="d-flex flex-column align-center">
        <v-icon>mdi-white-balance-sunny</v-icon>
        <div>استوایی</div>
      </div>

    </v-btn>
    <v-btn rounded="lg" class=" ml-4">
      <div class="d-flex flex-column align-center">
        <v-icon>mdi-pine-tree</v-icon>
        <div>جنگلی</div>
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
