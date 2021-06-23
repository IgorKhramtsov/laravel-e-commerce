<template>
    <button class="button text">
        <a :href="url" class="nav-basket">
            <span :class="{'has-badge': items_count > 0, 'fa-stack fa-1x ': true}" :data-count="items_count">
                <i class="fa fa-shopping-cart fa-stack-1x"></i>
            </span>
        </a>
    </button>
</template>

<script>
    import {mapState, mapGetters } from "vuex";

    export default {
        name: "CartNavIcon",
        props: ["url"],
        mounted() {
            this.$store.dispatch('cart/fetch');
        },
        computed: {
            ...mapState("cart", ['items']),
            ...mapGetters("cart", ['items_count']),
            count: function () {
                return this.$store.items_count;
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

</style>