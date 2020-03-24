var goodsSend = require("../../../components/goods/goods_send.js");

Page({
    data: {
        pageType: "MIAOSHA",
        order_refund: null,
        express_index: null
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var o = this;
        getApp().core.showLoading({
            title: "正在加载"
        }), getApp().request({
            url: getApp().api.miaosha.refund_detail,
            data: {
                order_refund_id: e.id
            },
            success: function(e) {
                0 == e.code && o.setData({
                    order_refund: e.data
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    onReady: function(e) {
        getApp().page.onReady(this);
    },
    onShow: function(e) {
        getApp().page.onShow(this), goodsSend.init(this);
    },
    onHide: function(e) {
        getApp().page.onHide(this);
    },
    onUnload: function(e) {
        getApp().page.onUnload(this);
    }
});