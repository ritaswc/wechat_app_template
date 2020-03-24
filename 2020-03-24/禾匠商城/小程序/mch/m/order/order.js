var app = getApp(), api = getApp().api;

Page({
    data: {
        status: 1,
        show_menu: !1,
        order_list: [],
        no_orders: !1,
        no_more_orders: !1
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var e = this;
        e.setData({
            status: parseInt(t.status || 1),
            loading_more: !0
        }), e.loadOrderList(function() {
            e.setData({
                loading_more: !1
            });
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
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    showMenu: function(t) {
        this.setData({
            show_menu: !this.data.show_menu
        });
    },
    loadOrderList: function(t) {
        var e = this, o = e.data.status, a = (e.data.current_page || 0) + 1, r = e.data.keyword || "";
        getApp().request({
            url: getApp().api.mch.order.list,
            data: {
                status: o,
                page: a,
                keyword: r
            },
            success: function(t) {
                0 == t.code && (1 != a || t.data.list && t.data.list.length || e.setData({
                    no_orders: !0
                }), t.data.list && t.data.list.length ? (e.data.order_list = e.data.order_list || [], 
                e.data.order_list = e.data.order_list.concat(t.data.list), e.setData({
                    order_list: e.data.order_list,
                    current_page: a
                })) : e.setData({
                    no_more_orders: !0
                }));
            },
            complete: function() {
                "function" == typeof t && t();
            }
        });
    },
    showSendModal: function(t) {
        this.setData({
            show_send_modal: !0,
            send_type: "express",
            order_index: t.currentTarget.dataset.index
        });
    },
    hideSendModal: function() {
        this.setData({
            show_send_modal: !1
        });
    },
    switchSendType: function(t) {
        var e = t.currentTarget.dataset.type;
        this.setData({
            send_type: e
        });
    },
    sendSubmit: function() {
        var e = this;
        if ("express" == e.data.send_type) return e.hideSendModal(), void getApp().core.navigateTo({
            url: "/mch/m/order-send/order-send?id=" + e.data.order_list[e.data.order_index].id
        });
        getApp().core.showModal({
            title: "提示",
            content: "无需物流方式订单将直接标记成已发货，确认操作？",
            success: function(t) {
                t.confirm && (getApp().core.showLoading({
                    title: "正在提交",
                    mask: !0
                }), getApp().request({
                    url: getApp().api.mch.order.send,
                    method: "post",
                    data: {
                        send_type: "none",
                        order_id: e.data.order_list[e.data.order_index].id
                    },
                    success: function(e) {
                        getApp().core.showModal({
                            title: "提示",
                            content: e.msg,
                            success: function(t) {
                                t.confirm && 0 == e.code && getApp().core.redirectTo({
                                    url: "/mch/m/order/order?status=2"
                                });
                            }
                        });
                    },
                    complete: function() {
                        getApp().core.hideLoading({
                            title: "正在提交",
                            mask: !0
                        });
                    }
                }));
            }
        });
    },
    showPicList: function(t) {
        getApp().core.previewImage({
            urls: this.data.order_list[t.currentTarget.dataset.index].pic_list,
            current: this.data.order_list[t.currentTarget.dataset.index].pic_list[t.currentTarget.dataset.pindex]
        });
    },
    refundPass: function(t) {
        var e = this, o = t.currentTarget.dataset.index, a = e.data.order_list[o].id, r = e.data.order_list[o].type;
        getApp().core.showModal({
            title: "提示",
            content: "确认同意" + (1 == r ? "退款？资金将原路返回！" : "换货？"),
            success: function(t) {
                t.confirm && (getApp().core.showLoading({
                    title: "正在处理",
                    mask: !0
                }), getApp().request({
                    url: getApp().api.mch.order.refund,
                    method: "post",
                    data: {
                        id: a,
                        action: "pass"
                    },
                    success: function(t) {
                        getApp().core.showModal({
                            title: "提示",
                            content: t.msg,
                            showCancel: !1,
                            success: function(t) {
                                getApp().core.redirectTo({
                                    url: "/" + e.route + "?" + getApp().helper.objectToUrlParams(e.options)
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
    refundDeny: function(t) {
        var e = this, o = t.currentTarget.dataset.index, a = e.data.order_list[o].id;
        e.data.order_list[o].type;
        getApp().core.showModal({
            title: "提示",
            content: "确认拒绝？",
            success: function(t) {
                t.confirm && (getApp().core.showLoading({
                    title: "正在处理",
                    mask: !0
                }), getApp().request({
                    url: getApp().api.mch.order.refund,
                    method: "post",
                    data: {
                        id: a,
                        action: "deny"
                    },
                    success: function(t) {
                        getApp().core.showModal({
                            title: "提示",
                            content: t.msg,
                            showCancel: !1,
                            success: function(t) {
                                getApp().core.redirectTo({
                                    url: "/" + e.route + "?" + getApp().helper.objectToUrlParams(e.options)
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
    searchSubmit: function(t) {
        var e = this, o = t.detail.value;
        e.setData({
            keyword: o,
            loading_more: !0,
            order_list: [],
            current_page: 0
        }), e.loadOrderList(function() {
            e.setData({
                loading_more: !1
            });
        });
    }
});