<template>
    <transition appear mode="out-in" name="fade">
    <b-container id="login-ctn">
        <b-card>
            <b-form
                @submit.prevent="login"
            >
                <h1>Inicia sesión</h1>
                <hr>
                <b-alert
                    class="my-2"
                    fade
                    show
                    :variant="isLoggedIn ? 'success' : 'danger'"
                    v-if="errorsMessages.length > 0 || isLoggedIn"
                >
                    <span
                        v-if="isLoggedIn"
                    >
                        Ya has iniciado sesión
                    </span>
                    <span
                        v-else
                    >
                        Se han producido los siguientes errores:
                        <span
                            v-for="(msg, idx) in errorsMessages"
                            :key="idx"
                        >
                            <br>- {{ msg }}
                        </span>
                    </span>
                </b-alert>
                <b-form-group>
                    <b-form-input
                        id="login-email"
                        placeholder="Email"
                        type="text"
                        v-model="email"
                    ></b-form-input>
                </b-form-group>
                <b-form-group>
                    <b-form-input
                        id="login-password"
                        placeholder="Contraseña"
                        type="password"
                        v-model="password"
                    ></b-form-input>
                </b-form-group>
                <b-row no-gutters>
                    <b-button
                        class="ml-0 mr-auto"
                        variant="outline-success"
                        type="submit"
                        v-if="isLoggedIn"
                        @click="$router.push('/')"
                    >
                        {{  authenticatedRole == 'admin' ? 'Ir a dashboard' : 'Realizar pedido' }}
                    </b-button>
                    <b-button
                        class="ml-auto mr-0"
                        :type="isLoggedIn ? 'submit' : ''"
                        :variant="isLoggedIn ? 'outline-warning' : 'outline-primary'"
                        @click="isLoggedIn ? $store.dispatch('auth/logout') : login"
                    >
                        {{ isLoggedIn ? 'Cerrar sesión' : 'Entrar' }}
                    </b-button>
                </b-row>
            </b-form>
        </b-card>
    </b-container>
    </transition>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    data() {
        return {
            email: null, /* V-model for the login email */
            errorsMessages: [], /* Store the errors messages from the api call */
            password: null, /* V-model for the login pass */
        }
    },
    computed: {
        ...mapGetters('auth', ['authStatus', 'isLoggedIn', 'authenticatedRole', 'authenticatedUser']),
    },
    methods: {
        async login(ev) {
            try {
                const loginData = {
                    email: this.email,
                    password: this.password,
                };
                /* Delegate vuex to login attempt the login */
                await this.$store.dispatch('auth/login', loginData);
                /* If the user log in, move it to his dashboard */
                this.$router.push({ name: 'home' });
            } catch (err) {
                /* Always reset the messages */
                this.errorsMessages = [];
                console.error(err.response);
                if (err.response) {
                    /* Get the error messages from the api */
                    const errors = err.response.data.errors;
                    Object.keys(errors).forEach(errorKey => {
                        this.errorsMessages.push(errors[errorKey][0]);
                    });
                } else {
                    /* If there are errors but out of the api process show a default message */
                    this.errorsMessages.push('No se ha podido iniciar sesión')
                }
            }
        }
    }
}
</script>
<style lang="scss">
    #login-ctn {
        left: 50%;
        position: absolute;
        top: 50%;
        transform: translate(-50%, -50%);
    }
</style>
