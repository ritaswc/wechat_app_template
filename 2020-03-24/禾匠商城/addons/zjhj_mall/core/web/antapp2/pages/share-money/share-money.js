if (typeof wx === 'undefined') var wx = getApp().core;
// pages/share-money/share-money.js
var app = getApp();
var api = app.api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        block: false,
        active: '',
        total_price: 0,
        price: 0,
        cash_price: 0,
        un_pay: 0,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function() {
        getApp().page.onReady(this);
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function() {
        getApp().page.onShow(this);
        var self = this;
        var share_setting = getApp().core.getStorageSync(getApp().const.SHARE_SETTING);
        var custom = getApp().core.getStorageSync(getApp().const.CUSTOM)
        self.setData({
            share_setting: share_setting,
            custom: custom
        });
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.share.get_price,
            success: function(res) {
                if (res.code == 0) {
                    self.setData({
                        total_price: res.data.price.total_price,
                        price: res.data.price.price,
                        cash_price: res.data.price.cash_price,
                        un_pay: res.data.price.un_pay
                    });
                }
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    tapName: function(e) {
        var self = this;
        var active = '';
        if (!self.data.block) {
            active = 'active';
        }
        self.setData({
            block: !self.data.block,
            active: active
        });

    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function() {
        getApp().page.onHide(this);
    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function() {
        getApp().page.onUnload(this);
    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function() {

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function() {

    },
})