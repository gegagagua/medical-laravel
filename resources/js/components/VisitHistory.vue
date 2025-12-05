<template>
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
        class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition"
      >
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <div class="flex items-center gap-4 mb-2">
              <span class="text-sm font-medium text-gray-900 dark:text-white">
                {{ formatDate(visit.date) }} {{ visit.time }}
              </span>
              <span :class="getStatusBadgeClass(visit.status)">
                {{ getStatusLabel(visit.status) }}
              </span>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
              <p v-if="visit.doctorName">
                <span class="font-medium">ექიმი:</span> {{ visit.doctorName }}
              </p>
              <p v-if="visit.department">
                <span class="font-medium">განყოფილება:</span> {{ visit.department }}
              </p>
              <p v-if="visit.service">
                <span class="font-medium">სერვისი:</span> {{ visit.service }}
              </p>
              <p v-if="visit.notes">
                <span class="font-medium">შენიშვნა:</span> {{ visit.notes }}
              </p>
            </div>
          </div>
          <Button
            v-if="!hasPayment(visit.id)"
            variant="primary"
            size="sm"
            @click="$emit('add-payment', visit)"
          >
            გადახდის დამატება
          </Button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Button from './ui/Button.vue';

export default {
  name: 'VisitHistory',
  components: {
    Button
  },
  props: {
    visits: {
      type: Array,
      default: () => []
    },
    payments: {
      type: Array,
      default: () => []
    }
  },
  emits: ['add-payment'],
  methods: {
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
        'pending': 'მოლოდინში',
        'confirmed': 'დადასტურებული',
        'cancelled': 'გაუქმებული',
        'completed': 'დასრულებული'
      };
      return statuses[status] || status;
    },
    getStatusBadgeClass(status) {
      const statusUpper = status?.toUpperCase();
      const classes = {
        'PENDING': 'px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'CONFIRMED': 'px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'CANCELLED': 'px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        'COMPLETED': 'px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
      };
      return classes[statusUpper] || classes['PENDING'];
    },
    hasPayment(visitId) {
      return this.payments.some(payment => 
        payment.appointment_id == visitId || payment.appointmentId == visitId
      );
    }
  }
};
</script>

