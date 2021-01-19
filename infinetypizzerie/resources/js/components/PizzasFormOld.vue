<template>
    <b-container class="mt-3">
        <b-card style="max-width: 30rem">
            <h1>Registro de pizza</h1>
            <hr>
            <b-form
                @submit.prevent="savePizza"
            >
                <b-alert
                    class="my-2"
                    dismissible
                    fade
                    v-if="result"
                    :show="alertCountdown"
                    :variant="result.status"
                    @dismissed="alertCountdown = 0"
                    @dismiss-count-down="countdown"
                >
                    {{ result.message }}
                </b-alert>
                <b-form-group
                    label="Nombre para la pizza"
                    label-for="pizza-name"
                >
                    <b-form-input
                        id="pizza-name"
                        placeholder="Introduce un nombre para la pizza"
                        required
                        type="text"
                        v-model="name"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    label="Precio de la pizza"
                    label-for="pizza-price"
                >
                    <b-form-input
                        id="pizza-price"
                        min="0"
                        placeholder="Introduce un precio para la pizza"
                        required
                        step="0.01"
                        type="number"
                        v-model="price"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    label="Selecciona un ingrediente"
                    label-for="'ig-selector"
                    v-if="ingredients.length > 0"
                >
                    <b-form-select
                        id="ig-selector"
                        multiple
                        v-model="selectedIngredients"
                        :select-size="5"
                    >
                        <b-form-select-option
                            v-for="ig in ingredients"
                            :key="ig.id"
                            :value="ig.id"
                        >
                            {{ ig.ingredient_name + ' - ' + ig.price }} €
                        </b-form-select-option>
                    </b-form-select>
                    <b-list-group class="pt-2">
                        <b-list-group-item
                            class="py-1"
                            variant="secondary"
                            v-for="(name, idx) in selectedIngredientsName"
                            :key="idx"
                        >
                            {{ name }}
                        </b-list-group-item>
                        <b-list-group-item
                            variant="dark"
                            v-if="selectedIngredients.length > 0"
                        >
                            <b-row>
                                <b-col>Total precio ingredientes:</b-col>
                                <b-col><b>{{ selectedIngredientsPrice }}€</b></b-col>
                            </b-row>
                        </b-list-group-item>
                    </b-list-group>
                </b-form-group>
                <b-alert
                    show
                    variant="warning"
                    v-else
                >
                    Es necesario que escojas ingredientes, añade alguno primero
                </b-alert>
                <hr>
                <b-row no-gutters>
                    <b-button
                        class="ml-0 mr-auto"
                        type="cancel"
                        variant="outline-warning"
                        @click="resetForm()"
                    >
                        Cancelar
                    </b-button>
                    <b-button
                        class="ml-auto mr-0"
                        type="submit"
                        variant="primary"
                        :disabled="saveBtn.disabled"
                        :title="saveBtn.title"
                    >
                        Guardar
                    </b-button>
                </b-row>
            </b-form>
        </b-card>
    </b-container>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
export default {
    data() {
        return {
            alertCountdown: 5, /* Improves the UX for the alert feedback on form saves */
            name: null, /* V-model for the pizza name */
            price: null, /* V-model for the pizza price */
            result: null, /* Will store the result of the pizza registration */
            selectedIngredients: [], /* V-model for the selected ingredients of a new pizza */
        }
    },
    computed: {
        ...mapGetters('ingredients', ['getIngredients']),
        /**
         * Get the existing ingredients from the store
         */
        ingredients() {
            return this.getIngredients;
        },
        /**
         * For improve the UX of the send button depending on the form status
         */
        saveBtn() {
            return {
                disabled: this.name == null || this.price == null || this.ingredients.length == 0,
                title: this.name == null || this.price == null || this.ingredients.length == 0 ? 'Revisa los campos del formulario' : 'Guardar'
            }
        },
        /**
         * Return the name of the selected ingredients
         */
        selectedIngredientsName() {
            return this.selectedIngredients.map(sig => {
                const idx = this.ingredients.findIndex(ig => ig.id == sig);
                if (idx != -1) {
                    return this.ingredients[idx].ingredient_name;
                }
            })
        },
        /**
         * Return the price sum of the current selected ingredients
         */
        selectedIngredientsPrice() {
            return this.ingredients.filter(ig => this.selectedIngredients.includes(ig.id)).reduce((acc, curr) => acc + parseFloat(curr.price), 0)
        }
    },
    methods: {
        ...mapActions('pizzas', ['save']),
        /**
         * Manage the alert auto hide behavior
         */
        countdown(count) {
            this.alertCounddown = count;
        },
        /**
         * Just reset the v-models related with the pizza
         */
        resetForm() {
            this.name = null;
            this.price = null;
            this.selectedIngredients = [];
        },
        /**
         * Saves the new pizza
         */
        async savePizza() {
            try {
                /* Vuex stores at the db */
                await this.save({ name: this.name, price: this.price, ingredients: this.selectedIngredients });
                this.result = {
                    status: 'success',
                    message: 'Pizza creada correctamente',
                }
            } catch (error) {
                console.error(error);
                this.result = {
                    status: 'danger',
                    message: 'No se ha podido crear la pizza',
                }
            } finally {
                this.resetForm();
            }
        },
    }
}
</script>
<style>

</style>
