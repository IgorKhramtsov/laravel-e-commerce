<template>
    <div class="panel-left reviews col-md-8">
        <form @submit.prevent="save" class="js-form-validate admin-product-form row" novalidate>
            <div class="col-md-4 align-items-center" >
                <div class="image-wrapper">
                    <div class="image">
                        <img class="img-thumbnail" :src="selected.image_url" v-if="selected.id > 0 && selected.image_url.length > 0">
                    </div>

                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="input float-left col-8">
                        <input type="text" name="name" id="prod_title" v-model="selected.name" required>
                        <label for="prod_title">Название</label>
                    </div>
                    <div class="input float-left col-4">
                        <input type="text" name="price" id="prod_price" v-model="selected.price" required
                               data-rule-type="number"
                               data-rule-min="1">
                        <label for="prod_price">Цена</label>
                    </div>
                    <div class="w-100"></div>
                    <div class="input col float-left">
                        <textarea name="description" id="prod_desc" v-model="selected.description" required></textarea>
                        <label for="prod_desc">Описание</label>
                    </div>
                    <div class="w-100"></div>


                </div>
            </div>
            <!--<div class="row">-->
                <div class="col-4 text-center">
                    <input type="hidden" name="id" v-model="selected.id">
                    <input type="file" name="image" class="inputfile" id="file-input" @change="fileChange">
                    <label for="file-input"> {{ fileName }} </label>
                </div>
                <div class="col-8">
                    <button type="submit" class="button text outline float-right"> {{ button_text }}</button>
                </div>
            <!--</div>-->
        </form>
    </div>
</template>

<script>
    import { mapState, mapGetters } from "vuex";

    export default {
        name: "AdminProductsForm",
        data() {
            return {
                fileName: 'Выберите файл',
                file: {},
            }
        },
        computed: {
            ...mapState("products", ['items', 'selected']),
            ...mapGetters("products", ['button_text']),
            token: function () {
                return Laravel.token;
            }
        },
        methods: {
            save() {
                let _this = this;
                if (this.selected.id > 0)
                    this.$store.dispatch("products/edit", this.selected)
                        .then(response => {
                            if (response.data.status === 'ok')
                                _this.$awn.success("Товар изменен");
                            else
                                _this.$awn.alert("Товар не изменен");
                        })
                        .catch(() => {
                            _this.$awn.alert("Товар не изменен");
                        });
                else
                    this.$store.dispatch("products/add", this.selected)
                        .then(response => {
                            if (response.data.status === 'ok')
                                _this.$awn.success("Товар добавлен");
                            else
                                _this.$awn.alert("Товар не добавлен");
                        })
                        .catch(() => {
                            _this.$awn.alert("Товар не добавлен");
                        });


                this.fileName = "Выберите файл";
            },
            fileChange($event) {
                this.fileName = $event.target.files[0].name;
                this.selected.image = $event.target.files[0];
            },
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .panel-left {
        padding-top: 20px;
        .admin-product-form {
            .input label {
                left: 30px;
            }
            .image-wrapper {
                img {
                    height: 110px;
                    padding: 5px;
                    margin-left: 20%;
                }
            }
        }
        textarea {
            min-height: 60px;
            height: 60px;
        }
        .button {

        }
    }
</style>