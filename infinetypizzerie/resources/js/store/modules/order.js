import axios from 'axios'

const actions = {
    /**
     * Add a pizza to the vuex store
     *
     * @param {Object} pizza { id, name, ingredients, price }
     */
    addPizza({ commit }, pizza) {
        this.$app.$emit('bv::show::popover', 'order-summary');
        commit('ADD', pizza);
    },
    /**
     * Create the order at the db
     */
    async createOrder({ commit, getters, rootGetters }) {
        try {
            axios.post('/api/createOrder', { user: rootGetters['auth/authenticatedUser'], userId: rootGetters['auth/authId'], order: getters.getOrder, subtotal: getters.getOrder.reduce((acc, curr) => parseFloat(acc) + parseFloat(curr.price), 0) });
            await this.$app.$emit('bv::hide::popover', 'order-summary');
            commit('RESET');
            const h = this.$app.$createElement;
            const msg = [('p', 'Pedido registrado correctamente.'), h('p', 'En unos minutos recibirás un email de confirmación.')];
            await this.$app.$bvModal.msgBoxOk(msg, { buttonSize:'sm', centered: true, size: 'sm' })
        } catch(error) {
            console.error(error);
        }
    },
    /**
     * Deletes a pizza from an order
     *
     * @param {Object} pizza { id, name, ingredients, price }
     */
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
    /**
     * Add a pizza to the state
     *
     * @param {Object} pizza { id, name, ingredients, price }
     */
    ADD(state, pizza) {
        state.pizzas.push(pizza);
    },
    /**
     * Deletes a pizza at the state
     *
     * @param {Number} index
     */
    DEL(state, index) {
        state.pizzas.splice(index, 1);
    },
    /**
     * Reset the state
     */
    RESET(state) {
        Vue.set(state, 'pizzas', []);
    },
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
