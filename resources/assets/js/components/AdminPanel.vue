<template>
    <div class="container container-admin">
        <div class="row">
            <div class="left-panel col-md-2">
                <ul>
                    <li v-for="(panel, index) in panels"
                        :class=" {'active': currentIndex === index} "
                        @click="currentIndex = index">{{ panelNames[index] }}
                    </li>
                </ul>
            </div>
            <div class="main-panel col-md-10 js-admin-mpanel">
                <h1 class="title"> {{ panelNames[currentIndex] }} </h1>
                <div class="row">
                    <transition name="component-translate-left" mode="out-in" appear>
                        <component :is="currentPanelComponentLeft"></component>
                    </transition>
                    <transition name="component-translate-right" mode="out-in" appear>
                        <component :is="currentPanelComponentRight"></component>
                    </transition>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from "vuex"
    import AdminProducts from "./AdminProducts.vue";
    import AdminProductsForm from "./AdminProductsForm.vue";
    import AdminReviews from "./AdminReviews.vue";
    import AdminReviewsForm from "./AdminReviewsForm.vue";
    import AdminOrder from "./AdminOrder.vue";
    import AdminOrders from "./AdminOrders.vue";

    export default {
        name: "AdminPanel",
        components: {
            'panel_products': AdminProductsForm,
            'panel_products_right': AdminProducts,
            'panel_reviews': AdminReviewsForm,
            'panel_reviews_right': AdminReviews,
            'panel_orders': AdminOrder,
            'panel_orders_right': AdminOrders,
        },
        data() {
            return {
                currentIndex: 0,
                panels: ['products', 'reviews', 'orders'],
                panelNames: ['Товары', 'Отзывы', 'Заказы'],
            };
        },
        computed: {
            currentPanelComponentLeft: function() {
                return 'panel_' + this.panels[this.currentIndex];
            },
            currentPanelComponentRight: function() {
                return 'panel_' + this.panels[this.currentIndex] + '_right';
            }
        },
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';
    @import '~bootstrap/scss/bootstrap';

    .component-translate-left{
        &-enter-active, &-leave-active {
            transition: all .3s ease;
            position: relative;
            left: 0;
        }
        &-enter, &-leave-to {
            left: -100%
        }
    }
    .component-translate-right{
        &-enter-active, &-leave-active {
            transition: all .5s ease;
            position: relative;
            right: 0;
        }
        &-enter, &-leave-to {
            right: -100%
        }
    }

    .container-admin {
        background-color: $panel-bg;
        margin-top: 50px;
        height: 80vh;
        .row {
            height: inherit;
            .main-panel {
                .row {
                    overflow: hidden;
                }
                .title {
                    padding-top: 10px;
                    margin-bottom: 0;
                    padding-left: 15px;
                    padding-bottom: 5px;
                    border-bottom: $border-color 1px solid;
                    text-transform: uppercase;
                    font-size: 1.7em;
                }
                .orders {
                    padding-right: 0;
                    margin-right: 0;
                    margin-left: 0;
                }
            }
            .left-panel {
                background-color: $panel-bg-darken;
                height: 100%;
                padding: 0;
                ul {
                    padding-top: 30px;
                    padding-left: 0;
                    li {
                        color: lighten($text-light-color, 12%);
                        font-size: 1.35em;
                        padding: 10px 0;
                        cursor: pointer;
                        text-align: center;
                        &.active {
                            background-color: #ce5835;
                            box-shadow: inset 0 0 2px rgba(#333, 0.2);
                        }
                        &:hover {
                            background-color: lighten($panel-bg-darken, 5%);
                            box-shadow: 0 0 2px rgba(#333, 0.2);
                        }
                    }
                }
            }
            .add-product {
                text-align: center;
            }
        }
    }
</style>
