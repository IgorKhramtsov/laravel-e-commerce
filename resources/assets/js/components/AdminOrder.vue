<template>
    <div class="panel-left col-md-8">
        <div class="order-details" v-if="selected.id > 0">
            <div class="order-header">
                <div class="row">
                    <div class="col-6">
                        <span class="name">{{ selected.name }}</span>
                        <div class="w-100"></div>
                        <span class="email">{{ selected.email }}</span>
                    </div>
                    <div class="col-6 float-right text-right">
                        <span class="total">{{ selected.total }} <i class="fa fa-ruble"></i></span>
                    </div>
                </div>
                <div class="row justify-content-end">
                    <div class="col-6">
                        <span class="destination"> {{ selected.country }} - {{ selected.city }} </span>
                    </div>
                    <div class="col-6 float-right text-right">
                        <span class="id col-6">Заказ: {{ selected.id }}</span>
                        <span class="status col-6">{{ selected.status }}</span>
                    </div>
                </div>

            </div>
            <div class="order-table">
                <div class="order-items">
                    <div v-for="(item, index) in selected_items">
                        <div class="row allign-items-center">
                            <div class="image col-2">
                                <div class="img-thumbnail"><img :src="item.product.image_url" alt=""></div>
                            </div>
                            <div class="title col-7 center"><span>{{ item.title }}</span></div>
                            <div class="quantity col-1 center"><span>{{ item.quantity }}</span></div>
                            <div class="price col-2 center"><span>{{ item.price }}</span></div>
                        </div>
                    </div>
                </div>
                <div class="order-summary">
                    <div class="row">
                        <div class="col">
                            <span>Итого</span>
                        </div>
                        <div class="col-6">
                            <span class="text-right float-right">{{ selected.total }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <span>Доставка</span>
                        </div>
                        <div class="col-6">
                            <span class="text-right float-right">{{ selected.shipment_method }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <span>Оплата</span>
                        </div>
                        <div class="col-6">
                            <span class="text-right float-right">{{ selected.payment_method }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from "vuex";

    export default {
        name: "AdminOrder",
        computed: {
            ...mapState("orders", ['items', 'selected', 'selected_items']),
            token: function () {
                return Laravel.token;
            }
        },
        methods: {}
    }
</script>

<style scoped lang="scss">
    @import '~@/_variables.scss';

    .order-details {
        padding: 0 60px;
        .order-header {
            margin-top: 30px;
            padding-bottom: 30px;
            border-bottom: $border-color 1px solid;
            .total {
                font-weight: bold;
                font-size: 2.5em;
            }
            .id {
                text-align: right;
                padding: 0;
                color: $text-secondary-color;
            }
            .status {
                padding: 0;
                padding-left: 10px;
            }
            .name {
                font-size: 1.2em;
            }
            .email, .destination {
                color: $text-secondary-color;
            }
        }
        .order-items {
            width: 100%;
            .row {
                border-bottom: $border-color 1px solid;
                margin: 0;
                padding: 8px 0;
                .center {
                    line-height: 50px;
                    padding: 0;
                }
                .price span {
                    float: right;
                    font-weight: bold;
                }
                .quantity span {
                    padding: 3px 10px;
                    border-top: $border-color 1px solid;
                    border-bottom: $border-color 1px solid;
                    border-radius: 2px;
                    text-align: center;
                }
                .image {
                    padding-left: 0;
                    .img-thumbnail {
                        width: fit-content;
                    }
                    img {
                        width: 40px;
                    }
                }
            }
        }
        .order-summary {
            width: 100%;
            .row {
                border-bottom: $border-color 1px solid;
                margin: 0;
                padding: 8px 0;
                .col, .col-6 {
                    padding: 0;
                }
                .float-right.text-right {
                    font-weight: bold;
                }
            }
        }
    }
</style>