var app = getApp(), api = getApp().api;

Page({
    data: {
        qrcode_pic: ""
    },
    onLoad: function(o) {
        getApp().page.onLoad(this, o);
        var e = this;
        getApp().request({
            url: getApp().api.mch.user.shop_qrcode,
            success: function(o) {
                0 == o.code ? e.setData({
                    header_bg: o.data.header_bg,
                    shop_logo: o.data.shop_logo,
                    shop_name: o.data.shop_name,
                    qrcode_pic: o.data.qrcode_pic
                }) : getApp().core.showModal({
                    title: "提示",
                    content: o.msg,
                    success: function() {}
                });
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
    onReachBottom: function() {}
});