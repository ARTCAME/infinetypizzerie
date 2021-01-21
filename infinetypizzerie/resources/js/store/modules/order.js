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
    },
    /**
     * Get orders from the db. If the user is an admin load all the orders from all the users
     */
    async loadOrders({ commit, rootGetters }) {
        const resp = rootGetters['auth/authenticatedRole'] == 'admin' ? await axios.get('/api/getOrders/') : await axios.get('/api/getOrders/' + rootGetters['auth/authId']);
        resp.data && commit('ADD_ORDERS', resp.data);
    }
}
const getters = {
    /**
     * Returns the existing order pizzas
     */
    getOrder: state => state.pizzas,
    /**
     * Returns the authenticated user orders, if his role is admin return all the orders from all the users
     */
    getOrders: state => state.orders,
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
     * Add the orders of the current logged user (if the user is an admin load add all the orders from all the users)
     *
     * @param {Array} orders
     */
    ADD_ORDERS(state, orders) {
        state.orders = orders;
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
    pizzas: [], /* Current order content */
    orders: [], /* Order historic */
}
export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters,
}
