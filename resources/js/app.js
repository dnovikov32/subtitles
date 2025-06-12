import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router'
import BootstrapVue from 'bootstrap-vue';
import Notifications from '@kyvg/vue3-notification'
import App from './components/App.vue';
import store from './store/index.js';
import routes from './routes.js';

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
})

const app = createApp(App);

app.use(router);
app.use(store);
app.use(BootstrapVue);
app.use(Notifications);

// window.app = app;
app.mount('#app');
