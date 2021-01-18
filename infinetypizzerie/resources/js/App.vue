<template>
    <transition appear mode="out-in" name="fade">
        <router-view></router-view>
    </transition>
</template>

<script>
export default {
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
