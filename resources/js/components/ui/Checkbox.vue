<template>
  <div class="flex items-start">
    <div class="flex items-center h-5">
      <input
        :id="checkboxId"
        type="checkbox"
        :class="['h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded', className]"
        v-bind="$attrs"
        :checked="checked"
        @change="$emit('update:checked', $event.target.checked)"
      />
    </div>
    <div v-if="label" class="ml-3 text-sm">
      <label :for="checkboxId" class="text-gray-700">
        {{ label }}
      </label>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Checkbox',
  inheritAttrs: false,
  props: {
    label: String,
    className: {
      type: String,
      default: ''
    },
    id: String,
    checked: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    checkboxId() {
      return this.id || `checkbox-${Math.random().toString(36).substr(2, 9)}`;
    }
  },
  emits: ['update:checked']
};
</script>

