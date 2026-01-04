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
          <div class="flex gap-2">
            <Button variant="secondary" @click="exportToExcel" class="hidden md:flex">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Excel-ში ექსპორტი
            </Button>
            <Button variant="primary" @click="openModal" class="hidden md:flex">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              ახალი გადახდა
            </Button>
          </div>
        </div>

        <!-- Filters -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              თარიღიდან
            </label>
            <input
              v-model="filters.dateFrom"
              type="date"
              @change="applyFilters"
              class="block w-full py-2 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              თარიღამდე
            </label>
            <input
              v-model="filters.dateTo"
              type="date"
              @change="applyFilters"
              class="block w-full py-2 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <div class="flex items-end">
            <Button variant="secondary" @click="clearFilters" class="w-full">
              ფილტრების გასუფთავება
            </Button>
          </div>
        </div>

        <!-- Mobile Add Button -->
        <div class="md:hidden mb-4 space-y-2">
          <Button variant="secondary" @click="exportToExcel" class="w-full">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Excel-ში ექსპორტი
          </Button>
          <Button variant="primary" @click="openModal" class="w-full">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            ახალი გადახდა
          </Button>
        </div>

          <Table
            :data="filteredPayments"
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
              v-model="formData.patient_id"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
              required
            >
              <option value="" v-if="patientsLoading">იტვირთება...</option>
              <option value="" v-else>აირჩიეთ პაციენტი</option>
              <option v-for="patient in patients" :key="patient.id" :value="patient.id">
                {{ patient.fullName }} {{ patient.last_name }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              ექიმი *
            </label>
            <select
              v-model="formData.doctor_id"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
              required
            >
              <option value="">აირჩიეთ ექიმი</option>
              <option v-for="user in doctorUsers" :key="user.id" :value="user.id">
                {{ user.first_name }} {{ user.last_name }} {{ user.doctor_role ? `(${user.doctor_role})` : '' }}
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
import { useToastStore } from '../stores/toast';
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
  setup() {
    const toastStore = useToastStore();
    return { toastStore };
  },
  data() {
    return {
      payments: [],
      allPayments: [],
      patients: [],
      patientsLoading: false,
      doctorUsers: [],
      loading: true,
      isModalOpen: false,
      submitting: false,
      error: '',
      filters: {
        dateFrom: '',
        dateTo: ''
      },
      formData: {
        patient_id: '',
        doctor_id: '',
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
          key: 'doctor',
          label: 'ექიმი',
          sortable: true,
          filterable: true,
          width: '180px',
          render: (value) => value ? `<span class="text-sm font-medium text-blue-600 dark:text-blue-400">${value}</span>` : `<span class="text-sm text-gray-400 dark:text-gray-500">-</span>`
        },
        {
          key: 'service',
          label: 'სერვისი',
          sortable: true,
          filterable: true,
          render: (value, item) => {
            let html = `<div class="text-sm text-gray-600 dark:text-gray-400">`;
            
            // If there are services with discounts, show them separately
            if (item.servicesDiscounts && Array.isArray(item.servicesDiscounts) && item.servicesDiscounts.length > 0) {
              item.servicesDiscounts.forEach((service, index) => {
                const serviceName = service.name || '';
                const servicePrice = parseFloat(service.price) || 0;
                const discount = parseFloat(service.discount) || 0;
                const discountedPrice = servicePrice * (1 - discount / 100);
                
                html += `<div class="mb-1 ${index > 0 ? 'mt-2 pt-2 border-t border-gray-200 dark:border-gray-700' : ''}">`;
                html += `<div class="font-medium">${serviceName}</div>`;
                html += `<div class="text-xs text-gray-500 dark:text-gray-500 mt-0.5">`;
                if (discount > 0) {
                  html += `<span class="line-through text-gray-400">₾${servicePrice.toFixed(2)}</span> `;
                  html += `<span class="text-green-600 dark:text-green-400 font-semibold">₾${discountedPrice.toFixed(2)}</span> `;
                  html += `<span class="text-orange-600 dark:text-orange-400">(-${discount.toFixed(2)}%)</span>`;
                } else {
                  html += `<span>₾${servicePrice.toFixed(2)}</span>`;
                }
                html += `</div></div>`;
              });
            } else {
              // Fallback to regular service display
              html += `<span>${value}</span>`;
            }
            
            html += `</div>`;
            return html;
          }
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
        },
        {
          key: 'actions',
          label: 'მოქმედებები',
          sortable: false,
          width: '120px',
          render: (value, item) => {
            return `
              <div class="flex justify-center" onclick="event.stopPropagation()">
                <button 
                  onclick="window.printPayment(${item.id}); return false;"
                  class="px-3 py-1 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition cursor-pointer flex items-center gap-1"
                  title="დაბეჭდვა"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                  </svg>
                  დაბეჭდვა
                </button>
              </div>
            `;
          }
        }
      ]
    };
  },
  computed: {
    filteredPayments() {
      let filtered = [...this.allPayments];

      // Filter by date range
      if (this.filters.dateFrom) {
        filtered = filtered.filter(p => {
          const paymentDate = new Date(p.date).toISOString().split('T')[0];
          return paymentDate >= this.filters.dateFrom;
        });
      }

      if (this.filters.dateTo) {
        filtered = filtered.filter(p => {
          const paymentDate = new Date(p.date).toISOString().split('T')[0];
          return paymentDate <= this.filters.dateTo;
        });
      }

      return filtered;
    },
    totalRevenue() {
      return this.filteredPayments.filter(p => p.status === 'paid').reduce((sum, p) => sum + Number(p.amount), 0);
    },
    pendingAmount() {
      return this.filteredPayments.filter(p => p.status === 'pending').reduce((sum, p) => sum + Number(p.amount), 0);
    },
    paidCount() {
      return this.filteredPayments.filter(p => p.status === 'paid').length;
    },
    pendingCount() {
      return this.filteredPayments.filter(p => p.status === 'pending').length;
    }
  },
  mounted() {
    this.fetchPayments();
    this.fetchPatients();
    this.fetchDoctors();
    // Set default date to today
    const today = new Date().toISOString().split('T')[0];
    this.formData.payment_date = today;
    // Make print method available globally for table actions
    window.printPayment = (id) => this.printPayment(id);
  },
  beforeUnmount() {
    // Clean up global method
    delete window.printPayment;
  },
  methods: {
    async fetchPatients() {
      this.patientsLoading = true;
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/patients', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        this.patients = response.data;
      } catch (error) {
        console.error('Failed to fetch patients:', error);
        this.patients = [];
      } finally {
        this.patientsLoading = false;
      }
    },
    async fetchDoctors() {
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/users', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        
        // Filter users with DOCTOR role
        this.doctorUsers = response.data.filter(user => user.role === 'DOCTOR')
          .sort((a, b) => {
            const nameA = `${a.last_name} ${a.first_name}`;
            const nameB = `${b.last_name} ${b.first_name}`;
            return nameA.localeCompare(nameB);
          });
      } catch (error) {
        console.error('Failed to fetch doctors:', error);
        this.doctorUsers = [];
      }
    },
    async fetchPayments() {
      this.loading = true;
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/payments', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        this.allPayments = response.data;
        this.payments = this.allPayments;
      } catch (error) {
        console.error('Failed to fetch payments:', error);
        this.error = error.response?.data?.message || 'გადახდების ჩატვირთვა ვერ მოხერხდა';
        this.allPayments = [];
        this.payments = [];
      } finally {
        this.loading = false;
      }
    },
    applyFilters() {
      // Filters are applied automatically via computed property
    },
    clearFilters() {
      this.filters = {
        dateFrom: '',
        dateTo: ''
      };
    },
    exportToExcel() {
      // Export filtered payments to Excel
      const dataToExport = this.filteredPayments.map(payment => {
        const date = new Date(payment.date);
        
        // Format services with discounts
        let servicesText = payment.service || '-';
        if (payment.servicesDiscounts && Array.isArray(payment.servicesDiscounts) && payment.servicesDiscounts.length > 0) {
          servicesText = payment.servicesDiscounts.map(service => {
            const serviceName = service.name || '';
            const servicePrice = parseFloat(service.price) || 0;
            const discount = parseFloat(service.discount) || 0;
            const discountedPrice = servicePrice * (1 - discount / 100);
            
            if (discount > 0) {
              return `${serviceName}: ₾${servicePrice.toFixed(2)} → ₾${discountedPrice.toFixed(2)} (-${discount.toFixed(2)}%)`;
            } else {
              return `${serviceName}: ₾${servicePrice.toFixed(2)}`;
            }
          }).join(' | ');
        }
        
        return {
          'ინვოისი': payment.invoiceNumber,
          'პაციენტი': payment.patientName,
          'ექიმი': payment.doctor || '-',
          'სერვისები': servicesText,
          'თანხა': Number(payment.amount).toFixed(2),
          'ფასდაკლება': payment.hasDiscount ? `${payment.discountPercentage?.toFixed(2) || 0}%` : '-',
          'გადახდის მეთოდი': this.getPaymentMethodLabel(payment.paymentMethod),
          'სტატუსი': this.getStatusLabel(payment.status),
          'თარიღი': date.toLocaleDateString('ka-GE', { year: 'numeric', month: '2-digit', day: '2-digit' }),
          'დრო': date.toLocaleTimeString('ka-GE', { hour: '2-digit', minute: '2-digit' })
        };
      });

      // Create CSV content
      if (dataToExport.length === 0) {
        this.toastStore.warning('ექსპორტირებისთვის მონაცემები არ არის');
        return;
      }

      // Get headers
      const headers = Object.keys(dataToExport[0]);
      
      // Create CSV rows
      const csvRows = [
        headers.join(','),
        ...dataToExport.map(row => 
          headers.map(header => {
            const value = row[header] || '';
            // Escape commas and quotes
            return `"${String(value).replace(/"/g, '""')}"`;
          }).join(',')
        )
      ];

      // Create CSV string
      const csvContent = csvRows.join('\n');
      
      // Add BOM for UTF-8 Excel compatibility
      const BOM = '\uFEFF';
      const blob = new Blob([BOM + csvContent], { type: 'text/csv;charset=utf-8;' });
      
      // Create download link
      const link = document.createElement('a');
      const url = URL.createObjectURL(blob);
      link.setAttribute('href', url);
      
      // Generate filename with date range
      const now = new Date();
      const dateStr = now.toISOString().split('T')[0];
      let filename = `payments_${dateStr}.csv`;
      
      if (this.filters.dateFrom && this.filters.dateTo) {
        filename = `payments_${this.filters.dateFrom}_${this.filters.dateTo}.csv`;
      }
      
      link.setAttribute('download', filename);
      link.style.visibility = 'hidden';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },
    getPaymentMethodLabel(method) {
      const methods = {
        cash: 'ნაღდი',
        card: 'ბარათი',
        transfer: 'გადარიცხვა'
      };
      return methods[method] || method;
    },
    getStatusLabel(status) {
      const statuses = {
        paid: 'გადახდილი',
        pending: 'მოლოდინში',
        cancelled: 'გაუქმებული'
      };
      return statuses[status] || status;
    },
    formatServicesForPrint(payment) {
      // If there are services with discounts, show them separately
      if (payment.servicesDiscounts && Array.isArray(payment.servicesDiscounts) && payment.servicesDiscounts.length > 0) {
        let html = '<div style="display: flex; flex-direction: column; gap: 12px;">';
        
        payment.servicesDiscounts.forEach((service, index) => {
          const serviceName = service.name || '';
          const servicePrice = parseFloat(service.price) || 0;
          const discount = parseFloat(service.discount) || 0;
          const discountedPrice = servicePrice * (1 - discount / 100);
          
          html += '<div style="padding: 10px; background-color: #f9fafb; border-radius: 6px; border-left: 3px solid #3b82f6;">';
          html += `<div style="font-weight: 600; font-size: 15px; margin-bottom: 6px;">${serviceName}</div>`;
          html += '<div style="font-size: 13px; color: #666;">';
          
          if (discount > 0) {
            html += `<span style="text-decoration: line-through; color: #999; margin-right: 8px;">₾${servicePrice.toFixed(2)}</span>`;
            html += `<span style="color: #22c55e; font-weight: 600; margin-right: 8px;">₾${discountedPrice.toFixed(2)}</span>`;
            html += `<span style="color: #f97316; font-weight: 500;">(ფასდაკლება: ${discount.toFixed(2)}%)</span>`;
          } else {
            html += `<span style="font-weight: 500;">₾${servicePrice.toFixed(2)}</span>`;
          }
          
          html += '</div></div>';
        });
        
        html += '</div>';
        return html;
      } else {
        // Fallback to regular service display
        return `<span>${payment.service || '-'}</span>`;
      }
    },
    openModal() {
      this.isModalOpen = true;
      this.error = '';
      const today = new Date().toISOString().split('T')[0];
      this.formData = {
        patient_id: '',
        doctor_id: '',
        service: '',
        amount: '',
        payment_date: today,
        payment_method: 'cash',
        status: 'pending'
      };
      if (!this.patients || this.patients.length === 0) {
        this.fetchPatients();
      }
      if (!this.doctorUsers || this.doctorUsers.length === 0) {
        this.fetchDoctors();
      }
    },
    closeModal() {
      this.isModalOpen = false;
      this.error = '';
    },
    async handleSubmit() {
      this.submitting = true;
      this.error = '';

      try {
        // Get doctor name from selected doctor_id
        const selectedDoctor = this.doctorUsers.find(d => d.id === Number(this.formData.doctor_id));
        const doctorName = selectedDoctor ? `${selectedDoctor.first_name} ${selectedDoctor.last_name}` : null;
        
        const paymentData = {
          ...this.formData,
          user_id: this.formData.doctor_id || null,
          doctor: doctorName
        };
        
        const token = localStorage.getItem('auth_token');
        await axios.post('/api/payments', paymentData, {
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
    },
    printPayment(paymentId) {
      const payment = this.allPayments.find(p => p.id === paymentId || p.invoiceNumber === paymentId);
      if (!payment) {
        this.toastStore.warning('გადახდა არ მოიძებნა');
        return;
      }

      const printWindow = window.open('', '_blank');
      if (!printWindow) return;

      const paymentDate = new Date(payment.date);
      const formattedDate = paymentDate.toLocaleDateString('ka-GE', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });

      const printContent = `
        <!DOCTYPE html>
        <html>
          <head>
            <title>გადახდის ინვოისი - ${payment.invoiceNumber}</title>
            <style>
              body {
                font-family: Arial, sans-serif;
                padding: 40px;
                color: #333;
                max-width: 800px;
                margin: 0 auto;
              }
              .header {
                text-align: center;
                margin-bottom: 40px;
                border-bottom: 3px solid #333;
                padding-bottom: 20px;
              }
              .header h1 {
                margin: 0;
                font-size: 28px;
                font-weight: bold;
              }
              .invoice-info {
                display: flex;
                justify-content: space-between;
                margin-bottom: 30px;
              }
              .info-section {
                flex: 1;
              }
              .info-section h3 {
                margin: 0 0 10px 0;
                font-size: 14px;
                color: #666;
                text-transform: uppercase;
              }
              .info-section p {
                margin: 5px 0;
                font-size: 16px;
                font-weight: 500;
              }
              .invoice-details {
                margin: 30px 0;
                border: 2px solid #333;
                border-radius: 8px;
                overflow: hidden;
              }
              .detail-row {
                display: flex;
                border-bottom: 1px solid #ddd;
                padding: 15px;
              }
              .detail-row:last-child {
                border-bottom: none;
              }
              .detail-label {
                font-weight: bold;
                width: 200px;
                color: #666;
              }
              .detail-value {
                flex: 1;
                font-size: 16px;
              }
              .amount-section {
                background-color: #f5f5f5;
                padding: 20px;
                border-radius: 8px;
                margin-top: 30px;
                text-align: center;
              }
              .amount-label {
                font-size: 18px;
                color: #666;
                margin-bottom: 10px;
              }
              .amount-value {
                font-size: 36px;
                font-weight: bold;
                color: #22c55e;
              }
              .footer {
                margin-top: 40px;
                padding-top: 20px;
                border-top: 1px solid #ddd;
                text-align: center;
                font-size: 12px;
                color: #666;
              }
              @media print {
                body {
                  padding: 20px;
                }
              }
            </style>
          </head>
          <body>
            <div class="header">
              <h1>გადახდის ინვოისი</h1>
              <p style="margin-top: 10px; font-size: 18px; color: #666;">ინვოისი #${payment.invoiceNumber}</p>
            </div>
            
            <div class="invoice-info">
              <div class="info-section">
                <h3>პაციენტი</h3>
                <p style="font-weight: 600; margin-bottom: 5px;">${payment.patientName || '-'}</p>
                ${payment.patientIdNumber ? `<p style="font-size: 14px; color: #666; margin: 3px 0;">პ/ნ: ${payment.patientIdNumber}</p>` : ''}
                ${payment.patientDateOfBirth ? `<p style="font-size: 14px; color: #666; margin: 3px 0;">დაბადების თარიღი: ${new Date(payment.patientDateOfBirth).toLocaleDateString('ka-GE', { year: 'numeric', month: 'long', day: 'numeric' })}</p>` : ''}
              </div>
              <div class="info-section" style="text-align: right;">
                <h3>გადახდის თარიღი</h3>
                <p>${formattedDate}</p>
              </div>
            </div>

            <div class="invoice-details">
              <div class="detail-row">
                <div class="detail-label">ექიმი:</div>
                <div class="detail-value">${payment.doctor || '-'}</div>
              </div>
              <div class="detail-row">
                <div class="detail-label">სერვისები:</div>
                <div class="detail-value">
                  ${this.formatServicesForPrint(payment)}
                </div>
              </div>
              <div class="detail-row">
                <div class="detail-label">გადახდის მეთოდი:</div>
                <div class="detail-value">${this.getPaymentMethodLabel(payment.paymentMethod)}</div>
              </div>
              <div class="detail-row">
                <div class="detail-label">სტატუსი:</div>
                <div class="detail-value">${this.getStatusLabel(payment.status)}</div>
              </div>
            </div>

            <div class="amount-section">
              <div class="amount-label">გადასახდელი თანხა</div>
              <div class="amount-value">₾${Number(payment.amount).toFixed(2)}</div>
            </div>

            <div class="footer">
              <p>დაბეჭდილია: ${new Date().toLocaleDateString('ka-GE', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
              })}</p>
            </div>
          </body>
        </html>
      `;
      
      printWindow.document.write(printContent);
      printWindow.document.close();
      
      setTimeout(() => {
        printWindow.print();
      }, 250);
    }
  }
};
</script>

