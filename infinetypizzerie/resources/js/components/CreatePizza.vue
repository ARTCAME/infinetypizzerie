<template>
    <create-form
        formTitle="Registro de pizza"
        :reset="resetForm"
        :result="result"
        :save="savePizza"
        :saveBtnDisabled="name == null || price == null || selectedIngredients.length == 0"
        :saveBtnTitle="name == null || price == null || selectedIngredients.length == 0 ? 'Revisa los campos del formulario' : 'Guardar'"
    >
        <template #inputs>
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
                label="Selecciona una imagen"
                label-for="pizza-img"
            >
                <b-form-file
                    accept="image/*"
                    placeholder="Añade una imagen"
                    drop-placeholder="Suelta aquí la imagen"
                    @change="selectImage($event)"
                ></b-form-file>
            </b-form-group>
            <b-form-group
                label="Selecciona ingredientes"
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
        </template>
    </create-form>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
export default {
    data() {
        return {
            image: null, /* V-model for the pizza image */
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
         * Just reset the v-models related with the pizza
         */
        resetForm() {
            this.name = null;
            this.price = null;
            this.image = null;
            this.selectedIngredients = [];
        },
        /**
         * Set the input file value
         */
        selectImage(ev) {
            this.image = ev.target.files[0];
        },
        /**
         * Saves the new pizza
         */
        async savePizza() {
            try {
                /* Vuex stores at the db */
                await this.save({ name: this.name, price: this.price, ingredients: this.selectedIngredients, image: this.image });
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
