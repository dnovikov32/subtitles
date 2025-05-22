import axios from '../../axios';

export default {
    namespaced: true,

    state: {
        subtitle: {},
        rows: []
    },

    getters: {
        subtitle (state) {
            return state.subtitle;
        },
        rows (state) {
            return state.rows;
        }
    },

    mutations: {
        ['SHOW_SUBTITLE_SUCCESS'] (state, data) {
            state.subtitle = data;
        },
        ['FIND_SUBTITLE_SUCCESS'] (state, data) {
            state.subtitle = data.subtitle;
            state.rows = data.rows;
        },
        ['UPDATE_SUBTITLE_SUCCESS'] (state, data) {
            state.subtitle = data.subtitle;
            state.rows = data.rows;
        },
        ['DESTROY_SUBTITLE_SUCCESS'] (state, data) {
            state.subtitle = {};
        }
    },

    actions: {
        show ({state, commit, rootState}, id) {
            return axios.get(`/api/v1/subtitles/show/${id}`)
                .then(
                    response => commit('SHOW_SUBTITLE_SUCCESS', response.data),
                    error => {}
                )
        },

        find ({state, commit, rootState}, id) {
            return axios.get(`/api/v1/subtitles/find/${id}`)
                .then(
                    response => commit('FIND_SUBTITLE_SUCCESS', response.data),
                    error => {}
                )
        },

        update ({state, commit, rootState}, model) {
            return axios.post('/api/v1/subtitles/update', model)
                .then(
                    response => commit('UPDATE_SUBTITLE_SUCCESS', response.data),
                    error => {}
                )
        },

        destroy ({state, commit, rootState}, id) {
            return axios.post('/api/v1/subtitles/destroy', {id: id})
                .then(
                    response => commit('DESTROY_SUBTITLE_SUCCESS', response.data),
                    error => {}
                )
        }
    }
};
