<template>
  <div class="flex items-start">
    <div class="flex items-center h-5">
      <input
        :id="checkboxId"
        type="checkbox"
        :class="['h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded', className]"
        v-bind="$attrs"
        :checked="modelValue"
        @change="$emit('update:modelValue', $event.target.checked)"
      />
    </div>
    <div v-if="label || $slots.label" class="ml-3 text-sm">
      <label :for="checkboxId" class="text-gray-700 dark:text-gray-300">
        <slot name="label">{{ label }}</slot>
      </label>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Checkbox',
  inheritAttrs: false,
  props: {
    modelValue: {
      type: Boolean,
      default: false
    },
    label: String,
    className: {
      type: String,
      default: ''
    },
    id: String
  },
  emits: ['update:modelValue'],
  computed: {
    checkboxId() {
      return this.id || `checkbox-${Math.random().toString(36).substr(2, 9)}`;
    }
  }
};
</script>
