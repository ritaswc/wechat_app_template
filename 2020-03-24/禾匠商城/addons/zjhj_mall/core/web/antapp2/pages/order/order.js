if (typeof wx === 'undefined') var wx = getApp().core;
// order.js
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
        order_list: [],
        show_no_data_tip: false,
        hide: 1,
        qrcode: ""
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        is_no_more = false;
        is_loading = false;
        p = 2;
        self.setData({
            options: options
        });
        self.loadOrderList(options.status || -1);
        var pages = getCurrentPages();
        if (pages.length < 2) {
            self.setData({
                show_index: true,
            });
        }
    },

    loadOrderList: function (status) {
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
        var data = {
            status: self.data.status,
        };
        var options = self.data.options;
        if (typeof self.data.options.order_id !== 'undefined'){
            data.order_id = self.data.options.order_id
        }
        getApp().request({
            url: getApp().api.order.list,
            data: data,
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        order_list: res.data.list,
                        pay_type_list: res.data.pay_type_list
                    });
                    var item = getApp().core.getStorageSync(getApp().const.ITEM);
                    if (item) {
                        getApp().core.removeStorageSync(getApp().const.ITEM);
                    }
                }
                self.setData({
                    show_no_data_tip: (self.data.order_list.length == 0),
                });
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },


    onReachBottom: function () {
        var self = this;
        if (is_loading || is_no_more)
            return;
        is_loading = true;
        getApp().request({
            url: getApp().api.order.list,
            data: {
                status: self.data.status,
                page: p,
            },
            success: function (res) {
                if (res.code == 0) {

                    var order_list = self.data.order_list.concat(res.data.list);
                    self.setData({
                        order_list: order_list,
                        pay_type_list: res.data.pay_type_list
                    });
                    if (res.data.list.length == 0) {
                        is_no_more = true;
                    }
                }
                p++;
            },
            complete: function () {
                is_loading = false;
            }
        });
    },

    /**
     * 已废弃
     * 新支付接口在/commons/order-pay/order-pay.js
     */
    orderPay_1: function (e) {
        var self = this;
        var pay_type_list = self.data.pay_type_list;
        if (pay_type_list.length == 1) {
            getApp().core.showLoading({
                title: "正在提交",
                mask: true,
            });
            if (pay_type_list[0]['payment'] == 0) {
                self.WechatPay(e);
            }
            if (pay_type_list[0]['payment'] == 3) {
                self.BalancePay(e);
            }
        } else {
            getApp().core.showModal({
                title: '提示',
                content: '选择支付方式',
                cancelText: '余额支付',
                confirmText: '线上支付',
                success: function (res) {
                    getApp().core.showLoading({
                        title: "正在提交",
                        mask: true,
                    });
                    if (res.confirm) {
                        self.WechatPay(e);
                    } else if (res.cancel) {
                        self.BalancePay(e);
                    }
                }
            })
        }
    },

    WechatPay: function (e) {
        getApp().request({
            url: getApp().api.order.pay_data,
            data: {
                order_id: e.currentTarget.dataset.id,
                pay_type: "WECHAT_PAY",
            },
            complete: function () {
                getApp().core.hideLoading();
            },
            success: function (res) {
                if (res.code == 0) {
                    getApp().core.requestPayment({
                        _res: res,
                        timeStamp: res.data.timeStamp,
                        nonceStr: res.data.nonceStr,
                        package: res.data.package,
                        signType: res.data.signType,
                        paySign: res.data.paySign,
                        success: function (e) {
                        },
                        fail: function (e) {
                        },
                        complete: function (e) {

                            if (e.errMsg == "requestPayment:fail" || e.errMsg == "requestPayment:fail cancel") {//支付失败转到待支付订单列表
                                getApp().core.showModal({
                                    title: "提示",
                                    content: "订单尚未支付",
                                    showCancel: false,
                                    confirmText: "确认",
                                    success: function (res) {
                                        if (res.confirm) {
                                            getApp().core.redirectTo({
                                                url: "/pages/order/order?status=0",
                                            });
                                        }
                                    }
                                });
                                return;
                            }
                            getApp().core.redirectTo({
                                url: "/pages/order/order?status=1",
                            });
                        },
                    });
                }
                if (res.code == 1) {
                    getApp().core.showToast({
                        title: res.msg,
                        image: "/images/icon-warning.png",
                    });
                }
            }
        });
    },

    BalancePay: function (e) {

        getApp().request({
            url: getApp().api.order.pay_data,
            data: {
                order_id: e.currentTarget.dataset.id,
                pay_type: "BALANCE_PAY",
            },
            complete: function () {
                getApp().core.hideLoading();
            },
            success: function (res) {
                if (res.code == 0) {
                    getApp().core.redirectTo({
                        url: "/pages/order/order?status=1",
                    });
                }
                if (res.code == 1) {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false
                    })
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
                        url: getApp().api.order.revoke,
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
                        url: getApp().api.order.confirm,
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
        }
    },
    hide: function (e) {
        this.setData({
            hide: 1
        });
    },
    onShow: function () {
        getApp().page.onShow(this);
    }

});