<template>
    <div class="panel-right col-md-4">
        <div class="products-wrapper">
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
                </ul>
            </div>

            <transition-group name="list" tag="div" class="list" ref="list">
                <div v-for="(product, index) in items" v-if="index < 5"
                     :class="{'admin-product-card js-admin-product':true, active: product.id === selected.id}"
                     :key="pagination.next_page_url + product.id"
                     @click="set_product(index, $event)"
                >
                    <h1 class="name">{{ product.name }}</h1>
                    <p class="description">{{ product.description }}</p>
                    <span class="price">{{ product.price }} <i class="fa fa-ruble"></i></span>
                    <span class="id">{{ product.id }}</span>
                </div>
            </transition-group>

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
    import { mapState } from "vuex";

    export default {
        name: "AdminProducts",
        mounted() {

                this.$store.dispatch('products/fetch_api');
        },
        computed: {
            ...mapState("products", ['items', 'pagination', 'sorting', 'selected']),
        },
        methods: {
            set_product: function (ind, $event) {
                this.$store.commit('products/SEL_PRODUCT', ind);
            },
            fetch_next: function (url) {
                this.$refs.list.$el.className = "list list-to-left";
                if (url != null)
                    this.$store.dispatch('products/fetch_api', { fetch_url: url.substring(url.indexOf("api")) });
            },
            fetch_prev: function (url) {
                this.$refs.list.$el.className = "list list-to-right";
                if (url != null)
                    this.$store.dispatch('products/fetch_api', { fetch_url: url.substring(url.indexOf("api")) });
            },
            sort: function (sort_by) {
                this.$store.dispatch('products/changeSort', { sort_by: sort_by });
            }
        },
    }

</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';
    @import '~bootstrap/scss/bootstrap';

    .list-enter-active {
        position: relative;
        transition: all .5s;
        left: 0;
        opacity: 0;
    }
    .list-leave-active {
        position: relative;
        transition: all .2s;
        left: 0;
    }
    .list-to-right .list-leave-to {
        left: 300px;
        opacity: 0;
    }
    .list-to-left .list-leave-to {
        left: -300px;
        opacity: 0;
    }
    .list-enter-to {
        transition-delay: .25s;
        opacity: 1;
    }


    .panel-right {
        overflow: hidden;
        height: 90%;
        .products-wrapper {
            height: 700px;
            border-left: $border-color 1px solid;
            position: relative;
        }
        .list {
            height: 94%;
            position: relative;
            top: 20px;
            .admin-product-card {
                height: 16%;
                width: 100%;
                @include clearfix;
                cursor: pointer;
                padding: 10px 15px;
                overflow: hidden;
                position: relative;
                //transition: all.2s;
                //&-move {
                    //transition: transform.5s;
                //}
                &::after {
                    border-bottom: $border-color 1px solid;
                    display: block;
                    content: "";
                    position: absolute;
                    left: 4%;
                    width: 92%;
                    bottom: 0;
                }
                &.active {
                    background-color: darken($panel-bg, 8%);
                    .description::after {
                        background: linear-gradient(rgba(255, 255, 255, 0), darken($panel-bg, 8%) 30%);
                    }
                }
                &:hover {
                    background-color: darken($panel-bg, 5%);
                    .description::after {
                        background: linear-gradient(rgba(255, 255, 255, 0), darken($panel-bg, 5%) 30%);
                    }
                }
                .description {
                    width: 90%;
                    float: left;
                    &::after {
                        content: "";
                        position: absolute;
                        bottom: -5px;
                        left: 0;
                        width: 100%;
                        height: 62px;
                        background: linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 1) 30%);
                    }
                }
                .price {
                    position: absolute;
                    bottom: 10px;
                    right: 15px;
                }
                .id {
                    position: absolute;
                    top: 10px;
                    right: 15px;
                }
                .name {
                    font-size: 1.1em;
                    font-weight: bold;
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
            position: absolute;
            top: 0;
            right: 0;
            @include clearfix;
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