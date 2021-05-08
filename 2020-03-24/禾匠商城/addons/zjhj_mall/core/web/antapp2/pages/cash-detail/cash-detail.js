if (typeof wx === 'undefined') var wx = getApp().core;
// pages/cash-detail/cash-detail.js
var app = getApp();
var api = getApp().api;
var is_no_more = false;
var is_loading = false;
var p = 2;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        status: -1,
        cash_list: [],
        show_no_data_tip: false,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        app.page.onLoad(this, options);
        var self = this;
        is_no_more = false;
        is_loading = false;
        p = 2;
        self.LoadCashList(options.status || -1);
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function() {

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function() {
        getApp().page.onShow(this);
    },
    LoadCashList: function(status) {
        var self = this;
        self.setData({
            status: parseInt(status || -1),
        });
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.share.cash_detail,
            data: {
                status: self.data.status,
            },
            success: function(res) {
                if (res.code == 0) {
                    self.setData({
                        cash_list: res.data.list,
                    });
                }
                self.setData({
                    show_no_data_tip: (self.data.cash_list.length == 0),
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
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
        getApp().page.onUnload(this)
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

        var self = this;
        if (is_loading || is_no_more)
            return;
        is_loading = true;
        getApp().request({
            url: getApp().api.share.cash_detail,
            data: {
                status: self.data.status,
                page: p,
            },
            success: function(res) {
                if (res.code == 0) {

                    var cash_list = self.data.cash_list.concat(res.data.list);
                    self.setData({
                        cash_list: cash_list,
                    });
                    if (res.data.list.length == 0) {
                        is_no_more = true;
                    }
                }
                p++;
            },
            complete: function() {
                is_loading = false;
            }
        });
    },
})