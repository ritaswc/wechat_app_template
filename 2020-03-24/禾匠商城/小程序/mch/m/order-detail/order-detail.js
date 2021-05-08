var app = getApp(), api = getApp().api;

Page({
    data: {
        show_edit_modal: !1,
        order_sub_price: "",
        order_sub_price_mode: !0,
        order_add_price: "",
        order_add_price_mode: !1,
        show_send_modal: !1,
        send_type: "express",
        order: null
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var t = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.mch.order.detail,
            data: {
                id: e.id
            },
            success: function(e) {
                0 == e.code ? t.setData({
                    order: e.data.order
                }) : getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1,
                    success: function(e) {
                        e.confirm && getApp().core.navigateBack();
                    }
                });
            },
            complete: function() {
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
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    copyUserAddress: function() {
        var t = this;
        getApp().core.setClipboardData({
            data: "收货人:" + t.data.order.username + ",联系电话:" + t.data.order.mobile + ",收货地址:" + t.data.order.address,
            success: function(e) {
                getApp().core.getClipboardData({
                    success: function(e) {
                        t.showToast({
                            title: "已复制收货信息"
                        });
                    }
                });
            }
        });
    },
    showEditModal: function(e) {
        this.setData({
            show_edit_modal: !0,
            order_sub_price: "",
            order_add_price: "",
            order_sub_price_mode: !0,
            order_add_price_mode: !1
        });
    },
    hideEditModal: function(e) {
        this.setData({
            show_edit_modal: !1
        });
    },
    tabSwitch: function(e) {
        var t = e.currentTarget.dataset.tab;
        "order_sub_price_mode" == t && this.setData({
            order_sub_price_mode: !0,
            order_add_price_mode: !1
        }), "order_add_price_mode" == t && this.setData({
            order_sub_price_mode: !1,
            order_add_price_mode: !0
        });
    },
    subPriceInput: function(e) {
        this.setData({
            order_sub_price: e.detail.value
        });
    },
    subPriceBlur: function(e) {
        var t = parseFloat(e.detail.value);
        t = isNaN(t) ? "" : t <= 0 ? "" : t.toFixed(2), this.setData({
            order_sub_price: t
        });
    },
    addPriceInput: function(e) {
        this.setData({
            order_add_price: e.detail.value
        });
    },
    addPriceBlur: function(e) {
        var t = parseFloat(e.detail.value);
        t = isNaN(t) ? "" : t <= 0 ? "" : t.toFixed(2), this.setData({
            order_add_price: t
        });
    },
    editPriceSubmit: function() {
        var d = this, e = d.data.order_sub_price_mode ? "sub" : "add";
        getApp().core.showLoading({
            mask: !0,
            title: "正在处理"
        }), getApp().request({
            url: getApp().api.mch.order.edit_price,
            method: "post",
            data: {
                order_id: d.data.order.id,
                type: e,
                price: "sub" == e ? d.data.order_sub_price : d.data.order_add_price
            },
            success: function(t) {
                getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    success: function(e) {
                        e.confirm && 0 == t.code && getApp().core.redirectTo({
                            url: "/mch/m/order-detail/order-detail?id=" + d.data.order.id
                        });
                    }
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    showSendModal: function() {
        this.setData({
            show_send_modal: !0,
            send_type: "express"
        });
    },
    hideSendModal: function() {
        this.setData({
            show_send_modal: !1
        });
    },
    switchSendType: function(e) {
        var t = e.currentTarget.dataset.type;
        this.setData({
            send_type: t
        });
    },
    sendSubmit: function() {
        var d = this;
        if ("express" == d.data.send_type) return d.hideSendModal(), void getApp().core.navigateTo({
            url: "/mch/m/order-send/order-send?id=" + d.data.order.id
        });
        getApp().core.showModal({
            title: "提示",
            content: "无需物流方式订单将直接标记成已发货，确认操作？",
            success: function(e) {
                e.confirm && (getApp().core.showLoading({
                    title: "正在提交",
                    mask: !0
                }), getApp().request({
                    url: getApp().api.mch.order.send,
                    method: "post",
                    data: {
                        send_type: "none",
                        order_id: d.data.order.id
                    },
                    success: function(t) {
                        getApp().core.showModal({
                            title: "提示",
                            content: t.msg,
                            success: function(e) {
                                e.confirm && 0 == t.code && getApp().core.redirectTo({
                                    url: "/mch/m/order-detail/order-detail?id=" + d.data.order.id
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
    }
});