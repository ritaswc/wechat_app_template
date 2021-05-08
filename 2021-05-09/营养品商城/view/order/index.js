const constant = require("../../util/constant.js");
const http = require("../../util/http.js");

Page({
    data: {
        order_status_list: constant.order_status_list,
        tab_index: 0,
        slider_offset: 0,
        slider_left: 0,
        slider_width: 0,
        order_list: []
    },
    onUnload: function () {

    },
    onLoad: function () {
        this.setData({
            slider_left: 0,
            slider_offset: this.data.window_width / 2 * this.data.tab_index,
            slider_width: this.data.window_width / 2
        });

        http.request({
            url: '/order/list',
            data: {
                page_index: 0,
                page_size: 0
            },
            success: function (data) {
                this.setData({
                    order_list: data
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
    handleTab: function (event) {
        this.setData({
            slider_offset: event.currentTarget.offsetLeft,
            tab_index: event.currentTarget.id
        });
    }
});
