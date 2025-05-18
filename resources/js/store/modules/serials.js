import Vue from 'vue';

export default {
    namespaced: true,

    state: {
        serial: {}
    },

    getters: {
        serial (state) {
            return state.serial;
        }
    },

    mutations: {
        ['SHOW_SERIAL_SUCCESS'] (state, data) {
            state.serial = data;
        }
    },

    actions: {

        show ({state, commit, rootState}, id) {
            return Vue.axios.get(`/api/v1/serials/show/${id}`)
                .then(
                    response => commit('SHOW_SERIAL_SUCCESS', response.data),
                    error => {}
                )
        }

    }
};