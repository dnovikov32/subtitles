import axios from '../../axios';

export default {
    namespaced: true,

    state: {
        films: [],
        serials: [],
        film: {}
    },

    getters: {
        films (state) {
            return state.films;
        },
        serials (state) {
            return state.serials;
        },
        film (state) {
            return state.film;
        }
    },

    mutations: {
        ['ALL_FILMS_SUCCESS'] (state, data) {
            state.films = data.films;
            state.serials = data.serials;
        },
        ['FIND_FILM_SUCCESS'] (state, data) {
            state.film = data;
        },
        ['CREATE_FILM_SUCCESS'] (state, data) {
            state.film = data;
        },

        ['DESTROY_FILM_SUCCESS'] (state, data) {
            state.film = {};
        }
    },

    actions: {
        all ({state, commit, rootState}) {
            return axios.get('/api/v1/films/index')
                .then(
                    response => commit('ALL_FILMS_SUCCESS', response.data),
                    error => {}
                )
        },

        find ({state, commit, rootState}, id) {
            return axios.get(`/api/v1/films/find/${id}`)
                .then(
                    response => commit('FIND_FILM_SUCCESS', response.data),
                    error => {}
                )
        },

        edit ({state, commit, rootState}, model) {
            return axios.post('/api/v1/films/edit', model)
                .then(
                    response => commit('CREATE_FILM_SUCCESS', response.data),
                    error => {}
                )
        },

        destroy ({state, commit, rootState}, id) {
            return axios.post('/api/v1/films/destroy', {id: id})
                .then(
                    response => commit('DESTROY_FILM_SUCCESS', response.data),
                    error => {}
                )
        }

    }
};
