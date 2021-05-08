if (typeof wx === 'undefined') var wx = getApp().core;
// pages/miaosha/order/order.js
// order.js


var is_no_more = false;
var is_loading = false;
var p = 2;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        status: 0,
        order_list: [],
        show_no_data_tip: false,
        hide: 1,
        qrcode: ""
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        var self = this;
        is_no_more = false;
        is_loading = false;
        p = 2;
        self.loadOrderList(options.status || 0);
        var pages = getCurrentPages();
        if (pages.length < 2) {
            self.setData({
                show_index: true,
            });
        }
    },

    loadOrderList: function(status) {
        if (status == undefined)
            status = -1;
        var self = this;
        self.setData({
            status: status,
        });
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.miaosha.order_list,
            data: {
                status: self.data.status,
            },
            success: function(res) {
                if (res.code == 0) {
                    self.setData({
                        order_list: res.data.list,
                    });
                }
                self.setData({
                    show_no_data_tip: (self.data.order_list.length == 0),
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },


    onReachBottom: function(options) {
        getApp().page.onReachBottom(this);
        var self = this;
        if (is_loading || is_no_more)
            return;
        is_loading = true;
        getApp().request({
            url: getApp().api.miaosha.order_list,
            data: {
                status: self.data.status,
                page: p,
            },
            success: function(res) {
                if (res.code == 0) {

                    var order_list = self.data.order_list.concat(res.data.list);
                    self.setData({
                        order_list: order_list,
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

    orderRevoke: function(e) {
        var self = this;
        getApp().core.showModal({
            title: "提示",
            content: "是否取消该订单？",
            cancelText: "否",
            confirmText: "是",
            success: function(res) {
                if (res.cancel)
                    return true;
                if (res.confirm) {
                    getApp().core.showLoading({
                        title: "操作中",
                    });
                    getApp().request({
                        url: getApp().api.miaosha.order_revoke,
                        data: {
                            order_id: e.currentTarget.dataset.id,
                        },
                        success: function(res) {
                            getApp().core.hideLoading();
                            getApp().core.showModal({
                                title: "提示",
                                content: res.msg,
                                showCancel: false,
                                success: function(res) {
                                    if (res.confirm) {
                                        self.loadOrderList(self.data.status);
                                    }
                                }
                            });
                        }
                    });
                }
            }
        });
    },

    orderConfirm: function(e) {
        var self = this;
        getApp().core.showModal({
            title: "提示",
            content: "是否确认已收到货？",
            cancelText: "否",
            confirmText: "是",
            success: function(res) {
                if (res.cancel)
                    return true;
                if (res.confirm) {
                    getApp().core.showLoading({
                        title: "操作中",
                    });
                    getApp().request({
                        url: getApp().api.miaosha.confirm,
                        data: {
                            order_id: e.currentTarget.dataset.id,
                        },
                        success: function(res) {
                            getApp().core.hideLoading();
                            getApp().core.showToast({
                                title: res.msg,
                            });
                            if (res.code == 0) {
                                self.loadOrderList(3);
                            }
                        }
                    });
                }
            }
        });
    },
    orderQrcode: function(e) {
        var self = this;
        var order_list = self.data.order_list;
        var index = e.target.dataset.index;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        if (self.data.order_list[index].offline_qrcode) {
            self.setData({
                hide: 0,
                qrcode: self.data.order_list[index].offline_qrcode
            });
            getApp().core.hideLoading();
        } else {
            getApp().request({
                url: getApp().api.order.get_qrcode,
                data: {
                    order_no: order_list[index].order_no
                },
                success: function(res) {
                    if (res.code == 0) {
                        self.setData({
                            hide: 0,
                            qrcode: res.data.url
                        });
                    } else {
                        getApp().core.showModal({
                            title: '提示',
                            content: res.msg,
                        })
                    }
                },
                complete: function() {
                    getApp().core.hideLoading();
                }
            });
        }
    },
    hide: function(e) {
        this.setData({
            hide: 1
        });
    },
    onShow: function(options) {
        getApp().page.onShow(this);
    }

});