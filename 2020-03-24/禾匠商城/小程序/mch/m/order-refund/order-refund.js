var app = getApp(), api = getApp().api;

Page({
    data: {},
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var t = this;
        t.setData({
            id: e.id || 0
        }), getApp().core.showLoading({
            title: "加载中",
            mask: !0
        }), getApp().request({
            url: getApp().api.mch.order.refund_detail,
            data: {
                id: t.data.id
            },
            success: function(e) {
                0 == e.code && t.setData(e.data), 1 == e.code && getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1
                });
            },
            complete: function(e) {
                getApp().core.hideLoading();
            }
        });
    },
    onReady: function() {
        getApp().page.onReady(this);
    },
    onShow: function() {
        getApp().page.onShow(this);
    },
    onHide: function() {
        getApp().page.onHide(this);
    },
    onUnload: function() {
        getApp().page.onUnload(this);
    },
    showPicList: function(e) {
        getApp().core.previewImage({
            urls: this.data.pic_list,
            current: this.data.pic_list[e.currentTarget.dataset.pindex]
        });
    },
    refundPass: function(e) {
        var t = this, o = t.data.id, p = t.data.type;
        getApp().core.showModal({
            title: "提示",
            content: "确认同意" + (1 == p ? "退款？资金将原路返回！" : "换货？"),
            success: function(e) {
                e.confirm && (getApp().core.showLoading({
                    title: "正在处理",
                    mask: !0
                }), getApp().request({
                    url: getApp().api.mch.order.refund,
                    method: "post",
                    data: {
                        id: o,
                        action: "pass"
                    },
                    success: function(e) {
                        getApp().core.showModal({
                            title: "提示",
                            content: e.msg,
                            showCancel: !1,
                            success: function(e) {
                                getApp().core.redirectTo({
                                    url: "/" + t.route + "?" + getApp().helper.objectToUrlParams(t.options)
                                });
                            }
                        });
                    },
                    complete: function() {
                        getApp().core.hideLoading();
                    }
                }));
            }
        });
    },
    refundDeny: function(e) {
        var t = this, o = t.data.id;
        getApp().core.showModal({
            title: "提示",
            content: "确认拒绝？",
            success: function(e) {
                e.confirm && (getApp().core.showLoading({
                    title: "正在处理",
                    mask: !0
                }), getApp().request({
                    url: getApp().api.mch.order.refund,
                    method: "post",
                    data: {
                        id: o,
                        action: "deny"
                    },
                    success: function(e) {
                        getApp().core.showModal({
                            title: "提示",
                            content: e.msg,
                            showCancel: !1,
                            success: function(e) {
                                getApp().core.redirectTo({
                                    url: "/" + t.route + "?" + getApp().helper.objectToUrlParams(t.options)
                                });
                            }
                        });
                    },
                    complete: function() {
                        getApp().core.hideLoading();
                    }
                }));
            }
        });
    }
});