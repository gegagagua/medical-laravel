<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <Navbar />

    <main class="max-w-[1485px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">მთლიანი შემოსავალი</p>
              <p class="text-3xl font-bold text-green-600 dark:text-green-400">
                ₾{{ totalRevenue.toFixed(2) }}
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
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">გადახდილი</p>
              <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ paidCount }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">მოლოდინში</p>
              <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ pendingCount }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">დაუფარავი</p>
              <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">
                ₾{{ pendingAmount.toFixed(2) }}
              </p>
            </div>
            <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
        <div class="mb-6 flex items-center justify-between">
          <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
              გადახდების ისტორია
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
              ყველა გადახდის სრული ჩანაწერი
            </p>
          </div>
          <Button variant="primary" @click="openModal" class="hidden md:flex">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            ახალი გადახდა
          </Button>
        </div>

        <!-- Mobile Add Button -->
        <Button variant="primary" @click="openModal" class="md:hidden w-full mb-4">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          ახალი გადახდა
        </Button>

        <Table
          :data="payments"
          :columns="columns"
          :page-size="10"
          :searchable="true"
          search-placeholder="მოძებნეთ გადახდა (ინვოისი, პაციენტი, სერვისი)..."
          empty-message="გადახდები არ მოიძებნა"
          :loading="loading"
        />
      </div>

      <!-- Add Payment Modal -->
      <Modal :isOpen="isModalOpen" title="ახალი გადახდის დამატება" @close="closeModal">
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              პაციენტი
            </label>
            <select
              v-model="formData.user_id"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
              required
            >
              <option value="">აირჩიეთ პაციენტი</option>
              <option v-for="patient in patients" :key="patient.id" :value="patient.id">
                {{ patient.first_name }} {{ patient.last_name }}
              </option>
            </select>
          </div>

          <Input
            v-model="formData.service"
            label="სერვისი"
            placeholder="მაგ: რუტინული შემოწმება, კონსულტაცია..."
            required
          />

          <div class="grid grid-cols-2 gap-4">
            <Input
              v-model="formData.amount"
              type="number"
              step="0.01"
              min="0"
              label="თანხა (₾)"
              placeholder="0.00"
              required
            />
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                გადახდის თარიღი
              </label>
              <input
                v-model="formData.payment_date"
                type="date"
                class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                required
              />
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              გადახდის მეთოდი
            </label>
            <select
              v-model="formData.payment_method"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
              required
            >
              <option value="cash">ნაღდი</option>
              <option value="card">ბარათი</option>
              <option value="transfer">გადარიცხვა</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              სტატუსი
            </label>
            <select
              v-model="formData.status"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
            >
              <option value="pending">მოლოდინში</option>
              <option value="paid">გადახდილი</option>
              <option value="cancelled">გაუქმებული</option>
            </select>
          </div>

          <div v-if="error" class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-sm text-red-800 dark:text-red-300">{{ error }}</p>
          </div>

          <div class="flex gap-3 pt-4">
            <Button type="button" variant="secondary" @click="closeModal" :full-width="true">
              გაუქმება
            </Button>
            <Button type="submit" variant="primary" :full-width="true" :disabled="submitting">
              {{ submitting ? 'იტვირთება...' : 'დამატება' }}
            </Button>
          </div>
        </form>
      </Modal>
    </main>
  </div>
</template>

<script>
import axios from 'axios';
import Navbar from './Navbar.vue';
import Table from './ui/Table.vue';
import Button from './ui/Button.vue';
import Modal from './ui/Modal.vue';
import Input from './ui/Input.vue';

export default {
  name: 'Payments',
  components: {
    Navbar,
    Table,
    Button,
    Modal,
    Input
  },
  data() {
    return {
      payments: [],
      patients: [],
      loading: true,
      isModalOpen: false,
      submitting: false,
      error: '',
      formData: {
        user_id: '',
        service: '',
        amount: '',
        payment_date: '',
        payment_method: 'cash',
        status: 'pending'
      },
      columns: [
        {
          key: 'invoiceNumber',
          label: 'ინვოისი',
          sortable: true,
          filterable: true,
          width: '150px',
          render: (value) => `<span class="font-mono font-semibold text-blue-600 dark:text-blue-400">${value}</span>`
        },
        {
          key: 'patientName',
          label: 'პაციენტი',
          sortable: true,
          filterable: true,
          width: '200px',
          render: (value) => `<span class="font-medium text-gray-900 dark:text-white">${value}</span>`
        },
        {
          key: 'service',
          label: 'სერვისი',
          sortable: true,
          filterable: true,
          render: (value) => `<span class="text-sm text-gray-600 dark:text-gray-400">${value}</span>`
        },
        {
          key: 'amount',
          label: 'თანხა',
          sortable: true,
          width: '120px',
          render: (value) => `<span class="font-bold text-green-600 dark:text-green-400">₾${Number(value).toFixed(2)}</span>`
        },
        {
          key: 'paymentMethod',
          label: 'გადახდის მეთოდი',
          sortable: true,
          filterable: true,
          width: '150px',
          render: (value) => {
            const methods = {
              cash: { label: 'ნაღდი', class: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' },
              card: { label: 'ბარათი', class: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' },
              transfer: { label: 'გადარიცხვა', class: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }
            };
            const method = methods[value];
            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${method.class}">${method.label}</span>`;
          }
        },
        {
          key: 'status',
          label: 'სტატუსი',
          sortable: true,
          filterable: true,
          width: '130px',
          render: (value) => {
            const statuses = {
              paid: { label: 'გადახდილი', class: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' },
              pending: { label: 'მოლოდინში', class: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' },
              cancelled: { label: 'გაუქმებული', class: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }
            };
            const status = statuses[value];
            return `<span class="px-3 py-1 rounded-full text-xs font-medium ${status.class}">${status.label}</span>`;
          }
        },
        {
          key: 'date',
          label: 'თარიღი',
          sortable: true,
          width: '150px',
          render: (value) => {
            const date = new Date(value);
            return `<span class="text-sm text-gray-600 dark:text-gray-400">${date.toLocaleDateString('ka-GE', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</span>`;
          }
        }
      ]
    };
  },
  computed: {
    totalRevenue() {
      return this.payments.filter(p => p.status === 'paid').reduce((sum, p) => sum + Number(p.amount), 0);
    },
    pendingAmount() {
      return this.payments.filter(p => p.status === 'pending').reduce((sum, p) => sum + Number(p.amount), 0);
    },
    paidCount() {
      return this.payments.filter(p => p.status === 'paid').length;
    },
    pendingCount() {
      return this.payments.filter(p => p.status === 'pending').length;
    }
  },
  mounted() {
    this.fetchPayments();
    this.fetchPatients();
    // Set default date to today
    const today = new Date().toISOString().split('T')[0];
    this.formData.payment_date = today;
  },
  methods: {
    async fetchPatients() {
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/users', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        // Filter only patients
        this.patients = response.data.filter(user => user.role === 'PATIENT');
      } catch (error) {
        console.error('Failed to fetch patients:', error);
      }
    },
    async fetchPayments() {
      this.loading = true;
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/payments', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        this.payments = response.data;
      } catch (error) {
        console.error('Failed to fetch payments:', error);
        this.error = error.response?.data?.message || 'გადახდების ჩატვირთვა ვერ მოხერხდა';
        this.payments = [];
      } finally {
        this.loading = false;
      }
    },
    openModal() {
      this.isModalOpen = true;
      this.error = '';
      const today = new Date().toISOString().split('T')[0];
      this.formData = {
        user_id: '',
        service: '',
        amount: '',
        payment_date: today,
        payment_method: 'cash',
        status: 'pending'
      };
    },
    closeModal() {
      this.isModalOpen = false;
      this.error = '';
    },
    async handleSubmit() {
      this.submitting = true;
      this.error = '';

      try {
        const token = localStorage.getItem('auth_token');
        await axios.post('/api/payments', this.formData, {
          headers: { 'Authorization': `Bearer ${token}` }
        });

        // Refresh payments list
        await this.fetchPayments();
        
        // Close modal
        this.closeModal();
      } catch (error) {
        this.error = error.response?.data?.message || 'შეცდომა მოხდა გადახდის დამატებისას';
      } finally {
        this.submitting = false;
      }
    }
  }
};
</script>

