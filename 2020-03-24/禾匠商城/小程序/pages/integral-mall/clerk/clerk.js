Page({
    data: {},
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var t = this;
        if (e.scene) {
            var o = e.scene;
            t.setData({
                type: ""
            });
        } else if (e.type) {
            t.setData({
                type: e.type,
                status: 1
            });
            o = e.id;
        } else {
            o = e.id;
            t.setData({
                status: 1,
                type: ""
            });
        }
        if ("undefined" == typeof my) ; else if (t.setData({
            type: ""
        }), null !== getApp().query) {
            var n = getApp().query;
            getApp().query = null;
            o = n.order_no;
        }
        o && (t.setData({
            order_id: o
        }), getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.integral.clerk_order_details,
            data: {
                id: o,
                type: t.data.type
            },
            success: function(e) {
                0 == e.code ? t.setData({
                    order_info: e.data
                }) : getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1,
                    success: function(e) {
                        e.confirm && getApp().core.redirectTo({
                            url: "/pages/integral-mall/order/order?status=2"
                        });
                    }
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        }));
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
    clerkOrder: function(e) {
        var t = this;
        getApp().core.showModal({
            title: "提示",
            content: "是否确认核销？",
            success: function(e) {
                e.confirm ? (getApp().core.showLoading({
                    title: "正在加载"
                }), getApp().request({
                    url: getApp().api.integral.clerk,
                    data: {
                        order_id: t.data.order_id
                    },
                    success: function(e) {
                        0 == e.code ? getApp().core.showModal({
                            showCancel: !1,
                            content: e.msg,
                            confirmText: "确认",
                            success: function(e) {
                                e.confirm && getApp().core.redirectTo({
                                    url: "/pages/index/index"
                                });
                            }
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
    }
});