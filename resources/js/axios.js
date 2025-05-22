import Axios from 'axios'
import store from './store'

const axios = Axios.create();

axios.interceptors.request.use(
    config => {
        config.headers['X-Requested-With'] = 'XMLHttpRequest';
        config.headers['Content-Type'] = 'application/json';
        // config.headers.post['Content-Type'] = 'multipart/form-data';

        let csrf = document.head.querySelector('meta[name="csrf-token"]');

        if (csrf) {
            config.headers['X-CSRF-TOKEN'] = csrf.content;
        } else {
            console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
        }

        store.dispatch('loading', true);
        store.dispatch('failing', []);

        return config;
    },
    error => {
        store.dispatch('loading', false);
        store.dispatch('failing', error.response.data);

        return Promise.reject(error);
    }
);

axios.interceptors.response.use(
    response => {
        store.dispatch('loading', false);
        store.dispatch('failing', []);

        return response;
    },
    error => {
        store.dispatch('loading', false);
        store.dispatch('failing', error.response.data);

        return Promise.reject(error);
    }
);

export default axios;
