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
        <PatientInfoCard 
          :patient="patient" 
          @print="printPatientInfo"
          @add-visit="openVisitModal"
        />

        <VisitHistory 
          :visits="visits" 
          :payments="payments"
          @add-payment="openPaymentModal"
        />
      </div>

      <!-- Error State -->
      <div v-else class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">პაციენტი არ მოიძებნა</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ error || 'შეცდომა მოხდა პაციენტის ჩატვირთვისას' }}</p>
      </div>

      <!-- Modals -->
      <VisitModal
        :is-open="isVisitModalOpen"
        :patient="patient"
        :labor-users="laborUsers"
        :services="services"
        @close="closeVisitModal"
        @submit="handleVisitSubmit"
      />

      <PaymentModal
        :is-open="isPaymentModalOpen"
        :patient="patient"
        :form-data="paymentFormData"
        @close="closePaymentModal"
        @submit="handlePaymentSubmit"
      />
    </main>
  </div>
</template>

<script>
import { useToastStore } from '../stores/toast';
import { patientService } from '../services/patientService';
import { visitService } from '../services/visitService';
import { paymentService } from '../services/paymentService';
import { userService } from '../services/userService';
import { serviceService } from '../services/serviceService';
import Navbar from './Navbar.vue';
import Button from './ui/Button.vue';
import PatientInfoCard from './PatientInfoCard.vue';
import VisitHistory from './VisitHistory.vue';
import VisitModal from './VisitModal.vue';
import PaymentModal from './PaymentModal.vue';

export default {
  name: 'PatientDetails',
  components: {
    Navbar,
    Button,
    PatientInfoCard,
    VisitHistory,
    VisitModal,
    PaymentModal
  },
  setup() {
    const toastStore = useToastStore();
    return { toastStore };
  },
  data() {
    return {
      patient: null,
      visits: [],
      payments: [],
      loading: true,
      isVisitModalOpen: false,
      isPaymentModalOpen: false,
      error: '',
      laborUsers: [],
      doctorUsers: [],
      services: [],
      paymentFormData: {
        service: '',
        doctor_id: '',
        doctor: '',
        department: '',
        appointment_id: '',
        amount: '',
        payment_date: '',
        payment_method: ''
      }
    };
  },
  mounted() {
    this.loadData();
  },
  methods: {
    async loadData() {
      await Promise.all([
        this.fetchPatientDetails(),
        this.fetchPatientVisits(),
        this.fetchPayments(),
        this.fetchLaborUsers(),
        this.fetchDoctors(),
        this.fetchServices()
      ]);
    },
    async fetchPatientDetails() {
      this.loading = true;
      this.error = '';
      
      try {
        const patientId = this.$route.params.id;
        this.patient = await patientService.getPatient(patientId);
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
        this.visits = await patientService.getPatientVisits(patientId);
      } catch (error) {
        console.error('Failed to fetch visits:', error);
        this.visits = [];
      }
    },
    async fetchPayments() {
      try {
        const patientId = this.$route.params.id;
        this.payments = await patientService.getPatientPayments(patientId);
      } catch (error) {
        console.error('Failed to fetch payments:', error);
        this.payments = [];
      }
    },
    async fetchLaborUsers() {
      try {
        this.laborUsers = await userService.getLaborUsers();
      } catch (error) {
        console.error('Failed to fetch labor users:', error);
        this.laborUsers = [];
      }
    },
    async fetchDoctors() {
      try {
        this.doctorUsers = await userService.getDoctors();
      } catch (error) {
        console.error('Failed to fetch doctors:', error);
        this.doctorUsers = [];
      }
    },
    async fetchServices() {
      try {
        this.services = await serviceService.getServices();
      } catch (error) {
        console.error('Failed to fetch services:', error);
        this.services = [];
      }
    },
    openVisitModal() {
      this.isVisitModalOpen = true;
    },
    closeVisitModal() {
      this.isVisitModalOpen = false;
    },
    async handleVisitSubmit(visitData) {
      try {
        const visitPayload = {
          patient_id: this.$route.params.id,
          doctor_id: visitData.doctor_id,
          doctor_name: visitData.doctor,
          department: visitData.department,
          service: visitData.service,
          service_id: visitData.service_id,
          date: visitData.date,
          time: visitData.time,
          status: 'PENDING',
          notes: visitData.notes || ''
        };

        await visitService.createVisit(visitPayload);
        
        await this.fetchPatientVisits();
        this.closeVisitModal();
        this.toastStore.success('ვიზიტი წარმატებით შეიქმნა');
      } catch (error) {
        console.error('Failed to create visit:', error);
        const errorMessage = error.response?.data?.message || error.response?.data?.error || 'შეცდომა მოხდა ვიზიტის შექმნისას';
        this.toastStore.error(errorMessage);
        throw error;
      }
    },
    async openPaymentModal(visit) {
      this.isPaymentModalOpen = true;
      const today = new Date().toISOString().split('T')[0];
      
      // Use visit's doctor_id directly if available, otherwise try to find by name
      let doctorId = visit?.doctor_id || null;
      
      // If doctor_id is not available, try to find it from doctor name
      if (!doctorId && visit?.doctorName) {
        if (this.doctorUsers.length === 0) {
          await this.fetchDoctors();
        }
        const doctor = this.doctorUsers.find(d => 
          `${d.first_name} ${d.last_name}`.trim().toLowerCase() === visit.doctorName.trim().toLowerCase()
        );
        if (doctor) {
          doctorId = doctor.id;
        }
      }
      
      // Fetch services if not already loaded
      if (this.services.length === 0) {
        await this.fetchServices();
      }
      
      // Get service price if service is selected
      let servicePrice = '';
      if (visit?.service) {
        const selectedService = this.services.find(s => s.name === visit.service || s.id === visit.service_id);
        if (selectedService) {
          servicePrice = selectedService.price;
        }
      }

      this.paymentFormData = {
        service: visit?.service || '',
        doctor_id: doctorId,
        doctor: visit?.doctorName || '',
        department: visit?.department || '',
        appointment_id: visit?.id || '',
        amount: servicePrice,
        payment_date: today,
        payment_method: ''
      };
    },
    closePaymentModal() {
      this.isPaymentModalOpen = false;
      this.paymentFormData = {
        service: '',
        doctor_id: '',
        doctor: '',
        department: '',
        appointment_id: '',
        amount: '',
        payment_date: '',
        payment_method: ''
      };
    },
    async handlePaymentSubmit(paymentData) {
      try {
        // If doctor_id is not set, try to find it from doctor name
        if (!paymentData.doctor_id && paymentData.doctor) {
          if (this.doctorUsers.length === 0) {
            await this.fetchDoctors();
          }
          const doctor = this.doctorUsers.find(d => 
            `${d.first_name} ${d.last_name}`.trim().toLowerCase() === paymentData.doctor.trim().toLowerCase()
          );
          if (doctor) {
            paymentData.doctor_id = doctor.id;
          }
        }

        // Get doctor name from selected doctor_id
        const selectedDoctor = this.doctorUsers.find(d => d.id === Number(paymentData.doctor_id));
        const doctorName = selectedDoctor ? `${selectedDoctor.first_name} ${selectedDoctor.last_name}` : paymentData.doctor;
        
        const paymentPayload = {
          patient_id: this.$route.params.id,
          user_id: paymentData.doctor_id || null,
          appointment_id: paymentData.appointment_id || null,
          service: paymentData.service,
          doctor: doctorName || null,
          amount: parseFloat(paymentData.amount),
          payment_date: paymentData.payment_date,
          payment_method: paymentData.payment_method,
          status: 'paid'
        };

        await paymentService.createPayment(paymentPayload);

        this.closePaymentModal();
        await this.fetchPayments();
        this.toastStore.success('გადახდა წარმატებით შეიქმნა');
      } catch (error) {
        console.error('Failed to create payment:', error);
        const errorMessage = error.response?.data?.message || error.response?.data?.error || 'შეცდომა მოხდა გადახდის შექმნისას';
        this.toastStore.error(errorMessage);
        throw error;
      }
    },
    goBack() {
      this.$router.push('/patients');
    },
    printPatientInfo() {
      const printWindow = window.open('', '_blank');
      if (!printWindow) return;

      const printContent = `
        <!DOCTYPE html>
        <html>
          <head>
            <title>პაციენტის ინფორმაცია</title>
            <style>
              body {
                font-family: Arial, sans-serif;
                padding: 20px;
                color: #333;
              }
              .header {
                text-align: center;
                margin-bottom: 30px;
                border-bottom: 2px solid #333;
                padding-bottom: 20px;
              }
              .header h1 {
                margin: 0;
                font-size: 24px;
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
                grid-template-columns: 150px 1fr;
                gap: 10px;
              }
              .info-label {
                font-weight: bold;
              }
              .info-value {
                color: #666;
              }
              table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 15px;
              }
              th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
              }
              th {
                background-color: #f2f2f2;
                font-weight: bold;
              }
              .no-visits {
                text-align: center;
                padding: 20px;
                color: #999;
              }
              .print-date {
                text-align: center;
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px solid #ccc;
                font-size: 12px;
                color: #666;
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
      
      setTimeout(() => {
        printWindow.print();
      }, 250);
    },
    formatDate(date) {
      if (!date) return '-';
      const d = new Date(date);
      return d.toLocaleDateString('ka-GE', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      });
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
    }
  }
};
</script>
