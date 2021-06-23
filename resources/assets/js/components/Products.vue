<template>
    <div class="container container-products">
        <div class="products row">
            <div class="sorting">
                <ul>
                    <li @click="sort('name')">Название
                        <i v-if="sorting.sort_by === 'name'">
                            <i v-if="sorting.sort_type === 'ASC'" class="fa fa-sort-up"></i>
                            <i v-if="sorting.sort_type === 'DESC'" class="fa fa-sort-down"></i>
                        </i>
                    </li>
                    <li @click="sort('price')">Цена
                        <i class="sort" v-if="sorting.sort_by === 'price'">
                            <i v-if="sorting.sort_type === 'ASC'" class="fa fa-sort-up"></i>
                            <i v-if="sorting.sort_type === 'DESC'" class="fa fa-sort-down"></i>
                        </i>
                    </li>
                    <li @click="sort('reviews_count')">Кол-во отзывов
                        <i class="sort" v-if="sorting.sort_by === 'reviews_count'">
                            <i v-if="sorting.sort_type === 'ASC'" class="fa fa-sort-up"></i>
                            <i v-if="sorting.sort_type === 'DESC'" class="fa fa-sort-down"></i>
                        </i>
                    </li>
                </ul>
            </div>
            <div class="product-card col-md-3" v-for="product in items">
                <a :href="'product/' + product.id">
                    <h1>{{ product.name }}</h1>
                    <div class="reviews">
                        <span>{{ product.reviews_count }} <i class="fa fa-comment"></i></span>
                    </div>
                    <img :src="product.image_url">
                    <p>{{ product.description }}</p>
                    <span class="price">{{ product.price }} Руб.</span>
                </a>
            </div>
            <div class="pagination">
                <button :class="{'pag-prev':true, 'text':true, 'disabled': pagination.prev_page_url === null }"
                        @click="fetch_prev(pagination.prev_page_url)">Назад
                </button>
                <span class="pag-cur">{{ pagination.current_page }}</span>
                <button :class="{'pag-next': true, text:true, 'disabled': pagination.next_page_url === null }"
                        @click="fetch_next(pagination.next_page_url)">Вперед
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "MainProducts",
        mounted() {
            if( !!window.Laravel.js_data ) {
                let data = window.Laravel.js_data[0];
                console.log(data);
                if( !! data.next_page_url)
                    data.next_page_url = data.next_page_url.replace('products', 'api/products');
                if( !! data.prev_page_url)
                    data.prev_page_url = data.prev_page_url.replace('products', 'api/products');
                window.Laravel.js_data[0] = data;
                this.$store.dispatch('products/fetch', window.Laravel.js_data);
                delete window.Laravel.js_data;
            }
            else
                this.$store.dispatch("products/fetch_api", {per_page: 16});
        },
        computed: {
            ...mapState("products", ['items', 'pagination', 'sorting']),
        },
        methods: {
            fetch_next: function (url) {
                if (url != null)
                    this.$store.dispatch('products/fetch_api', {
                        fetch_url: url.substring(url.indexOf("api")),
                        per_page: 16
                    });
            },
            fetch_prev: function (url) {
                if (url != null)
                    this.$store.dispatch('products/fetch_api', {
                        fetch_url: url.substring(url.indexOf("api")),
                        per_page: 16
                    });
            },
            sort: function (sort_by) {
                this.$store.dispatch('products/changeSort', {sort_by: sort_by, per_page: 16});
            }
        },
    }
</script>



<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .container-products {
        background-color: $panel-bg;
        margin-top: 50px;
        margin-bottom: 100px;
        .row {
            padding: 45px 20px 65px 20px;
            position: relative;
            .sorting {
                position: absolute;
                top: 10px;
                right: 0;
            }
            .pagination {
                position: absolute;
                bottom: 20px;
                left: calc(50% - 90px);
            }
            .product-card {
                padding: 10px 20px;
                //border: transparent 1px solid;
                margin-bottom: 15px;
                a:hover {
                    color: inherit;
                }
                a {
                    &:hover {
                        //border-color: $border-color;
                        &::before {
                            width: 100%;
                            height: 100%;
                        }
                        &::after {
                            width: 100%;
                            height: 100%;
                        }
                    }
                    &::before {
                        border-left: $border-color 1px solid;
                        border-top: $border-color 1px solid;
                        content: "";
                        position: absolute;
                        top: 0;
                        left: 0;
                        transition: all .5s ease;
                        width: 0;
                        height: 0;
                    }
                    &::after {
                        border-right: $border-color 1px solid;
                        border-bottom: $border-color 1px solid;
                        content: "";
                        position: absolute;
                        bottom: 0;
                        right: 0;
                        transition: all .5s ease;
                        width: 0;
                        height: 0;
                    }
                }
                h1 {
                    float: left;
                    font-size: 1.75em;
                }
                img {
                    width: 100%;
                }
                p {
                    max-height: 130px;
                    overflow: hidden;
                }
                .reviews {
                    float: right;
                }
                .price {
                    float: right;
                }
            }
        }

        .pagination {
            display: block;
            width: 180px;
            margin: auto;
            text-align: center;
            margin-top: 15px;
            font-size: 1.1em;
            line-height: 30px;
            button {
                margin: 0;
            }
            .pag-prev {
                float: left;
            }
            .pag-next {
                float: right;
            }
            .pag-cur {
                font-size: 1.3em;
            }
        }
        .sorting {
            float: right;
            height: 30px;
            li {
                float: left;
                margin-right: 25px;
                cursor: pointer;
                position: relative;
                &:hover {
                    &::before {
                        width: 100%;
                    }
                }
                &::before {
                    content: "";
                    position: absolute;
                    width: 0;
                    transition: width .2s;
                    border-bottom: $accent-color 1px solid;
                    bottom: 0;
                    left: 0;
                }
                .fa {
                    color: $accent-color;
                }
            }
        }
    }

</style>