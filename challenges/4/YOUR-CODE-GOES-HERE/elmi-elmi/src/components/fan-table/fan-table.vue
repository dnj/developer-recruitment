<template>
<section class="bg-grey-lighten-5">
<!--  <h1>-->
<!--    {{ $vuetify.locale.t('$vuetify.aaa') }}-->
<!--  </h1>-->
  <FanTableOscillating/>
  <FanTableActions/>
  <FanTableRotateSpeed/>
  <FanTableWindState/>
</section>
</template>

<script>
import FanTableOscillating from "@/components/fan-table/fan-table-oscillating";
import FanTableActions from "@/components/fan-table/fan-table-actions";
import FanTableRotateSpeed from "@/components/fan-table/fan-table-rotate-speed";
import FanTableWindState from "@/components/fan-table/fan-table-wind-state";

export default {
  name: "SectionFanTable",
  components: {FanTableWindState, FanTableRotateSpeed, FanTableActions, FanTableOscillating}
}
</script>

<style scoped>

</style>
