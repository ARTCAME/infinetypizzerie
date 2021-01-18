<template>
    <div>
        <b-container>
            <h1>Registro de ingredientes</h1>
            <hr>
            <b-form
                @cancel.prevent="cancelIg"
                @submit.prevent="saveIg"
            >
                <b-form-group
                    label="Nombre del ingrediente"
                    label-for="ig-name"
                >
                    <b-form-input
                        id="ig-name"
                        placeholder="Introduce un nombre para el ingrediente"
                        required
                        v-model="name"
                    ></b-form-input>
                </b-form-group>
                <b-button
                    type="cancel"
                    variant="outline-warning"
                >
                    Cancelar
                </b-button>
                <b-button
                    type="submit"
                    variant="primary"
                >
                    Guardar
                </b-button>
            </b-form>
        </b-container>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: null,
        }
    },
    methods: {
        cancelIg() {
            console.log(this.name);
        },
        async saveIg() {
            console.log(this.name);
            const resp = await axios.post('/api/createIngredient', { name: this.name });
            // const resp = await axios.post('/api/createPizza', { name: this.name });
            // const resp = await axios.get('/api/createPizza'/* , { name: this.name } */);
            // console.log(resp);
        },
    }
}
</script>

<style>

</style>
