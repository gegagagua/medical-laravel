import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

// Mount the app if there's a root element
const rootElement = document.getElementById('app');
if (rootElement) {
    createApp(App).use(router).mount('#app');
}
