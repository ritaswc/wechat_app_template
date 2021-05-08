const constant = require("../../util/constant.js");
const notification = require('../../util/notification.js');
const storage = require("../../util/storage.js");
const Quantity = require('../../component/quantity/index');

Page(Object.assign({}, Quantity, {
    data: {
        color: constant.color,
        is_all: false,
        is_select: false,
        is_edit: false,
        cart_total: parseFloat(0).toFixed(2),
        cart_list: storage.getCart()
    },
    onUnload: function () {
        notification.remove('notification_cart_index_load', this);
    },
    onLoad: function () {
        notification.on('notification_cart_index_load', this, function (data) {
            this.handleLoad();
        });

        this.handleLoad();
    },
    onReady: function () {

    },
    onShow: function () {

    },
    onHide: function () {

    },
    onPullDownRefresh: function () {

    },
    onReachBottom: function () {

    },
    onShareAppMessage: function () {

    },
    handleLoad: function () {
        var cart_list = storage.getCart();
        var is_all = cart_list.length > 0;
        var is_select = false;
        var cart_total = 0;

        for (var i = 0; i < cart_list.length; i++) {
            var product = cart_list[i];

            product.product_total_price = (product.product_quantity.quantity * product.product_price).toFixed(2);

            if (product.is_check) {
                is_select = true;

                cart_total += product.product_quantity.quantity * product.product_price;
            } else {
                is_all = false;
            }
        }

        this.setData({
            is_all: is_all,
            is_select: is_select,
            cart_total: cart_total,
            cart_list: cart_list
        });
    },
    handleSingle: function (event) {
        var is_all = true;
        var is_select = false;
        var cart_total = 0;

        for (var i = 0; i < this.data.cart_list.length; i++) {
            var product = this.data.cart_list[i];

            if (product.product_id == event.currentTarget.id) {
                product.is_check = !product.is_check;
            }

            if (product.is_check) {
                is_select = true;

                cart_total += product.product_quantity.quantity * product.product_price;
            }
        }

        this.setData({
            is_select: is_select,
            cart_total: cart_total.toFixed(2),
            cart_list: this.data.cart_list
        });
    },
    handleAll: function () {
        if (this.data.cart_list.length == 0) {
            return;
        }

        var is_all = !this.data.is_all;
        var cart_total = 0;

        for (var i = 0; i < this.data.cart_list.length; i++) {
            var product = this.data.cart_list[i];

            product.is_check = is_all;

            if (is_all) {
                cart_total += product.product_quantity.quantity * product.product_price;
            }
        }

        this.setData({
            is_all: is_all,
            is_select: is_all,
            cart_total: cart_total.toFixed(2),
            cart_list: this.data.cart_list
        });

        storage.setCart(this.data.cart_list);
    },
    handleZanQuantityChange(event) {
        var componentId = event.componentId;
        var quantity = event.quantity;
        var cart_total = 0;

        for (var i = 0; i < this.data.cart_list.length; i++) {
            var product = this.data.cart_list[i];

            if (product.product_id == componentId) {
                product.product_quantity.quantity = quantity;

                product.product_total_price = (product.product_quantity.quantity * product.product_price).toFixed(2);
            }

            if (product.is_check) {
                cart_total += product.product_quantity.quantity * product.product_price;
            }
        }

        this.setData({
            cart_total: cart_total.toFixed(2),
            cart_list: this.data.cart_list
        });

        storage.setCart(this.data.cart_list);
    },
    handleBalance: function () {
        var cart_list = this.data.cart_list;
        var uncheck_cart_list = [];
        var product_list = [];
        var is_all = cart_list.length > 0;
        var is_select = false;
        var cart_total = 0;

        for (var i = 0; i < cart_list.length; i++) {
            if (cart_list[i].is_check) {
                product_list.push(cart_list[i]);
            } else {
                uncheck_cart_list.push(cart_list[i]);
            }
        }

        storage.setCart(uncheck_cart_list);
        storage.setProduct(product_list);

        for (var i = 0; i < uncheck_cart_list.length; i++) {
            var product = cart_list[i];

            product.product_total_price = (product.product_quantity.quantity * product.product_price).toFixed(2);

            if (product.is_check) {
                is_select = true;

                cart_total += product.product_quantity.quantity * product.product_price;
            } else {
                is_all = false;
            }
        }

        this.setData({
            is_all: is_all,
            is_select: is_select,
            cart_total: cart_total,
            cart_list: uncheck_cart_list
        });

        wx.navigateTo({
            url: '/view/order/check'
        });
    }
}));
