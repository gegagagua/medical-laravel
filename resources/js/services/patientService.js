import axios from 'axios';

const getAuthToken = () => localStorage.getItem('auth_token');

const getAuthHeaders = () => ({
  'Authorization': `Bearer ${getAuthToken()}`,
  Accept: 'application/json',
});

export const patientService = {
  async getPatient(patientId) {
    const response = await axios.get(`/api/patients/${patientId}`, {
      headers: getAuthHeaders()
    });
    return response.data;
  },

  async getPatientVisits(patientId) {
    const response = await axios.get('/api/visits', {
      headers: getAuthHeaders()
    });
    
    // Filter visits for this patient
    return response.data
      .filter(visit => visit.patient_id == patientId)
      .sort((a, b) => new Date(b.date) - new Date(a.date));
  },

  async getPatientPayments(patientId) {
    const response = await axios.get('/api/payments', {
      headers: getAuthHeaders()
    });

    const list = Array.isArray(response.data) ? response.data : [];
    const pid = Number(patientId);

    return list.filter((payment) => {
      const rowPatientId = Number(payment.patientId ?? payment.patient_id);
      return Number.isFinite(pid) && rowPatientId === pid;
    });
  }
};

