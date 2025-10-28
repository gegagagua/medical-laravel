<template>
  <div class="relative">
    <div class="absolute inset-0 flex items-center">
      <div class="w-full border-t border-gray-300"></div>
    </div>
    <div v-if="text" class="relative flex justify-center text-sm">
      <span class="px-2 bg-white text-gray-500">
        {{ text }}
      </span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Divider',
  props: {
    text: String
  }
};
</script>

