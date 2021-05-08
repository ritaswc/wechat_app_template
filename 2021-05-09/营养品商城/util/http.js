const constant = require("./constant.js");
const storage = require("./storage.js");
const util = require("./util.js");

function request(config) {
    wx.showLoading({
        title: '加载中..'
    });

    wx.showToast({
        title: '加载中..',
        icon: 'loading',
        mask: true,
        duration: constant.duration * 10
    });

    wx.request({
        url: constant.host + config.url,
        method: 'POST',
        header: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Token': storage.getToken(),
            'Platform': 'WX',
            'Version': '1.0.0'
        },
        data: config.data,
        success: function (response) {
            wx.hideToast();

            if (response.data.code == 200) {
                config.success(response.data.data);
            } else {
                util.showFailToast({
                    title: response.data.message
                });
            }
        },
        fail: function () {
            wx.hideLoading();

            util.showFailToast({
                title: '网络出现错误'
            });
        }
    });
}

module.exports = {
    request: request
};