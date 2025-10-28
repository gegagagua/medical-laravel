import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../components/HomePage.vue';
import AuthPage from '../components/AuthPage.vue';
import RegisterPage from '../components/RegisterPage.vue';
import Dashboard from '../components/Dashboard.vue';
import Users from '../components/Users.vue';
import Patients from '../components/Patients.vue';
import Payments from '../components/Payments.vue';
import Visits from '../components/Visits.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomePage
  },
  {
    path: '/auth',
    name: 'Auth',
    component: AuthPage
  },
  {
    path: '/register',
    name: 'Register',
    component: RegisterPage
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/users',
    name: 'Users',
    component: Users,
    meta: { requiresAuth: true }
  },
  {
    path: '/patients',
    name: 'Patients',
    component: Patients,
    meta: { requiresAuth: true }
  },
  {
    path: '/payments',
    name: 'Payments',
    component: Payments,
    meta: { requiresAuth: true }
  },
  {
    path: '/visits',
    name: 'Visits',
    component: Visits,
    meta: { requiresAuth: true }
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/'
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Navigation guard for authentication
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('auth_token');
  
  if (to.meta.requiresAuth && !token) {
    next('/auth');
  } else if (to.path === '/auth' && token) {
    next('/dashboard');
  } else {
    next();
  }
});

export default router;

