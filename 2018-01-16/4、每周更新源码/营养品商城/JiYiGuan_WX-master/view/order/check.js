const notification = require('../../util/notification.js');
const storage = require("../../util/storage.js");
const http = require("../../util/http.js");
const Quantity = require('../../component/quantity/index');

Page(Object.assign({}, Quantity, {
    data: {
        is_play: false,
        is_delivery: false,
        delivery: {
            delivery_name: '',
            delivery_phone: '',
            delivery_address: ''
        },
        product_list: [],
        freight: 0,
        total: 0
    },
    onUnload: function () {
        notification.remove('notification_order_check_delivery', this);
    },
    onLoad: function () {
        notification.on('notification_order_check_delivery', this, function (data) {
            this.setData({
                delivery: {
                    delivery_name: data.delivery_name,
                    delivery_phone: data.delivery_phone,
                    delivery_address: data.delivery_address
                }
            });
        });

        http.request({
            url: '/order/check',
            data: {
                product_list: []
            },
            success: function (data) {
                var is_play = true;
                var is_delivery = false;
                var product_list = storage.getProduct();
                var freight = 0;
                var total = 0;

                if (data.delivery_name == '') {
                    is_play = false;

                    wx.showModal({
                        content: '您还没有收货地址，是否新建一个？',
                        success: function (res) {
                            if (res.confirm) {
                                wx.navigateTo({
                                    url: '/view/delivery/index'
                                });
                            } else if (res.cancel) {

                            }
                        }
                    });
                } else {
                    is_delivery = true;
                }

                var delivery = {
                    delivery_name: data.delivery_name,
                    delivery_phone: data.delivery_phone,
                    delivery_address: data.delivery_address
                }

                for (var i = 0; i < product_list.length; i++) {
                    var product = product_list[i];

                    var product_total_price = product.product_quantity.quantity * product.product_price;

                    product.product_total_price = product_total_price.toFixed(2);
                    total += product_total_price;
                }

                if (!total > 0) {
                    is_play = false;
                }

                this.setData({
                    is_play: is_play,
                    is_delivery: is_delivery,
                    delivery: delivery,
                    product_list: product_list,
                    freight: new Number(freight).toFixed(2),
                    total: total.toFixed(2)
                });
            }.bind(this)
        });
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
    handlePay: function () {
        if (!this.data.is_play) {
            return;
        }

        var product_list = this.data.product_list;

        //转换数量的格式
        for (var i = 0; i < product_list.length; i++) {
            if (typeof (product_list[i].product_quantity.quantity) != 'undefined') {
                product_list[i].product_quantity = product_list[i].product_quantity.quantity;
            }
        }

        console.log(getApp().globalData.open_id);

        http.request({
            url: '/order/save',
            data: {
                order_delivery_name: this.data.delivery.delivery_name,
                order_delivery_phone: this.data.delivery.delivery_phone,
                order_delivery_address: this.data.delivery.delivery_address,
                order_message: '',
                order_pay_type: 'WECHAT_PAY',
                product_list: product_list,
                open_id: getApp().globalData.open_id,
                pay_type: 'WX'
            },
            success: function (data) {
                var order_id = data.orderId;

                wx.requestPayment({
                    timeStamp: data.timeStamp,
                    nonceStr: data.nonceStr,
                    package: data.package,
                    signType: data.signType,
                    paySign: data.paySign,
                    appId: 'wxccad1afcac054867',
                    success: function (response) {
                        wx.redirectTo({
                            url: '/view/order/result'
                        });
                    },
                    fail: function (response) {

                    }
                })
            }.bind(this)
        });
    }
}));