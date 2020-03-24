var is_no_more = !1, is_loading = !1, p = 2;

Page({
    data: {
        hide: 1,
        qrcode: "",
        scrollLeft: 0,
        scrollTop: 0
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t), this.systemInfo = getApp().core.getSystemInfoSync();
        var e = getApp().core.getStorageSync(getApp().const.STORE);
        this.setData({
            store: e
        });
        is_loading = is_no_more = !1, p = 2, this.loadOrderList(t.status || -1);
        var o = 0;
        o = 2 <= t.status ? 600 : 0, this.setData({
            scrollLeft: o
        });
    },
    onReady: function(t) {
        getApp().page.onReady(this);
    },
    onShow: function(t) {
        getApp().page.onShow(this);
    },
    onHide: function(t) {
        getApp().page.onHide(this);
    },
    onUnload: function(t) {
        getApp().page.onUnload(this);
    },
    onPullDownRefresh: function(t) {
        getApp().page.onPullDownRefresh(this);
    },
    loadOrderList: function(e) {
        null == e && (e = -1);
        var o = this;
        o.setData({
            status: e
        }), getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.group.order.list,
            data: {
                status: o.data.status
            },
            success: function(t) {
                0 == t.code && o.setData({
                    order_list: t.data.list
                }), o.setData({
                    show_no_data_tip: 0 == t.data.list.length
                }), 4 != e && o.countDown();
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    countDown: function() {
        var s = this;
        setInterval(function() {
            var t = s.data.order_list;
            for (var e in t) {
                var o = new Date(t[e].limit_time_ms[0], t[e].limit_time_ms[1] - 1, t[e].limit_time_ms[2], t[e].limit_time_ms[3], t[e].limit_time_ms[4], t[e].limit_time_ms[5]) - new Date(), a = parseInt(o / 1e3 / 60 / 60 / 24, 10), i = parseInt(o / 1e3 / 60 / 60 % 24, 10), r = parseInt(o / 1e3 / 60 % 60, 10), n = parseInt(o / 1e3 % 60, 10);
                a = s.checkTime(a), i = s.checkTime(i), r = s.checkTime(r), n = s.checkTime(n), 
                t[e].limit_time = {
                    days: a,
                    hours: 0 < i ? i : "00",
                    mins: 0 < r ? r : "00",
                    secs: 0 < n ? n : "00"
                }, s.setData({
                    order_list: t
                });
            }
        }, 1e3);
    },
    checkTime: function(t) {
        return (t = 0 < t ? t : 0) < 10 && (t = "0" + t), t;
    },
    onReachBottom: function(t) {
        getApp().page.onReachBottom(this);
        var o = this;
        is_loading || is_no_more || (is_loading = !0, getApp().request({
            url: getApp().api.group.order.list,
            data: {
                status: o.data.status,
                page: p
            },
            success: function(t) {
                if (0 == t.code) {
                    var e = o.data.order_list.concat(t.data.list);
                    o.setData({
                        order_list: e
                    }), 0 == t.data.list.length && (is_no_more = !0);
                }
                p++;
            },
            complete: function() {
                is_loading = !1;
            }
        }));
    },
    goHome: function(t) {
        getApp().core.redirectTo({
            url: "/pages/pt/index/index"
        });
    },
    orderPay_1: function(t) {
        getApp().core.showLoading({
            title: "正在提交",
            mask: !0
        }), getApp().request({
            url: getApp().api.group.pay_data,
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
                            url: "/pages/pt/order/order?status=1"
                        }) : getApp().core.showModal({
                            title: "提示",
                            content: "订单尚未支付",
                            showCancel: !1,
                            confirmText: "确认",
                            success: function(t) {
                                t.confirm && getApp().core.redirectTo({
                                    url: "/pages/pt/order/order?status=0"
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
    goToGroup: function(t) {
        getApp().core.navigateTo({
            url: "/pages/pt/group/details?oid=" + t.target.dataset.id
        });
    },
    getOfflineQrcode: function(t) {
        var e = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.group.order.get_qrcode,
            data: {
                order_no: t.currentTarget.dataset.id
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
    goToCancel: function(e) {
        var o = this;
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
                    url: getApp().api.group.order.revoke,
                    data: {
                        order_id: e.currentTarget.dataset.id
                    },
                    success: function(t) {
                        getApp().core.hideLoading(), getApp().core.showModal({
                            title: "提示",
                            content: t.msg,
                            showCancel: !1,
                            success: function(t) {
                                t.confirm && o.loadOrderList(o.data.status);
                            }
                        });
                    }
                }));
            }
        });
    },
    switchNav: function(t) {
        var e = t.currentTarget.dataset.status;
        getApp().core.redirectTo({
            url: "/pages/pt/order/order?status=" + e
        });
    },
    goToRefundDetail: function(t) {
        var e = t.currentTarget.dataset.refund_id;
        getApp().core.navigateTo({
            url: "/pages/pt/order-refund-detail/order-refund-detail?id=" + e
        });
    }
});