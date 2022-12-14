<template>
  <div id="container" class="d-flex flex-column justify-content-center align-items-center">
    <div class="fan" :class="oscillationClassObject">
      <img
      src="@/assets/blades.svg"
      alt="blades"
      class="fan-border"
      :class="rotationClassObject"
      :style="{
        animationDuration: `${getRotationSpeed}s`
      }"
      />
    </div>
    <div class="fan-center" :class="oscillationClassObject"></div>
    <div class="fan-border__inner-1" :class="oscillationClassObject"></div>
    <div class="fan-border__inner-2" :class="oscillationClassObject"></div>
    <div class="fan-border__inner-3" :class="oscillationClassObject"></div>
    <div class="fan-stand"></div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { mapGetters } from "vuex";

interface Rotation {
  [key: string]: boolean;
}

interface Oscillation {
  'fan-oscillation': boolean;
}

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
    ...mapGetters(['getPower', 'getOscillation', 'getSpeed', 'getRotationMode', 'getRotationAnimation']),

    rotationClassObject(): object {
      let rotation: Rotation = {
        'fan-rotation': this.getPower
      }
      rotation[`fan-rotation__` + this.getRotationMode] = this.getPower;
      return rotation
    },

    oscillationClassObject() : object {
      let oscillation: Oscillation = {
        'fan-oscillation': this.getOscillation && this.getPower
      }
      return oscillation
    },

    getRotationSpeed(): number {
      let oldMax: number = 100;
      let oldMin: number = 10;
      let newMax: number = 0.4;
      let newMin: number = 3;
      let value: number = this.getSpeed;
      return ( (value - oldMin) / (oldMax - oldMin) ) * (newMax - newMin) + newMin
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
  background: radial-gradient(circle, rgba(255,255,255,1) 10%, rgba(255,255,255,0.25) 100%);
  border-radius: 50%;
  box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px;

  &-border {
    border: 1.5px solid white;
    border-radius: 50%;

    &__inner-1 {
      width: 77.5px;
      height: 77.5px;
      background-color: transparent;
      border: 1.5px solid white;
      border-radius: 50%;
      position: absolute;
      margin-bottom: 12px;
    }

    &__inner-2 {
      width: 105px;
      height: 105px;
      background-color: transparent;
      border: 1.5px solid white;
      border-radius: 50%;
      position: absolute;
      margin-bottom: 12px;
    }

    &__inner-3 {
      width: 132.5px;
      height: 132.5px;
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
    background: linear-gradient(180deg, rgba(48,133,159,0.9) 0%, rgba(255,255,255,1) 100%);
    position: absolute;
    bottom: 0;
  }

  &-oscillation {
    animation: oscillation 5s infinite linear;
  }

  &-rotation {

    animation-name: rotation;
    animation-duration: 5s;
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