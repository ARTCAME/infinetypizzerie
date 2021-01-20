<template>
    <b-card header="Lista de ingredientes:" no-body style="max-width: 30rem">
        <b-alert
            class="m-3"
            show
            variant="warning"
            v-if="ingredients.length == 0"
        >
            Cuando crees algún ingrediente lo verás aquí
        </b-alert>
        <b-list-group
            flush
            v-else
        >
            <b-list-group-item
                v-for="ingredient in ingredients"
                :key="ingredient.id"
            >
                <b-row>
                    <b-col>
                        <span
                            v-if="onEdit != ingredient.id"
                        >
                            {{ ingredient.ingredient_name }}
                        </span>
                        <b-form-group
                            v-else
                            :description="ingredient.ingredient_name"
                        >
                            <b-form-input
                                v-model="updatedIngredient.name"
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col class="ml-auto mr-0">
                        <span
                            v-if="onEdit != ingredient.id"
                        >
                            {{ ingredient.price + '€' }}
                        </span>
                        <b-form-group
                            v-else
                            :description="ingredient.price + '€'"
                        >
                            <b-form-input
                                step="0.01"
                                type="number"
                                v-model="updatedIngredient.price"
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col class="col-auto">
                        <b-button
                            size="sm"
                            title="Guardar"
                            variant="outline-primary"
                            v-if="onEdit == ingredient.id"
                            @click="saveEdit(ingredient)"
                        >
                            <b-icon icon="check-all">></b-icon>
                        </b-button>
                        <b-button
                            size="sm"
                            variant="outline-secondary"
                            :title="onEdit != ingredient.id ? 'Editar' : 'Cancelar'"
                            @click="onEdit != ingredient.id ? edit(ingredient) : cancel()"
                        >
                            <b-icon
                                :icon="onEdit != ingredient.id ? 'pencil' : 'x'"
                            ></b-icon>
                        </b-button>
                        <b-button
                            size="sm"
                            title="Borrar ingrediente"
                            variant="outline-warning"
                            @click="remove(ingredient)"
                        >
                            <b-icon icon="backspace"></b-icon>
                        </b-button>
                    </b-col>
                </b-row>
            </b-list-group-item>
        </b-list-group>
    </b-card>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
export default {
    data() {
        return {
            onEdit: null, /* Stores the id of an edit ingredient */
            updatedIngredient: {
                name: null,
                price: null,
            }, /* V-model for the edit ingredient */
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
        ...mapActions('ingredients', ['delete', 'update']),
        /**
         * Cancel the edition of an ingredient
         */
        cancel() {
            this.onEdit = null;
        },
        /**
         * Actives the edition of an ingredient
         *
         * @param {Object} ingredient { id, name, price }
         */
        edit(ingredient) {
            this.onEdit = ingredient.id;
            this.updatedIngredient.name = ingredient.ingredient_name;
            this.updatedIngredient.price = ingredient.price;
        },
        /**
         * Call vuex to remove an ingredient on the db
         *
         * @param {Object} ingredient { id, name, price }
         */
        remove(ingredient) {
            try {
                this.delete(ingredient);
            } catch (error) {
                console.error(error);
            }
        },
        /**
         * Call vuex to update an ingredient with the form new data
         *
         * @param {Object} ingredient { id, name, price }
         */
        saveEdit(ingredient) {
            try {
                this.update({ newIngredientData: this.updatedIngredient, ingredient: ingredient });
                this.cancel();
            } catch(error) {
                console.error(error);
            }
        },
    },
}
</script>
