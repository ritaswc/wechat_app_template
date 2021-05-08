var app = getApp(), api = app.api;

Page({
    data: {
        form: {
            name: "",
            mobile: ""
        },
        img: "/images/img-share-un.png",
        agree: 0,
        show_modal: !1
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
    },
    onReady: function() {
        getApp().page.onReady(this);
    },
    onShow: function() {
        getApp().page.onShow(this);
        var o = this, t = getApp().getUser(), e = getApp().core.getStorageSync(getApp().const.SHARE_SETTING);
        getApp().getConfig(function(e) {
            var t = e.store, a = "分销商";
            t && t.share_custom_data && (a = t.share_custom_data.words.share_name.name), o.setData({
                share_name: a
            }), wx.setNavigationBarTitle({
                title: "申请成为" + a
            });
        }), getApp().core.showLoading({
            title: "加载中"
        }), o.setData({
            share_setting: e
        }), getApp().request({
            url: getApp().api.share.check,
            method: "POST",
            success: function(e) {
                0 == e.code && (t.is_distributor = e.data, getApp().setUser(t), 1 == e.data && getApp().core.redirectTo({
                    url: "/pages/share/index"
                })), o.setData({
                    __user_info: t
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    onHide: function() {
        getApp().page.onHide(this);
    },
    onUnload: function() {
        getApp().page.onUnload(this);
    },
    formSubmit: function(e) {
        var t = this, a = getApp().getUser();
        if (t.data.form = e.detail.value, null != t.data.form.name && "" != t.data.form.name) if (null != t.data.form.mobile && "" != t.data.form.mobile) {
            if (/^\+?\d[\d -]{8,12}\d/.test(t.data.form.mobile)) {
                var o = e.detail.value;
                o.form_id = e.detail.formId, 0 != t.data.agree ? (getApp().core.showLoading({
                    title: "正在提交",
                    mask: !0
                }), getApp().request({
                    url: getApp().api.share.join,
                    method: "POST",
                    data: o,
                    success: function(e) {
                        0 == e.code ? (a.is_distributor = 2, getApp().setUser(a), getApp().core.redirectTo({
                            url: "/pages/add-share/index"
                        })) : getApp().core.showToast({
                            title: e.msg,
                            image: "/images/icon-warning.png"
                        });
                    }
                })) : getApp().core.showToast({
                    title: "请先阅读并确认分销申请协议！！",
                    image: "/images/icon-warning.png"
                });
            } else getApp().core.showModal({
                title: "提示",
                content: "手机号格式不正确",
                showCancel: !1
            });
        } else getApp().core.showToast({
            title: "请填写联系方式！",
            image: "/images/icon-warning.png"
        }); else getApp().core.showToast({
            title: "请填写姓名！",
            image: "/images/icon-warning.png"
        });
    },
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    agreement: function() {
        getApp().core.getStorageSync(getApp().const.SHARE_SETTING);
        this.setData({
            show_modal: !0
        });
    },
    agree: function() {
        var e = this, t = e.data.agree;
        0 == t ? (t = 1, e.setData({
            img: "/images/img-share-agree.png",
            agree: t
        })) : 1 == t && (t = 0, e.setData({
            img: "/images/img-share-un.png",
            agree: t
        }));
    },
    close: function() {
        this.setData({
            show_modal: !1,
            img: "/images/img-share-agree.png",
            agree: 1
        });
    }
});