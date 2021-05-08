if (typeof wx === 'undefined') var wx = getApp().core;
// pages/share-qrcode/share-qrcode.js
var app = getApp();
var api = app.api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        qrcode: ""
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        app.page.onLoad(this, options);
        var self = this;
        var setting = getApp().core.getStorageSync(getApp().const.SHARE_SETTING)
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.share.get_qrcode,
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        qrcode: res.data
                    });
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false
                    })
                }
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        getApp().page.onShow(this);
        var self = this;
        var user_info = getApp().getUser();
        self.setData({
            user_info: user_info,
        });
    },

    click: function () {
        var self = this;
        wx.previewImage({
            current: self.data.qrcode,
            urls: [self.data.qrcode]
        })
    },
})