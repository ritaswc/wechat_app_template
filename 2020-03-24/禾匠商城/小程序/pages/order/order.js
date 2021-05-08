var app = getApp(), api = getApp().api, is_no_more = !1, is_loading = !1, p = 2;

Page({
    data: {
        status: -1,
        order_list: [],
        show_no_data_tip: !1,
        hide: 1,
        qrcode: ""
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var e = this;
        is_loading = is_no_more = !1, p = 2, e.setData({
            options: t
        }), e.loadOrderList(t.status || -1), getCurrentPages().length < 2 && e.setData({
            show_index: !0
        });
    },
    loadOrderList: function(t) {
        null == t && (t = -1);
        var e = this;
        e.setData({
            status: t
        }), getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        });
        var a = {
            status: e.data.status
        };
        e.data.options;
        void 0 !== e.data.options.order_id && (a.order_id = e.data.options.order_id), getApp().request({
            url: getApp().api.order.list,
            data: a,
            success: function(t) {
                0 == t.code && (e.setData({
                    order_list: t.data.list,
                    pay_type_list: t.data.pay_type_list
                }), getApp().core.getStorageSync(getApp().const.ITEM) && getApp().core.removeStorageSync(getApp().const.ITEM));
                e.setData({
                    show_no_data_tip: 0 == e.data.order_list.length
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    onReachBottom: function() {
        var a = this;
        is_loading || is_no_more || (is_loading = !0, getApp().request({
            url: getApp().api.order.list,
            data: {
                status: a.data.status,
                page: p
            },
            success: function(t) {
                if (0 == t.code) {
                    var e = a.data.order_list.concat(t.data.list);
                    a.setData({
                        order_list: e,
                        pay_type_list: t.data.pay_type_list
                    }), 0 == t.data.list.length && (is_no_more = !0);
                }
                p++;
            },
            complete: function() {
                is_loading = !1;
            }
        }));
    },
    orderPay_1: function(e) {
        var a = this, t = a.data.pay_type_list;
        1 == t.length ? (getApp().core.showLoading({
            title: "正在提交",
            mask: !0
        }), 0 == t[0].payment && a.WechatPay(e), 3 == t[0].payment && a.BalancePay(e)) : getApp().core.showModal({
            title: "提示",
            content: "选择支付方式",
            cancelText: "余额支付",
            confirmText: "线上支付",
            success: function(t) {
                getApp().core.showLoading({
                    title: "正在提交",
                    mask: !0
                }), t.confirm ? a.WechatPay(e) : t.cancel && a.BalancePay(e);
            }
        });
    },
    WechatPay: function(t) {
        getApp().request({
            url: getApp().api.order.pay_data,
            data: {
                order_id: t.currentTarget.dataset.id,
                pay_type: "WECHAT_PAY"
            },
            complete: function() {
                getApp().core.hideLoading();
            },
            success: function(t) {
                0 == t.code && getApp().core.requestPayment({
                    _res: t,
                    timeStamp: t.data.timeStamp,
                    nonceStr: t.data.nonceStr,
                    package: t.data.package,
                    signType: t.data.signType,
                    paySign: t.data.paySign,
                    success: function(t) {},
                    fail: function(t) {},
                    complete: function(t) {
                        "requestPayment:fail" != t.errMsg && "requestPayment:fail cancel" != t.errMsg ? getApp().core.redirectTo({
                            url: "/pages/order/order?status=1"
                        }) : getApp().core.showModal({
                            title: "提示",
                            content: "订单尚未支付",
                            showCancel: !1,
                            confirmText: "确认",
                            success: function(t) {
                                t.confirm && getApp().core.redirectTo({
                                    url: "/pages/order/order?status=0"
                                });
                            }
                        });
                    }
                }), 1 == t.code && getApp().core.showToast({
                    title: t.msg,
                    image: "/images/icon-warning.png"
                });
            }
        });
    },
    BalancePay: function(t) {
        getApp().request({
            url: getApp().api.order.pay_data,
            data: {
                order_id: t.currentTarget.dataset.id,
                pay_type: "BALANCE_PAY"
            },
            complete: function() {
                getApp().core.hideLoading();
            },
            success: function(t) {
                0 == t.code && getApp().core.redirectTo({
                    url: "/pages/order/order?status=1"
                }), 1 == t.code && getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1
                });
            }
        });
    },
    orderRevoke: function(e) {
        var a = this;
        getApp().core.showModal({
            title: "提示",
            content: "是否取消该订单？",
            cancelText: "否",
            confirmText: "是",
            success: function(t) {
                if (t.cancel) return !0;
                t.confirm && (getApp().core.showLoading({
                    title: "操作中"
                }), getApp().request({
                    url: getApp().api.order.revoke,
                    data: {
                        order_id: e.currentTarget.dataset.id
                    },
                    success: function(t) {
                        getApp().core.hideLoading(), getApp().core.showModal({
                            title: "提示",
                            content: t.msg,
                            showCancel: !1,
                            success: function(t) {
                                t.confirm && a.loadOrderList(a.data.status);
                            }
                        });
                    }
                }));
            }
        });
    },
    orderConfirm: function(e) {
        var a = this;
        getApp().core.showModal({
            title: "提示",
            content: "是否确认已收到货？",
            cancelText: "否",
            confirmText: "是",
            success: function(t) {
                if (t.cancel) return !0;
                t.confirm && (getApp().core.showLoading({
                    title: "操作中"
                }), getApp().request({
                    url: getApp().api.order.confirm,
                    data: {
                        order_id: e.currentTarget.dataset.id
                    },
                    success: function(t) {
                        getApp().core.hideLoading(), getApp().core.showToast({
                            title: t.msg
                        }), 0 == t.code && a.loadOrderList(3);
                    }
                }));
            }
        });
    },
    orderQrcode: function(t) {
        var e = this, a = e.data.order_list, o = t.target.dataset.index;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), e.data.order_list[o].offline_qrcode ? (e.setData({
            hide: 0,
            qrcode: e.data.order_list[o].offline_qrcode
        }), getApp().core.hideLoading()) : getApp().request({
            url: getApp().api.order.get_qrcode,
            data: {
                order_no: a[o].order_no
            },
            success: function(t) {
                0 == t.code ? e.setData({
                    hide: 0,
                    qrcode: t.data.url
                }) : getApp().core.showModal({
                    title: "提示",
                    content: t.msg
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    hide: function(t) {
        this.setData({
            hide: 1
        });
    },
    onShow: function() {
        getApp().page.onShow(this);
    }
});