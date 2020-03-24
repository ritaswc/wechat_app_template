var app = getApp(), api = getApp().api, goodsSend = require("../../components/goods/goods_send.js");

Page({
    data: {
        isPageShow: !1,
        pageType: "STORE",
        order_refund: null,
        express_index: null
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var o = this;
        getApp().core.showLoading({
            title: "正在加载"
        }), getApp().request({
            url: getApp().api.order.refund_detail,
            data: {
                order_refund_id: e.id
            },
            success: function(e) {
                0 == e.code && o.setData({
                    order_refund: e.data,
                    isPageShow: !0
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    onReady: function() {},
    onShow: function() {
        getApp().page.onShow(this), goodsSend.init(this);
    },
    onHide: function() {},
    onUnload: function() {}
});