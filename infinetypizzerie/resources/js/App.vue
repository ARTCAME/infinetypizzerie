<template>
    <div>
        <!-- <navbar-component
            v-if="isLoggedIn && $route.name != 'login'"
        ></navbar-component> -->
        <transition appear mode="out-in" name="fade">
            <router-view></router-view>
        </transition>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    computed: {
        ...mapGetters('auth', ['authStatus', 'isLoggedIn', 'authenticatedRole', 'authenticatedUser']),
    }
}
</script>
<style lang="scss">

/* TRANSITIONS */
/* Opacity transition */
.fade-enter{
    opacity: 0;
}
.fade-enter-active{
    transition: opacity .2s;
}
.fade-leave-active{
    transition: opacity .2s;
    opacity: 0;
}
</style>
