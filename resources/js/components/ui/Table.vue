<template>
  <div class="w-full space-y-4">
    <!-- Search and Actions Bar -->
    <div v-if="searchable" class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
      <div class="w-full sm:w-96">
        <Input
          :placeholder="searchPlaceholder"
          :model-value="searchQuery"
          @update:model-value="handleSearchChange"
        >
          <template #icon>
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </template>
        </Input>
      </div>
      <Button v-if="hasActiveFilters" variant="secondary" @click="clearFilters" class="whitespace-nowrap">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        ფილტრების გასუფთავება
      </Button>
    </div>

    <!-- Results Info -->
    <div class="text-sm text-gray-600 dark:text-gray-400">
      ნაჩვენებია {{ startIndex + 1 }}-{{ Math.min(startIndex + pageSize, sortedData.length) }} / {{ sortedData.length }} ჩანაწერი
      <span v-if="data.length !== sortedData.length"> (გაფილტრული {{ data.length }}-დან)</span>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
      <table class="w-full text-left">
        <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
          <tr>
            <th
              v-for="column in columns"
              :key="column.key"
              class="px-4 py-3 text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider"
              :style="{ width: column.width }"
            >
              <button
                v-if="column.sortable"
                @click="handleSort(column.key)"
                class="flex items-center gap-1 hover:text-gray-900 dark:hover:text-white transition-colors"
              >
                {{ column.label }}
                <svg v-if="sortColumn === column.key" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path v-if="sortDirection === 'asc'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                  <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <span v-else>{{ column.label }}</span>
            </th>
          </tr>
          <!-- Column Filters Row -->
          <tr v-if="hasFilterableColumns" class="bg-gray-50 dark:bg-gray-800">
            <th v-for="column in columns" :key="`filter-${column.key}`" class="px-4 py-2">
              <input
                v-if="column.filterable"
                type="text"
                placeholder="ფილტრი..."
                :value="columnFilters[column.key] || ''"
                @input="handleColumnFilter(column.key, $event.target.value)"
                class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500"
              />
            </th>
          </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
          <!-- Loading State -->
          <tr v-if="loading">
            <td :colspan="columns.length" class="px-6 py-12 text-center">
              <div class="flex items-center justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-3 text-gray-600 dark:text-gray-400">იტვირთება...</span>
              </div>
            </td>
          </tr>
          <!-- Empty State -->
          <tr v-else-if="paginatedData.length === 0">
            <td :colspan="columns.length" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
              {{ emptyMessage }}
            </td>
          </tr>
          <!-- Data Rows -->
          <tr
            v-else
            v-for="(item, rowIndex) in paginatedData"
            :key="rowIndex"
            @click="handleRowClick(item)"
            :class="[
              'transition-colors',
              onRowClick ? 'cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800' : ''
            ]"
          >
            <td
              v-for="column in columns"
              :key="column.key"
              class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap"
            >
              <component
                :is="column.render ? 'span' : 'span'"
                v-if="!column.render"
              >
                {{ item[column.key]?.toString() || '-' }}
              </component>
              <span v-else v-html="getRenderedContent(column, item[column.key], item)"></span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="totalPages > 1" class="flex flex-col sm:flex-row items-center justify-between gap-4">
      <div class="text-sm text-gray-600 dark:text-gray-400">
        გვერდი {{ currentPage }} / {{ totalPages }}
      </div>
      <div class="flex gap-2">
        <Button variant="secondary" @click="currentPage = 1" :disabled="currentPage === 1">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
          </svg>
        </Button>
        <Button variant="secondary" @click="prevPage" :disabled="currentPage === 1">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </Button>
        
        <!-- Page Numbers -->
        <div class="hidden sm:flex gap-2">
          <Button
            v-for="pageNum in visiblePages"
            :key="pageNum"
            :variant="currentPage === pageNum ? 'primary' : 'secondary'"
            @click="currentPage = pageNum"
          >
            {{ pageNum }}
          </Button>
        </div>

        <Button variant="secondary" @click="nextPage" :disabled="currentPage === totalPages">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </Button>
        <Button variant="secondary" @click="currentPage = totalPages" :disabled="currentPage === totalPages">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
          </svg>
        </Button>
      </div>
    </div>
  </div>
</template>

<script>
import Input from './Input.vue';
import Button from './Button.vue';

export default {
  name: 'Table',
  components: {
    Input,
    Button
  },
  props: {
    data: {
      type: Array,
      required: true
    },
    columns: {
      type: Array,
      required: true
    },
    pageSize: {
      type: Number,
      default: 10
    },
    searchable: {
      type: Boolean,
      default: true
    },
    searchPlaceholder: {
      type: String,
      default: 'ძებნა...'
    },
    emptyMessage: {
      type: String,
      default: 'მონაცემები არ მოიძებნა'
    },
    loading: {
      type: Boolean,
      default: false
    },
    onRowClick: {
      type: Function,
      default: null
    }
  },
  data() {
    return {
      searchQuery: '',
      sortColumn: null,
      sortDirection: 'asc',
      currentPage: 1,
      columnFilters: {}
    };
  },
  computed: {
    hasFilterableColumns() {
      return this.columns.some(col => col.filterable);
    },
    filteredData() {
      let result = [...this.data];

      // Global search
      if (this.searchQuery) {
        result = result.filter(item =>
          this.columns.some(column => {
            const value = item[column.key];
            return value?.toString().toLowerCase().includes(this.searchQuery.toLowerCase());
          })
        );
      }

      // Column-specific filters
      Object.entries(this.columnFilters).forEach(([key, filterValue]) => {
        if (filterValue) {
          result = result.filter(item => {
            const value = item[key];
            return value?.toString().toLowerCase().includes(filterValue.toLowerCase());
          });
        }
      });

      return result;
    },
    sortedData() {
      if (!this.sortColumn) return this.filteredData;

      return [...this.filteredData].sort((a, b) => {
        const aValue = a[this.sortColumn];
        const bValue = b[this.sortColumn];

        if (aValue == null) return 1;
        if (bValue == null) return -1;

        let comparison = 0;
        if (typeof aValue === 'number' && typeof bValue === 'number') {
          comparison = aValue - bValue;
        } else {
          comparison = String(aValue).localeCompare(String(bValue));
        }

        return this.sortDirection === 'asc' ? comparison : -comparison;
      });
    },
    totalPages() {
      return Math.ceil(this.sortedData.length / this.pageSize);
    },
    startIndex() {
      return (this.currentPage - 1) * this.pageSize;
    },
    paginatedData() {
      return this.sortedData.slice(this.startIndex, this.startIndex + this.pageSize);
    },
    visiblePages() {
      const pages = [];
      const maxVisible = 5;
      
      if (this.totalPages <= maxVisible) {
        for (let i = 1; i <= this.totalPages; i++) {
          pages.push(i);
        }
      } else if (this.currentPage <= 3) {
        for (let i = 1; i <= maxVisible; i++) {
          pages.push(i);
        }
      } else if (this.currentPage >= this.totalPages - 2) {
        for (let i = this.totalPages - 4; i <= this.totalPages; i++) {
          pages.push(i);
        }
      } else {
        for (let i = this.currentPage - 2; i <= this.currentPage + 2; i++) {
          pages.push(i);
        }
      }
      
      return pages;
    },
    hasActiveFilters() {
      return this.searchQuery || Object.values(this.columnFilters).some(v => v);
    }
  },
  watch: {
    currentPage() {
      // Reset to first page when filters change
      if (this.currentPage > this.totalPages) {
        this.currentPage = 1;
      }
    }
  },
  methods: {
    handleSearchChange(value) {
      this.searchQuery = value;
      this.currentPage = 1;
    },
    handleSort(columnKey) {
      if (this.sortColumn === columnKey) {
        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
      } else {
        this.sortColumn = columnKey;
        this.sortDirection = 'asc';
      }
    },
    handleColumnFilter(columnKey, value) {
      this.columnFilters = {
        ...this.columnFilters,
        [columnKey]: value
      };
      this.currentPage = 1;
    },
    clearFilters() {
      this.searchQuery = '';
      this.columnFilters = {};
      this.currentPage = 1;
    },
    handleRowClick(item) {
      if (this.onRowClick) {
        this.onRowClick(item);
      }
    },
    getRenderedContent(column, value, item) {
      if (column.render && typeof column.render === 'function') {
        const result = column.render(value, item);
        return typeof result === 'string' ? result : '';
      }
      return value?.toString() || '-';
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    }
  }
};
</script>

