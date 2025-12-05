<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <Navbar />

    <main class="max-w-[1485px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">სულ ვიზიტები</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ totalAppointments }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">დადასტურებული</p>
              <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ confirmedCount }}</p>
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
              <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ pendingCount }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">დღევანდელი</p>
              <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ todayAppointments }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
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
              ვიზიტების სია
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
              ყველა დაგეგმილი და დასრულებული ვიზიტი
            </p>
          </div>
          <Button 
            v-if="authStore.userRole !== 'LABOR'"
            variant="primary"
            @click="createNewVisit"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            ახალი ვიზიტი
          </Button>
        </div>

        <!-- Filters -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              პაციენტი
            </label>
            <select
              v-model="filters.patientId"
              @change="applyFilters"
              class="block w-full py-2 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">ყველა პაციენტი</option>
              <option v-for="patient in patients" :key="patient.id" :value="patient.id">
                {{ patient.first_name }} {{ patient.last_name }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              სტატუსი
            </label>
            <select
              v-model="filters.status"
              @change="applyFilters"
              class="block w-full py-2 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">ყველა სტატუსი</option>
              <option value="PENDING">მოლოდინში</option>
              <option value="CONFIRMED">დადასტურებული</option>
              <option value="CANCELLED">გაუქმებული</option>
              <option value="COMPLETED">დასრულებული</option>
            </select>
          </div>

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
        </div>

        <div class="mb-4 flex justify-end">
          <Button variant="secondary" @click="clearFilters">
            ფილტრების გასუფთავება
          </Button>
        </div>

        <Table
          :data="filteredAppointments"
          :columns="columns"
          :page-size="10"
          :searchable="true"
          search-placeholder="მოძებნეთ ვიზიტი (პაციენტი, ექიმი, სერვისი, შენიშვნა)..."
          empty-message="ვიზიტები არ მოიძებნა"
          :loading="loading"
        />
      </div>
    </main>
  </div>
</template>

<script>
import axios from 'axios';
import { useAuthStore } from '../stores/auth';
import { useToastStore } from '../stores/toast';
import Navbar from './Navbar.vue';
import Table from './ui/Table.vue';
import Button from './ui/Button.vue';

export default {
  name: 'Visits',
  components: {
    Navbar,
    Table,
    Button
  },
  setup() {
    const authStore = useAuthStore();
    const toastStore = useToastStore();
    return { authStore, toastStore };
  },
  data() {
    return {
      appointments: [],
      allAppointments: [],
      patients: [],
      loading: true,
      filters: {
        patientId: '',
        status: '',
        dateFrom: '',
        dateTo: ''
      },
      columns: [
        {
          key: 'id',
          label: 'ID',
          sortable: true,
          width: '80px'
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
          key: 'doctorName',
          label: 'ექიმი',
          sortable: true,
          filterable: true,
          render: (value) => `<span class="text-sm text-gray-600 dark:text-gray-400">${value || '-'}</span>`
        },
        {
          key: 'department',
          label: 'განყოფილება',
          sortable: true,
          filterable: true,
          width: '150px',
          render: (value) => `<span class="text-sm text-gray-600 dark:text-gray-400">${value || '-'}</span>`
        },
        {
          key: 'service',
          label: 'სერვისი',
          sortable: true,
          filterable: true,
          width: '250px',
          render: (value) => `<span class="text-sm text-gray-600 dark:text-gray-400">${value || '-'}</span>`
        },
        {
          key: 'date',
          label: 'თარიღი',
          sortable: true,
          width: '130px',
          render: (value) => {
            const date = new Date(value);
            return `<span class="font-medium text-gray-900 dark:text-white">${date.toLocaleDateString('ka-GE', { year: 'numeric', month: 'short', day: 'numeric' })}</span>`;
          }
        },
        {
          key: 'time',
          label: 'დრო',
          sortable: true,
          width: '100px',
          render: (value) => `<span class="font-mono text-sm text-gray-900 dark:text-white">${value}</span>`
        },
        {
          key: 'status',
          label: 'სტატუსი',
          sortable: true,
          filterable: true,
          width: '180px',
          render: (value, item) => {
            const statuses = {
              PENDING: { label: 'მოლოდინში', class: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' },
              CONFIRMED: { label: 'დადასტურებული', class: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' },
              CANCELLED: { label: 'გაუქმებული', class: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' },
              COMPLETED: { label: 'დასრულებული', class: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' },
              // Handle lowercase for backward compatibility
              pending: { label: 'მოლოდინში', class: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' },
              confirmed: { label: 'დადასტურებული', class: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' },
              cancelled: { label: 'გაუქმებული', class: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' },
              completed: { label: 'დასრულებული', class: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }
            };
            const status = statuses[value] || { label: value, class: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' };
            const visitId = item.id;
            const currentStatus = value?.toUpperCase() || value || 'PENDING';
            const canEdit = window.vm && (window.vm.authStore?.userRole === 'DOCTOR' || window.vm.authStore?.userRole === 'LABOR');
            
            if (canEdit) {
              return `
                <select 
                  data-visit-id="${visitId}"
                  class="visit-status-select px-3 py-1 rounded-lg text-xs font-medium bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer"
                  value="${currentStatus}"
                >
                  <option value="PENDING" ${currentStatus === 'PENDING' ? 'selected' : ''}>მოლოდინში</option>
                  <option value="CONFIRMED" ${currentStatus === 'CONFIRMED' ? 'selected' : ''}>დადასტურებული</option>
                  <option value="CANCELLED" ${currentStatus === 'CANCELLED' ? 'selected' : ''}>გაუქმებული</option>
                  <option value="COMPLETED" ${currentStatus === 'COMPLETED' ? 'selected' : ''}>დასრულებული</option>
                </select>
              `;
            }
            return `<span class="px-3 py-1 rounded-full text-xs font-medium ${status.class}">${status.label}</span>`;
          }
        },
        {
          key: 'status_changed_at',
          label: 'სტატუსის შეცვლის თარიღი',
          sortable: true,
          width: '200px',
          render: (value) => {
            if (!value) return '<span class="text-sm text-gray-500 dark:text-gray-400">-</span>';
            const date = new Date(value);
            return `<span class="text-sm text-gray-600 dark:text-gray-400">${date.toLocaleDateString('ka-GE', { year: 'numeric', month: 'short', day: 'numeric' })} ${date.toLocaleTimeString('ka-GE', { hour: '2-digit', minute: '2-digit' })}</span>`;
          }
        },
        {
          key: 'notes',
          label: 'შენიშვნა',
          filterable: true,
          render: (value) => `<span class="text-sm text-gray-600 dark:text-gray-400">${value || '-'}</span>`
        }
      ]
    };
  },
  computed: {
    filteredAppointments() {
      let filtered = [...this.allAppointments];

      // Filter by logged-in doctor/labor user - show only their visits
      if (this.authStore.user && (this.authStore.userRole === 'DOCTOR' || this.authStore.userRole === 'LABOR')) {
        filtered = filtered.filter(a => {
          // Match by doctor_id
          if (a.doctor_id) {
            return a.doctor_id == this.authStore.user.id;
          }
          // Fallback: match by doctor name if doctor_id is not available
          if (a.doctorName) {
            const userFullName = `${this.authStore.user.first_name} ${this.authStore.user.last_name}`;
            return a.doctorName === userFullName;
          }
          return false;
        });
      }

      // Filter by patient
      if (this.filters.patientId) {
        filtered = filtered.filter(a => {
          // Try to match by patient_id if available, otherwise by patient name
          if (a.patient_id) {
            return a.patient_id == this.filters.patientId;
          }
          const patient = this.patients.find(p => p.id == this.filters.patientId);
          if (patient) {
            const patientName = `${patient.first_name} ${patient.last_name}`;
            return a.patientName === patientName;
          }
          return false;
        });
      }

      // Filter by status
      if (this.filters.status) {
        filtered = filtered.filter(a => {
          const status = a.status?.toUpperCase();
          return status === this.filters.status.toUpperCase();
        });
      }

      // Filter by date range
      if (this.filters.dateFrom) {
        filtered = filtered.filter(a => {
          const visitDate = new Date(a.date).toISOString().split('T')[0];
          return visitDate >= this.filters.dateFrom;
        });
      }

      if (this.filters.dateTo) {
        filtered = filtered.filter(a => {
          const visitDate = new Date(a.date).toISOString().split('T')[0];
          return visitDate <= this.filters.dateTo;
        });
      }

      return filtered;
    },
    totalAppointments() {
      return this.filteredAppointments.length;
    },
    confirmedCount() {
      return this.filteredAppointments.filter(a => {
        const status = a.status?.toUpperCase();
        return status === 'CONFIRMED' || status === 'confirmed';
      }).length;
    },
    pendingCount() {
      return this.filteredAppointments.filter(a => {
        const status = a.status?.toUpperCase();
        return status === 'PENDING' || status === 'pending';
      }).length;
    },
    todayAppointments() {
      const today = new Date().toISOString().split('T')[0];
      return this.filteredAppointments.filter(a => {
        const visitDate = new Date(a.date).toISOString().split('T')[0];
        return visitDate === today;
      }).length;
    }
  },
  mounted() {
    // Load user from storage
    this.authStore.loadFromStorage();
    this.fetchAppointments();
    this.fetchPatients();
    
    // Expose component instance to window for dropdown handlers
    window.vm = this;
    
    // Add event delegation for status dropdown changes
    this.$nextTick(() => {
      document.addEventListener('change', (e) => {
        if (e.target.classList.contains('visit-status-select')) {
          const visitId = parseInt(e.target.dataset.visitId);
          const newStatus = e.target.value;
          if (visitId && newStatus) {
            this.updateVisitStatus(visitId, newStatus);
          }
        }
      });
    });
  },
  methods: {
    async fetchPatients() {
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/patients', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        this.patients = response.data;
      } catch (error) {
        console.error('Failed to fetch patients:', error);
      }
    },
    async fetchAppointments() {
      this.loading = true;
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/visits', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        
        // Keep patient_id for filtering
        this.allAppointments = response.data;
        
        this.appointments = this.allAppointments;
      } catch (error) {
        console.error('Failed to fetch visits:', error);
        this.error = error.response?.data?.message || 'ვიზიტების ჩატვირთვა ვერ მოხერხდა';
        this.allAppointments = [];
        this.appointments = [];
      } finally {
        this.loading = false;
      }
    },
    applyFilters() {
      // Filters are applied automatically via computed property
      // This method can be used for additional logic if needed
    },
    clearFilters() {
      this.filters = {
        patientId: '',
        status: '',
        dateFrom: '',
        dateTo: ''
      };
    },
    createNewVisit() {
      this.$router.push('/patients');
    },
    async updateVisitStatus(visitId, newStatus) {
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.patch(`/api/visits/${visitId}`, {
          status: newStatus
        }, {
          headers: { 'Authorization': `Bearer ${token}` }
        });

        // Update the visit in the local array
        const visitIndex = this.allAppointments.findIndex(v => v.id === visitId);
        if (visitIndex !== -1) {
          this.allAppointments[visitIndex].status = newStatus;
          this.allAppointments[visitIndex].status_changed_at = response.data.visit?.status_changed_at || new Date().toISOString();
        }

        this.toastStore.showToast('სტატუსი წარმატებით განახლდა', 'success');
        
        // Refresh appointments to get updated data with status_changed_at
        await this.fetchAppointments();
      } catch (error) {
        console.error('Failed to update visit status:', error);
        this.toastStore.showToast('სტატუსის განახლება ვერ მოხერხდა', 'error');
      }
    }
  }
};
</script>
