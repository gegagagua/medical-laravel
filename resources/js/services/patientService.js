import axios from 'axios';

const getAuthToken = () => localStorage.getItem('auth_token');

const getAuthHeaders = () => ({
  'Authorization': `Bearer ${getAuthToken()}`
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
    
    // Filter payments for this patient
    return response.data.filter(
      payment => payment.patientId == patientId || payment.patient_id == patientId
    );
  }
};

