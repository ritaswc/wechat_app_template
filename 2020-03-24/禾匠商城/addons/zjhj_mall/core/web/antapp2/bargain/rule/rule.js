if (typeof wx === 'undefined') var wx = getApp().core;
// bargain/rule/rule.js
var app = getApp();
var api = getApp().api;
Page({

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        var self = this;
        getApp().page.onLoad(this, options);
        getApp().core.showLoading({
            title: '加载中',
        });
        getApp().request({
            url: getApp().api.bargain.setting,
            success: function(res) {
                if (res.code == 0) {
                    self.setData(res.data);
                } else {
                    self.showLoading({
                        title: res.msg
                    });
                }
            },
            complete:function(res){
                getApp().core.hideLoading();
            }
        });
    },
})