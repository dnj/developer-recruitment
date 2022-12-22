import { createStore } from "vuex";
import type { RootState } from "./types";

export default createStore<RootState>({
  state: {
    power: false,
    oscillation: false,
    speed: 10,
    rotation_mode: 'simple'
  },
  mutations: {
    updatePower(state, payload: boolean) {
      state.power = payload
    },

    updateOscillation(state, payload: boolean) {
      state.oscillation = payload
    },

    updateSpeed(state, payload: number) {
      state.speed = payload;
    },

    updateRotationMode(state, payload: string) {
      state.rotation_mode = payload;
    }
  },
  getters: {
    getPower(state) : boolean {
      return state.power;
    },

    getOscillation(state) : boolean {
      return state.oscillation;
    },

    getSpeed(state) : number {
      return state.speed;
    },

    getRotationMode(state) : string {
      return state.rotation_mode;
    }
  }
})