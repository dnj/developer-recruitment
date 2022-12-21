<template>
  <div id="container" class="d-flex flex-column justify-content-center align-items-center">
    <div class="fan fan-oscillation">
      <img
      src="@/assets/blades.svg"
      alt="blades"
      class="fan-border"
      :class="getRotation"
      />
    </div>
    <div class="fan-center fan-oscillation"></div>
    <div class="fan-stand"></div>
    <div class="fan-border__inner-1 fan-oscillation"></div>
    <div class="fan-border__inner-2 fan-oscillation"></div>
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

  margin-bottom: 12px;
  background: radial-gradient(circle, rgba(255,255,255,1) 1%, rgba(255,255,255,0) 100%);
  border-radius: 50%;
  // box-shadow: 0px 0px 50px 0px rgba(0,0,0,0.5);

  &-border {
    border: 1.5px solid white;
    border-radius: 50%;

    &__inner-1 {
      width: 90px;
      height: 90px;
      background-color: transparent;
      border: 1.5px solid white;
      border-radius: 50%;
      position: absolute;
      margin-bottom: 12px;
    }

    &__inner-2 {
      width: 130px;
      height: 130px;
      background-color: transparent;
      border: 1.5px solid white;
      border-radius: 50%;
      position: absolute;
      margin-bottom: 12px;
    }
  }

  &-center {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: white;
    position: absolute;
    margin-bottom: 12px;
    box-shadow: 0px 0px 100px 0px rgba(0,0,0,0.5);
  }

  &-stand {
    width: 12px;
    height: 25%;
    background: linear-gradient(90deg, rgba(255,255,255,1) 0%, rgba(204,204,204,1) 100%);
    position: absolute;
    bottom: 0;
  }

  &-oscillation {
    animation: oscillation 5s infinite linear;
  }

  &-rotation {
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
}

@keyframes rotation {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(359deg);
  }
}

@keyframes oscillation {
  0% {
    transform: rotateY(0deg);
  }

  25% {
    transform: rotateY(45deg);
  }

  50% {
    transform: rotateY(0deg);
  }

  75% {
    transform: rotateY(-45deg);
  }

  100% {
    transform: rotateY(0deg);
  }

}
</style>