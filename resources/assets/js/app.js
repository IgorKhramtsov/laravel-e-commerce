// TODO: Парсить ответ сервера при создании товара

require('jquery');
require('./bootstrap');
require('jquery-validation');

import vueStore from "./store/VueStore";
import VueAWN from "vue-awesome-notifications"

window.Vue = require('vue');



Vue.component('main-products', require('./components/Products.vue'));
Vue.component('product-reviews', require('./components/Reviews.vue'));
Vue.component('admin-panel', require('./components/AdminPanel.vue'));
Vue.component('product', require('./components/ProductPage.vue'));
Vue.component('cart-nav-icon', require('./components/CartNavIcon.vue'));
Vue.component('cart', require('./components/Cart.vue'));

Vue.component('login-image', {
    render: function (createElement) {
        return createElement('img', {
            attrs: {
                src: this.src
            }
        });
    },
    computed: {
        src: function () {
            let hours = new Date().getHours();
            if (hours >= 6 && hours < 12)
                return "images/login-image-sunset.jpg";
            else if (hours >= 12 && hours < 18)
                return "images/login-form-image.jpg";
            else
                return "images/login-image-night.jpg";
        },
    }
});

Vue.use(VueAWN, {
    position: "top",
    labels: {
        success: "Успешно",
        alert: "Ошибка",
    }
});

const app = new Vue({
    el: '#app',
    data: {
        product: {},
    },
    computed: {
        currentTime: function () {
            let hours = new Date().getHours();
            if (hours >= 6 && hours < 12)
                return "sunset";
            else if (hours >= 12 && hours < 18)
                return "day";
            else
                return "night";
        }
    },
    store: vueStore
});


$(document).ready(function () {
    $("#login-switch").on("click", function () {
        let $this = $(this);
        let tmp = $this.text();
        $this.text($this.data("text"));
        $this.data("text", tmp);
        $(".js-register-form").animate({"height": "toggle"}, "slow");
        $(".js-login-form").animate({"height": "toggle"}, "fast");
    });
});


function formSubmit(form, event) {
    event.preventDefault();
    let $form = $(form);
    localStorage.clear();
    $.ajax({
        type: $form.attr("method"),
        url: $form.attr("action"),
        data: $form.serialize(),
        success: function (response) {
            catchFormResponse(response);
        }
    })
}

function catchFormResponse(response) {
    if (response.status === "error") {
        let message = "";
        let errors = response.errors;
        $.each(errors, function (key, el) {
            message += el;
            $("textarea[name=" + key + "]").addClass("error");
        });
        response.description = message;
    }

    showNotify(response);
}

function showNotify(response) {
    let title = response.message,
        message = response.description,
        isError = response.status === "error";
    let $notify = $(".notify");
    let $notify_clone = $notify.first().clone();

    $notify_clone.appendTo("body");
    $notify_clone.css("z-index", +$notify.last().css("z-index") + 1);
    $notify_clone.children(".title").text(title);
    $notify_clone.children(".message").text(message);
    if (isError)
        $notify_clone.addClass("error");

    $notify_clone.animate({top: '50px'}, "slow");
    setTimeout(function () {
        $notify_clone.animate({top: '-100px'}, "slow");
        setTimeout(function () {
            $notify_clone.remove();
        }, 500);
    }, 5000);
}