import axios from 'axios'

const actions = {
    add({ commit }, pizza) {
        commit('ADD', pizza);
    },
    delete({ commit, state }, pizza) {
        const index = state.pizzas.findIndex(spi => spi.id == pizza.id);
        index != -1 && commit('DEL', index);
    }
}
const getters = {
    /**
     * Returns the existing order pizzas
     */
    getOrder: state => state.pizzas,
}
const mutations = {
    ADD(state, pizza) {
        state.pizzas.push(pizza);
    },
    DEL(state, index) {
        state.pizzas.splice(index, 1);
    }
}
const state = {
    pizzas: [],
}
export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters,
}
