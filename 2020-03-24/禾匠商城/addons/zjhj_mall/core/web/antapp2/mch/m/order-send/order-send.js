if (typeof wx === 'undefined') var wx = getApp().core;
var app = getApp();
var api = getApp().api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        order: {},
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        getApp().core.showLoading({
            title: '正在加载',
            mask: true,
        });
        getApp().request({
            url: getApp().api.mch.order.detail,
            data: {
                'id': options.id,
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        order: res.data.order,
                    });
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function (e) {
                            if (e.confirm) {
                                getApp().core.navigateBack();
                            }
                        }
                    });
                }
            },
            complete: function () {
                getApp().core.hideLoading();
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
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function () {
        getApp().page.onHide(this);
    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function () {
        getApp().page.onUnload(this);
    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {

    },

    expressChange: function (e) {
        var self = this;
        self.data.order.default_express = self.data.order.express_list[e.detail.value].express;
        self.setData({
            order: self.data.order,
        });
    },

    expressInput: function (e) {
        var self = this;
        self.data.order.default_express = e.detail.value;
    },

    expressNoInput: function (e) {
        var self = this;
        self.data.order.express_no = e.detail.value;
    },

    wordsInput: function (e) {
        var self = this;
        self.data.order.words = e.detail.value;
    },

    formSubmit: function (e) {
        var self = this;
        getApp().core.showLoading({
            title: '正在提交',
            mask: true,
        });
        getApp().request({
            url: getApp().api.mch.order.send,
            method: 'post',
            data: {
                send_type: 'express',
                order_id: self.data.order.id,
                express: e.detail.value.express,
                express_no: e.detail.value.express_no,
                words: e.detail.value.words,
            },
            success: function (res) {
                getApp().core.showModal({
                    title: '提示',
                    content: res.msg,
                    showCancel: false,
                    success: function (e) {
                        if (e.confirm) {
                            if (res.code == 0) {
                                getApp().core.redirectTo({
                                    url: '/mch/m/order/order?status=2',
                                });
                            }
                        }
                    }
                });
            },
            complete: function (res) {
                getApp().core.hideLoading();
            }
        });
    },

});