import axios from 'axios'

const actions = {
    addPizza({ commit }, pizza) {
        this.$app.$emit('bv::show::popover', 'order-summary');
        commit('ADD', pizza);
    },
    createOrder({ getters, rootGetters }) {
        axios.post('/api/createOrder', { user: rootGetters['auth/authenticatedUser'], userId: rootGetters['auth/authId'], order: getters.getOrder, subtotal: getters.getOrder.reduce((acc, curr) => acc + curr.price, 0) });
    },
    deletePizza({ commit, state }, pizza) {
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
