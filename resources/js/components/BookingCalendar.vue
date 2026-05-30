<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <Navbar />

    <main class="max-w-[1485px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
          <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
              ჯავშნების კალენდარი
            </h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm">
              დღის ჯავშნები 08:00 - 20:00 (საათობრივად)
            </p>
          </div>

          <!-- Date controls -->
          <div class="flex flex-wrap items-center gap-2">
            <Button variant="secondary" @click="shiftDate(-1)">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </Button>
            <input
              v-model="selectedDate"
              type="date"
              class="py-2 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <Button variant="secondary" @click="shiftDate(1)">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </Button>
            <Button variant="outline" @click="goToToday">
              დღეს
            </Button>
            <Button variant="primary" @click="openBookingModal(null)">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              ახალი ჯავშანი
            </Button>
          </div>
        </div>

        <!-- Filter row -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              ექიმი / მედპერ.
            </label>
            <select
              v-model="filters.doctorId"
              class="block w-full py-2 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">ყველა ექიმი</option>
              <option v-for="doc in doctors" :key="doc.id" :value="doc.id">
                {{ doc.first_name }} {{ doc.last_name }}
                <template v-if="doc.doctor_role"> — {{ doc.doctor_role }}</template>
              </option>
            </select>
          </div>

          <div class="flex items-end">
            <Button variant="secondary" :full-width="true" @click="clearFilters">
              ფილტრების გასუფთავება
            </Button>
          </div>
        </div>

        <!-- Stats row -->
        <div class="mt-6 grid grid-cols-2 gap-3">
          <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-100 dark:border-blue-900">
            <p class="text-xs text-blue-700 dark:text-blue-300">დღის ჯავშნები</p>
            <p class="text-2xl font-bold text-blue-700 dark:text-blue-200">{{ filteredBookings.length }}</p>
          </div>
          <div class="bg-gray-50 dark:bg-gray-900/40 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <p class="text-xs text-gray-700 dark:text-gray-300">თავისუფალი საათები</p>
            <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
              {{ freeHourCount }} / {{ hours.length }}
            </p>
          </div>
        </div>
      </div>

      <!-- Calendar grid -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
        <div class="mb-4">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            {{ formattedSelectedDate }}
          </h3>
        </div>

        <div v-if="loading" class="py-20 text-center text-gray-500 dark:text-gray-400">
          იტვირთება...
        </div>

        <div v-else class="grid grid-cols-1 gap-2">
          <div
            v-for="hour in hours"
            :key="hour"
            class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden"
          >
            <div class="flex flex-col md:flex-row">
              <!-- Hour label -->
              <div
                class="md:w-28 flex-shrink-0 bg-gray-50 dark:bg-gray-900/40 px-3 py-2 flex items-center justify-between md:justify-center md:flex-col md:py-2 border-b md:border-b-0 md:border-r border-gray-200 dark:border-gray-700 leading-tight"
              >
                <span class="text-lg font-bold text-gray-900 dark:text-white font-mono">{{ hour }}:00</span>
                <span class="text-xs text-gray-500 dark:text-gray-400 md:mt-0.5">
                  {{ bookingsByHour[hour]?.length || 0 }} ჯავშანი
                </span>
              </div>

              <!-- Bookings list -->
              <div class="flex-1 px-3 py-2">
                <div
                  v-if="bookingsByHour[hour] && bookingsByHour[hour].length > 0"
                  class="flex flex-wrap gap-2"
                >
                  <div
                    v-for="b in bookingsByHour[hour]"
                    :key="b.id"
                    class="group flex-1 min-w-[240px] max-w-full bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 border border-blue-200 dark:border-blue-800 rounded-md px-3 py-2 hover:shadow-md transition"
                  >
                    <div class="flex items-start justify-between gap-2">
                      <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1.5 mb-1">
                          <span class="font-mono text-[11px] px-1.5 py-0.5 bg-white dark:bg-gray-800 rounded text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700">
                            {{ formatTime(b.time) }}
                          </span>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white text-sm truncate">
                          {{ b.patientName || '—' }}
                        </p>
                        <p class="text-[12px] text-gray-600 dark:text-gray-300 truncate">
                          <template v-if="b.patientIdNumber">პ/ნ: {{ b.patientIdNumber }}</template>
                          <template v-if="b.patientPhone"> · ტელ: {{ b.patientPhone }}</template>
                        </p>
                        <p class="text-[12px] text-gray-700 dark:text-gray-300 truncate">
                          <svg class="inline w-3 h-3 mr-0.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                          </svg>
                          {{ b.doctorName || '—' }}
                        </p>
                      </div>
                      <button
                        type="button"
                        @click="deleteBooking(b)"
                        class="opacity-0 group-hover:opacity-100 transition text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 p-1"
                        title="წაშლა"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
                <div v-else class="flex items-center justify-between gap-2">
                  <p class="text-sm text-gray-400 dark:text-gray-500 italic">
                    თავისუფალია
                  </p>
                  <button
                    type="button"
                    @click="openBookingModal(hour)"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    დაჯავშნა
                  </button>
                </div>
              </div>

              <!-- Quick add button (when slot has bookings) -->
              <div
                v-if="bookingsByHour[hour] && bookingsByHour[hour].length > 0"
                class="md:w-28 flex-shrink-0 border-t md:border-t-0 md:border-l border-gray-200 dark:border-gray-700 flex items-center justify-center px-2 py-2"
              >
                <button
                  type="button"
                  @click="openBookingModal(hour)"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                  დამატება
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Booking modal -->
    <Modal :isOpen="isBookingModalOpen" title="ახალი ჯავშანი" @close="closeBookingModal">
      <form @submit.prevent="submitBooking" class="space-y-4">
        <Input
          v-model="bookingForm.patient_name"
          label="პაციენტის სახელი/გვარი *"
          placeholder="გვარი სახელი"
          required
        />

        <Input
          v-model="bookingForm.patient_id_number"
          label="პირადი ნომერი *"
          placeholder="01001001001"
          required
        />

        <Input
          v-model="bookingForm.patient_phone"
          label="ტელეფონი *"
          placeholder="5XX XX XX XX"
          required
        />

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            ექიმი *
          </label>
          <select
            v-model="bookingForm.doctor_id"
            class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          >
            <option value="">აირჩიეთ ექიმი</option>
            <option v-for="doc in doctors" :key="doc.id" :value="doc.id">
              {{ doc.first_name }} {{ doc.last_name }}
              <template v-if="doc.doctor_role"> — {{ doc.doctor_role }}</template>
            </option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            საათი *
          </label>
          <select
            v-model="bookingForm.time"
            class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          >
            <option value="">აირჩიეთ საათი</option>
            <option v-for="h in hours" :key="h" :value="h + ':00'">{{ h }}:00</option>
          </select>
        </div>

        <p class="text-xs text-gray-500 dark:text-gray-400">
          თარიღი: <span class="font-medium text-gray-700 dark:text-gray-300">{{ formattedSelectedDate }}</span>
        </p>

        <div v-if="bookingError" class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
          <p class="text-sm text-red-800 dark:text-red-300">{{ bookingError }}</p>
        </div>

        <div class="flex gap-3 pt-2">
          <Button type="button" variant="secondary" :full-width="true" @click="closeBookingModal">
            გაუქმება
          </Button>
          <Button type="submit" variant="primary" :full-width="true" :disabled="submitting">
            {{ submitting ? 'ინახება...' : 'დაჯავშნა' }}
          </Button>
        </div>
      </form>
    </Modal>
  </div>
</template>

<script>
import axios from 'axios';
import { useAuthStore } from '../stores/auth';
import { useToastStore } from '../stores/toast';
import { getTodayDateString, formatDateToInput } from '../utils/dateUtils';
import { formatGeorgianDate } from '../utils/georgianDate';
import Navbar from './Navbar.vue';
import Button from './ui/Button.vue';
import Modal from './ui/Modal.vue';
import Input from './ui/Input.vue';

export default {
  name: 'BookingCalendar',
  components: {
    Navbar,
    Button,
    Modal,
    Input,
  },
  setup() {
    const authStore = useAuthStore();
    const toastStore = useToastStore();
    return { authStore, toastStore };
  },
  data() {
    return {
      loading: true,
      submitting: false,
      bookings: [],
      doctors: [],
      selectedDate: getTodayDateString(),
      filters: {
        doctorId: '',
      },
      isBookingModalOpen: false,
      bookingError: '',
      bookingForm: this.makeEmptyBookingForm(),
    };
  },
  computed: {
    hours() {
      const list = [];
      for (let h = 8; h <= 20; h++) {
        list.push(String(h).padStart(2, '0'));
      }
      return list;
    },
    formattedSelectedDate() {
      return formatGeorgianDate(this.selectedDate);
    },
    filteredBookings() {
      return this.bookings.filter((b) => {
        if (this.filters.doctorId && Number(b.doctor_id) !== Number(this.filters.doctorId)) {
          return false;
        }
        return true;
      });
    },
    bookingsByHour() {
      const map = {};
      for (const h of this.hours) {
        map[h] = [];
      }
      for (const b of this.filteredBookings) {
        const hour = this.extractHour(b.time);
        if (hour && map[hour]) {
          map[hour].push(b);
        }
      }
      Object.keys(map).forEach((key) => {
        map[key].sort((a, b) => String(a.time).localeCompare(String(b.time)));
      });
      return map;
    },
    freeHourCount() {
      return this.hours.filter((h) => !this.bookingsByHour[h] || this.bookingsByHour[h].length === 0).length;
    },
  },
  watch: {
    selectedDate() {
      this.fetchBookings();
    },
  },
  mounted() {
    this.authStore.loadFromStorage();
    this.fetchDoctors();
    this.fetchBookings();
  },
  methods: {
    makeEmptyBookingForm(prefill = {}) {
      return {
        patient_name: '',
        patient_id_number: '',
        patient_phone: '',
        doctor_id: '',
        time: prefill.time || '',
      };
    },
    extractHour(timeValue) {
      if (!timeValue) return null;
      const match = String(timeValue).match(/^(\d{1,2})/);
      if (!match) return null;
      const n = parseInt(match[1], 10);
      if (!Number.isFinite(n) || n < 0 || n > 23) return null;
      return String(n).padStart(2, '0');
    },
    formatTime(timeValue) {
      if (!timeValue) return '—';
      return String(timeValue).slice(0, 5);
    },
    shiftDate(days) {
      const d = new Date(this.selectedDate);
      if (Number.isNaN(d.getTime())) {
        this.selectedDate = getTodayDateString();
        return;
      }
      d.setDate(d.getDate() + days);
      this.selectedDate = formatDateToInput(d);
    },
    goToToday() {
      this.selectedDate = getTodayDateString();
    },
    clearFilters() {
      this.filters = { doctorId: '' };
    },
    async fetchBookings() {
      this.loading = true;
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/bookings', {
          params: { date: this.selectedDate },
          headers: { Authorization: `Bearer ${token}` },
        });
        this.bookings = Array.isArray(response.data) ? response.data : [];
      } catch (error) {
        console.error('Failed to fetch bookings:', error);
        this.toastStore.error('ჯავშნების ჩატვირთვა ვერ მოხერხდა');
        this.bookings = [];
      } finally {
        this.loading = false;
      }
    },
    async fetchDoctors() {
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/users', {
          headers: { Authorization: `Bearer ${token}` },
        });
        this.doctors = (response.data || []).filter(
          (u) => u.role === 'DOCTOR' || u.role === 'LABOR'
        );
      } catch (error) {
        console.error('Failed to fetch doctors:', error);
        this.doctors = [];
      }
    },
    openBookingModal(hour) {
      this.bookingError = '';
      this.bookingForm = this.makeEmptyBookingForm({
        time: hour ? `${hour}:00` : '',
      });
      if (this.filters.doctorId) {
        this.bookingForm.doctor_id = this.filters.doctorId;
      }
      this.isBookingModalOpen = true;
    },
    closeBookingModal() {
      this.isBookingModalOpen = false;
      this.submitting = false;
      this.bookingError = '';
    },
    async submitBooking() {
      if (this.submitting) return;
      this.bookingError = '';

      const form = this.bookingForm;
      if (!form.patient_name?.trim() || !form.patient_id_number?.trim() || !form.patient_phone?.trim()) {
        this.bookingError = 'შეავსეთ პაციენტის სახელი, პირადი ნომერი და ტელეფონი';
        return;
      }
      if (!form.doctor_id || !form.time) {
        this.bookingError = 'აირჩიეთ ექიმი და საათი';
        return;
      }

      const doctor = this.doctors.find((d) => Number(d.id) === Number(form.doctor_id));
      const doctorName = doctor ? `${doctor.first_name} ${doctor.last_name}` : '';

      const payload = {
        patient_name: form.patient_name.trim(),
        patient_id_number: form.patient_id_number.trim(),
        patient_phone: form.patient_phone.trim(),
        doctor_id: Number(form.doctor_id),
        doctor_name: doctorName,
        date: this.selectedDate,
        time: form.time,
      };

      this.submitting = true;
      try {
        const token = localStorage.getItem('auth_token');
        await axios.post('/api/bookings', payload, {
          headers: { Authorization: `Bearer ${token}` },
        });
        this.toastStore.success('ჯავშანი წარმატებით შეიქმნა');
        this.closeBookingModal();
        await this.fetchBookings();
      } catch (error) {
        const data = error.response?.data;
        if (data?.errors && typeof data.errors === 'object') {
          const messages = Object.values(data.errors).flat().filter(Boolean);
          this.bookingError = messages.length ? messages.join(' ') : 'ჯავშნის შექმნა ვერ მოხერხდა';
        } else {
          this.bookingError = data?.message || error.message || 'ჯავშნის შექმნა ვერ მოხერხდა';
        }
      } finally {
        this.submitting = false;
      }
    },
    async deleteBooking(booking) {
      if (!booking?.id) return;
      if (!window.confirm('წავშალოთ ჯავშანი?')) return;
      try {
        const token = localStorage.getItem('auth_token');
        await axios.delete(`/api/bookings/${booking.id}`, {
          headers: { Authorization: `Bearer ${token}` },
        });
        this.toastStore.success('ჯავშანი წაიშალა');
        await this.fetchBookings();
      } catch (error) {
        console.error('Failed to delete booking:', error);
        this.toastStore.error('ჯავშნის წაშლა ვერ მოხერხდა');
      }
    },
  },
};
</script>
