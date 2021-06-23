<template>
    <div class="container cart">
        <transition name="fade" appear mode="out-in">
            <div v-if="items_count > 0" key="cart">
                <table>
                    <tr v-for="(item, id) in items" class="item">
                        <td class="image"><a :href="'/product/' + id"> <img :src="item.image_url" :alt="item.title"> </a>
                        </td>
                        <td class="title"><a :href="'/product/' + id"> {{ item.title }} </a></td>
                        <td class="inputs">
                            <span class="label">Количество</span>
                            <div v-if="is_created">
                                <span class="quantity"> {{ item.quantity }} </span>
                            </div>

                            <div class="input-group" v-else>
                                <span>
                                    <button type="button" class="button" :disabled="item.quantity <= 1" data-type="minus"
                                            @click="sub(id, item.quantity - 1)">
                                      <span class="fa fa-minus"></span>
                                    </button>
                                </span>

                                    <input type="text" name="quantity" v-model="item.quantity" min="1" max="10" @change="update(id, item)">

                                    <span>
                                    <button type="button" class="button" data-type="plus" @click="add(id, item.quantity + 1)">
                                      <span class="fa fa-plus"></span>
                                    </button>
                                </span>
                            </div>
                        </td>
                        <td class="summ"> {{ item.price * item.quantity }} <i class="fa fa-ruble"></i></td>
                        <td class="remove" >
                            <button @click="remove(id)" class="button text"><i class="fa fa-remove" v-if=" !is_created "></i></button>
                        </td>
                    </tr>

                    <tr class="item">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="total">Итого: <span class="amount"> {{ total }} <i class="fa fa-ruble"></i> </span></td>
                        <td></td>
                    </tr>
                </table>

                <transition name="fade">
                    <div key="order_info" class="order-info" v-if=" !is_created ">
                    <div class="row">
                        <div :class="{'contacts col': true, 'filled': is_order_contact_filled} ">
                                <h2 class="title">Контактные данные</h2>
                                <div class="row">
                                    <div class="input col-12">
                                        <input type="text" name="name" id="email" v-model="order.contact.email" @change="updateOrder" required>
                                        <label for="email">Электронная почта</label>
                                    </div>
                                    <div class="input col-12">
                                        <input type="tel" name="phone" id="phone" v-model="order.contact.phone" @change="updateOrder" required>
                                        <label for="phone">Телефон</label>
                                    </div>
                                    <div class="input col-6">
                                        <input type="text" name="firstName" id="fname" v-model="order.contact.fname" @change="updateOrder"
                                               required>
                                        <label for="fname">Имя</label>
                                    </div>
                                    <div class="input col-6 float-right">
                                        <input type="text" name="lastName" id="lname" v-model="order.contact.lname" @change="updateOrder" required>
                                        <label for="lname">Фамилия</label>
                                    </div>
                                    <div class="input col-6 float-left">
                                        <input type="text" name="middleName" id="mname" v-model="order.contact.mname" @change="updateOrder"
                                               required>
                                        <label for="mname">Отчество</label>
                                    </div>
                                </div>
                            </div>

                        <div :class="{'address col': true, 'filled': is_order_address_filled} ">
                            <h2 class="title">Адрес доставки</h2>
                            <div class="row">
                                <div class="input col-12">
                                    <input type="text" name="country" id="cntry" v-model="order.address.country" @change="updateOrder" required>
                                    <label for="cntry">Страна</label>
                                </div>
                                <div class="input col-8">
                                    <input type="text" name="city" id="city" v-model="order.address.city" @change="updateOrder" required>
                                    <label for="city">Город</label>
                                </div>
                                <div class="input col-4">
                                    <input type="text" name="zip" id="zip" v-model="order.address.zip" @change="updateOrder" required>
                                    <label for="zip">Индекс</label>
                                </div>
                                <div class="input col-8">
                                    <input type="text" name="address" id="address" v-model="order.address.address" @change="updateOrder" required>
                                    <label for="address">Адрес</label>
                                </div>
                                <div class="input col-4">
                                    <input type="text" name="home" id="home" v-model="order.address.home" @change="updateOrder" required>
                                    <label for="home">Номер дома</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div :class="{'shipment col': true, 'filled': is_order_shipment_choosed}">
                            <h2 class="title">Метод доставки</h2>
                            <div>
                                <div class="md-radio">
                                    <input type="radio" id="shipment_1" name="shipment_method" value="russian_post" @change="updateOrder"
                                           v-model="order.shipment_method">
                                    <label for="shipment_1">Почта России</label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" id="shipment_2" name="shipment_method" value="ponyexpress" @change="updateOrder"
                                           v-model="order.shipment_method">
                                    <label for="shipment_2">PonyExpress</label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" id="shipment_3" name="shipment_method" value="cdek" @change="updateOrder"
                                           v-model="order.shipment_method">
                                    <label for="shipment_3">CDEK</label>
                                </div>
                            </div>
                        </div>

                        <div :class="{'payment col': true, 'filled': is_order_payment_choosed}">
                            <h2 class="title">Метод оплаты</h2>
                            <div>
                                <div class="md-radio">
                                    <input type="radio" id="paymethod_1" name="payment_method" value="visa_mastercard" @change="updateOrder"
                                           v-model="order.payment_method">
                                    <label for="paymethod_1">Visa/MasterCard</label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" id="paymethod_2" name="payment_method" value="bank" @change="updateOrder"
                                           v-model="order.payment_method">
                                    <label for="paymethod_2">Банковский перевод</label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" id="paymethod_3" name="payment_method" value="qiwi_paypal" @change="updateOrder"
                                           v-model="order.payment_method">
                                    <label for="paymethod_3">Qiwi/Paypal</label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" id="paymethod_4" name="payment_method" value="cash" @change="updateOrder"
                                           v-model="order.payment_method">
                                    <label for="paymethod_4">Наложенный платеж</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </transition>

                <transition name="fade">
                    <div key="payment_button" class="make-payment" v-if=" is_created ">
                        <div class="row justify-content-end">
                            <div class="col-3">
                                <form method="post" action="https://wl.walletone.com/checkout/checkout/Index">
                                    <input type="hidden" name="WMI_MERCHANT_ID"    :value=" WMI.WMI_MERCHANT_ID ">
                                    <input type="hidden" name="WMI_PAYMENT_AMOUNT" :value=" WMI.WMI_PAYMENT_AMOUNT ">
                                    <input type="hidden" name="WMI_CURRENCY_ID"    :value=" WMI.WMI_CURRENCY_ID ">
                                    <input type="hidden" name="WMI_DESCRIPTION"    :value=" WMI.WMI_DESCRIPTION ">
                                    <input type="hidden" name="WMI_SUCCESS_URL"    :value=" WMI.WMI_SUCCESS_URL ">
                                    <input type="hidden" name="WMI_FAIL_URL"       :value=" WMI.WMI_FAIL_URL ">
                                    <input type="hidden" name="WMI_SIGNATURE"      :value=" WMI.WMI_SIGNATURE ">
                                    <input type="hidden" name="order_id"           :value=" WMI.order_id">
                                    <button class="button float-right accent">Перейти к оплате</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div key="order_button" class="make-order" v-else >
                        <div class="row justify-content-end">
                            <div class="col-2">
                                <button :class="{'button float-right accent': true, 'disabled': is_order_data_empty }" @click="createOrder">Подтвердить</button>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>

            <div v-else class="empty" key="empty_cart">
                <div>
                    <span> Ваша корзина пуста :( </span>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    import {mapState, mapGetters} from "vuex";

    export default {
        name: "Cart",
        mounted() {
            this.$store.dispatch('cart/fetch');
        },
        computed: {
            ...mapState("cart", ['items', 'order', 'WMI']),
            ...mapGetters("cart", ['total', 'items_count', 'is_order_data_empty', 'is_order_contact_filled',
                'is_order_address_filled', 'is_order_shipment_choosed', 'is_order_payment_choosed']),
            is_created() {
                return (typeof this.WMI!== 'undefined' && typeof this.WMI.WMI_SIGNATURE !== 'undefined');
            }
        },
        methods: {
            add: function (index, quantity) {
                let _this = this;
                this.$store.dispatch("cart/quantity_upd", {id: index, quantity: quantity})
                    .then(response => {
                        if(response.data.status === 'ok')
                            _this.$awn.success("Количество изменено");
                        else
                            _this.$awn.alert("Количество не изменено");
                    })
                    .catch(() => { _this.$awn.alert("Количество не изменено"); });
            },
            sub: function (index, quantity) {
                let _this = this;
                if (quantity > 0)
                    this.$store.dispatch("cart/quantity_upd", {id: index, quantity: quantity})
                        .then(response => {
                            if(response.data.status === 'ok')
                                _this.$awn.success("Количество изменено");
                            else
                                _this.$awn.alert("Количество не изменено");
                        })
                        .catch(() => { _this.$awn.alert("Количество не изменено"); });
                else
                    _this.$awn.alert("Количество не может быть меньше 1");
            },
            update: function(index, item) {
                let _this = this;
                this.$store.dispatch("cart/quantity_upd", {id: index, quantity: item.quantity})
                    .then(response => {
                        if(response.data.status === 'ok')
                            _this.$awn.success("Количество изменено");
                        else
                            _this.$awn.alert("Количество не изменено");
                    })
                    .catch(() => { _this.$awn.alert("Количество не изменено"); });
            },
            remove: function (index) {
                let _this = this;
                this.$store.dispatch("cart/remove", {id: index})
                    .then(response => {
                        if(response.data.status === 'ok')
                            _this.$awn.success("Товар удален из корзины");
                        else
                            _this.$awn.alert("Товар не удален из корзины");
                    })
                    .catch(() => { _this.$awn.alert("Товар не удален из корзины"); });
            },
            goToPayment: function() {
                this.order_stage = ORDER_PAYMENT;
            },
            backToDetails: function() {
                this.order_stage = ORDER_DETAILS;
            },
            createOrder: function () {
                let _this = this;
                this.$store.dispatch("cart/createOrder")
                    .then(response => {
                        if(response.data.status === 'ok')
                            _this.$awn.success("Заказ создан");
                        else
                            _this.$awn.alert("Заказ не создан");
                    })
                    .catch(() => { _this.$awn.alert("Заказ не создан"); });
            },
            updateOrder: function () {
                this.$store.dispatch("cart/saveOrder");
            },
        },
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .fade-enter-active, .fade-leave-active {
        transition: opacity .2s;
    }
    .fade-enter, .fade-leave-to {
        opacity: 0;
    }

    .cart {
        margin-top: 50px;
        margin-bottom: 80px;
        table>tr, .col, .empty {
            box-shadow: 1px 1px 2px $border-color;
        }
        .empty {
            background: $panel-bg;
            height: 400px;
            color: lighten($text-color, 20%);

            &>div {
                text-align: center;
                line-height: 400px;
                font-size: 2.5em;
            }
        }
        table {
            border-spacing: 0 15px;
            border-collapse: separate;
        }
        .item {
            background: $panel-bg;
            width: 100%;
            td {
                padding: 20px;
                padding-left: 30px;
                position: relative;
                &.title {
                    width: 15%;
                    text-align: left;
                    color: $accent-color;
                    font-size: 1.3em;
                    padding-left: 0;
                }
                &.image {
                    padding-right: 5px;
                    width: 10%;
                    img {
                        width: 100px;
                        border-radius: 100%;
                    }
                }
                &.inputs {
                    width: 20%;
                    .label {
                        position: absolute;
                        top: 28px;
                        left: 30px;
                        color: lighten($text-color, 60%);
                        font-weight: bold;
                    }
                    .input-group {
                        height: 40px;
                        input {
                            width: 60px;
                            text-align: center;
                            border: $border-color 1px solid;
                        }
                        button {
                            height: 40px;
                            line-height: 20px;
                            .fa {
                                font-size: 1.2em;
                            }
                        }
                    }
                }
                &.summ {
                    width: 40%;
                    text-align: right;
                    font-size: 2em;
                    .fa {
                        font-size: .9em;
                    }
                }
                &.remove {
                    width: 5%;
                    font-size: 1.2em;
                    color: $error-color;
                }

                &.total {
                    text-align: right;
                    font-size: 1.35em;
                    .amount {
                        font-size: 1.4em;
                    }
                }
            }

        }

        .order-info {
            padding: 0 15px;
            .row {
                margin-bottom: 15px;
                .col {
                    background: $panel-bg;
                    padding: 20px 30px;
                    border: transparent 1px solid;
                    transition: border-color.5s;
                    &.filled {
                        border-color: rgba($valid-color, .4);
                    }
                    h2.title {
                        font-size: 1.4em;
                        margin-bottom: 15px;
                    }
                    .input {
                        margin: 0;
                        margin-bottom: 10px;
                        label {
                            left: 25px;
                        }
                    }
                    &:first-child {
                        margin-right: 15px;
                    }
                }
            }
        }
    }

</style>