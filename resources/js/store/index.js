import Vue from 'vue';
import Vuex from 'vuex';
import films from './modules/films';
import subtitles from './modules/subtitles';
import serials from './modules/serials';

Vue.use(Vuex);

export default new Vuex.Store({

    state: {
        isLoading: false,
        errors: []
    },

    getters: {
        isDev(state) {
            return true;
        },
        isLoading(state) {
            return state.isLoading;
        },
        errors(state) {
            return state.errors;
        },
        hasError(state) {
            return state.errors.length > 0;
        }
    },

    mutations: {
        ['SET_LOADER'](state, value) {
            state.isLoading = value;
        },
        ['SET_ERRORS'] (state, errors) {
            state.errors = errors;
        }
    },

    actions: {
        loading({state, commit, rootState}, value) {
            commit('SET_LOADER', value);
        },

        failing ({state, commit, rootState}, errors) {
            commit('SET_ERRORS', errors);
        }
    },

    modules: {
        films: films,
        serials: serials,
        subtitles: subtitles,
    }

});
