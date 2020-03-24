var app = getApp(), api = getApp().api;

Page({
    data: {
        isPageShow: !1,
        order: null,
        getGoodsTotalPrice: function() {
            return this.data.order.total_price;
        }
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var t = this;
        getApp().core.showLoading({
            title: "正在加载"
        });
        var o = getCurrentPages(), a = o[o.length - 2];
        getApp().request({
            url: getApp().api.order.detail,
            data: {
                order_id: e.id,
                route: a.route
            },
            success: function(e) {
                0 == e.code && t.setData({
                    order: e.data,
                    isPageShow: !0
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
    location: function() {
        var e = this.data.order.shop;
        getApp().core.openLocation({
            latitude: parseFloat(e.latitude),
            longitude: parseFloat(e.longitude),
            address: e.address,
            name: e.name
        });
    },
    orderRevoke: function(t) {
        var o = this;
        getApp().core.showModal({
            title: "提示",
            content: "是否退款该订单？",
            cancelText: "否",
            confirmText: "是",
            success: function(e) {
                if (e.cancel) return !0;
                e.confirm && (getApp().core.showLoading({
                    title: "操作中"
                }), getApp().request({
                    url: getApp().api.order.revoke,
                    data: {
                        order_id: t.currentTarget.dataset.id
                    },
                    success: function(e) {
                        getApp().core.hideLoading(), getApp().core.showModal({
                            title: "提示",
                            content: e.msg,
                            showCancel: !1,
                            success: function(e) {
                                e.confirm && o.onLoad({
                                    id: o.data.order.order_id
                                });
                            }
                        });
                    }
                }));
            }
        });
    }
});