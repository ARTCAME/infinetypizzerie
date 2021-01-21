import axios from 'axios'

const actions = {
    /**
     * Removes an ingredient on the db and state
     *
     * @param {Object} pizzaData { id, name, price }
     */
    async delete({ commit, state }, pizzaData) {
        const resp = await axios.post('/api/deletePizza', pizzaData);
        resp && commit('DEL', state.pizzas.findIndex(p => p.id == pizzaData.id));
    },
    /**
     * Initializes the referencied ingredients of every pizza and stores it on the state. Is called on load and save new pizzas
     *
     * @param {Object} pizza
     */
    async initIngredients({ commit, rootGetters }, pizza) {
        let pizzaIngredients = await axios.get('/api/getPizzaIngredients/' + pizza.id);
        if (pizzaIngredients.data.length) {
            pizza.ingredients = [];
            for (const ingredient of pizzaIngredients.data) {
                pizza.ingredients.push(rootGetters['ingredients/getIngredientById'](ingredient.ingredient_id));
            }
            commit('ADD', pizza);
        }
    },
    /**
     * Load the existing pizzas from the db
     */
    async load({ dispatch }) {
        const resp = await axios.get('/api/getPizzas');
        for (const pizza of resp.data) {
            dispatch('initIngredients', pizza);
        }
    },
    /**
     * Saves the new pizza on the db and push it to the state
     *
     * @param {Object} pizzaData { name, price, ingredients, image }
     */
    async save({ dispatch }, pizzaData) {
        /* Create a form data element is usefull to treat the image object at the backend */
        let formData = new FormData();
        formData.append('image', pizzaData.image);
        formData.append('name', pizzaData.name);
        formData.append('ingredients', JSON.stringify(pizzaData.ingredients));
        formData.append('price', pizzaData.price);
        const resp = await axios.post('/api/createPizza', formData);
        // const resp = await axios.post('/api/createPizza', pizzaData);
        for (const pizza of resp.data) {
            dispatch('initIngredients', pizza);
        }
    },
    /**
     * Save an update
     *
     * @param {Object} newPizzaData { name, price, ingredients, image}
     * @param {Object} pizza { id, name, price, image }
     */
    async update({ commit, rootGetters, state }, { newPizzaData, pizza } ) {
        /* Create a form data element is usefull to treat the image object at the backend */
        let formData = new FormData();
        formData.append('image', newPizzaData.image);
        formData.append('name', newPizzaData.name);
        formData.append('ingredients', JSON.stringify(newPizzaData.ingredients));
        formData.append('price', newPizzaData.price);
        formData.append('id', pizza.id);
        const resp = await axios.post('/api/updatePizza', formData);
        // const resp = await axios.post('/api/updatePizza', { newData: newPizzaData, currData: pizza });
        let updatedPizza = resp.data;
        let pizzaIngredients = await axios.get('/api/getPizzaIngredients/' + updatedPizza.id);
        if (pizzaIngredients.data.length) {
            updatedPizza.ingredients = [];
            for (const ingredient of pizzaIngredients.data) {
                updatedPizza.ingredients.push(rootGetters['ingredients/getIngredientById'](ingredient.ingredient_id));
            }
            commit('UPDATE', { index: state.pizzas.findIndex(p => p.id == updatedPizza.id), newData: updatedPizza });
        }
    }
}
const getters = {
    /**
     * Returns the existing pizzas
     */
    getPizzas: state => state.pizzas,
}
const mutations = {
    /**
     * Adds a new pizza to the state
     *
     * @param {Object} newPizza { name, price }
     */
    ADD(state, newPizza) {
        state.pizzas.push(newPizza);
    },
    /**
     * Removes an entry
     *
     * @param {Number} index
     */
    DEL(state, index) {
        state.pizzas.splice(index, 1);
    },
    /**
     * Set the existing pizzas from the db on the store
     *
     * @param {Array} newPizzas
     */
    LOAD(state, newPizzas) {
        Vue.set(state, 'pizzas', newPizzas);
    },
    /**
     * Update the state with the updated pizza
     *
     * @param {Number} index
     * @param {Object} newData { id, name, price, ingredients }
     */
    UPDATE(state, { index, newData }) {
        Vue.set(state.pizzas, index, newData);
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
