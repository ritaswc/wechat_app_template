var utils = require("../../../utils/helper.js");

Page({
    data: {
        hide: 1,
        qrcode: ""
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e), this.getOrderDetails(e);
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
        var o = "";
        if ("undefined" == typeof my) o = e.scene; else if (null !== getApp().query) {
            var t = getApp().query;
            getApp().query = null, o = t.order_id;
        }
        var n = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.book.clerk_order_details,
            method: "get",
            data: {
                id: o
            },
            success: function(e) {
                0 == e.code ? n.setData({
                    goods: e.data
                }) : getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1,
                    success: function(e) {
                        e.confirm && getApp().core.redirectTo({
                            url: "/pages/user/user"
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
    nowWriteOff: function(e) {
        var o = this;
        getApp().core.showModal({
            title: "提示",
            content: "是否确认核销？",
            success: function(e) {
                e.confirm ? (getApp().core.showLoading({
                    title: "正在加载"
                }), getApp().request({
                    url: getApp().api.book.clerk,
                    data: {
                        order_id: o.data.goods.id
                    },
                    success: function(e) {
                        0 == e.code ? getApp().core.redirectTo({
                            url: "/pages/user/user"
                        }) : getApp().core.showModal({
                            title: "警告！",
                            showCancel: !1,
                            content: e.msg,
                            confirmText: "确认",
                            success: function(e) {
                                e.confirm && getApp().core.redirectTo({
                                    url: "/pages/index/index"
                                });
                            }
                        });
                    },
                    complete: function() {
                        getApp().core.hideLoading();
                    }
                })) : e.cancel;
            }
        });
    }
});