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
                    <!-- Customer order cart -->
                    <cart></cart>
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
            <b-card-group
                deck
                v-if="authenticatedRole == 'admin'"
            >
                <!-- Shown only to the admin users -->
                <!-- Create ingredients form -->
                <create-ingredient></create-ingredient>
                <!-- Ingredients variety -->
                <view-ingredients></view-ingredients>
            </b-card-group>
            <!-- Pizzas variety -->
            <h1>Pizzas</h1>
            <hr>
            <b-alert
                show
                variant="warning"
                v-if="pizzas.length == 0"
            >
                {{ authenticatedRole == 'admin' ? 'Cuando crees alguna pizza aparecerá aquí' : 'Aún no existen pizzas, un admin debe crearlas' }}
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
            <!-- Create pizzas form -->
            <!-- Shown only to the admin users -->
            <create-pizza
                class="my-3"
                v-if="authenticatedRole == 'admin'"
            ></create-pizza>
        </b-container>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
export default {
    computed: {
        ...mapGetters('auth', ['authenticatedRole', 'authenticatedUser']),
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
