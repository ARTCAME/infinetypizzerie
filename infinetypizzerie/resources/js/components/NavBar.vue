<template>
    <b-navbar
        fixed="top"
        toggleable="md"
    >
        <span>
            <b-navbar-brand>
                <router-link>
                </router-link>
            </b-navbar-brand>
            <b-navbar-nav>
                <b-nav-text
                    v-if="isLoggedIn">
                    <router-link
                        :title="'Has iniciado sesión con el usuario: ' + authenticatedUser"
                        :to="{ name : 'home' }">
                    </router-link>
                </b-nav-text>
            </b-navbar-nav>
            <button
                :class="'navbar-toggler' + (collapsed ? ' not-collapsed' : ' collapsed')"
                @click="collapsed = !collapsed">
                <fa-icon icon="bars"></fa-icon>
            </button>
            <b-collapse
                id="nav-collapse"
                is-nav
                v-model="collapsed"
                @hide="navExpand('ig-main-navbar')"
                @show="navExpand('ig-main-navbar')">
                <b-navbar-nav id="main-navbar-nav" class="ml-auto">
                    <b-navbar-nav
                        v-if="authenticatedRole == 'user'">
                        <b-nav-item
                            active-class="active"
                            class="p-2"
                            exact
                            v-for="(route, key) in routes.user"
                            :key="route.path">
                            <router-link
                                :id=route.name
                                :key="key"
                                :to="{ name : route.path }">
                                {{ route.name }}
                            </router-link>
                        </b-nav-item>
                    </b-navbar-nav>
                    <b-navbar-nav
                        v-if="authenticatedRole == 'admin'">
                        <b-nav-item
                            active-class="active"
                            class="p-2"
                            exact
                            v-for="(route, key) in routes.admin"
                            :key="route.path">
                            <router-link
                                :id=route.name
                                :key="key"
                                :to="{ name : route.path }">
                                {{ route.name }}
                            </router-link>
                        </b-nav-item>
                    </b-navbar-nav>
                    <b-navbar-nav
                        v-if="authenticatedRole == 'tester'">
                        <b-nav-item
                            active-class="active"
                            class="p-2"
                            exact
                            v-for="(route, key) in routes.tester"
                            :key="route.path">
                            <router-link
                                :id=route.name
                                :key="key"
                                :to="{ name : route.path }">
                                {{ route.name }}
                            </router-link>
                        </b-nav-item>
                    </b-navbar-nav>
                    <b-navbar-nav
                        v-if="authenticatedRole == 'root'">
                        <b-nav-item
                            active-class="active"
                            class="p-2"
                            exact
                            v-for="(route, key) in routes.root"
                            :key="route.path">
                            <router-link
                                :id=route.name
                                :key="key"
                                :to="{ name : route.path }">
                                {{ route.name }}
                            </router-link>
                        </b-nav-item>
                    </b-navbar-nav>
                    <b-navbar-nav>
                        <b-nav-item
                            class="p-2 pl-md-3"
                            v-if="isLoggedIn">
                            <a
                                href="#"
                                class="text-muted"
                                @click.prevent="inLogout()">
                                CERRAR SESIÓN
                            </a>
                        </b-nav-item>
                    </b-navbar-nav>
                </b-navbar-nav>
            </b-collapse>
        </span>
  <b-row>
      <b-col>
          {{ user.name }}
      </b-col>
      <b-col
        v-if="user.role === 'admin'"
      >
        <router-link
      </b-col>
  </b-row>
</template>

<script>
export default {

}
</script>

<style>

</style>
