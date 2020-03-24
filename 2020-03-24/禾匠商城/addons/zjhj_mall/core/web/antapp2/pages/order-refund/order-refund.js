if (typeof wx === 'undefined') var wx = getApp().core;
var app = getApp();
var api = getApp().api;
var goodsRefund = require('../../components/goods/goods_refund.js');
Page({

    /**
     * 页面的初始数据
     */
    data: {
        isPageShow: false,
        pageType: 'STORE',
        switch_tab_1: "active",
        switch_tab_2: "",
        goods: {},
        refund_data_1: {},
        refund_data_2: {},
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        wx.showLoading({
            title: '加载中',
        })
        getApp().request({
            url: getApp().api.order.refund_preview,
            data: {
                order_detail_id: options.id,
            },
            success: function (res) {
                wx.hideLoading();
                if (res.code == 0) {
                    self.setData({
                        goods: res.data,
                        isPageShow: true,
                    });
                }
                if (res.code == 1) {
                    getApp().core.showModal({
                        title: "提示",
                        content: res.msg,
                        image: "/images/icon-warning.png",
                        success: function (res) {
                            if (res.confirm) {
                                getApp().core.navigateBack();
                            }
                        }
                    });
                }
            }
        });
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {
        getApp().page.onReady(this);
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        getApp().page.onShow(this);
        goodsRefund.init(this);
    },
});