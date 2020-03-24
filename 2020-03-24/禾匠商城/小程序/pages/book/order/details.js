var utils = require("../../../utils/helper.js");

Page({
    data: {
        hide: 1,
        qrcode: ""
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e), this.setData({
            options: e
        }), this.getOrderDetails(e);
    },
    onReady: function(e) {
        getApp().page.onReady(this);
    },
    onShow: function(e) {
        getApp().page.onShow(this);
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
    getOrderDetails: function(e) {
        var t = e.oid, o = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.book.order_details,
            method: "get",
            data: {
                id: t
            },
            success: function(e) {
                0 == e.code ? o.setData({
                    attr: JSON.parse(e.data.attr),
                    goods: e.data
                }) : getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1,
                    success: function(e) {
                        e.confirm && getApp().core.redirectTo({
                            url: "/pages/book/order/order?status=1"
                        });
                    }
                });
            },
            complete: function(e) {
                setTimeout(function() {
                    getApp().core.hideLoading();
                }, 1e3);
            }
        });
    },
    goToGoodsDetails: function(e) {
        getApp().core.redirectTo({
            url: "/pages/book/details/details?id=" + this.data.goods.goods_id
        });
    },
    orderCancel: function(e) {
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        });
        var t = e.currentTarget.dataset.id;
        getApp().request({
            url: getApp().api.book.order_cancel,
            data: {
                id: t
            },
            success: function(e) {
                0 == e.code && getApp().core.redirectTo({
                    url: "/pages/book/order/order?status=0"
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    GoToPay: function(e) {
        getApp().core.showLoading({
            title: "正在提交",
            mask: !0
        }), getApp().request({
            url: getApp().api.book.order_pay,
            data: {
                id: e.currentTarget.dataset.id
            },
            complete: function() {
                getApp().core.hideLoading();
            },
            success: function(e) {
                0 == e.code && getApp().core.requestPayment({
                    _res: e,
                    timeStamp: e.data.timeStamp,
                    nonceStr: e.data.nonceStr,
                    package: e.data.package,
                    signType: e.data.signType,
                    paySign: e.data.paySign,
                    success: function(e) {},
                    fail: function(e) {},
                    complete: function(e) {
                        "requestPayment:fail" != e.errMsg && "requestPayment:fail cancel" != e.errMsg ? getApp().core.redirectTo({
                            url: "/pages/book/order/order?status=1"
                        }) : getApp().core.showModal({
                            title: "提示",
                            content: "订单尚未支付",
                            showCancel: !1,
                            confirmText: "确认",
                            success: function(e) {
                                e.confirm && getApp().core.redirectTo({
                                    url: "/pages/book/order/order?status=0"
                                });
                            }
                        });
                    }
                }), 1 == e.code && getApp().core.showToast({
                    title: e.msg,
                    image: "/images/icon-warning.png"
                });
            }
        });
    },
    goToShopList: function(e) {
        getApp().core.redirectTo({
            url: "/pages/book/shop/shop?ids=" + this.data.goods.shop_id
        });
    },
    orderQrcode: function(e) {
        var t = this;
        e.target.dataset.index;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), t.data.goods.offline_qrcode ? (t.setData({
            hide: 0,
            qrcode: t.data.goods.offline_qrcode
        }), getApp().core.hideLoading()) : getApp().request({
            url: getApp().api.book.get_qrcode,
            data: {
                order_no: t.data.goods.order_no
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
    comment: function(e) {
        getApp().core.navigateTo({
            url: "/pages/book/order-comment/order-comment?id=" + e.target.dataset.id,
            success: function(e) {},
            fail: function(e) {},
            complete: function(e) {}
        });
    },
    applyRefund: function(e) {
        var t = this.data.options.oid;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.book.apply_refund,
            data: {
                order_id: t
            },
            success: function(e) {
                0 == e.code ? getApp().core.showModal({
                    title: "提示",
                    content: "申请退款成功",
                    showCancel: !1,
                    success: function(e) {
                        e.confirm && getApp().core.redirectTo({
                            url: "/pages/book/order/order?status=3"
                        });
                    }
                }) : getApp().core.showModal({
                    title: "提示",
                    content: e.msg
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    }
});