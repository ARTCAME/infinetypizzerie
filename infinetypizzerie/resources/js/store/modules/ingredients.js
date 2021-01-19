import axios from 'axios'

const actions = {
    /**
     * Removes an ingredient on the db and state
     *
     * @param {Object} ingredientData { id, ingredient_name, price }
     */
    async delete({ commit, state }, ingredientData) {
        const resp = await axios.post('/api/deleteIngredient', ingredientData);
        resp && commit('DEL', state.ingredients.findIndex(p => p.id == ingredientData.id));
    },
    /**
     * Load the existing ingredients from the db
     */
    async load({ commit }) {
        const ingredients = await axios.get('/api/getIngredients');
        ingredients && commit('LOAD', ingredients.data);
    },
    /**
     * Saves the new ingredient on the db and push it to the state
     *
     * @param {Object} ingredientData { name, price }
     */
    async save({ commit }, ingredientData) {
        const resp = await axios.post('/api/createIngredient', ingredientData);
        resp && commit('ADD', resp.data);
    },
    /**
     * Save an ingredient update
     *
     * @param {Object} newIngredientData { name, price }
     * @param {Object} ingredient { id, ingredient_name, price }
     */
    async update({ commit }, { newIngredientData, ingredient } ) {
        const resp = await axios.post('/api/updateIngredient', { newData: newIngredientData, currData: ingredient });
        resp && commit('UPDATE', { index: state.ingredients.findIndex(p => p.id == ingredient.id), newData: resp.data });
    }
}
const getters = {
    /**
     * Returns the existing ingredients
     */
    getIngredients: state => state.ingredients,
    /**
     * Returns an ingredient by its id
     *
     * @param {Number} id
     */
    getIngredientById: state => id => { return state.ingredients.find(ig => ig.id == id) },
}
const mutations = {
    /**
     * Adds a new ingredient to the state
     *
     * @param {Object} newIngredient { name, price }
     */
    ADD(state, newIngredient) {
        state.ingredients.push(newIngredient);
    },
    /**
     * Removes an entry
     *
     * @param {Number} index
     */
    DEL(state, index) {
        state.ingredients.splice(index, 1);
    },
    /**
     * Set the existing ingredients from the db on the store
     *
     * @param {Array} newIngredients
     */
    LOAD(state, newIngredients) {
        Vue.set(state, 'ingredients', newIngredients);
    },
    /**
     * Update the state with the updated ingredient
     *
     * @param {Number} index
     * @param {Object} newData { id, ingredient_name, price }
     */
    UPDATE(state, { index, newData }) {
        Vue.set(state.ingredients, index, newData);
    }
}
const state = {
    ingredients: [],
}
export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters,
}
