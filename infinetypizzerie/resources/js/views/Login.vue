<template>
    <transition appear mode="out-in" name="fade">
        <b-container id="login-ctn">
            <b-card no-body>
                <b-tabs card>
                    <!-- Login -->
                    <b-tab title="Inicia sesión" active>
                        <b-form
                            @submit.prevent="loginUser"
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
                                    type="email"
                                    v-model="loginData.email"
                                ></b-form-input>
                            </b-form-group>
                            <b-form-group>
                                <b-form-input
                                    id="login-password"
                                    placeholder="Contraseña"
                                    type="password"
                                    v-model="loginData.password"
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
                    </b-tab>
                    <!-- Register -->
                    <b-tab
                        title="Regístrate"
                        v-if="!isLoggedIn"
                    >
                        <b-form
                            @submit.prevent="newUser"
                        >
                            <h1>Regístrate</h1>
                            <hr>
                            <b-alert
                                class="my-2"
                                fade
                                show
                                variant="danger"
                                v-if="userExists"
                            >
                                <span>
                                    Este e-mail ya esta registrado, introduce otro
                                </span>
                                <span
                                    v-if="errorsMessages.length > 0"
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
                                    id="reg-name"
                                    placeholder="Nombre"
                                    type="text"
                                    v-model="regData.name"
                                ></b-form-input>
                            </b-form-group>
                            <b-form-group>
                                <b-form-input
                                    id="reg-email"
                                    placeholder="Email"
                                    type="email"
                                    v-model="regData.email"
                                ></b-form-input>
                            </b-form-group>
                            <b-form-group>
                                <b-form-input
                                    id="reg-password"
                                    placeholder="Contraseña"
                                    type="password"
                                    v-model="regData.password"
                                ></b-form-input>
                            </b-form-group>
                            <b-form-group>
                                <b-form-select
                                    id="reg-role"
                                    placeholder="Contraseña"
                                    v-model="regData.role"
                                    :options="['admin', 'user']"
                                ></b-form-select>
                            </b-form-group>
                            <b-row no-gutters>
                                <b-button
                                    class="ml-auto mr-0"
                                    type="submit"
                                    variant="outline-primary"
                                    :disabled="userExists"
                                >
                                    Regístrate
                                </b-button>
                            </b-row>
                        </b-form>
                    </b-tab>
                </b-tabs>
            </b-card>
        </b-container>
    </transition>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
export default {
    data() {
        return {
            loginData: {
                email: null,
                password: null,
            }, /* V-model for the login data */
            errorsMessages: [], /* Store the errors messages from the api call */
            regData: {
                name: null,
                email: null,
                password: null,
                role: null,
            }, /* V-model for the register data */
        }
    },
    computed: {
        ...mapGetters('auth', ['authStatus', 'isLoggedIn', 'authenticatedRole', 'authenticatedUser', 'getExistingUsernames']),
        /**
         * Returns if a to register email is already used
         */
        userExists() {
            return this.getExistingUsernames.includes(this.regData.email);
        }
    },
    methods: {
        ...mapActions('auth', ['currentUsernames', 'login', 'register']),
        async loginUser() {
            try {
                /* Delegate vuex to attempt the login */
                await this.login(this.loginData);
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
        },
        async newUser() {
            try {
                /* Delegate vuex to attempt the login */
                await this.register(this.regData);
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
                    this.errorsMessages.push('No se ha podido registrar el usuario')
                }
            }
        },
    },
    mounted() {
        /* Load the existing usernames registered */
        this.currentUsernames();
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
