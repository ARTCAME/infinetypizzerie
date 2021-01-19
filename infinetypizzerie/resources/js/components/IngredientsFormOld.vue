<template>
    <b-container class="mt-3">
        <b-card style="max-width: 30rem">
            <h1>Registro de ingrediente</h1>
            <hr>
            <b-form
                @submit.prevent="saveIg"
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
import { mapActions } from 'vuex';
export default {
    data() {
        return {
            alertCountdown: 5, /* Improves the UX for the alert feedback on form saves */
            name: null, /* V-model for the ingredient name */
            price: null, /* V-model for the ingredient price */
            result: null, /* Will store the result of the ingredient registration */
        }
    },
    computed: {
        /**
         * Will improve the UX of the send button depending on the form status
         */
        saveBtn() {
            return {
                disabled: this.name == null || this.price == null,
                title: this.name == null || this.price == null ? 'Revisa los campos del formulario' : 'Guardar'
            }
        }
    },
    methods: {
        ...mapActions('ingredients', ['load', 'save']),
        /**
         * Manage the alert auto hide behavior
         */
        countdown(count) {
            this.alertCounddown = count;
        },
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
<style>

</style>
