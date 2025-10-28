import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';
import AuthPage from './components/AuthPage.vue';

// Mount the app if there's a root element
const rootElement = document.getElementById('app');
if (rootElement) {
    createApp(AuthPage).mount('#app');
}
