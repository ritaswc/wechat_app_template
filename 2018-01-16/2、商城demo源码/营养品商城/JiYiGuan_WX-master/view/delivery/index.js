const notification = require('../../util/notification.js');
const constant = require("../../util/constant.js");
const http = require("../../util/http.js");

Page({
    data: {
        color: constant.color,
        is_select: false,
        delivery_list: []
    },
    onUnload: function () {
        notification.remove('notification_delivery_index_load', this);
    },
    onLoad: function (option) {
        notification.on('notification_delivery_index_load', this, function (data) {
            this.handleLoad();
        });

        var is_select = false;

        if (typeof (option.is_select) != 'undefined') {
            is_select = option.is_select;
        }

        this.setData({
            is_select: is_select
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
        http.request({
            url: '/delivery/list',
            data: {
                page_index: 0,
                page_size: 0
            },
            success: function (data) {
                this.setData({
                    delivery_list: data
                });
            }.bind(this)
        });
    },
    handleClick: function (event) {
        var delivery_id = event.currentTarget.id;
        var delivery = {};
        var delivery_list = this.data.delivery_list;

        if (this.data.is_select) {
            for (var i = 0; i < delivery_list.length; i++) {
                if (delivery_list[i].delivery_id == delivery_id) {
                    delivery_list[i].is_select = true;

                    delivery = delivery_list[i];

                    break;
                }
            }

            notification.emit('notification_order_check_delivery', delivery);

            this.setData({
                delivery_list: delivery_list
            });

            setTimeout(function () {
                wx.navigateBack();
            }, 500);
        } else {
            wx.navigateTo({
                url: '/view/delivery/detail?delivery_id=' + delivery_id
            });
        }
    }
});
