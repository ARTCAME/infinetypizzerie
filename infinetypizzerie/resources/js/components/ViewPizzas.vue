<template>
    <b-card
        footer-class="px-3"
        img-src="../img/pizza1.jpg"
        img-top
        no-body
        :img-alt="pizza.name"
    >
        <b-row class="px-3 py-1" no-gutters>
            <b-button
                size="sm"
                variant="outline-secondary"
                :title="onEdit != pizza.id ? 'Editar' : 'Cancelar'"
                @click="onEdit != pizza.id ? edit(pizza) : cancel()"
            >
                <b-icon
                    :icon="onEdit != pizza.id ? 'pencil' : 'x'"
                ></b-icon>
            </b-button>
            <b-button
                class="ml-1"
                size="sm"
                title="Guardar"
                variant="outline-primary"
                v-if="onEdit == pizza.id"
                @click="saveEdit(pizza)"
            >
                <b-icon icon="check-all">></b-icon>
            </b-button>
            <b-button
                class="ml-auto mr-0"
                size="sm"
                title="Borrar pizza"
                variant="outline-danger"
                @click="remove(pizza)"
            >
                <b-icon icon="backspace"></b-icon>
            </b-button>
        </b-row>
        <b-card-text>
            <div
                class="m-3"
                v-if="onEdit == pizza.id"
            >
                <b-form-select
                    multiple
                    v-model="selectedIngredients"
                    :select-size="5"
                >
                    <b-form-select-option
                        v-for="ig in ingredients"
                        :disabled="updatedPizza.ingredients.some(pig => pig.id == ig.id)"
                        :key="ig.id"
                        :title="updatedPizza.ingredients.some(pig => pig.id == ig.id) ? 'Ya está añadido' : ''"
                        :value="ig.id"
                    >
                        {{ ig.ingredient_name + ' - ' + ig.price }} €
                    </b-form-select-option>
                </b-form-select>
                <b-button
                    class="mt-1 w-100"
                    size="sm"
                    @click="addIngredients()"
                >
                    Añadir seleccionados
                </b-button>
            </div>
            <span
                v-if="onEdit != pizza.id"
            >
                <h5 class="p-2 text-center">{{ pizza.name }}</h5>
            </span>
            <b-form-group
                class="mx-3"
                v-else
                :description="pizza.name"
            >
                <b-form-input
                    v-model="updatedPizza.name"
                ></b-form-input>
            </b-form-group>
            <b-list-group flush>
                <b-list-group-item
                    class="px-3 py-1"
                    v-for="ig in (onEdit != pizza.id ? pizza.ingredients : updatedPizza.ingredients)"
                    :key="ig.id"
                >
                    <b-row no-gutters>
                        {{ ig.ingredient_name }}
                        <b-button
                            class="ml-auto mr-0"
                            size="sm"
                            title="Borrar ingrediente"
                            variant="outline-warning"
                            v-if="onEdit == pizza.id"
                            @click="removeIngredient(ig)"
                        >
                            <b-icon icon="backspace"></b-icon>
                        </b-button>
                    </b-row>
                </b-list-group-item>
            </b-list-group>
        </b-card-text>
        <template #footer>
            <b-row no-gutters>
                <b-col>
                    <span
                        v-if="onEdit != pizza.id"
                    >
                        <b>{{ pizza.price }}€</b>
                    </span>
                    <b-form-group
                        class="mr-3"
                        v-else
                        :description="pizza.price + '€'"
                    >
                        <b-form-input
                            step="0.01"
                            type="number"
                            v-model="updatedPizza.price"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col class="col-auto">
                    <b-button
                        variant="outline-success"
                        :disabled="onEdit == pizza.id"
                        @click="addPizza(pizza)"
                    >
                        Pedir
                    </b-button>
                </b-col>
            </b-row>
        </template>
    </b-card>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
export default {
    data() {
        return {
            onEdit: null, /* Stores the id of an edit pizza */
            selectedIngredients: [], /* Stores the selected ingredients to add to an editing pizza */
            updatedPizza: null, /* V-model for the edit pizza */
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
    },
    methods: {
        ...mapActions('pizzas', ['delete', 'update']),
        ...mapActions('order', ['addPizza']),
        /**
         * Add ingredients to an editing pizza, only are allowed the no added yet
         */
        addIngredients() {
            var ingredients = [];
            this.selectedIngredients.map(sig => {
                const idx = this.ingredients.findIndex(ig => ig.id == sig);
                if (idx != -1) {
                    /* Add the new ingredients to the local copy of the editing pizza */
                    this.updatedPizza.ingredients.push(this.ingredients[idx]);
                }
            });
            /* Reset the selected after every ingredient add */
            this.selectedIngredients = [];
        },
        /**
         * Cancel the edition of a pizza
         */
        cancel() {
            this.onEdit = null;
            this.updatedPizza = null;
        },
        /**
         * Actives the edition of a pizza storing its id on the onEdit global variable
         *
         * @param {Object} pizza { id, name, price }
         */
        edit(pizza) {
            this.onEdit = pizza.id;
            /* Make a non referenced copy of the vuex data to manage it locally */
            this.updatedPizza = JSON.parse(JSON.stringify(pizza));
        },
        /**
         * Call vuex to remove a pizza on the db
         *
         * @param {Object} pizza { id, name, price }
         */
        remove(pizza) {
            try {
                this.delete(pizza);
            } catch (error) {
                console.error(error);
            }
        },
        /**
         * Removes ingredients of the local copy of an editing pizza
         *
         * @param {Object} ingredient { id, ingredient_name, price }
         */
        removeIngredient(ingredient) {
            try {
                const index = this.pizza.ingredients.findIndex(pi => pi.id == ingredient.id);
                if (index != -1) {
                    this.updatedPizza.ingredients.splice(index, 1);
                }
            } catch (error) {
                console.error(error);
            }
        },
        /**
         * Call vuex to update a pizza with the new data
         *
         * @param {Object} pizza { id, name, price }
         */
        saveEdit(pizza) {
            try {
                this.update({ newPizzaData: this.updatedPizza, pizza: pizza });
                this.cancel();
            } catch(error) {
                console.error(error);
            }
        },
    },
    props: {
        pizza: Object, /* The pizza to show on the card */
    }
}
</script>

<style>

</style>
