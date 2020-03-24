var goodsRefund = require("../../../components/goods/goods_refund.js");

Page({
    data: {
        pageType: "MIAOSHA",
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
        var t = this;
        getApp().request({
            url: getApp().api.miaosha.refund_preview,
            data: {
                order_detail_id: e.id
            },
            success: function(e) {
                if (0 == e.code) {
                    var o = e.data;
                    o.order_detail_id = o.order_id, t.setData({
                        goods: o
                    });
                }
                1 == e.code && getApp().core.showModal({
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