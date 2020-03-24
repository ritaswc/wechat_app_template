var app = getApp(), api = getApp().api, goodsRefund = require("../../components/goods/goods_refund.js");

Page({
    data: {
        isPageShow: !1,
        pageType: "STORE",
        switch_tab_1: "active",
        switch_tab_2: "",
        goods: {},
        refund_data_1: {},
        refund_data_2: {}
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var o = this;
        wx.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.order.refund_preview,
            data: {
                order_detail_id: e.id
            },
            success: function(e) {
                wx.hideLoading(), 0 == e.code && o.setData({
                    goods: e.data,
                    isPageShow: !0
                }), 1 == e.code && getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    image: "/images/icon-warning.png",
                    success: function(e) {
                        e.confirm && getApp().core.navigateBack();
                    }
                });
            }
        });
    },
    onReady: function() {
        getApp().page.onReady(this);
    },
    onShow: function() {
        getApp().page.onShow(this), goodsRefund.init(this);
    }
});