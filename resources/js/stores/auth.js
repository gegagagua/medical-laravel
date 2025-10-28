import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    userRole: (state) => state.user?.role,
    userName: (state) => {
      if (state.user) {
        return `${state.user.first_name} ${state.user.last_name}`;
      }
      return '';
    }
  },

  actions: {
    setAuth(user, token) {
      this.user = user;
      this.token = token;
      
      // Save to localStorage
      localStorage.setItem('auth_token', token);
      localStorage.setItem('user', JSON.stringify(user));
    },

    logout() {
      this.user = null;
      this.token = null;
      
      // Clear localStorage
      localStorage.removeItem('auth_token');
      localStorage.removeItem('user');
    },

    loadFromStorage() {
      const token = localStorage.getItem('auth_token');
      const userStr = localStorage.getItem('user');
      
      if (token && userStr) {
        try {
          this.user = JSON.parse(userStr);
          this.token = token;
        } catch (e) {
          console.error('Failed to parse user from localStorage:', e);
          this.logout();
        }
      }
    },

    updateUser(user) {
      this.user = user;
      localStorage.setItem('user', JSON.stringify(user));
    }
  }
});

