import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './router';

// Mount the app if there's a root element
const rootElement = document.getElementById('app');
if (rootElement) {
    const app = createApp(App);
    const pinia = createPinia();
    
    app.use(pinia);
    app.use(router);
    app.mount('#app');
}
