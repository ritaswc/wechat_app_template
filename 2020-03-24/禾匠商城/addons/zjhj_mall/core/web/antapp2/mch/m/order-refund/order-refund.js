if (typeof wx === 'undefined') var wx = getApp().core;
var app = getApp();
var api = getApp().api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        self.setData({
            id: options.id || 0,
        });
        getApp().core.showLoading({
            title: '加载中',
            mask: true,
        });
        getApp().request({
            url: getApp().api.mch.order.refund_detail,
            data: {
                id: self.data.id,
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData(res.data);
                }
                if (res.code == 1) {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                    });
                }
            },
            complete: function (res) {
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


    showPicList: function (e) {
        var self = this;
        getApp().core.previewImage({
            urls: self.data.pic_list,
            current: self.data.pic_list[e.currentTarget.dataset.pindex],
        });
    },

    refundPass: function (e) {
        var self = this;
        var id = self.data.id;
        var type = self.data.type;
        getApp().core.showModal({
            title: '提示',
            content: '确认同意' + (type == 1 ? '退款？资金将原路返回！' : '换货？'),
            success: function (e) {
                if (e.confirm) {
                    getApp().core.showLoading({
                        title: '正在处理',
                        mask: true,
                    });
                    getApp().request({
                        url: getApp().api.mch.order.refund,
                        method: 'post',
                        data: {
                            id: id,
                            action: 'pass',
                        },
                        success: function (res) {
                            getApp().core.showModal({
                                title: '提示',
                                content: res.msg,
                                showCancel: false,
                                success: function (e) {
                                    getApp().core.redirectTo({
                                        url: '/' + self.route + '?' + getApp().helper.objectToUrlParams(self.options),
                                    });
                                }
                            });
                        },
                        complete: function () {
                            getApp().core.hideLoading();
                        },
                    });
                }
            }
        });
    },

    refundDeny: function (e) {
        var self = this;
        var id = self.data.id;
        getApp().core.showModal({
            title: '提示',
            content: '确认拒绝？',
            success: function (e) {
                if (e.confirm) {
                    getApp().core.showLoading({
                        title: '正在处理',
                        mask: true,
                    });
                    getApp().request({
                        url: getApp().api.mch.order.refund,
                        method: 'post',
                        data: {
                            id: id,
                            action: 'deny',
                        },
                        success: function (res) {
                            getApp().core.showModal({
                                title: '提示',
                                content: res.msg,
                                showCancel: false,
                                success: function (e) {
                                    getApp().core.redirectTo({
                                        url: '/' + self.route + '?' + getApp().helper.objectToUrlParams(self.options),
                                    });
                                }
                            });
                        },
                        complete: function () {
                            getApp().core.hideLoading();
                        },
                    });
                }
            }
        });
    },

});