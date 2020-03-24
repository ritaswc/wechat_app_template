Page({
    data: {
        second: 60
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var e = this;
        getApp().request({
            url: getApp().api.user.sms_setting,
            method: "get",
            data: {
                page: 1
            },
            success: function(t) {
                e.setData({
                    status: 0 == t.code
                });
            }
        });
    },
    gainPhone: function() {
        this.setData({
            gainPhone: !0,
            handPhone: !1
        });
    },
    handPhone: function() {
        this.setData({
            gainPhone: !1,
            handPhone: !0
        });
    },
    nextStep: function() {
        var e = this, t = this.data.handphone;
        t && 11 == t.length ? getApp().request({
            url: getApp().api.user.user_hand_binding,
            method: "POST",
            data: {
                content: t
            },
            success: function(t) {
                0 == t.code ? (e.timer(), e.setData({
                    content: t.msg,
                    timer: !0
                })) : (t.code, getApp().core.showToast({
                    title: t.msg
                }));
            }
        }) : getApp().core.showToast({
            title: "手机号码错误"
        });
    },
    timer: function() {
        var a = this;
        new Promise(function(t, e) {
            var n = setInterval(function() {
                a.setData({
                    second: a.data.second - 1
                }), a.data.second <= 0 && (a.setData({
                    timer: !1
                }), t(n));
            }, 1e3);
        }).then(function(t) {
            clearInterval(t);
        });
    },
    HandPhoneInput: function(t) {
        this.setData({
            handphone: t.detail.value
        });
    },
    CodeInput: function(t) {
        this.setData({
            code: t.detail.value
        });
    },
    PhoneInput: function(t) {
        this.setData({
            phoneNum: t.detail.value
        });
    },
    onSubmit: function() {
        var e = this, t = e.data.gainPhone, n = e.data.handPhone, a = t ? 1 : n ? 2 : 0;
        if (t) {
            var o = e.data.phoneNum;
            if (o) {
                if (11 != o.length) return void getApp().core.showToast({
                    title: "手机号码错误"
                });
                var i = o;
            } else {
                if (!(i = e.data.PhoneNumber)) return void getApp().core.showToast({
                    title: "手机号码错误"
                });
            }
        } else {
            i = e.data.handphone;
            if (!/^\+?\d[\d -]{8,12}\d/.test(i)) return void getApp().core.showToast({
                title: "手机号码错误"
            });
            var s = e.data.code;
            if (!s) return void getApp().core.showToast({
                title: "请输入验证码"
            });
        }
        getApp().request({
            url: getApp().api.user.user_empower,
            method: "POST",
            data: {
                phone: i,
                phone_code: s,
                bind_type: a
            },
            success: function(t) {
                0 == t.code ? e.setData({
                    binding: !0,
                    binding_num: i
                }) : 1 == t.code && getApp().core.showToast({
                    title: t.msg
                });
            }
        });
    },
    renewal: function() {
        this.setData({
            binding: !1,
            gainPhone: !0,
            handPhone: !1
        });
    },
    onShow: function() {
        getApp().page.onShow(this);
        var t = this, e = t.data.__user_info;
        e && e.binding ? t.setData({
            binding_num: e.binding,
            binding: !0
        }) : t.setData({
            gainPhone: !0,
            handPhone: !1
        });
    }
});