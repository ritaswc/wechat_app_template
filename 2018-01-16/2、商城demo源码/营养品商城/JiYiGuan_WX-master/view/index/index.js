const constant = require("../../util/constant.js");
const http = require("../../util/http.js");

Page({
    data: {
        window_width: getApp().globalData.window_width,
        banner_list: [{
            banner_id: 0,
            banner_image: 'http://api.jiyiguan.nowui.com/upload/6a4dbae2ac824d2fb170638d55139666/original/00b1216e83b84226978d63703e7d597b.jpg'
        }, {
            banner_id: 1,
            banner_image: 'http://api.jiyiguan.nowui.com/upload/6a4dbae2ac824d2fb170638d55139666/original/00b1216e83b84226978d63703e7d597b.jpg'
        }, {
            banner_id: 2,
            banner_image: 'http://api.jiyiguan.nowui.com/upload/6a4dbae2ac824d2fb170638d55139666/original/00b1216e83b84226978d63703e7d597b.jpg'
        }],
        category_list: [],
        product_list: []
    },
    onUnload: function () {

    },
    onLoad: function () {
        // wx.clearStorage();

        var category_list = constant.category_list.concat();
        category_list.splice(0, 1);
        category_list.push(constant.category_list[0]);

        this.setData({
            category_list: category_list
        });

        http.request({
            url: '/product/hot/list',
            data: {

            },
            success: function (data) {
                for (var i = 0; i < data.length; i++) {
                    data[i].product_image_original = constant.host + JSON.parse(data[i].product_image_original)[0];
                    data[i].product_price = data[i].product_price.toFixed(2);
                }

                this.setData({
                    product_list: data
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

    }
});
