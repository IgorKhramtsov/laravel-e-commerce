<template>
    <div class="container-product container">
        <div class="row">
            <div class="left-side col-md-4">
                <img :src="product.image_url">
            </div>
            <div class="right-side col-md-8 align-middle">
                <h1>{{ product.name }}</h1>
                <p>{{ product.description }}</p>
                <span class="price"> {{ product.price }} Руб.</span>

                <form @submit.prevent="buy">
                    <input type="hidden" name="id" v-model="product.id">
                    <input type="hidden" name="quantity" v-model="quantity">
                    <button class="button text outline">В корзину</button>
                </form>
            </div>
        </div>
        <product-reviews :product_id="product.id"></product-reviews>
    </div>
</template>

<script>
    export default {
        name: "ProductPage",
        data() {
            return {
                quantity: 1,
            }
        },
        props: ['product'],
        methods: {
            buy: function () {
                let _this = this;
                let formData = new FormData();
                formData.append("id", this.product.id);
                formData.append("quantity", this.quantity);
                axios.post("/cart/add", formData).then(function (response) {
                    if(response.data.status === "ok") {
                        _this.$awn.success("Товар добавлен в корзину");
                        _this.$store.commit("cart/FETCH", response.data);
                        return;
                    }
                    _this.$store.dispatch("cart/fetch");

                });
            },

        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .container-product {
        margin-top: 50px;
        background: $panel-bg;
        padding: 20px 50px;
        margin-bottom: 80px;
        img {
            width: 450px;
        }
        .right-side {
            margin: auto;
            padding-left: 140px;
            h1 {
                color: $text-accent-color;
            }
            .price {
                padding-bottom: 3px;
                border-bottom: $border-color 1px solid;
            }
        }
        button {
            float: right;
        }

    }
</style>