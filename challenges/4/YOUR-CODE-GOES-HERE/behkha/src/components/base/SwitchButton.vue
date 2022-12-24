<template>
  <div>
    <label class="switch">
      <input type="checkbox" @click="toggleCheckbox" />
      <div class="slider round"></div>
    </label>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  name: "SwitchButton",
  data() {
    return {
      checkbox: false
    }
  },
  methods: {
    toggleCheckbox() {
      this.checkbox = !this.checkbox
      this.$emit('toggle', this.checkbox)
    }
  }
});
</script>

<style lang="scss" scoped>
.switch {
	position: relative;
	display: inline-block;
  padding: 0;
  margin: 0;
	width: 60px;
	height: 34px;
	input {
		display: none;
	}
}
.slider {
	position: absolute;
	cursor: pointer;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: #ccc;
	-webkit-transition: 0.4s;
	transition: 0.4s;
	&:before {
		position: absolute;
		content: "";
		height: 26px;
		width: 26px;
		left: 4px;
		bottom: 4px;
		background-color: white;
		-webkit-transition: 0.4s;
		transition: 0.4s;
	}
}
input {
	&:checked {
		+ {
			.slider {
				background-color: var(--primary-color);
				&:before {
					-webkit-transform: translateX(26px);
					-ms-transform: translateX(26px);
					transform: translateX(26px);
				}
			}
		}
	}
	&:focus {
		+ {
			.slider {
				box-shadow: 0 0 1px var(--primary-color);
			}
		}
	}
}
.slider.round {
	border-radius: 34px;
	&:before {
		border-radius: 50%;
	}
}
</style>