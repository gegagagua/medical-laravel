import axios from 'axios';

const getAuthToken = () => localStorage.getItem('auth_token');

const getAuthHeaders = () => ({
  'Authorization': `Bearer ${getAuthToken()}`,
  Accept: 'application/json',
});

export const paymentService = {
  async createPayment(paymentData) {
    const response = await axios.post('/api/payments', paymentData, {
      headers: getAuthHeaders(),
    });
    if (response.status !== 201 || response.data == null || response.data.id == null) {
      const msg =
        response.data?.message ||
        response.data?.error ||
        'გადახდის შექმნის პასუხი არასწორია';
      const err = new Error(msg);
      err.response = response;
      throw err;
    }
    return response.data;
  },

  async getPayments() {
    const response = await axios.get('/api/payments', {
      headers: getAuthHeaders()
    });
    return response.data;
  }
};

