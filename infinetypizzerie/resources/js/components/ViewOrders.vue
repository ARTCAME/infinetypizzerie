<template>
    <b-card no-body>
        <b-list-group flush>
            <b-list-group-item
                v-for="order in getOrders"
                :key="order.id"
            >
                <b-row>
                    <b-col>
                        <small>Fecha:</small>
                        <br>
                        {{ order.created_at }}
                    </b-col>
                    <b-col>
                        <small>Pedido:</small>
                        <br>
                        <li
                            v-for="(item, idx) in order.order"
                            :key="item.id + '-' + idx"
                        >
                            {{ item.name}}
                        </li>
                    </b-col>
                    <b-col class="col-auto mr-0">
                        <small>Importe:</small>
                        <br>
                        {{ order.price }}€
                    </b-col>
                </b-row>
            </b-list-group-item>
        </b-list-group>
    </b-card>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    data() {
        return {
        }
    },
    computed: {
        ...mapGetters('order', ['getOrders']),
    }
}
</script>
