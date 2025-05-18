import Axios from 'axios'

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

        window.app.$store.dispatch('loading', true);
        window.app.$store.dispatch('failing', []);

        return config;
    },
    error => {
        window.app.$store.dispatch('loading', false);
        window.app.$store.dispatch('failing', error.response.data);

        return Promise.reject(error);
    }
);

axios.interceptors.response.use(
    response => {
        window.app.$store.dispatch('loading', false);
        window.app.$store.dispatch('failing', []);

        return response;
    },
    error => {
        window.app.$store.dispatch('loading', false);
        window.app.$store.dispatch('failing', error.response.data);

        return Promise.reject(error);
    }
);

export default axios;