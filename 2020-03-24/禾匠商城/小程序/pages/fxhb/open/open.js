Page({
    data: {
        page_img: {
            bg: getApp().webRoot + "/statics/images/fxhb/bg.png",
            close: getApp().webRoot + "/statics/images/fxhb/close.png",
            hongbao_bg: getApp().webRoot + "/statics/images/fxhb/hongbao_bg.png",
            open_hongbao_btn: getApp().webRoot + "/statics/images/fxhb/open_hongbao_btn.png"
        }
    },
    onLoad: function(e) {
        var o = this;
        getApp().page.onLoad(this, e), getApp().core.showLoading({
            title: "加载中",
            mask: !0
        }), getApp().request({
            url: getApp().api.fxhb.open,
            success: function(e) {
                getApp().core.hideLoading(), 0 == e.code && (e.data.hongbao_id ? getApp().core.redirectTo({
                    url: "/pages/fxhb/detail/detail?id=" + e.data.hongbao_id
                }) : o.setData(e.data)), 1 == e.code && getApp().core.showModal({
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
    },
    onReady: function() {
        getApp().page.onReady(this);
    },
    onShow: function() {
        getApp().page.onShow(this);
    },
    showRule: function() {
        this.setData({
            showRule: !0
        });
    },
    closeRule: function() {
        this.setData({
            showRule: !1
        });
    },
    openHongbao: function(e) {
        getApp().core.showLoading({
            title: "抢红包中",
            mask: !0
        }), getApp().request({
            url: getApp().api.fxhb.open_submit,
            method: "post",
            data: {
                form_id: e.detail.formId
            },
            success: function(e) {
                0 == e.code ? getApp().core.redirectTo({
                    url: "/pages/fxhb/detail/detail?id=" + e.data.hongbao_id
                }) : (getApp().core.hideLoading(), getApp().core.showModal({
                    content: e.msg,
                    showCancel: !1
                }));
            }
        });
    }
});