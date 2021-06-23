<template>
    <div class="panel-left reviews col-md-8">
        <div class="admin-review-form" v-if="selected.id > 0">
            <input type="hidden" name="_token" :value="token">
            <input type="hidden" name="id" v-model="selected.id">
            <p>{{ selected.text }}</p>
            <button class="button text delete" @click="delete_review"> Удалить</button>
            <button class="button text outline publish" @click="publish_review"> Опубликовать</button>
        </div>
    </div>
</template>

<script>
    import { mapState } from "vuex";

    export default {
        name: "AdminReviewsForm",
        computed: {
            ...mapState("reviews", ['items', 'selected']),
            token: function () {
                return Laravel.token;
            },
        },
        methods: {
            publish_review() {
                let _this = this;
                this.$store.dispatch("reviews/publish", this.selected.id)
                    .then(response => {
                        if (response.data.status === 'ok')
                            _this.$awn.success("Отзыв опубликован");
                        else
                            _this.$awn.alert("Отзыв не опубликован");
                    })
                    .catch(() => {
                        _this.$awn.alert("Отзыв не опубликован");
                    });
            },
            delete_review() {
                let _this = this;
                this.$store.dispatch("reviews/delete", this.selected.id)
                    .then(response => {
                        if (response.data.status === 'ok')
                            _this.$awn.success("Отзыв удален");
                        else
                            _this.$awn.alert("Отзыв не удален");
                    })
                    .catch(() => {
                        _this.$awn.alert("Отзыв не удален");
                    });
            }
        }

    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .admin-review-form {
        .delete {
            float: left;
        }
        .publish {
            float: right;
        }
    }
</style>