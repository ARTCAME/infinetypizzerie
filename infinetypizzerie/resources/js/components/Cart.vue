<template>
    <b-popover
        id="cart-popover"
        placement="bottomleft"
        target="order-summary"
    >
        <template #title>
            <h6 class="m-0 text-center">Resumen de tu pedido</h6>
        </template>
        <b-alert
            show
            variant="info"
            v-if="orderPizzas.length == 0"
        >
            Aún no hay nada en tu pedido
        </b-alert>
        <b-list-group
            v-else
        >
            <b-list-group-item class="py-1"
                v-for="(pizza, idx) in orderPizzas"
                :key="pizza.id + '-' + idx"
            >
                <b-row no-gutters>
                    <b-col>{{ pizza.name }}</b-col>
                    <b-col>{{ pizza.price }}€</b-col>
                    <b-col class="col-auto">
                        <b-button
                            class="order-remove-btn"
                            size="sm"
                            title="Borrar pizza"
                            variant="outline-danger"
                            @click="deletePizza(pizza)"
                        >
                            <b-icon icon="x"></b-icon>
                        </b-button>
                    </b-col>
                </b-row>
                <small
                    v-for="(ingredient, idx) in pizza.ingredients"
                    :key="pizza.id + '-' + ingredient.id"
                >
                    {{ ingredient.ingredient_name + (idx == (pizza.ingredients.length - 1) ? '' : ', ' ) }}
                </small>
            </b-list-group-item>
        </b-list-group>
        <h6 class="mt-4 text-right">Total: {{ subtotal }}€</h6>
        <hr>
        <b-row no-gutters>
            <b-button
                class="ml-auto mr-0 w-100"
                :disabled="orderPizzas.length == 0"
                :variant="orderPizzas.length == 0 ? 'outline-success' : 'success'"
                @click="createOrder()"
            >
                Realizar pedido
            </b-button>
        </b-row>
    </b-popover>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
export default {
    computed: {
        ...mapGetters('auth', ['authenticatedUser']),
        ...mapGetters('order', ['getOrder']),
        /**
         * Return the current existing pizzas for an order on vuex
         */
        orderPizzas() {
            return this.getOrder;
        },
        /**
         * Total amount of the order
         */
        subtotal() {
            return this.orderPizzas.reduce((acc, curr) => parseFloat(acc) + parseFloat(curr.price), 0);
        },
    },
    methods: {
        ...mapActions('order', ['createOrder', 'deletePizza']),
    }
}
</script>
<style>
    #cart-popover {
        min-width: 20rem!important;
    }
</style>
<style lang="scss" scoped>
    .order-remove-btn {
        height: 25px;
        line-height: 23px;
        padding: 0;
        width: 25px;
    }
</style>
