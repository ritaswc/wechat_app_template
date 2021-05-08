if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        hide: 1
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        self.loadOrderList(options.status || 0);
    },

    loadOrderList: function (status) {
        var self = this;
        if (status == undefined) {
            status = -1;
        }
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.integral.list,
            data: {
                status: status,
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        order_list: res.data.list,
                        status: status
                    });
                }
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },


    orderSubmitPay: function (e) {
        var self = this;
        var data = e.currentTarget.dataset;
        getApp().core.showLoading({
            title: "提交中",
            mask: true,
        });
        getApp().request({
            url: getApp().api.integral.order_submit,
            data: {
                id: data.id,
            },
            success: function (res) {
                if (res.code == 0) {
                    getApp().core.hideLoading();
                    getApp().core.requestPayment({
                        _res: res,
                        timeStamp: res.data.timeStamp,
                        nonceStr: res.data.nonceStr,
                        package: res.data.package,
                        signType: res.data.signType,
                        paySign: res.data.paySign,
                        complete: function (e) {
                            if (e.errMsg == "requestPayment:fail" || e.errMsg == "requestPayment:fail cancel") {
                                getApp().core.showModal({
                                    title: "提示",
                                    content: "订单尚未支付",
                                    showCancel: false,
                                    confirmText: "确认",
                                });
                                return;
                            }
                            if (e.errMsg == "requestPayment:ok") {

                                getApp().core.redirectTo({
                                    url: "/pages/integral-mall/order/order?status=1",
                                });
                            }
                        },
                    });
                } else {
                    getApp().core.hideLoading();
                    getApp().core.showModal({
                        title: "提示",
                        content: res.msg,
                        showCancel: false,
                        confirmText: "确认",
                    });
                }
            }
        });
    },


    orderRevoke: function (e) {
        var self = this;
        getApp().core.showModal({
            title: "提示",
            content: "是否取消该订单？",
            cancelText: "否",
            confirmText: "是",
            success: function (res) {
                if (res.cancel)
                    return true;
                if (res.confirm) {
                    getApp().core.showLoading({
                        title: "操作中",
                    });
                    getApp().request({
                        url: getApp().api.integral.revoke,
                        data: {
                            order_id: e.currentTarget.dataset.id,
                        },
                        success: function (res) {
                            getApp().core.hideLoading();
                            getApp().core.showModal({
                                title: "提示",
                                content: res.msg,
                                showCancel: false,
                                success: function (res) {
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

    orderConfirm: function (e) {
        var self = this;
        getApp().core.showModal({
            title: "提示",
            content: "是否确认已收到货？",
            cancelText: "否",
            confirmText: "是",
            success: function (res) {
                if (res.cancel)
                    return true;
                if (res.confirm) {
                    getApp().core.showLoading({
                        title: "操作中",
                    });
                    getApp().request({
                        url: getApp().api.integral.confirm,
                        data: {
                            order_id: e.currentTarget.dataset.id,
                        },
                        success: function (res) {
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


    orderQrcode: function (e) {
        var self = this;
        var order_list = self.data.order_list;
        var index = e.target.dataset.index;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.integral.get_qrcode,
            data: {
                order_no: order_list[index].order_no
            },
            success: function (res) {
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
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },

    hide: function (e) {
        this.setData({
            hide: 1
        });
    }

});