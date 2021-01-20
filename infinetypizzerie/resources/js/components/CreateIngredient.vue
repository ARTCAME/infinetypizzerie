<template>
    <create-form
        formTitle="Registro de ingrediente"
        :reset="resetForm"
        :result="result"
        :save="saveIg"
        :saveBtnDisabled="name == null || price == null"
        :saveBtnTitle="name == null || price == null ? 'Revisa los campos del formulario' : 'Guardar'"
    >
        <template #inputs>
            <b-form-group
                label="Nombre del ingrediente"
                label-for="ig-name"
            >
                <b-form-input
                    id="ig-name"
                    placeholder="Introduce un nombre para el ingrediente"
                    required
                    type="text"
                    v-model="name"
                ></b-form-input>
            </b-form-group>
            <b-form-group
                label="Precio del ingrediente por unidad"
                label-for="ig-price"
            >
                <b-form-input
                    id="ig-price"
                    min="0"
                    placeholder="Introduce un precio para el ingrediente"
                    required
                    step="0.01"
                    type="number"
                    v-model="price"
                ></b-form-input>
            </b-form-group>
        </template>
    </create-form>
</template>

<script>
import { mapActions } from 'vuex';
export default {
    data() {
        return {
            name: null, /* V-model for the ingredient name */
            price: null, /* V-model for the ingredient price */
            result: null, /* Will store the result of the ingredient registration */
        }
    },
    methods: {
        ...mapActions('ingredients', ['save']),
        /**
         * Just reset the v-models related to the created ingredient
         */
        resetForm() {
            this.name = null;
            this.price = null;
        },
        /**
         * Saves the new ingredient
         */
        async saveIg() {
            try {
                /* Vuex stores at the db */
                await this.save({ name: this.name, price: this.price });
                this.result = {
                    status: 'success',
                    message: 'Ingrediente creado correctamente',
                }
            } catch (error) {
                console.error(error);
                this.result = {
                    status: 'danger',
                    message: 'No se ha podido crear el ingrediente',
                }
            } finally {
                this.resetForm();
            }
        },
    }
}
</script>
