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
                <span class="font-medium">სერვისი:</span> 
                {{ Array.isArray(visit.service) ? visit.service.join(', ') : visit.service }}
              </p>
              <p v-if="visit.notes">
                <span class="font-medium">შენიშვნა:</span> {{ visit.notes }}
              </p>
            </div>
          </div>
          <div class="flex gap-2">
            <Button
              v-if="!hasPayment(visit.id)"
              variant="primary"
              size="sm"
              @click="$emit('add-payment', visit)"
            >
              გადახდის დამატება
            </Button>
            <Button
              variant="secondary"
              size="sm"
              @click="openDeleteModal(visit)"
              class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
            >
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
              წაშლა
            </Button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <Modal :isOpen="isDeleteModalOpen" title="ვიზიტის წაშლა" @close="closeDeleteModal">
      <div class="space-y-4">
        <p class="text-gray-700 dark:text-gray-300">
          დარწმუნებული ხართ, რომ გსურთ ვიზიტის 
          <span class="font-semibold text-gray-900 dark:text-white">
            "{{ getVisitDisplayName() }}"
          </span>
          წაშლა?
        </p>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          ეს მოქმედება შეუქცევადია.
        </p>
        <div class="flex gap-3 pt-4">
          <Button type="button" variant="secondary" @click="closeDeleteModal" :full-width="true">
            გაუქმება
          </Button>
          <Button type="button" variant="primary" @click="confirmDelete" :full-width="true" class="bg-red-600 hover:bg-red-700">
            წაშლა
          </Button>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script>
import Button from './ui/Button.vue';
import Modal from './ui/Modal.vue';

export default {
  name: 'VisitHistory',
  components: {
    Button,
    Modal
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
  emits: ['add-payment', 'delete-visit'],
  data() {
    return {
      isDeleteModalOpen: false,
      visitToDelete: null
    };
  },
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
    },
    isPending(status) {
      const statusUpper = status?.toUpperCase();
      return statusUpper === 'PENDING' || statusUpper === 'pending';
    },
    openDeleteModal(visit) {
      this.visitToDelete = visit;
      this.isDeleteModalOpen = true;
    },
    closeDeleteModal() {
      this.isDeleteModalOpen = false;
      this.visitToDelete = null;
    },
    confirmDelete() {
      if (this.visitToDelete) {
        this.$emit('delete-visit', this.visitToDelete);
        this.closeDeleteModal();
      }
    },
    getVisitDisplayName() {
      if (!this.visitToDelete) return '';
      const dateStr = this.formatDate(this.visitToDelete.date);
      const timeStr = this.visitToDelete.time || '';
      const serviceStr = Array.isArray(this.visitToDelete.service) 
        ? this.visitToDelete.service.join(', ') 
        : (this.visitToDelete.service || '');
      return `${dateStr} ${timeStr}${serviceStr ? ' - ' + serviceStr : ''}`;
    }
  }
};
</script>

