Page({
    data: {},
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
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
        var t = this, e = "";
        if ("undefined" == typeof my) e = t.options.scene; else if (null !== getApp().query) {
            var o = getApp().query;
            getApp().query = null, e = o.order_id;
        }
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.group.order.clerk_order_details,
            data: {
                id: e
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
    clerkOrder: function(e) {
        var t = this;
        getApp().core.showModal({
            title: "提示",
            content: "是否确认核销？",
            success: function(e) {
                e.confirm ? (getApp().core.showLoading({
                    title: "正在加载"
                }), getApp().request({
                    url: getApp().api.group.order.clerk,
                    data: {
                        order_id: t.data.order_info.order_id
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
    },
    location: function() {
        var e = this.data.order_info.shop;
        getApp().core.openLocation({
            latitude: parseFloat(e.latitude),
            longitude: parseFloat(e.longitude),
            address: e.address,
            name: e.name
        });
    }
});