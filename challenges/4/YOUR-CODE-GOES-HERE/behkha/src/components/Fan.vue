<template>
  <div id="container" class="d-flex flex-column justify-content-center align-items-center">
    <div class="fan">
      <img
      src="@/assets/blades.svg"
      alt="blades"
      class="fan-border"
      :class="getRotation"
      />
    </div>
    <div class="fan-center"></div>
    <div class="fan-stand"></div>
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
  position: relative;
  width: 100%;
  height: max-content;
  padding: 60px 0;
  background-color: var(--primary-color);
}

.fan {

  display: flex;
  flex-direction: column;
  z-index: 3 !important;
  margin-bottom: 12px;

  &-border {
    border: 1.5px solid white;
    border-radius: 50%;
  }

  &-center {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: white;
    position: absolute;
  }

  &-stand {
    width: 12px;
    height: 25%;
    background-color: white;
    position: absolute;
    bottom: 0;
    z-index: 2 !important;
  }

  &-rotation {
    animation-name: rotation;
    animation-duration: 1s;
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