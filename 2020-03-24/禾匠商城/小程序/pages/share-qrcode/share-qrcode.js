var app = getApp(), api = app.api;

Page({
    data: {
        qrcode: ""
    },
    onLoad: function(e) {
        app.page.onLoad(this, e);
        var t = this;
        getApp().core.getStorageSync(getApp().const.SHARE_SETTING);
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.share.get_qrcode,
            success: function(e) {
                0 == e.code ? t.setData({
                    qrcode: e.data
                }) : getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    onShow: function() {
        getApp().page.onShow(this);
        var e = getApp().getUser();
        this.setData({
            user_info: e
        });
    },
    click: function() {
        wx.previewImage({
            current: this.data.qrcode,
            urls: [ this.data.qrcode ]
        });
    }
});