<template>
    <b-card
        img-src="../img/pizza1.jpg"
        img-top
        :img-alt="pizza.name"
        :title="pizza.name"
    >
        <b-card-text>
            <b>Ingredients</b>
            <ul>
                <li
                    v-for="(ig, idx) in pizza.ingredients"
                    :key="idx"
                >
                    {{ ig }}
                </li>
            </ul>
        </b-card-text>
    </b-card>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    computed: {
        ...mapGetters('ingredients', ['getIngredients']),
    },
    props: {
        pizza: Object, /* The pizza to show on the card */
    }
}
</script>

<style>

</style>
