<template>
  <div class="relative">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
      სერვისები *
    </label>
    <div class="relative">
      <input
        v-model="searchQuery"
        type="text"
        :placeholder="placeholder"
        :disabled="disabled"
        :readonly="readonly"
        @focus="handleFocus"
        @input="handleInput"
        @blur="handleBlur"
        class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
        :class="{
          'bg-gray-100 dark:bg-gray-800 cursor-not-allowed': disabled || readonly
        }"
      />
      <!-- Selected Services Display -->
      <div v-if="selectedServices.length > 0" class="mt-2 flex flex-wrap gap-2">
        <span
          v-for="(service, index) in selectedServices"
          :key="service.id"
          class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm"
        >
          {{ service.name }}
          <button
            type="button"
            @click="removeService(index)"
            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </span>
      </div>
      <div
        v-if="isDropdownOpen && filteredServices.length > 0"
        class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto"
      >
        <div
          v-for="service in filteredServices"
          :key="service.id"
          @mousedown.prevent="selectService(service)"
          :class="[
            'px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-700 last:border-b-0',
            isServiceSelected(service.id) ? 'bg-blue-50 dark:bg-blue-900/30' : ''
          ]"
        >
          <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
              <input
                type="checkbox"
                :checked="isServiceSelected(service.id)"
                @change="toggleService(service)"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
              />
              <span class="text-sm font-medium text-gray-900 dark:text-white">
                {{ service.name }}
              </span>
            </div>
            <span class="text-sm text-gray-500 dark:text-gray-400">
              {{ service.price }} ₾
            </span>
          </div>
        </div>
      </div>
      <div
        v-if="isDropdownOpen && filteredServices.length === 0 && searchQuery"
        class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg p-4"
      >
        <p class="text-sm text-gray-500 dark:text-gray-400">სერვისი არ მოიძებნა</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ServiceSearch',
  props: {
    modelValue: {
      type: Array,
      default: () => []
    },
    services: {
      type: Array,
      default: () => []
    },
    department: {
      type: String,
      default: ''
    },
    placeholder: {
      type: String,
      default: 'ძიება სერვისებში...'
    },
    disabled: {
      type: Boolean,
      default: false
    },
    readonly: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:modelValue', 'select'],
  data() {
    return {
      searchQuery: '',
      isDropdownOpen: false,
      selectedServices: []
    };
  },
  computed: {
    filteredServices() {
      if (!this.department) {
        return [];
      }
      let services = this.services.filter(service => 
        service.department === this.department
      );
      
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        services = services.filter(service => 
          service.name.toLowerCase().includes(query) ||
          service.price.toString().includes(query)
        );
      }
      
      return services;
    }
  },
  watch: {
    modelValue: {
      handler(newValue) {
        if (Array.isArray(newValue)) {
          this.selectedServices = [...newValue];
        } else {
          this.selectedServices = [];
        }
      },
      immediate: true,
      deep: true
    },
    department() {
      this.searchQuery = '';
      this.isDropdownOpen = false;
      // Clear selected services when department changes
      this.selectedServices = [];
      this.$emit('update:modelValue', []);
    }
  },
  methods: {
    handleFocus() {
      if (!this.disabled && !this.readonly) {
        this.isDropdownOpen = true;
      }
    },
    handleInput() {
      if (!this.disabled && !this.readonly) {
        this.isDropdownOpen = true;
      }
    },
    handleBlur() {
      // Delay to allow click event on dropdown items
      setTimeout(() => {
        this.isDropdownOpen = false;
      }, 200);
    },
    isServiceSelected(serviceId) {
      return this.selectedServices.some(s => s.id === serviceId);
    },
    toggleService(service) {
      const index = this.selectedServices.findIndex(s => s.id === service.id);
      if (index > -1) {
        this.removeService(index);
      } else {
        this.selectService(service);
      }
    },
    selectService(service) {
      if (!this.isServiceSelected(service.id)) {
        this.selectedServices.push(service);
        this.$emit('update:modelValue', [...this.selectedServices]);
        this.$emit('select', service);
      }
      this.searchQuery = '';
    },
    removeService(index) {
      this.selectedServices.splice(index, 1);
      this.$emit('update:modelValue', [...this.selectedServices]);
    }
  }
};
</script>
