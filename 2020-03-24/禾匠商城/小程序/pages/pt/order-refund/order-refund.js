var goodsRefund = require("../../../components/goods/goods_refund.js");

Page({
    data: {
        pageType: "PINTUAN",
        switch_tab_1: "active",
        switch_tab_2: "",
        goods: {
            goods_pic: "https://goss1.vcg.com/creative/vcg/800/version23/VCG21f302700c4.jpg"
        },
        refund_data_1: {},
        refund_data_2: {}
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var o = this;
        getApp().request({
            url: getApp().api.group.order.refund_preview,
            data: {
                order_id: e.id
            },
            success: function(e) {
                0 == e.code && o.setData({
                    goods: e.data
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
    onReady: function(e) {
        getApp().page.onReady(this);
    },
    onShow: function(e) {
        getApp().page.onShow(this), goodsRefund.init(this);
    }
});