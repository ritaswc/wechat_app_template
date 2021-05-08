if (typeof wx === 'undefined') var wx = getApp().core;
var goodsSend = require('../../../components/goods/goods_send.js');
Page({

    /**
     * 页面的初始数据
     */
    data: {
        pageType: 'MIAOSHA',
        order_refund: null,
        express_index: null,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        var self = this;

        getApp().core.showLoading({
            title: "正在加载",
        });
        getApp().request({
            url: getApp().api.miaosha.refund_detail,
            data: {
                order_refund_id: options.id,
            },
            success: function(res) {
                if (res.code == 0) {
                    self.setData({
                        order_refund: res.data,
                    });
                }
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    
    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function(options) {
        getApp().page.onReady(this);

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function(options) {
        getApp().page.onShow(this);
        goodsSend.init(this);
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function(options) {
        getApp().page.onHide(this);

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function(options) {
        getApp().page.onUnload(this);

    },
})