<template>
  <button
    :class="[
      'inline-flex justify-center items-center px-4 py-3 border rounded-lg font-medium transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed',
      variants[variant],
      fullWidth ? 'w-full' : 'w-auto'
    ]"
    v-bind="$attrs"
  >
    <slot />
  </button>
</template>

<script>
export default {
  name: 'Button',
  props: {
    variant: {
      type: String,
      default: 'primary',
      validator: (value) => ['primary', 'secondary', 'outline', 'social'].includes(value)
    },
    fullWidth: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    variants() {
      return {
        primary: 'border-transparent shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
        secondary: 'border-gray-300 shadow-sm bg-white text-gray-700 hover:bg-gray-50 focus:ring-gray-500',
        outline: 'border-2 border-gray-300 bg-white text-gray-900 hover:bg-gray-50 focus:ring-gray-500',
        social: 'border-gray-300 shadow-sm bg-white text-gray-700 hover:bg-gray-50 focus:ring-gray-500 py-2.5',
      };
    }
  }
};
</script>

