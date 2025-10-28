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
            variant="primary"
            @click="createNewVisit"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            ახალი ვიზიტი
          </Button>
        </div>

        <Table
          :data="appointments"
          :columns="columns"
          :page-size="10"
          :searchable="true"
          search-placeholder="მოძებნეთ ვიზიტი (პაციენტი, ექიმი, შენიშვნა)..."
          empty-message="ვიზიტები არ მოიძებნა"
          :loading="loading"
        />
      </div>
    </main>
  </div>
</template>

<script>
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
  data() {
    return {
      appointments: [],
      loading: true,
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
          render: (value) => `<span class="text-sm text-gray-600 dark:text-gray-400">${value}</span>`
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
          width: '140px',
          render: (value) => {
            const statuses = {
              pending: { label: 'მოლოდინში', class: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' },
              confirmed: { label: 'დადასტურებული', class: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' },
              cancelled: { label: 'გაუქმებული', class: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' },
              completed: { label: 'დასრულებული', class: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }
            };
            const status = statuses[value];
            return `<span class="px-3 py-1 rounded-full text-xs font-medium ${status.class}">${status.label}</span>`;
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
    totalAppointments() {
      return this.appointments.length;
    },
    confirmedCount() {
      return this.appointments.filter(a => a.status === 'confirmed').length;
    },
    pendingCount() {
      return this.appointments.filter(a => a.status === 'pending').length;
    },
    todayAppointments() {
      const today = new Date().toISOString().split('T')[0];
      return this.appointments.filter(a => a.date === today).length;
    }
  },
  mounted() {
    this.fetchAppointments();
  },
  methods: {
    async fetchAppointments() {
      this.loading = true;
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/appointments', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        this.appointments = response.data;
      } catch (error) {
        // Mock data
        this.appointments = [
          {
            id: 1,
            patientName: 'გიორგი მამულაშვილი',
            patientId: 1,
            doctorName: 'დრ. ნინო ბერიძე',
            date: '2025-01-20',
            time: '10:00',
            status: 'confirmed',
            notes: 'რუტინული შემოწმება',
            createdAt: '2025-01-15T10:30:00'
          },
          {
            id: 2,
            patientName: 'ნინო ბერიძე',
            patientId: 2,
            doctorName: 'დრ. დავით გელაშვილი',
            date: '2025-01-21',
            time: '14:30',
            status: 'pending',
            notes: 'გულის შემოწმება',
            createdAt: '2025-01-14T15:20:00'
          },
          {
            id: 3,
            patientName: 'დავით გელაშვილი',
            patientId: 3,
            doctorName: 'დრ. მარიამ ქავთარაძე',
            date: '2025-01-19',
            time: '09:00',
            status: 'confirmed',
            notes: 'დიაბეტის კონტროლი',
            createdAt: '2025-01-13T09:15:00'
          },
          {
            id: 4,
            patientName: 'მარიამ ქავთარაძე',
            patientId: 4,
            doctorName: 'დრ. ლუკა ლობჟანიძე',
            date: '2025-01-18',
            time: '16:00',
            status: 'completed',
            notes: 'არტრიტის მკურნალობა',
            createdAt: '2025-01-12T14:45:00'
          },
          {
            id: 5,
            patientName: 'ლუკა ლობჟანიძე',
            patientId: 5,
            doctorName: 'დრ. თამარ შენგელია',
            date: '2025-01-22',
            time: '11:30',
            status: 'pending',
            notes: 'სპორტული ტრავმა',
            createdAt: '2025-01-11T11:30:00'
          },
          {
            id: 6,
            patientName: 'თამარ შენგელია',
            patientId: 6,
            doctorName: 'დრ. ნინო ბერიძე',
            date: '2025-01-17',
            time: '15:00',
            status: 'cancelled',
            notes: 'ანემიის კონსულტაცია',
            createdAt: '2025-01-10T16:00:00'
          },
          {
            id: 7,
            patientName: 'გიორგი მამულაშვილი',
            patientId: 1,
            doctorName: 'დრ. დავით გელაშვილი',
            date: '2025-01-23',
            time: '12:00',
            status: 'confirmed',
            notes: 'კონტროლი',
            createdAt: '2025-01-09T10:00:00'
          }
        ];
      } finally {
        this.loading = false;
      }
    },
    createNewVisit() {
      alert('ახალი ვიზიტის დამატება - დაბრუნდით პაციენტების გვერდზე და აირჩიეთ პაციენტი');
    }
  }
};
</script>
