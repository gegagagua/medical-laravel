import axios from 'axios';

const getAuthToken = () => localStorage.getItem('auth_token');

const getAuthHeaders = () => ({
  'Authorization': `Bearer ${getAuthToken()}`
});

export const userService = {
  async getUsers() {
    const response = await axios.get('/api/users', {
      headers: getAuthHeaders()
    });
    return response.data;
  },

  async getDoctors() {
    const users = await this.getUsers();
    return users
      .filter(user => user.role === 'DOCTOR')
      .sort((a, b) => {
        const nameA = `${a.last_name} ${a.first_name}`;
        const nameB = `${b.last_name} ${b.first_name}`;
        return nameA.localeCompare(nameB);
      });
  },

  async getLaborUsers() {
    const users = await this.getUsers();
    return users
      .filter(user => user.role === 'LABOR')
      .sort((a, b) => {
        const nameA = `${a.last_name} ${a.first_name}`;
        const nameB = `${b.last_name} ${b.first_name}`;
        return nameA.localeCompare(nameB);
      });
  }
};

