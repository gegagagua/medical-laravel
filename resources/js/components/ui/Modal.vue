<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div 
      class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" 
      style="opacity: 0.5;"
      @click="onClose"
    />

    <!-- Modal -->
    <div class="flex min-h-full items-center justify-center p-4" @click.stop>
      <div
        :class="['relative w-full', sizeClasses[size], 'bg-white dark:bg-gray-800 rounded-2xl shadow-xl transform transition-all']"
        @click.stop
      >
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
            {{ title }}
          </h3>
          <button
            @click="onClose"
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Content -->
        <div class="p-6">
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Modal',
  props: {
    isOpen: {
      type: Boolean,
      required: true
    },
    title: {
      type: String,
      required: true
    },
    size: {
      type: String,
      default: 'md',
      validator: (value) => ['sm', 'md', 'lg', 'xl'].includes(value)
    }
  },
  computed: {
    sizeClasses() {
      return {
        sm: 'max-w-md',
        md: 'max-w-lg',
        lg: 'max-w-2xl',
        xl: 'max-w-4xl'
      };
    }
  },
  watch: {
    isOpen(newVal) {
      if (newVal) {
        document.body.style.overflow = 'hidden';
        // Add ESC key listener when modal opens
        document.addEventListener('keydown', this.handleEscape);
      } else {
        document.body.style.overflow = 'unset';
        // Remove ESC key listener when modal closes
        document.removeEventListener('keydown', this.handleEscape);
      }
    }
  },
  methods: {
    onClose() {
      this.$emit('close');
    },
    handleEscape(event) {
      if (event.key === 'Escape' && this.isOpen) {
        this.onClose();
      }
    }
  },
  beforeUnmount() {
    document.body.style.overflow = 'unset';
    document.removeEventListener('keydown', this.handleEscape);
  }
};
</script>

