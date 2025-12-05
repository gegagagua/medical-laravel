<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <Navbar />
    <main class="max-w-[1485px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">სულ სერვისები</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ services.length }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">საშუალო ფასი</p>
              <p class="text-3xl font-bold text-green-600 dark:text-green-400">
                ₾{{ averagePrice.toFixed(2) }}
              </p>
            </div>
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">განყოფილებები</p>
              <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ uniqueDepartments }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
        <div class="mb-6 flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
          <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">სერვისების სია</h2>
            <p class="text-gray-600 dark:text-gray-400">ყველა სერვისის სრული ინფორმაცია</p>
          </div>
          <div class="flex gap-2">
            <Button variant="primary" @click="openModal" class="flex-1 md:flex-initial">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              ახალი სერვისი
            </Button>
          </div>
        </div>

        <Table
          :data="services"
          :columns="columns"
          :page-size="10"
          :searchable="true"
          search-placeholder="მოძებნეთ სერვისი (დასახელება, განყოფილება)..."
          empty-message="სერვისები არ მოიძებნა"
          :loading="loading"
          :on-row-click="null"
        />
      </div>

      <!-- Add/Edit Service Modal -->
      <Modal :isOpen="isModalOpen" :title="editingService ? 'სერვისის რედაქტირება' : 'ახალი სერვისის დამატება'" @close="closeModal">
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <Input
            v-model="formData.name"
            label="დასახელება"
            placeholder="მაგ: FT3 - თავისუფალი ტრიიოდთირონინი"
            required
          />

          <Input
            v-model="formData.discounted_name"
            label="ფასდაკლებული დასახელება"
            placeholder="მაგ: FT3 (ფასდაკლებით)"
          />

          <Input
            v-model="formData.price"
            label="ფასი"
            type="number"
            step="0.01"
            min="0"
            placeholder="0.00"
            required
          />

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              განყოფილება
            </label>
            <select
              v-model="formData.department"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
            >
              <option value="">აირჩიეთ განყოფილება</option>
              <option value="ენდოკრინოლოგია">ენდოკრინოლოგია</option>
              <option value="ბავშვთა და მოზრდილთა ენდოკრინოლოგია">ბავშვთა და მოზრდილთა ენდოკრინოლოგია</option>
              <option value="ექიმი">ექიმი</option>
              <option value="რადიოლოგია">რადიოლოგია</option>
              <option value="ოფთალმოლოგია">ოფთალმოლოგია</option>
              <option value="ნევროლოგია">ნევროლოგია</option>
              <option value="კარდიოლოგია">კარდიოლოგია</option>
              <option value="ტრავმატოლოგია">ტრავმატოლოგია</option>
              <option value="ოტორინოლარინგოლოგია">ოტორინოლარინგოლოგია</option>
            </select>
          </div>

          <div v-if="error" class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-sm text-red-800 dark:text-red-300">{{ error }}</p>
          </div>

          <div class="flex gap-3 pt-4">
            <Button type="button" variant="secondary" @click="closeModal" :full-width="true">
              გაუქმება
            </Button>
            <Button type="submit" variant="primary" :full-width="true" :disabled="loading">
              {{ loading ? 'შენახვა...' : (editingService ? 'განახლება' : 'დამატება') }}
            </Button>
          </div>
        </form>
      </Modal>

      <!-- Delete Confirmation Modal -->
      <Modal :isOpen="isDeleteModalOpen" title="სერვისის წაშლა" @close="closeDeleteModal">
        <div class="space-y-4">
          <p class="text-gray-700 dark:text-gray-300">
            დარწმუნებული ხართ, რომ გსურთ სერვისის "<strong>{{ serviceToDelete?.name }}</strong>" წაშლა?
          </p>
          <p class="text-sm text-gray-500 dark:text-gray-400">
            ეს მოქმედება შეუქცევადია.
          </p>
          <div class="flex gap-3 pt-4">
            <Button type="button" variant="secondary" @click="closeDeleteModal" :full-width="true">
              გაუქმება
            </Button>
            <Button type="button" variant="primary" @click="confirmDelete" :full-width="true" :disabled="loading" class="bg-red-600 hover:bg-red-700">
              {{ loading ? 'წაშლა...' : 'წაშლა' }}
            </Button>
          </div>
        </div>
      </Modal>
    </main>
  </div>
</template>

<script>
import Navbar from './Navbar.vue';
import Button from './ui/Button.vue';
import Modal from './ui/Modal.vue';
import Input from './ui/Input.vue';
import Table from './ui/Table.vue';
import { useToastStore } from '../stores/toast';
import axios from 'axios';

export default {
  name: 'Services',
  components: {
    Navbar,
    Button,
    Modal,
    Input,
    Table
  },
  setup() {
    const toastStore = useToastStore();
    return { toastStore };
  },
  data() {
    return {
      services: [],
      loading: false,
      isModalOpen: false,
      isDeleteModalOpen: false,
      editingService: null,
      serviceToDelete: null,
      error: '',
      formData: {
        name: '',
        discounted_name: '',
        price: '',
        department: ''
      },
      columns: [
        {
          key: 'name',
          label: 'დასახელება',
          sortable: true
        },
        {
          key: 'discounted_name',
          label: 'ფასდაკლებული დასახელება',
          sortable: false,
          render: (value) => value || '-'
        },
        {
          key: 'price',
          label: 'ფასი',
          sortable: true,
          render: (value) => `₾${parseFloat(value || 0).toFixed(2)}`
        },
        {
          key: 'department',
          label: 'განყოფილება',
          sortable: true,
          render: (value) => value || '-'
        },
        {
          key: 'actions',
          label: 'მოქმედებები',
          sortable: false,
          render: (value, row) => {
            return `
              <div class="flex gap-2" onclick="event.stopPropagation()">
                <button 
                  onclick="window.editService(${row.id}); return false;"
                  class="px-3 py-1 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition cursor-pointer"
                >
                  რედაქტირება
                </button>
                <button 
                  onclick="window.deleteService(${row.id}); return false;"
                  class="px-3 py-1 text-sm bg-red-500 hover:bg-red-600 text-white rounded-lg transition cursor-pointer"
                >
                  წაშლა
                </button>
              </div>
            `;
          }
        }
      ]
    };
  },
  computed: {
    averagePrice() {
      if (this.services.length === 0) return 0;
      const total = this.services.reduce((sum, service) => sum + parseFloat(service.price || 0), 0);
      return total / this.services.length;
    },
    uniqueDepartments() {
      const departments = this.services
        .map(s => s.department)
        .filter(d => d && d.trim() !== '');
      return new Set(departments).size;
    }
  },
  mounted() {
    this.fetchServices();
    // Make methods available globally for table actions
    window.editService = (id) => this.editService(id);
    window.deleteService = (id) => this.deleteService(id);
  },
  beforeUnmount() {
    // Clean up global methods
    delete window.editService;
    delete window.deleteService;
  },
  methods: {
    async fetchServices() {
      this.loading = true;
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/services', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        this.services = response.data;
      } catch (error) {
        console.error('Failed to fetch services:', error);
        this.error = error.response?.data?.message || 'სერვისების ჩატვირთვა ვერ მოხერხდა';
        this.services = [];
        this.toastStore.showToast('სერვისების ჩატვირთვა ვერ მოხერხდა', 'error');
      } finally {
        this.loading = false;
      }
    },
    openModal() {
      this.isModalOpen = true;
      this.editingService = null;
      this.error = '';
      this.formData = {
        name: '',
        discounted_name: '',
        price: '',
        department: ''
      };
    },
    closeModal() {
      this.isModalOpen = false;
      this.editingService = null;
      this.error = '';
      this.formData = {
        name: '',
        discounted_name: '',
        price: '',
        department: ''
      };
    },
    editService(id) {
      const service = this.services.find(s => s.id === id);
      if (!service) return;

      this.editingService = service;
      this.formData = {
        name: service.name || '',
        discounted_name: service.discounted_name || '',
        price: service.price || '',
        department: service.department || ''
      };
      this.isModalOpen = true;
      this.error = '';
    },
    deleteService(id) {
      const service = this.services.find(s => s.id === id);
      if (!service) return;

      this.serviceToDelete = service;
      this.isDeleteModalOpen = true;
    },
    closeDeleteModal() {
      this.isDeleteModalOpen = false;
      this.serviceToDelete = null;
    },
    async confirmDelete() {
      if (!this.serviceToDelete) return;

      this.loading = true;
      try {
        const token = localStorage.getItem('auth_token');
        await axios.delete(`/api/services/${this.serviceToDelete.id}`, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });

        this.toastStore.showToast('სერვისი წარმატებით წაიშალა', 'success');
        await this.fetchServices();
        this.closeDeleteModal();
      } catch (error) {
        console.error('Failed to delete service:', error);
        this.error = error.response?.data?.message || 'სერვისის წაშლა ვერ მოხერხდა';
        this.toastStore.showToast('სერვისის წაშლა ვერ მოხერხდა', 'error');
      } finally {
        this.loading = false;
      }
    },
    async handleSubmit() {
      this.loading = true;
      this.error = '';

      try {
        const token = localStorage.getItem('auth_token');
        const url = this.editingService 
          ? `/api/services/${this.editingService.id}`
          : '/api/services';
        const method = this.editingService ? 'patch' : 'post';

        const response = await axios[method](url, this.formData, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });

        // Success - show green toast, close modal, refresh list
        this.toastStore.showToast(
          this.editingService ? 'სერვისი წარმატებით განახლდა' : 'სერვისი წარმატებით დაემატა',
          'success'
        );
        await this.fetchServices();
        this.closeModal();
      } catch (error) {
        console.error('Failed to save service:', error);
        // Only show error if response status is 400 or higher
        if (error.response && error.response.status >= 400) {
          this.error = error.response?.data?.message || 'სერვისის შენახვა ვერ მოხერხდა';
          this.toastStore.showToast('სერვისის შენახვა ვერ მოხერხდა', 'error');
        } else {
          // For network errors or other issues, still try to show success if we have a response
          if (error.response && error.response.status < 400) {
            this.toastStore.showToast(
              this.editingService ? 'სერვისი წარმატებით განახლდა' : 'სერვისი წარმატებით დაემატა',
              'success'
            );
            await this.fetchServices();
            this.closeModal();
          } else {
            this.error = error.response?.data?.message || 'სერვისის შენახვა ვერ მოხერხდა';
            this.toastStore.showToast('სერვისის შენახვა ვერ მოხერხდა', 'error');
          }
        }
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>

