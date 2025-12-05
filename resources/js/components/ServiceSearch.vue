<template>
  <div class="relative">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
      სერვისი *
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
      <div
        v-if="isDropdownOpen && filteredServices.length > 0"
        class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto"
      >
        <div
          v-for="service in filteredServices"
          :key="service.id"
          @mousedown.prevent="selectService(service)"
          class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-700 last:border-b-0"
        >
          <div class="flex justify-between items-center">
            <span class="text-sm font-medium text-gray-900 dark:text-white">
              {{ service.name }}
            </span>
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
      type: String,
      default: ''
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
      isDropdownOpen: false
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
    modelValue(newValue) {
      if (newValue && !this.searchQuery) {
        this.searchQuery = newValue;
      } else if (!newValue) {
        this.searchQuery = '';
      }
    },
    department() {
      this.searchQuery = '';
      this.isDropdownOpen = false;
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
        this.$emit('update:modelValue', this.searchQuery);
      }
    },
    handleBlur() {
      // Delay to allow click event on dropdown items
      setTimeout(() => {
        this.isDropdownOpen = false;
      }, 200);
    },
    selectService(service) {
      this.searchQuery = service.name;
      this.isDropdownOpen = false;
      this.$emit('update:modelValue', service.name);
      this.$emit('select', service);
    }
  }
};
</script>

