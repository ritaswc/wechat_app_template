Page({
    options: "",
    data: {
        hide: 1,
        qrcode: ""
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e), this.options = e;
    },
    onReady: function(e) {
        getApp().page.onReady(this);
    },
    onShow: function(e) {
        getApp().page.onShow(this);
        this.loadOrderDetails();
    },
    onHide: function(e) {
        getApp().page.onHide(this);
    },
    onUnload: function(e) {
        getApp().page.onUnload(this);
    },
    onPullDownRefresh: function(e) {
        getApp().page.onPullDownRefresh(this);
    },
    onReachBottom: function(e) {
        getApp().page.onReachBottom(this);
    },
    onShareAppMessage: function(e) {
        getApp().page.onShareAppMessage(this);
        var t = this, o = "/pages/pt/group/details?oid=" + t.data.order_info.order_id;
        return {
            title: t.data.order_info.goods_list[0].name,
            path: o,
            imageUrl: t.data.order_info.goods_list[0].goods_pic,
            success: function(e) {}
        };
    },
    loadOrderDetails: function() {
        var t = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.group.order.detail,
            data: {
                order_id: t.options.id
            },
            success: function(e) {
                0 == e.code ? (3 != e.data.status && t.countDownRun(e.data.limit_time_ms), t.setData({
                    order_info: e.data,
                    limit_time: e.data.limit_time
                })) : getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1,
                    success: function(e) {
                        e.confirm && getApp().core.redirectTo({
                            url: "/pages/pt/order/order"
                        });
                    }
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    copyText: function(e) {
        var t = e.currentTarget.dataset.text;
        getApp().core.setClipboardData({
            data: t,
            success: function() {
                getApp().core.showToast({
                    title: "已复制"
                });
            }
        });
    },
    countDownRun: function(n) {
        var r = this;
        setInterval(function() {
            var e = new Date(n[0], n[1] - 1, n[2], n[3], n[4], n[5]) - new Date(), t = parseInt(e / 1e3 / 60 / 60 % 24, 10), o = parseInt(e / 1e3 / 60 % 60, 10), i = parseInt(e / 1e3 % 60, 10);
            t = r.checkTime(t), o = r.checkTime(o), i = r.checkTime(i), r.setData({
                limit_time: {
                    hours: 0 < t ? t : 0,
                    mins: 0 < o ? o : 0,
                    secs: 0 < i ? i : 0
                }
            });
        }, 1e3);
    },
    checkTime: function(e) {
        return e < 10 && (e = "0" + e), e;
    },
    toConfirm: function(e) {
        var t = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.group.order.confirm,
            data: {
                order_id: t.data.order_info.order_id
            },
            success: function(e) {
                0 == e.code ? getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1,
                    success: function(e) {
                        e.confirm && getApp().core.redirectTo({
                            url: "/pages/pt/order-details/order-details?id=" + t.data.order_info.order_id
                        });
                    }
                }) : getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1,
                    success: function(e) {
                        e.confirm && getApp().core.redirectTo({
                            url: "/pages/pt/order-details/order-details?id=" + t.data.order_info.order_id
                        });
                    }
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    goToGroup: function(e) {
        getApp().core.redirectTo({
            url: "/pages/pt/group/details?oid=" + this.data.order_info.order_id,
            success: function(e) {},
            fail: function(e) {},
            complete: function(e) {}
        });
    },
    location: function() {
        var e = this.data.order_info.shop;
        getApp().core.openLocation({
            latitude: parseFloat(e.latitude),
            longitude: parseFloat(e.longitude),
            address: e.address,
            name: e.name
        });
    },
    getOfflineQrcode: function(e) {
        var t = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.group.order.get_qrcode,
            data: {
                order_no: e.currentTarget.dataset.id
            },
            success: function(e) {
                0 == e.code ? t.setData({
                    hide: 0,
                    qrcode: e.data.url
                }) : getApp().core.showModal({
                    title: "提示",
                    content: e.msg
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    hide: function(e) {
        this.setData({
            hide: 1
        });
    },
    orderRevoke: function() {
        var t = this;
        getApp().core.showModal({
            title: "提示",
            content: "是否取消该订单？",
            cancelText: "否",
            confirmtext: "是",
            success: function(e) {
                e.confirm && (getApp().core.showLoading({
                    title: "操作中"
                }), getApp().request({
                    url: getApp().api.group.order.revoke,
                    data: {
                        order_id: t.data.order_info.order_id
                    },
                    success: function(e) {
                        getApp().core.hideLoading(), getApp().core.showModal({
                            title: "提示",
                            content: e.msg,
                            showCancel: !1,
                            success: function(e) {
                                e.confirm && t.loadOrderDetails();
                            }
                        });
                    }
                }));
            }
        });
    }
});