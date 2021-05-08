const constant = require("../../util/constant.js");
const http = require("../../util/http.js");

Page({
    data: {
        window_width: getApp().globalData.window_width,
        window_height: getApp().globalData.window_height,
        list: [],
        category_list: constant.category_list,
        category_id: '',
        product_list: []
    },
    onUnload: function () {

    },
    onLoad: function (option) {
        var category_id = '';

        if (typeof (option.category_id) != 'undefined') {
            category_id = option.category_id;
        }

        this.setData({
            category_id: category_id
        });

        http.request({
            url: '/product/all/list',
            data: {

            },
            success: function (data) {
                for (var i = 0; i < data.length; i++) {
                    data[i].product_image_original = constant.host + JSON.parse(data[i].product_image_original)[0];
                    data[i].product_price = data[i].product_price.toFixed(2);
                }

                var product_list = [];
                for (var i = 0; i < data.length; i++) {
                    if (data[i].category_id == this.data.category_id || this.data.category_id == '') {
                        product_list.push(data[i]);
                    }
                }

                this.setData({
                    list: data,
                    product_list: product_list
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
    handleCategory: function (event) {
        var category_id = event.currentTarget.id;
        var product_list = [];

        for (var i = 0; i < this.data.list.length; i++) {
            if (this.data.list[i].category_id == category_id || category_id == '') {
                product_list.push(this.data.list[i]);
            }
        }
        
        this.setData({
            category_id: category_id,
            product_list: product_list
        });
    }
});
