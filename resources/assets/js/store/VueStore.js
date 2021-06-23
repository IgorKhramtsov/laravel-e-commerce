import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";
import { products, reviews, cart, orders } from "../api";
import VueAWN from "vue-awesome-notifications";

const api_token = window.Laravel.api_token;

Vue.use(Vuex);

const moduleProducts = {
    namespaced: true,
    state: {
        items: {},
        selected: { id: -1, name: '', description: '', price: 0, image: {} },
        pagination: {},
        sorting: { sort_by: "name", sort_type: "ASC" }
    },
    mutations: {
        FETCH(state, data) {
            state.items = data[0].data;
            state.pagination.current_page = data[0].current_page;
            state.pagination.next_page_url = data[0].next_page_url;
            state.pagination.prev_page_url = data[0].prev_page_url;
            state.sorting.sort_by = data.sort_by;
            state.sorting.sort_type = data.sort_type;

            //state.selected = { id: -1, name: '', description: '', price: 0, image: {} };
        },
        updateProduct(state, product, index) {
            state.items[index] = product;
        },
        SEL_PRODUCT(state, ind) {
            if (ind <= state.items.length)
                if (state.selected !== state.items[ind]) {
                    state.selected = state.items[ind];
                    return;
                }
            state.selected = { id: -1, name: '', description: '', price: 0, image: {} };
        }
    },
    actions: {
        fetch_api({ commit, state }, options = {}) {
            if (typeof options.fetch_url === 'undefined')
                options.fetch_url = products;
            if (typeof options.per_page === 'undefined')
                options.per_page = 5;

            return axios.get(options.fetch_url, {
                params: {
                    sort_by: state.sorting.sort_by,
                    sort_type: state.sorting.sort_type,
                    per_page: options.per_page,
                }
            })
                .then(response => {
                    commit("FETCH", response.data);
                    let url = response.request.responseURL.replace('api/', '');
                    window.history.pushState(null, null, url);
                })
                .catch();
        },
        fetch( {commit}, data ) {
            commit("FETCH", data);
        },
        edit({}, product) {
            let formData = new FormData();
            formData.append("name", product.name);
            formData.append("description", product.description);
            formData.append("price", product.price);
            formData.append("image", product.image);
            formData.append("api_token", api_token);
            formData.append("_method", "PUT");
            return axios.post(`${products}/${product.id}`, formData)
                .then((response) => {
                    this.dispatch('products/fetch_api');
                    return response;
                });
        },
        add({}, product) {
            let formData = new FormData();
            formData.append("name", product.name);
            formData.append("description", product.description);
            formData.append("price", product.price);
            formData.append("image", product.image);
            formData.append("api_token", api_token);
            return axios.post(`${products}`, formData)
                .then((response) => {
                    this.dispatch('products/fetch_api');
                    return response;
                });
        },
        changeSort({ state }, options = {}) {
            if (state.sorting.sort_by === options.sort_by) {
                if (state.sorting.sort_type === "ASC") {
                    state.sorting.sort_type = "DESC";
                } else {
                    state.sorting.sort_type = "ASC";
                }
            } else {
                state.sorting.sort_by = options.sort_by;
                state.sorting.sort_type = "ASC";
            }
            this.dispatch('products/fetch_api', options);
        }
    },
    getters: {
        button_text(state) {
            if (state.selected.id > 0)
                return "Изменить";
            else
                return "Добавить";
        }
    }
};

const moduleReviews = {
    namespaced: true,
    state: {
        items: {},
        selected: { id: -1, text: "", product_id: -1 },
        pagination: {},
    },
    mutations: {
        FETCH(state, data) {
            if (typeof data.data != 'undefined') {
                state.items = data.data;
                state.pagination.current_page = data.current_page;
                state.pagination.next_page_url = data.next_page_url;
                state.pagination.prev_page_url = data.prev_page_url;
            }
            else {
                state.items = data;
            }
        },
        SEL_REVIEW(state, ind) {
            if (ind <= state.items.length)
                if (state.selected !== state.items[ind]) {
                    state.selected = state.items[ind];
                    return;
                }
            state.selected = { id: -1, text: "", product_id: -1 };
        }
    },
    actions: {
        fetch({ commit }, options = {}) {
            if (typeof options.fetch_url === 'undefined')
                options.fetch_url = reviews;
            if (!(typeof options.product_id === 'undefined'))
                options.fetch_url += "/" + options.product_id;

            return axios.get(options.fetch_url, {
                params: {
                    api_token: api_token
                },
            })
                .then(response => commit("FETCH", response.data))
                .catch();
        },
        add({}, review) {
            return axios.post(reviews, {
                text: review.text,
                product_id: review.product_id,
                api_token: api_token,
            });
        },
        publish({}, review_id) {
            return axios.post(`${reviews}/${review_id}`, {
                api_token: api_token,
            })
                .then((response) => {
                    this.dispatch("reviews/fetch");
                    return response;
                });
        },
        delete({}, review_id) {
            return axios.delete(`${reviews}/${review_id}`, {
                params: {
                    api_token: api_token,
                },
            })
                .then((response) => {
                    this.dispatch("reviews/fetch");
                    return response;
                });
        },
    },
};

const moduleCart = {
    namespaced: true,
    state: {
        items: {},
        count: 0,
        order: {
            contact: {
                email: "",
                phone: "",
                fname: "",
                lname: "",
                mname: "",
            },
            address: {
                country: "",
                city: "",
                zip: "",
                address: "",
                home: "",
            },
            shipment_method: "",
            payment_method: "",
        },
        WMI: {},
    },
    mutations: {
        FETCH(state, data) {
            state.items = data.data;
            state.count = data.count;
            if (typeof data.info!== 'undefined' && typeof data.info.payment_method !== 'undefined')
                state.order = data.info;
            if (typeof data.WMI!== 'undefined' && typeof data.WMI.WMI_SIGNATURE !== 'undefined')
                state.WMI = data.WMI;
        },
    },
    actions: {
        fetch({ commit }) {
            return axios.get(cart)
                .then(response => commit("FETCH", response.data))
                .catch();
        },
        quantity_upd({ commit }, args) {
            return axios.post(`${cart}/update`, {
                id: args.id,
                quantity: args.quantity
            })
                .then(response => {
                    commit("FETCH", response.data);
                    return response;
                });
        },
        remove({ commit }, args) {
            return axios.post(`${cart}/remove`, { id: args.id })
                .then(response => {
                    commit("FETCH", response.data);
                    return response;
                });
        },
        createOrder({ commit, state }) {
            return axios.post(`${cart}/order`, {
                order: state.order,
            })
                .then(response => {
                    commit("FETCH", response.data);
                    return response;
                });
        },
        saveOrder({ commit, state }) {
            axios.post(`${cart}/saveOrder`, {
                order: state.order,
            });
        },
    },
    getters: {
        items_count(state) {
            if (typeof state.count === 'undefined' || state.count < 1)
                return 0;
            if (state.count >= 10)
                return "9+";
            else
                return state.count;
        },
        total(state) {
            let total = 0;
            let arr = JSON.parse(JSON.stringify(state.items));

            for (let item in arr) {
                total += arr[item].price * arr[item].quantity;
            }
            return total;
        },
        is_order_contact_filled(state) {
            return state.order.contact.email && state.order.contact.phone
                && state.order.contact.fname && state.order.contact.lname;
        },
        is_order_address_filled(state) {
            return state.order.address.country && state.order.address.city
                && state.order.address.address && state.order.address.home
                && state.order.address.zip;
        },
        is_order_shipment_choosed(state) {
            return state.order.shipment_method && true;
        },
        is_order_payment_choosed(state) {
            return state.order.payment_method && true;
        },
        is_order_data_empty(state, getters) {
            return !(getters.is_order_address_filled && getters.is_order_contact_filled
                && getters.is_order_payment_choosed && getters.is_order_shipment_choosed);
        }
    },

};

const moduleOrder = {
    namespaced: true,
    state: {
        items: {},
        selected: {},
        selected_items: {},
        pagination: {},
        sorting: { sort_by: "date", sort_type: "ASC" }
    },

    mutations: {
        FETCH(state, data) {
            for (let key in data[0].data) {
                let val = data[0].data[key];

                val.forEach(function (item) {
                    let fname = item.last_name.substr(0, 1).toUpperCase() + item.last_name.substr(1);
                    let other_name = item.first_name.substr(0, 1).toUpperCase() + ".";
                    if (typeof item.middle_name !== "undefined" && item.middle_name !== null)
                        other_name += item.middle_name.substr(0, 1).toUpperCase() + ".";
                    item.name = `${fname} ${other_name}`;
                });
            }
            state.items = data[0].data;

            state.pagination.current_page = data[0].current_page;
            state.pagination.next_page_url = data[0].next_page_url;
            state.pagination.prev_page_url = data[0].prev_page_url;
            state.sorting.sort_by = data.sort_by;
            state.sorting.sort_type = data.sort_type;
        },
        updateOrder(state, order, index) {
            state.items[index] = order;
        },
        SEL_ORDER(state, params) {
            if (params.ind_sec <= state.items[params.ind_first].length) {
                if (state.selected !== state.items[params.ind_first][params.ind_sec]) {
                    state.selected = state.items[params.ind_first][params.ind_sec];
                    axios.get(`${orders}/${state.selected.id}`, {
                        params: {
                            api_token: api_token,
                        },
                    })
                        .then(response => state.selected_items = response.data)
                        .catch();
                    return;
                }
            }
            state.selected = {};
        }
    },
    actions: {
        fetch_api({ commit, state }, options = {}) {
            if (typeof options.fetch_url === 'undefined')
                options.fetch_url = orders;
            if (typeof options.per_page === 'undefined')
                options.per_page = 8;

            return axios.get(options.fetch_url, {
                params: {
                    sort_by: state.sorting.sort_by,
                    sort_type: state.sorting.sort_type,
                    per_page: options.per_page,
                    api_token: api_token,
                }
            })
                .then(response => commit("FETCH", response.data))
                .catch();
        },
        fetch({ commit }) {
            return products;
        },
        edit({}, product) {
            let formData = new FormData();
            formData.append("name", product.name);
            formData.append("description", product.description);
            formData.append("price", product.price);
            formData.append("image", product.image);
            formData.append("_method", "PUT");
            axios.post(`${products}/${product.id}`, formData)
                .then(() => this.dispatch('products/fetch_api'));
        },
        changeSort({ state }, options = {}) {
            if (state.sorting.sort_by === options.sort_by) {
                if (state.sorting.sort_type === "ASC") {
                    state.sorting.sort_type = "DESC";
                } else {
                    state.sorting.sort_type = "ASC";
                }
            } else {
                state.sorting.sort_by = options.sort_by;
                state.sorting.sort_type = "ASC";
            }
            this.dispatch('orders/fetch_api', options);
        },
    }
};

const vueStore = new Vuex.Store({
    modules: {
        products: moduleProducts,
        reviews: moduleReviews,
        cart: moduleCart,
        orders: moduleOrder,
    },
});

export default vueStore;