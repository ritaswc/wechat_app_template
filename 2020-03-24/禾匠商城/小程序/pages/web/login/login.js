Page({
    data: {},
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
    },
    onReady: function(e) {
        getApp().page.onReady(this);
    },
    onShow: function(e) {
        getApp().page.onShow(this);
    },
    onHide: function(e) {
        getApp().page.onHide(this);
    },
    onUnload: function(e) {
        getApp().page.onUnload(this);
    },
    loginSubmit: function() {
        var e = this.options.scene || !1;
        if ("undefined" != typeof my && null !== getApp().query) {
            var n = getApp().query;
            getApp().query = null, e = n.token;
        }
        if (!e) return getApp().core.showModal({
            title: "提示",
            content: "无效的Token，请刷新页面后重新扫码登录",
            showCancel: !1,
            success: function(e) {
                e.confirm && getApp().core.redirectTo({
                    url: "/pages/index/index"
                });
            }
        }), !1;
        getApp().core.showLoading({
            title: "正在处理",
            mask: !0
        }), getApp().request({
            url: getApp().api.user.web_login + "&token=" + e,
            success: function(e) {
                getApp().core.hideLoading(), getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1,
                    success: function(e) {
                        e.confirm && getApp().core.redirectTo({
                            url: "/pages/index/index"
                        });
                    }
                });
            }
        });
    }
});