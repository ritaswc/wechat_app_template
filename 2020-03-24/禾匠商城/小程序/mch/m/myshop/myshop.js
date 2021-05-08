var app = getApp(), api = getApp().api;

Page({
    data: {
        is_show: !1
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var e = this;
        getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.mch.user.myshop,
            success: function(t) {
                getApp().core.hideLoading(), 0 == t.code && (0 === t.data.mch.is_open && getApp().core.showModal({
                    title: "提示",
                    content: "\b店铺已被关闭！请联系管理员",
                    showCancel: !1,
                    success: function(t) {
                        t.confirm && getApp().core.navigateBack();
                    }
                }), e.setData(t.data), e.setData({
                    is_show: !0
                })), 1 == t.code && getApp().core.redirectTo({
                    url: "/mch/apply/apply"
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
    navigatorSubmit: function(t) {
        getApp().request({
            url: getApp().api.user.save_form_id + "&form_id=" + t.detail.formId
        }), getApp().core.navigateTo({
            url: t.detail.value.url
        });
    },
    showPcUrl: function() {
        this.setData({
            show_pc_url: !0
        });
    },
    hidePcUrl: function() {
        this.setData({
            show_pc_url: !1
        });
    },
    copyPcUrl: function() {
        var e = this;
        getApp().core.setClipboardData({
            data: e.data.pc_url,
            success: function(t) {
                e.showToast({
                    title: "内容已复制"
                });
            }
        });
    }
});