<template>
    <div>
        <!-- Navbar -->
        <b-navbar toggleable="sm" type="dark" variant="dark">
            <b-navbar-brand href="#">infinety</b-navbar-brand>
            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
            <b-collapse id="nav-collapse" is-nav>
                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <b-nav-item id="order-summary">
                        <b-icon icon="basket3" title="Ver pedido"></b-icon>
                        <b-badge pill variant="light">{{ orderPizzas.length }}</b-badge>
                    </b-nav-item>
                    <b-popover
                        placement="bottomleft"
                        target="order-summary"
                        title="Resumen de tu pedido"
                    >
                        <b-alert
                            show
                            variant="info"
                            v-if="orderPizzas.length == 0"
                        >
                            Aún no hay nada en tu pedido
                        </b-alert>
                        <b-list-group
                        >
                            <b-list-group-item class="py-1">
                                <b-row no-gutters>
                                    <b-col>Pizza 1</b-col>
                                    <b-col>22.1€</b-col>
                                    <b-col class="col-auto">
                                        <b-button
                                            class="order-remove-btn"
                                            size="sm"
                                            title="Borrar pizza"
                                            variant="outline-danger"
                                            @click="remove(pizza)"
                                        >
                                            <b-icon icon="backspace"></b-icon>
                                        </b-button>
                                    </b-col>
                                </b-row>
                            </b-list-group-item>
                            <b-list-group-item class="py-1">
                                <b-row no-gutters>
                                    <b-col>Pizza 1</b-col>
                                    <b-col>22.1€</b-col>
                                    <b-col class="col-auto">
                                        <b-button
                                            class="order-remove-btn"
                                            size="sm"
                                            title="Borrar pizza"
                                            variant="outline-danger"
                                            @click="remove(pizza)"
                                        >
                                            <b-icon icon="backspace"></b-icon>
                                        </b-button>
                                    </b-col>
                                </b-row>
                            </b-list-group-item>
                        </b-list-group>
                        <h6 class="mt-4 text-right">Total: {{ orderPizzas.reduce((acc, curr) => acc + curr, 0) }}€</h6>
                        <hr>
                        <b-row no-gutters>
                            <b-button class="ml-auto mr-0 w-100" variant="outline-success">Realizar pedido</b-button>
                        </b-row>
                    </b-popover>
                    <b-nav-item-dropdown right>
                        <!-- Using 'button-content' slot -->
                        <template #button-content>
                            <b>{{ authenticatedUser }}</b>
                        </template>
                        <b-dropdown-item
                            @click="logout"
                        >
                            Cerrar sesión
                        </b-dropdown-item>
                    </b-nav-item-dropdown>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
        <b-container class="my-3">
            <!-- Pizzas variety -->
            <h1>Pizzas</h1>
            <hr>
            <b-alert
                show
                variant="warning"
                v-if="pizzas.length == 0"
            >
                Cuando crees alguna pizza aparecerá aquí
            </b-alert>
            <span
                v-else
            >
                <b-card-group columns>
                    <view-pizzas
                        v-for="pizza in pizzas"
                        :key="pizza.id"
                        :pizza="pizza"
                    ></view-pizzas>
                </b-card-group>
            </span>
            <b-card-group deck>
                <!-- Create ingredients form -->
                <create-ingredient></create-ingredient>
                <!-- Ingredients variety -->
                <view-ingredients></view-ingredients>
            </b-card-group>
            <!-- Create pizzas form -->
            <create-pizza class="my-3"></create-pizza>
        </b-container>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
export default {
    computed: {
        ...mapGetters('auth', ['authenticatedUser']),
        ...mapGetters('order', ['getOrder']),
        ...mapGetters('pizzas', ['getPizzas']),
        /**
         * Return the current existing pizzas on vuex
         */
        pizzas() {
            return this.getPizzas;
        },
        /**
         * Return the current existing pizzas for an order on vuex
         */
        orderPizzas() {
            return this.getOrder;
        }
    },
    mounted() {
        this.$store.dispatch('ingredients/load');
        this.$store.dispatch('pizzas/load');
    },
    methods: {
        ...mapActions('auth', ['logout']),
        ...mapActions('ingredients', ['load']),
        ...mapActions('pizzas', ['load']),
    }
}
</script>
<style lang="scss" scoped>
    .order-remove-btn {
        height: 25px;
        line-height: 23px;
        padding: 0;
        width: 25px;
    }
</style>
