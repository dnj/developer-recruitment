<template>
  <v-app>
    <v-main class="mx-auto">
      <v-responsive max-width="320" max-height="745">
        <SectionFanTable/>
      </v-responsive>
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import SectionFanTable from '@/components/fan-table/fan-table.vue'
</script>
