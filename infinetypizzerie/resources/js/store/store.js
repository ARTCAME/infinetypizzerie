import auth from './modules/auth';
import ingredients from './modules/ingredients';
import order from './modules/order';
import pizzas from './modules/pizzas';
import Vue from 'vue';
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        auth,
        ingredients,
        order,
        pizzas,
    },
    name: 'infinety',
    namespaced: true,
    strict: true,
});
