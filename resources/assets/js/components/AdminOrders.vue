<template>
    <div class="panel-right col-md-4 row">
        <div class="orders-wrapper col-12">
            <div class="sorting col-12">
            <span @click="sort('created_at')">Дата
                <i v-if="sorting.sort_by === 'created_at'">
                    <i v-if="sorting.sort_type === 'ASC'" class="fa fa-sort-up"></i>
                    <i v-if="sorting.sort_type === 'DESC'" class="fa fa-sort-down"></i>
                </i>
            </span>
                <span @click="sort('total')">Стоимость
                <i class="sort" v-if="sorting.sort_by === 'total'">
                    <i v-if="sorting.sort_type === 'ASC'" class="fa fa-sort-up"></i>
                    <i v-if="sorting.sort_type === 'DESC'" class="fa fa-sort-down"></i>
                </i>
            </span>
                <span @click="sort('status')">Статус
                <i class="sort" v-if="sorting.sort_by === 'status'">
                    <i v-if="sorting.sort_type === 'ASC'" class="fa fa-sort-up"></i>
                    <i v-if="sorting.sort_type === 'DESC'" class="fa fa-sort-down"></i>
                </i>
            </span>
            </div>
            <transition-group name="list" tag="div" class="list" ref="list">
                <div v-for="(arr, index) in items" :key=" pagination.next_page_url + index ">
                    <div class="date-group">{{ index }}</div>
                    <div v-for="(item, index2) in arr"
                         :class="{'admin-order-item js-admin-order row': true, active: item.id === selected.id}"
                         :key="Math.random()"
                         @click="select(index, index2)">
                        <span class="time col-6"> {{ item.time }} </span>
                        <span class="total col-6 float-right"> {{ item.total }} <i class="fa fa-ruble"></i> </span>
                        <div class="w-100"></div>
                        <span class="id-status col-6"> {{ item.id }} - {{ item.status }} </span>
                        <span class="name col-6 float-right"> {{ item.name }} </span>
                    </div>
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
        name: "AdminOrders",
        mounted() {
            this.$store.dispatch('orders/fetch_api');
        },
        computed: {
            ...mapState("orders", ['items', 'pagination', 'sorting', 'selected']),
        },
        methods: {
            select: function (ind_first, index_second, $event) {
                this.$store.commit('orders/SEL_ORDER', { ind_first: ind_first, ind_sec: index_second });
            },
            fetch_next: function (url) {
                this.$refs.list.$el.className = "list list-to-left";
                if (url != null)
                    this.$store.dispatch('orders/fetch_api', { fetch_url: url.substring(url.indexOf("api")) });
            },
            fetch_prev: function (url) {
                this.$refs.list.$el.className = "list list-to-right";
                if (url != null)
                    this.$store.dispatch('orders/fetch_api', { fetch_url: url.substring(url.indexOf("api")) });
            },
            sort: function (sort_by) {
                this.$store.dispatch('orders/changeSort', { sort_by: sort_by });
            }
        },
    }
</script>

<style scoped lang="scss">
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
        padding-right: 0;
        margin-right: 0;
        .orders-wrapper {
            height: 100%;
            border-left: $border-color 1px solid;
            padding: 0;
        }
        .list, .list-to-left, .list-to-right {
            position: relative;
            height: 600px;
            .date-group {
                background: #F6F6F7;
                font-size: .9em;
                padding: 3px 13px;
                font-weight: bold;
            }
            .admin-order-item {
                margin: 0;
                position: relative;
                cursor: pointer;
                overflow: hidden;
                padding: 5px 0;
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
                }
                &:hover {
                    background-color: darken($panel-bg, 5%);
                }
                .float-right {
                    text-align: right;
                }
                .id-status {
                    color: lighten($text-color, 50%);
                }
                .name {
                    color: lighten($text-color, 50%);
                }
                .total {
                    font-size: 1.2em;

                }
                .time {

                    font-size: 1.2em;
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
            text-align: center;
            padding: 5px 0;
            span {
                margin-right: 20px;
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