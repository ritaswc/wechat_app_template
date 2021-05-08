if (typeof wx === 'undefined') var wx = getApp().core;
var WxParse = require('../../wxParse/wxParse.js');
Page({

    /**
     * 页面的初始数据
     */
    data: {
        version: getApp()._version
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        
        var self = this;
        getApp().request({
            url: getApp().api.default.article_detail,
            data: {
                id: options.id,
            },
            success: function (res) {
                if (res.code == 0) {
                    getApp().core.setNavigationBarTitle({
                        title: res.data.title,
                    });
                    WxParse.wxParse("content", "html", res.data.content, self);
                }
                if (res.code == 1) {
                    getApp().core.showModal({
                        title: "提示",
                        content: res.msg,
                        showCancel: false,
                        confirm: function (e) {
                            if (e.confirm) {
                                getApp().core.navigateBack();
                            }
                        }
                    });
                }
            }
        });
    },

})