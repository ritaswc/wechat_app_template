var app = getApp(), api = getApp().api;

Page({
    data: {
        order: {}
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
    expressChange: function(e) {
        var t = this;
        t.data.order.default_express = t.data.order.express_list[e.detail.value].express, 
        t.setData({
            order: t.data.order
        });
    },
    expressInput: function(e) {
        this.data.order.default_express = e.detail.value;
    },
    expressNoInput: function(e) {
        this.data.order.express_no = e.detail.value;
    },
    wordsInput: function(e) {
        this.data.order.words = e.detail.value;
    },
    formSubmit: function(e) {
        getApp().core.showLoading({
            title: "正在提交",
            mask: !0
        }), getApp().request({
            url: getApp().api.mch.order.send,
            method: "post",
            data: {
                send_type: "express",
                order_id: this.data.order.id,
                express: e.detail.value.express,
                express_no: e.detail.value.express_no,
                words: e.detail.value.words
            },
            success: function(t) {
                getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    success: function(e) {
                        e.confirm && 0 == t.code && getApp().core.redirectTo({
                            url: "/mch/m/order/order?status=2"
                        });
                    }
                });
            },
            complete: function(e) {
                getApp().core.hideLoading();
            }
        });
    }
});