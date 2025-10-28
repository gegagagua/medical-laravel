<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <Navbar />
    <main class="max-w-[1485px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">სულ პაციენტები</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ patients.length }}</p>
            </div>
            <div className="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
              <svg className="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">აქტიური</p>
            <p class="text-3xl font-bold text-green-600">{{ activePatients }}</p>
          </div>
          <div className="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
            <svg className="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">მამრობითი</p>
            <p class="text-3xl font-bold text-blue-600">{{ malePatients }}</p>
          </div>
          <div className="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
            <svg className="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">მდედრობითი</p>
            <p class="text-3xl font-bold text-pink-600">{{ femalePatients }}</p>
          </div>
          <div className="w-12 h-12 bg-pink-100 dark:bg-pink-900 rounded-lg flex items-center justify-center">
            <svg className="w-6 h-6 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
        <div class="mb-6 flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
          <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">პაციენტების სია</h2>
            <p class="text-gray-600 dark:text-gray-400">ყველა დარეგისტრირებული პაციენტის სრული ინფორმაცია</p>
          </div>
          <div class="flex gap-2">
            <Button variant="secondary" @click="handleImport" class="flex-1 md:flex-initial">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
              </svg>
              იმპორტი
            </Button>
            <Button variant="primary" @click="openModal" class="flex-1 md:flex-initial">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              ახალი პაციენტი
            </Button>
          </div>
        </div>

        <Table
          :data="patients"
          :columns="columns"
          :page-size="10"
          :searchable="true"
          search-placeholder="მოძებნეთ პაციენტი (სახელი, პ/ნ, ტელეფონი)..."
          empty-message="პაციენტები არ მოიძებნა"
          :loading="loading"
        />
      </div>

             <!-- Add Visit Modal -->
             <Modal :isOpen="isVisitModalOpen" title="ახალი ვიზიტის დამატება" @close="closeVisitModal">
               <form @submit.prevent="submitVisit" class="space-y-4">
                 <div v-if="visitFormData.patient_name" class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                   <p class="text-sm font-medium text-blue-900 dark:text-blue-100">
                     პაციენტი: {{ visitFormData.patient_name }}
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
                   <input
                     v-model="visitFormData.department"
                     type="text"
                     placeholder="მაგ: ენდოკრინოლოგია"
                     class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                     required
                   />
                 </div>

                 <div>
                   <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                     ექიმი / მედპერ. *
                   </label>
                   <input
                     v-model="visitFormData.doctor"
                     type="text"
                     placeholder="მაგ: ლომთაძე, ნინო"
                     class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                     required
                   />
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
                   <Button type="submit" variant="primary" :full-width="true" :disabled="loading">
                     {{ loading ? 'შექმნა...' : 'ვიზიტის შექმნა' }}
                   </Button>
                 </div>
               </form>
             </Modal>

             <!-- Add Patient Modal -->
             <Modal :isOpen="isModalOpen" title="ახალი პაციენტის დამატება" @close="closeModal">
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <Input
              v-model="formData.first_name"
              label="სახელი"
              placeholder="გაიყვანე სახელი"
              required
            />
            <Input
              v-model="formData.last_name"
              label="გვარი"
              placeholder="გაიყვანე გვარი"
              required
            />
          </div>
          
          <Input
            v-model="formData.id_number"
            label="პირადი ნომერი"
            placeholder="01001012345"
            required
          />
          
          <div class="grid grid-cols-2 gap-4">
            <Input
              v-model="formData.age"
              label="ასაკი"
              type="number"
              placeholder="18"
              required
            />
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                სქესი
              </label>
              <select
                v-model="formData.gender"
                class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                required
              >
                <option value="male">მამრობითი</option>
                <option value="female">მდედრობითი</option>
              </select>
            </div>
          </div>

          <Input
            v-model="formData.phone"
            label="ტელეფონი"
            type="tel"
            placeholder="+995 555 123 456"
          />

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              სტატუსი
            </label>
            <select
              v-model="formData.status"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
              required
            >
              <option value="active">აქტიური</option>
              <option value="inactive">არააქტიური</option>
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
              {{ loading ? 'იტვირთება...' : 'დამატება' }}
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
  name: 'Patients',
  components: {
    Navbar,
    Table,
    Button,
    Modal,
    Input
  },
  data() {
      return {
        patients: [],
        loading: true,
        isModalOpen: false,
        isVisitModalOpen: false,
        error: '',
        formData: {
          first_name: '',
          last_name: '',
          id_number: '',
          age: '',
          gender: 'male',
          phone: '',
          diagnosis: '',
          status: 'active'
        },
        visitFormData: {
          patient_id: '',
          date: '',
          department: '',
          doctor: '',
          time: '',
          notes: ''
        },
      columns: [
        {
          key: 'id',
          label: 'ID',
          sortable: true,
          width: '80px'
        },
        {
          key: 'fullName',
          label: 'სახელი გვარი',
          sortable: true,
          filterable: true,
          render: (value) => `<span class="font-medium">${value}</span>`
        },
        {
          key: 'idNumber',
          label: 'პირადი ნომერი',
          filterable: true,
          render: (value) => `<span class="font-mono text-sm">${value}</span>`
        },
        {
          key: 'age',
          label: 'ასაკი',
          sortable: true,
          width: '100px',
          render: (value) => `${value} წ.`
        },
        {
          key: 'gender',
          label: 'სქესი',
          sortable: true,
          filterable: true,
          width: '120px',
          render: (value) => {
            const isMale = value === 'male';
            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${isMale ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200'}">${isMale ? 'მამრობითი' : 'მდედრობითი'}</span>`;
          }
        },
        {
          key: 'phone',
          label: 'ტელეფონი',
          filterable: true,
          render: (value) => `<a href="tel:${value}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">${value}</a>`
        },
        {
          key: 'status',
          label: 'სტატუსი',
          sortable: true,
          filterable: true,
          width: '120px',
          render: (value) => {
            const isActive = value === 'active';
            return `<span class="px-3 py-1 rounded-full text-xs font-medium ${isActive ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'}">${isActive ? 'აქტიური' : 'არააქტიური'}</span>`;
          }
        },
        {
          key: 'lastVisit',
          label: 'ბოლო ვიზიტი',
          sortable: true,
          render: (value) => {
            const date = new Date(value);
            return date.toLocaleDateString('ka-GE');
          }
        },
        {
          key: 'actions',
          label: 'მოქმედება',
          width: '120px',
          render: (_, row) => `
            <button 
              onclick="window.patientsComponent?.openVisitModal(${row.id})"
              class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs font-medium transition">
              ახალი ვიზიტი
            </button>
          `
        }
      ]
    };
  },
  computed: {
    activePatients() {
      return this.patients.filter(p => p.status === 'active').length;
    },
    malePatients() {
      return this.patients.filter(p => p.gender === 'male').length;
    },
    femalePatients() {
      return this.patients.filter(p => p.gender === 'female').length;
    }
  },
  mounted() {
    this.fetchPatients();
    // Make component accessible for inline button handlers
    window.patientsComponent = this;
  },
  methods: {
    async fetchPatients() {
      this.patients = [
          { id: 1, fullName: 'გიორგი მამულაშვილი', idNumber: '01001012345', age: 35, gender: 'male', phone: '+995 555 123 456', status: 'active', diagnosis: 'რუტინული შემოწმება', lastVisit: '2025-01-15T10:30:00' },
          { id: 2, fullName: 'ნინო ბერიძე', idNumber: '01001098765', age: 28, gender: 'female', phone: '+995 555 234 567', status: 'active', diagnosis: 'გულის შემოწმება', lastVisit: '2025-01-14T15:20:00' },
          { id: 3, fullName: 'დავით გელაშვილი', idNumber: '01001055555', age: 45, gender: 'male', phone: '+995 555 345 678', status: 'active', diagnosis: 'დიაბეტის კონტროლი', lastVisit: '2025-01-10T09:15:00' },
          { id: 4, fullName: 'მარიამ ქავთარაძე', idNumber: '01001044444', age: 52, gender: 'female', phone: '+995 555 456 789', status: 'inactive', diagnosis: 'არტრიტი', lastVisit: '2024-12-20T14:45:00' },
          { id: 5, fullName: 'ლუკა ლობჟანიძე', idNumber: '01001033333', age: 22, gender: 'male', phone: '+995 555 567 890', status: 'active', diagnosis: 'სპორტული ტრავმა', lastVisit: '2025-01-12T11:30:00' },
          { id: 6, fullName: 'თამარ შენგელია', idNumber: '01001022222', age: 38, gender: 'female', phone: '+995 555 678 901', status: 'active', diagnosis: 'ანემია', lastVisit: '2025-01-13T16:00:00' }
        ];
      this.loading = false;
      // try {
      //   const token = localStorage.getItem('auth_token');
      //   const response = await axios.get('/api/patients', {
      //     headers: { 'Authorization': `Bearer ${token}` }
      //   });
      //   this.patients = response.data;
      // } catch (error) {
      //   this.patients = [
      //     { id: 1, fullName: 'გიორგი მამულაშვილი', idNumber: '01001012345', age: 35, gender: 'male', phone: '+995 555 123 456', status: 'active', diagnosis: 'რუტინული შემოწმება', lastVisit: '2025-01-15T10:30:00' },
      //     { id: 2, fullName: 'ნინო ბერიძე', idNumber: '01001098765', age: 28, gender: 'female', phone: '+995 555 234 567', status: 'active', diagnosis: 'გულის შემოწმება', lastVisit: '2025-01-14T15:20:00' },
      //     { id: 3, fullName: 'დავით გელაშვილი', idNumber: '01001055555', age: 45, gender: 'male', phone: '+995 555 345 678', status: 'active', diagnosis: 'დიაბეტის კონტროლი', lastVisit: '2025-01-10T09:15:00' },
      //     { id: 4, fullName: 'მარიამ ქავთარაძე', idNumber: '01001044444', age: 52, gender: 'female', phone: '+995 555 456 789', status: 'inactive', diagnosis: 'არტრიტი', lastVisit: '2024-12-20T14:45:00' },
      //     { id: 5, fullName: 'ლუკა ლობჟანიძე', idNumber: '01001033333', age: 22, gender: 'male', phone: '+995 555 567 890', status: 'active', diagnosis: 'სპორტული ტრავმა', lastVisit: '2025-01-12T11:30:00' },
      //     { id: 6, fullName: 'თამარ შენგელია', idNumber: '01001022222', age: 38, gender: 'female', phone: '+995 555 678 901', status: 'active', diagnosis: 'ანემია', lastVisit: '2025-01-13T16:00:00' }
      //   ];
      // } finally {
      //   this.loading = false;
      // }
    },
    openModal() {
      this.isModalOpen = true;
      this.error = '';
      this.formData = {
        first_name: '',
        last_name: '',
        id_number: '',
        age: '',
        gender: 'male',
        phone: '',
        diagnosis: '',
        status: 'active'
      };
    },
    closeModal() {
      this.isModalOpen = false;
      this.error = '';
    },
    async handleSubmit() {
      this.loading = true;
      this.error = '';

      try {
        const token = localStorage.getItem('auth_token');
        
        const submitData = {
          ...this.formData,
          fullName: `${this.formData.first_name} ${this.formData.last_name}`,
          idNumber: this.formData.id_number
        };
        
        await axios.post('/api/patients', submitData, {
          headers: { 'Authorization': `Bearer ${token}` }
        });

        await this.fetchPatients();
        this.closeModal();
      } catch (error) {
        this.error = error.response?.data?.message || 'შეცდომა მოხდა პაციენტის დამატებისას';
      } finally {
        this.loading = false;
      }
    },
    handleImport() {
      alert('იმპორტის ფუნქცია მალე დაემატება');
    },
    openVisitModal(patientId) {
      const patient = this.patients.find(p => p.id === patientId);
      if (!patient) return;

      // Set current date
      const today = new Date();
      const dateStr = today.toISOString().split('T')[0];
      
      this.visitFormData = {
        patient_id: patientId,
        patient_name: patient.fullName,
        date: dateStr,
        department: '',
        doctor: '',
        time: '',
        notes: ''
      };
      this.isVisitModalOpen = true;
      this.error = '';
    },
    closeVisitModal() {
      this.isVisitModalOpen = false;
      this.error = '';
      this.visitFormData = {
        patient_id: '',
        date: '',
        department: '',
        doctor: '',
        time: '',
        notes: ''
      };
    },
    async createVisit(patientId) {
      const patient = this.patients.find(p => p.id === patientId);
      if (!patient) return;

      this.openVisitModal(patientId);
    },
    async submitVisit() {
      this.loading = true;
      this.error = '';

      try {
        // Validate required fields
        if (!this.visitFormData.date || !this.visitFormData.department || 
            !this.visitFormData.doctor || !this.visitFormData.time) {
          this.error = 'გთხოვთ შეავსოთ ყველა აუცილებელი ველი';
          this.loading = false;
          return;
        }

        const token = localStorage.getItem('auth_token');
        const patient = this.patients.find(p => p.id === this.visitFormData.patient_id);
        
        const appointmentData = {
          patient_id: this.visitFormData.patient_id,
          patient_name: patient ? patient.fullName : '',
          doctor_name: this.visitFormData.doctor,
          department: this.visitFormData.department,
          date: this.visitFormData.date,
          time: this.visitFormData.time,
          status: 'pending',
          notes: this.visitFormData.notes || ''
        };

        await axios.post('/api/appointments', appointmentData, {
          headers: { 'Authorization': `Bearer ${token}` }
        });

        this.closeVisitModal();
        this.$router.push('/visits');
      } catch (error) {
        console.error('Failed to create visit:', error);
        this.error = error.response?.data?.message || 'შეცდომა მოხდა ვიზიტის შექმნისას';
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>
