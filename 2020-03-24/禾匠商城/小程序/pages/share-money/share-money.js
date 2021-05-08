var app = getApp(), api = app.api;

Page({
    data: {
        block: !1,
        active: "",
        total_price: 0,
        price: 0,
        cash_price: 0,
        un_pay: 0
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
    },
    onReady: function() {
        getApp().page.onReady(this);
    },
    onShow: function() {
        getApp().page.onShow(this);
        var e = this, t = getApp().core.getStorageSync(getApp().const.SHARE_SETTING), a = getApp().core.getStorageSync(getApp().const.CUSTOM);
        e.setData({
            share_setting: t,
            custom: a
        }), getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.share.get_price,
            success: function(t) {
                0 == t.code && e.setData({
                    total_price: t.data.price.total_price,
                    price: t.data.price.price,
                    cash_price: t.data.price.cash_price,
                    un_pay: t.data.price.un_pay
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    tapName: function(t) {
        var e = this, a = "";
        e.data.block || (a = "active"), e.setData({
            block: !e.data.block,
            active: a
        });
    },
    onHide: function() {
        getApp().page.onHide(this);
    },
    onUnload: function() {
        getApp().page.onUnload(this);
    },
    onPullDownRefresh: function() {},
    onReachBottom: function() {}
});