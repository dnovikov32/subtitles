import _ from 'lodash'
import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from './axios';
import VueAxios from 'vue-axios';
import BootstrapVue from 'bootstrap-vue';
import Notifications from 'vue-notification';
import App from './components/App.vue';
import Home from './components/main/Home.vue';
import FilmsIndex from './components/films/FilmsIndex';
import FilmsEdit from './components/films/FilmsEdit';
import SerialsShow from './components/serial/SerialsShow';
import SubtitlesShow from './components/subtitles/SubtitlesShow';
import SubtitlesEdit from './components/subtitles/SubtitlesEdit';
import store from './store/index.js';

Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(BootstrapVue);
Vue.use(Notifications);

window.Vue = Vue;

const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'active',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/films/index',
            name: 'films.index',
            component: FilmsIndex
        },
        {
            path: '/films/create',
            name: 'films.create',
            component: FilmsEdit
        },
        {
            path: '/films/update/:id',
            name: 'films.update',
            component: FilmsEdit
        },
        {
            path: '/films/edit/:id',
            name: 'films.edit',
            component: FilmsEdit
        },
        {
            path: '/serials/show/:id',
            name: 'serials.show',
            component: SerialsShow
        },
        {
            path: '/subtitles/show/:id',
            name: 'subtitles.show',
            component: SubtitlesShow
        },
        {
            path: '/subtitles/edit/:id',
            name: 'subtitles.edit',
            component: SubtitlesEdit
        },
    ],
});

window.app = new Vue({
    el: '#app',
    store,
    router,
    components: {App}
});
