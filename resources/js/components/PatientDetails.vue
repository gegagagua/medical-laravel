<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <Navbar />
    
    <main class="max-w-[1485px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Back Button -->
      <div class="mb-6">
        <Button variant="secondary" @click="goBack">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          უკან
        </Button>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>

      <!-- Patient Details -->
      <div v-else-if="patient" class="space-y-6">
        <!-- Patient Info Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
          <div class="flex items-start justify-between mb-6">
            <div>
              <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                {{ patient.first_name }} {{ patient.last_name }}
              </h1>
              <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                <span>ID: {{ patient.id }}</span>
                <span v-if="patient.role" :class="getRoleBadgeClass(patient.role)">
                  {{ getRoleLabel(patient.role) }}
                </span>
              </div>
            </div>
            <div class="flex gap-3">
              <Button variant="secondary" @click="printPatientInfo">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                დაბეჭდვა
              </Button>
              <Button variant="primary" @click="openVisitModal">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                ახალი ვიზიტი
              </Button>
            </div>
          </div>

          <!-- Patient Details Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                ელ. ფოსტა
              </label>
              <p class="text-lg text-gray-900 dark:text-white">
                <a :href="`mailto:${patient.email}`" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                  {{ patient.email }}
                </a>
              </p>
            </div>

            <div v-if="patient.phone">
              <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                ტელეფონი
              </label>
              <p class="text-lg text-gray-900 dark:text-white">
                <a :href="`tel:${patient.phone}`" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                  {{ patient.phone }}
                </a>
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                რეგისტრაციის თარიღი
              </label>
              <p class="text-lg text-gray-900 dark:text-white">
                {{ formatDate(patient.created_at) }}
              </p>
            </div>
          </div>
        </div>

        <!-- Visit History -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
            ვიზიტების ისტორია
          </h2>

          <!-- Empty State -->
          <div v-if="visits.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">ვიზიტები არ მოიძებნა</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">პაციენტს ჯერ არ ჰქონია ვიზიტები</p>
          </div>

          <!-- Visits List -->
          <div v-else class="space-y-4">
            <div
              v-for="visit in visits"
              :key="visit.id"
              class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
            >
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <div class="flex items-center gap-3 mb-2">
                    <span class="font-semibold text-gray-900 dark:text-white">
                      {{ formatDate(visit.date) }} - {{ visit.time }}
                    </span>
                    <span :class="getStatusBadgeClass(visit.status)">
                      {{ getStatusLabel(visit.status) }}
                    </span>
                  </div>
                  
                  <div v-if="visit.doctorName" class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                    <span class="font-medium">ექიმი:</span> {{ visit.doctorName }}
                  </div>
                  
                  <div v-if="visit.department" class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                    <span class="font-medium">განყოფილება:</span> {{ visit.department }}
                  </div>
                  
                  <div v-if="visit.notes" class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    <span class="font-medium">შენიშვნა:</span> {{ visit.notes }}
                  </div>
                </div>
                <div class="ml-4">
                  <Button variant="primary" @click="openPaymentModal(visit)" class="text-sm px-3 py-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    გადახდის დამატება
                  </Button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">პაციენტი არ მოიძებნა</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ error || 'შეცდომა მოხდა პაციენტის ჩატვირთვისას' }}</p>
      </div>

      <!-- Add Payment Modal -->
      <Modal :isOpen="isPaymentModalOpen" title="გადახდის დამატება" @close="closePaymentModal">
        <form @submit.prevent="submitPayment" class="space-y-4">
          <div v-if="patient" class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <p class="text-sm font-medium text-blue-900 dark:text-blue-100">
              პაციენტი: {{ patient.first_name }} {{ patient.last_name }}
            </p>
          </div>

          <div v-if="paymentFormData.doctor" class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <p class="text-sm font-medium text-green-900 dark:text-green-100">
              ექიმი: {{ paymentFormData.doctor }}
            </p>
          </div>

          <Input
            v-model="paymentFormData.service"
            type="text"
            label="სერვისი *"
            placeholder="მაგ: კონსულტაცია, გამოკვლევა..."
            required
          />

          <Input
            v-model="paymentFormData.amount"
            type="number"
            label="თანხა *"
            placeholder="0.00"
            step="0.01"
            min="0"
            required
          />

          <Input
            v-model="paymentFormData.payment_date"
            type="date"
            label="გადახდის თარიღი *"
            required
          />

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              გადახდის მეთოდი *
            </label>
            <select
              v-model="paymentFormData.payment_method"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
              required
            >
              <option value="">აირჩიეთ მეთოდი</option>
              <option value="cash">ნაღდი</option>
              <option value="card">ბარათი</option>
              <option value="transfer">გადარიცხვა</option>
            </select>
          </div>

          <div v-if="paymentError" class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-sm text-red-800 dark:text-red-300">{{ paymentError }}</p>
          </div>

          <div class="flex gap-3 pt-4">
            <Button type="button" variant="secondary" @click="closePaymentModal" :full-width="true">
              გაუქმება
            </Button>
            <Button type="submit" variant="primary" :full-width="true" :disabled="submittingPayment">
              {{ submittingPayment ? 'შექმნა...' : 'გადახდის შექმნა' }}
            </Button>
          </div>
        </form>
      </Modal>

      <!-- Add Visit Modal -->
      <Modal :isOpen="isVisitModalOpen" title="ახალი ვიზიტის დამატება" @close="closeVisitModal">
        <form @submit.prevent="submitVisit" class="space-y-4">
          <div v-if="patient" class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <p class="text-sm font-medium text-blue-900 dark:text-blue-100">
              პაციენტი: {{ patient.first_name }} {{ patient.last_name }}
            </p>
          </div>

          <Input
            v-model="visitFormData.date"
            type="date"
            label="თარიღი"
            required
          />

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              განყოფილება *
            </label>
            <select
              v-model="visitFormData.department"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
              required
            >
              <option value="">აირჩიეთ პროფესია</option>
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

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              ექიმი / მედპერ. *
            </label>
            <select
              v-model="visitFormData.doctor"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
              required
              :disabled="!visitFormData.department"
            >
              <option value="">{{ visitFormData.department ? 'აირჩიეთ ექიმი' : 'ჯერ აირჩიეთ განყოფილება' }}</option>
              <option 
                v-for="user in filteredDoctors" 
                :key="user.id" 
                :value="`${user.first_name} ${user.last_name}`"
              >
                {{ user.first_name }} {{ user.last_name }}
              </option>
            </select>
          </div>

          <Input
            v-model="visitFormData.time"
            type="time"
            label="მიღების საათი"
            required
          />

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              შენიშვნა
            </label>
            <textarea
              v-model="visitFormData.notes"
              rows="3"
              placeholder="დამატებითი ინფორმაცია..."
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
            ></textarea>
          </div>

          <div v-if="error" class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-sm text-red-800 dark:text-red-300">{{ error }}</p>
          </div>

          <div class="flex gap-3 pt-4">
            <Button type="button" variant="secondary" @click="closeVisitModal" :full-width="true">
              გაუქმება
            </Button>
            <Button type="submit" variant="primary" :full-width="true" :disabled="submitting">
              {{ submitting ? 'შექმნა...' : 'ვიზიტის შექმნა' }}
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
import Button from './ui/Button.vue';
import Modal from './ui/Modal.vue';
import Input from './ui/Input.vue';

export default {
  name: 'PatientDetails',
  components: {
    Navbar,
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
      patient: null,
      visits: [],
      loading: true,
      isVisitModalOpen: false,
      isPaymentModalOpen: false,
      submitting: false,
      submittingPayment: false,
      error: '',
      paymentError: '',
      laborUsers: [],
      visitFormData: {
        date: '',
        department: '',
        doctor: '',
        time: '',
        notes: ''
      },
      paymentFormData: {
        service: '',
        doctor: '',
        amount: '',
        payment_date: '',
        payment_method: ''
      }
    };
  },
  computed: {
    filteredDoctors() {
      if (!this.visitFormData.department) {
        return [];
      }
      return this.laborUsers.filter(user => 
        user.doctor_role === this.visitFormData.department
      );
    }
  },
  watch: {
    'visitFormData.department'() {
      // Clear doctor selection when department changes
      this.visitFormData.doctor = '';
    }
  },
  mounted() {
    this.fetchPatientDetails();
    this.fetchPatientVisits();
    this.fetchLaborUsers();
  },
  methods: {
    async fetchPatientDetails() {
      this.loading = true;
      this.error = '';
      
      try {
        const patientId = this.$route.params.id;
        const token = localStorage.getItem('auth_token');
        const response = await axios.get(`/api/patients/${patientId}`, {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        this.patient = response.data;
      } catch (error) {
        console.error('Failed to fetch patient:', error);
        this.error = error.response?.data?.message || 'პაციენტის ჩატვირთვა ვერ მოხერხდა';
      } finally {
        this.loading = false;
      }
    },
    async fetchPatientVisits() {
      try {
        const patientId = this.$route.params.id;
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/visits', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        
        // Filter visits for this patient
        this.visits = response.data.filter(visit => visit.patient_id == patientId)
          .sort((a, b) => new Date(b.date) - new Date(a.date));
      } catch (error) {
        console.error('Failed to fetch visits:', error);
      }
    },
    async fetchLaborUsers() {
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/users', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        
        // Filter users with LABOR role
        this.laborUsers = response.data.filter(user => user.role === 'LABOR')
          .sort((a, b) => {
            // Sort by last name, then first name
            const nameA = `${a.last_name} ${a.first_name}`;
            const nameB = `${b.last_name} ${b.first_name}`;
            return nameA.localeCompare(nameB);
          });
      } catch (error) {
        console.error('Failed to fetch labor users:', error);
        this.laborUsers = [];
      }
    },
    goBack() {
      this.$router.push('/patients');
    },
    openVisitModal() {
      this.isVisitModalOpen = true;
      this.error = '';
      const today = new Date().toISOString().split('T')[0];
      const now = new Date();
      const defaultTime = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
      this.visitFormData = {
        date: today,
        department: '',
        doctor: '',
        time: defaultTime,
        notes: ''
      };
    },
    closeVisitModal() {
      this.isVisitModalOpen = false;
      this.error = '';
    },
    openPaymentModal(visit) {
      this.isPaymentModalOpen = true;
      this.paymentError = '';
      const today = new Date().toISOString().split('T')[0];
      this.paymentFormData = {
        service: '',
        doctor: visit.doctorName || '',
        amount: '',
        payment_date: today,
        payment_method: ''
      };
    },
    closePaymentModal() {
      this.isPaymentModalOpen = false;
      this.paymentError = '';
    },
    async submitPayment() {
      this.submittingPayment = true;
      this.paymentError = '';

      try {
        if (!this.paymentFormData.service || !this.paymentFormData.amount || 
            !this.paymentFormData.payment_date || !this.paymentFormData.payment_method) {
          this.paymentError = 'გთხოვთ შეავსოთ ყველა აუცილებელი ველი';
          this.submittingPayment = false;
          return;
        }

        const token = localStorage.getItem('auth_token');
        
        const paymentData = {
          patient_id: this.$route.params.id,
          service: this.paymentFormData.service,
          doctor: this.paymentFormData.doctor || null,
          amount: parseFloat(this.paymentFormData.amount),
          payment_date: this.paymentFormData.payment_date,
          payment_method: this.paymentFormData.payment_method,
          status: 'paid'
        };

        await axios.post('/api/payments', paymentData, {
          headers: { 'Authorization': `Bearer ${token}` }
        });

        this.closePaymentModal();
        this.toastStore.success('გადახდა წარმატებით შეიქმნა');
      } catch (error) {
        console.error('Failed to create payment:', error);
        const errorMessage = error.response?.data?.message || error.response?.data?.error || 'შეცდომა მოხდა გადახდის შექმნისას';
        this.paymentError = errorMessage;
        this.toastStore.error(errorMessage);
      } finally {
        this.submittingPayment = false;
      }
    },
    async submitVisit() {
      this.submitting = true;
      this.error = '';

      try {
        if (!this.visitFormData.date || !this.visitFormData.department || 
            !this.visitFormData.doctor || !this.visitFormData.time) {
          this.error = 'გთხოვთ შეავსოთ ყველა აუცილებელი ველი';
          this.submitting = false;
          return;
        }

        const token = localStorage.getItem('auth_token');
        
        const visitData = {
          patient_id: this.$route.params.id,
          doctor_name: this.visitFormData.doctor,
          department: this.visitFormData.department,
          date: this.visitFormData.date,
          time: this.visitFormData.time,
          status: 'PENDING',
          notes: this.visitFormData.notes || ''
        };

        await axios.post('/api/visits', visitData, {
          headers: { 'Authorization': `Bearer ${token}` }
        });

        // Refresh visits list
        await this.fetchPatientVisits();
        
        this.closeVisitModal();
        this.toastStore.success('ვიზიტი წარმატებით შეიქმნა');
      } catch (error) {
        console.error('Failed to create visit:', error);
        const errorMessage = error.response?.data?.message || error.response?.data?.error || 'შეცდომა მოხდა ვიზიტის შექმნისას';
        this.error = errorMessage;
        this.toastStore.error(errorMessage);
      } finally {
        this.submitting = false;
      }
    },
    formatDate(dateString) {
      if (!dateString) return '-';
      const date = new Date(dateString);
      return date.toLocaleDateString('ka-GE', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      });
    },
    getRoleLabel(role) {
      const labels = {
        'PATIENT': 'პაციენტი',
        'DOCTOR': 'ექიმი',
        'ADMIN': 'ადმინი',
        'LABOR': 'ლაბორანტი'
      };
      return labels[role] || role;
    },
    getRoleBadgeClass(role) {
      const classes = {
        'PATIENT': 'px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'DOCTOR': 'px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'ADMIN': 'px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
        'LABOR': 'px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200'
      };
      return classes[role] || 'px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
    },
    getStatusLabel(status) {
      const statuses = {
        'PENDING': 'მოლოდინში',
        'CONFIRMED': 'დადასტურებული',
        'CANCELLED': 'გაუქმებული',
        'COMPLETED': 'დასრულებული',
        pending: 'მოლოდინში',
        confirmed: 'დადასტურებული',
        cancelled: 'გაუქმებული',
        completed: 'დასრულებული'
      };
      return statuses[status] || status;
    },
    getStatusBadgeClass(status) {
      const statusUpper = status?.toUpperCase();
      const classes = {
        'PENDING': 'px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'CONFIRMED': 'px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'CANCELLED': 'px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        'COMPLETED': 'px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
      };
      return classes[statusUpper] || 'px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
    },
    printPatientInfo() {
      // Create print window content
      const printWindow = window.open('', '_blank');
      
      const printContent = `
          <!DOCTYPE html>
          <html>
            <head>
              <title>პაციენტის ინფორმაცია - ${this.patient.first_name} ${this.patient.last_name}</title>
              <style>
                @page {
                  size: A4;
                  margin: 2cm;
                }
                body {
                  font-family: Arial, sans-serif;
                  font-size: 12px;
                  line-height: 1.6;
                  color: #000;
                  margin: 0;
                  padding: 20px;
                }
                .header {
                  text-align: center;
                  border-bottom: 2px solid #000;
                  padding-bottom: 20px;
                  margin-bottom: 30px;
                }
                .header h1 {
                  margin: 0;
                  font-size: 24px;
                  color: #000;
                }
                .section {
                  margin-bottom: 30px;
                }
                .section-title {
                  font-size: 18px;
                  font-weight: bold;
                  margin-bottom: 15px;
                  border-bottom: 1px solid #ccc;
                  padding-bottom: 5px;
                }
                .info-grid {
                  display: grid;
                  grid-template-columns: 1fr 2fr;
                  gap: 10px;
                  margin-bottom: 15px;
                }
                .info-label {
                  font-weight: bold;
                  color: #333;
                }
                .info-value {
                  color: #000;
                }
                table {
                  width: 100%;
                  border-collapse: collapse;
                  margin-top: 15px;
                }
                th, td {
                  border: 1px solid #ccc;
                  padding: 8px;
                  text-align: left;
                }
                th {
                  background-color: #f0f0f0;
                  font-weight: bold;
                }
                tr:nth-child(even) {
                  background-color: #f9f9f9;
                }
                .no-visits {
                  text-align: center;
                  padding: 20px;
                  color: #666;
                  font-style: italic;
                }
                .print-date {
                  text-align: right;
                  color: #666;
                  font-size: 10px;
                  margin-top: 30px;
                  padding-top: 20px;
                  border-top: 1px solid #ccc;
                }
              </style>
            </head>
            <body>
              <div class="header">
                <h1>პაციენტის ინფორმაცია</h1>
              </div>
              
              <div class="section">
                <div class="section-title">პაციენტის საბაზო მონაცემები</div>
                <div class="info-grid">
                  <div class="info-label">სახელი:</div>
                  <div class="info-value">${this.patient.first_name} ${this.patient.last_name}</div>
                  
                  <div class="info-label">ID:</div>
                  <div class="info-value">${this.patient.id}</div>
                  
                  <div class="info-label">ელ. ფოსტა:</div>
                  <div class="info-value">${this.patient.email}</div>
                  
                  ${this.patient.phone ? `
                  <div class="info-label">ტელეფონი:</div>
                  <div class="info-value">${this.patient.phone}</div>
                  ` : ''}
                  
                  <div class="info-label">როლი:</div>
                  <div class="info-value">${this.getRoleLabel(this.patient.role)}</div>
                  
                  <div class="info-label">რეგისტრაციის თარიღი:</div>
                  <div class="info-value">${this.formatDate(this.patient.created_at)}</div>
                </div>
              </div>
              
              <div class="section">
                <div class="section-title">ვიზიტების ისტორია</div>
                ${this.visits.length > 0 ? `
                  <table>
                    <thead>
                      <tr>
                        <th>თარიღი</th>
                        <th>დრო</th>
                        <th>ექიმი</th>
                        <th>განყოფილება</th>
                        <th>სტატუსი</th>
                        <th>შენიშვნა</th>
                      </tr>
                    </thead>
                    <tbody>
                      ${this.visits.map(visit => {
                        const visitDate = new Date(visit.date);
                        const formattedDate = visitDate.toLocaleDateString('ka-GE', { 
                          year: 'numeric', 
                          month: '2-digit', 
                          day: '2-digit' 
                        });
                        return `
                          <tr>
                            <td>${formattedDate}</td>
                            <td>${visit.time}</td>
                            <td>${visit.doctorName || '-'}</td>
                            <td>${visit.department || '-'}</td>
                            <td>${this.getStatusLabel(visit.status)}</td>
                            <td>${visit.notes || '-'}</td>
                          </tr>
                        `;
                      }).join('')}
                    </tbody>
                  </table>
                ` : `
                  <div class="no-visits">ვიზიტები არ მოიძებნა</div>
                `}
              </div>
              
              <div class="print-date">
                დაბეჭდილია: ${new Date().toLocaleDateString('ka-GE', { 
                  year: 'numeric', 
                  month: 'long', 
                  day: 'numeric',
                  hour: '2-digit',
                  minute: '2-digit'
                })}
              </div>
            </body>
          </html>
      `;
      
      printWindow.document.write(printContent);
      printWindow.document.close();
      
      // Wait for content to load, then print
      setTimeout(() => {
        printWindow.print();
      }, 250);
    }
  }
};
</script>

