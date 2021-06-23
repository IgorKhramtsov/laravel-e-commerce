<template>
    <div class="panel-right col-md-4">
        <div class="reviews-wrapper">
            <transition-group name="list" tag="div" class="list" ref="list">
                <div v-for="(review, index) in items" v-if="index < 15"
                     :class="{'admin-review js-admin-review': true, 'active': review.id === selected.id}"
                     :data-id="review.id"
                     :key=" pagination.next_page_url + review.id "
                     @click="set_review(index)"
                >
                    <p class="text">{{ review.text }}</p>
                    <span class="data">{{ review.created_at.substr(0, 10) }}</span>
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
        name: "AdminReviews",
        computed: {
            ...mapState("reviews", ["items", 'pagination', 'selected']),
        },
        mounted() {
            this.$store.dispatch("reviews/fetch");
        },
        methods: {
            set_review: function (ind) {
                this.$store.commit('reviews/SEL_REVIEW', ind);
            },
            fetch_next: function (url) {
                this.$refs.list.$el.className = "list list-to-left";
                if (url != null)
                    this.$store.dispatch('reviews/fetch', { fetch_url: url.substring(url.indexOf("api")) });
            },
            fetch_prev: function (url) {
                this.$refs.list.$el.className = "list list-to-right";
                if (url != null)
                    this.$store.dispatch('reviews/fetch', { fetch_url: url.substring(url.indexOf("api")) });
            },
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

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
        height: 90%;
        .reviews-wrapper {
            height: 700px;
            border-left: 1px $border-color solid;
            .list {
                height: inherit;
                overflow: hidden;
            }
            .admin-review {
                height: 6%;
                padding: 3px 8px;
                box-sizing: border-box;
                overflow: hidden;
                position: relative;
                cursor: pointer;
                &::after {
                    content: "";
                    position: absolute;
                    //bottom: -5px;
                    //left: 0;
                    //width: 100%;
                    //height: 28px;
                    //background: linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 1));
                    bottom: 0;
                    left: 4%;
                    width: 92%;
                    border-bottom: $border-color 1px solid;
                }
                &.active {
                    background-color: darken($panel-bg, 8%);
                    p::after {
                        background: linear-gradient(rgba(255, 255, 255, 0), darken($panel-bg, 8%) 77%);
                    }
                }
                &:hover {
                    background-color: darken($panel-bg, 5%);
                    p::after {
                        background: linear-gradient(rgba(255, 255, 255, 0), darken($panel-bg, 5%) 77%);
                    }
                }
                p {
                    float: left;
                    width: 70%;
                    &::after {
                        content: "";
                        position: absolute;
                        bottom: -5px;
                        left: 0;
                        width: 100%;
                        height: 33px;
                        background: linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 1) 77%);
                    }
                }
                .data {
                    position: absolute;
                    top: 10px;
                    right: 15px;
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
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
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
    }
</style>