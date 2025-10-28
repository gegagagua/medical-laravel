<template>
  <div class="w-full">
    <label v-if="label" :for="inputId" class="block text-sm font-medium text-gray-700 mb-2" style="color: #b1b4bb;">
      {{ label }}
    </label>
    <div class="relative">
      <div v-if="icon" class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <slot name="icon">{{ icon }}</slot>
      </div>
      <input
        :id="inputId"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :class="[
          'block w-full py-3 border rounded-lg bg-white text-gray-900 placeholder-gray-400 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition',
          icon ? 'pl-10' : 'pl-3',
          rightElement ? 'pr-10' : 'pr-3',
          error ? 'border-red-500 focus:ring-red-500' : ''
        ]"
        v-bind="$attrs"
      />
      <div v-if="rightElement" class="absolute inset-y-0 right-0 pr-3 flex items-center">
        <slot name="rightElement">{{ rightElement }}</slot>
      </div>
    </div>
    <p v-if="error" class="mt-1 text-sm text-red-600">
      {{ error }}
    </p>
  </div>
</template>

<script>
export default {
  name: 'Input',
  inheritAttrs: false,
  props: {
    modelValue: {
      type: String,
      default: ''
    },
    label: String,
    error: String,
    icon: null,
    rightElement: null
  },
  emits: ['update:modelValue'],
  computed: {
    inputId() {
      return this.$attrs.id || `input-${Math.random().toString(36).substr(2, 9)}`;
    }
  }
};
</script>
