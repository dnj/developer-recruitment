<template>
  <div id="container">
    <img
      src="@/assets/blades.svg"
      alt="blades"
      :class="getRotation"
    />
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
export default defineComponent({
  name: "Fan",
  data() {
    return {
      fanRotation: {
        status: true,
        type: 'simple'
      }
    }
  },
  computed: {
    getRotation(): object {
      interface Rotation {
        [key: string]: boolean;
      }
      let rotation: Rotation = {
        'fan-rotation': this.fanRotation.status
      }
      rotation[`fan-rotation__` + this.fanRotation.type] = this.fanRotation.status;
      return rotation
    }
  }
});
</script>

<style scoped lang="scss">
#container {
  width: 100%;
  height: 33vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-color: var(--primary-color);
}

.fan-rotation {
  animation-name: rotation;
  animation-duration: 2s;
  animation-iteration-count: infinite;

  &__simple {
    animation-timing-function: linear;
  }

  &__oceanic {
    animation-timing-function: ease-in-out;
  }

  &__tropical {
    animation-timing-function: ease;
  }

  &__woodsy {
    animation-timing-function: cubic-bezier(0.5, 0.36, 0.55, 0.84);
  }
}

@keyframes rotation {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(359deg);
  }
}
</style>