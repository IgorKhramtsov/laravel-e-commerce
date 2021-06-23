<template>
    <div class="reviews">
        <h1>Отзывы</h1>
        <div class="row">
            <div class="review-form">
                <form @submit.prevent="save">
                    <div class="input">
                        <textarea name="text" v-model="text"></textarea>
                    </div>
                    <input type="hidden" name="product_id" :value="product_id">
                    <button class="button text outline">Отправить</button>
                </form>
            </div>
        </div>
        <div class="review row" v-for="review in items">
            <p class="review-text">{{ review.text }}</p>
            <span class="date">{{ review.created_at.substr(0, 10) }}</span>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "reviews",
        mounted() {
            this.$store.dispatch("reviews/fetch", {product_id: this.product_id});
        },
        data() {
            return {
                text: "",
            }
        },
        props: ['product_id'],
        computed: {
            ...mapState("reviews", ['items']),
        },
        methods: {
            save: function() {
                let _this = this;
                this.$store.dispatch('reviews/add', {text: this.text, product_id: this.product_id})
                    .then(response => {
                        if(response.data.status === 'ok')
                            _this.$awn.success("Отзыв добавлен");
                        else
                            _this.$awn.alert("Отзыв не добавлен");
                    })
                    .catch(() => _this.$awn.alert("Отзыв не добавлен"));
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .reviews {
        margin-top: 40px;
        text-align: center;
        h1 {
            border-bottom: $border-color 2px solid;
        }
        .review-form {
            margin: auto;
            .input {
                width: 460px;
                textarea {
                    height: 80px;
                }
            }
        }
        button {
            margin-bottom: 15px;
        }
        .review {
            border-bottom: $border-color 1px solid;
            position: relative;
            padding: 10px 20px;
            width: 500px;
            margin: auto;
            .review-text {
                width: 85%;
                text-align: left;
            }
            .date {
                position: absolute;
                top: 5px;
                right: 15px;
            }
            &:last-child {
                border-bottom: 0;
            }
        }
    }
</style>