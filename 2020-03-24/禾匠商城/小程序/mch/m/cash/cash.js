var app = getApp(), api = getApp().api;

Page({
    data: {
        price: 0,
        cash_max_day: -1,
        selected: 0
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
    },
    onReady: function() {
        getApp().page.onReady(this);
    },
    onShow: function() {
        getApp().page.onShow(this);
        var a = this;
        getApp().core.showLoading({
            title: "正在提交",
            mask: !0
        }), getApp().request({
            url: getApp().api.mch.user.cash_preview,
            success: function(e) {
                if (0 == e.code) {
                    var t = {};
                    t.price = e.data.money, t.type_list = e.data.type_list, a.setData(t);
                }
            },
            complete: function(e) {
                getApp().core.hideLoading();
            }
        });
    },
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    showCashMaxDetail: function() {
        getApp().core.showModal({
            title: "提示",
            content: "今日剩余提现金额=平台每日可提现金额-今日所有用户提现金额"
        });
    },
    select: function(e) {
        var t = e.currentTarget.dataset.index;
        t != this.data.check && this.setData({
            name: "",
            mobile: "",
            bank_name: ""
        }), this.setData({
            selected: t
        });
    },
    formSubmit: function(e) {
        var t = this, a = parseFloat(parseFloat(e.detail.value.cash).toFixed(2)), i = t.data.price;
        if (a) if (i < a) getApp().core.showToast({
            title: "提现金额不能超过" + i + "元",
            image: "/images/icon-warning.png"
        }); else if (a < 1) getApp().core.showToast({
            title: "提现金额不能低于1元",
            image: "/images/icon-warning.png"
        }); else {
            var o = t.data.selected;
            if (0 == o || 1 == o || 2 == o || 3 == o || 4 == o) {
                if ("my" == t.data.__platform && 0 == o && (o = 2), 1 == o || 2 == o || 3 == o) {
                    if (!(c = e.detail.value.name) || null == c) return void getApp().core.showToast({
                        title: "姓名不能为空",
                        image: "/images/icon-warning.png"
                    });
                    if (!(s = e.detail.value.mobile) || null == s) return void getApp().core.showToast({
                        title: "账号不能为空",
                        image: "/images/icon-warning.png"
                    });
                }
                if (3 == o) {
                    if (!(n = e.detail.value.bank_name) || null == n) return void getApp().core.showToast({
                        title: "开户行不能为空",
                        image: "/images/icon-warning.png"
                    });
                } else var n = "";
                if (4 == o || 0 == o) {
                    n = "";
                    var s = "", c = "";
                }
                getApp().core.showLoading({
                    title: "正在提交",
                    mask: !0
                }), getApp().request({
                    url: getApp().api.mch.user.cash,
                    method: "POST",
                    data: {
                        cash_val: a,
                        nickname: c,
                        account: s,
                        bank_name: n,
                        type: o,
                        scene: "CASH",
                        form_id: e.detail.formId
                    },
                    success: function(t) {
                        getApp().core.hideLoading(), getApp().core.showModal({
                            title: "提示",
                            content: t.msg,
                            showCancel: !1,
                            success: function(e) {
                                e.confirm && 0 == t.code && getApp().core.redirectTo({
                                    url: "/mch/m/cash-log/cash-log"
                                });
                            }
                        });
                    }
                });
            } else getApp().core.showToast({
                title: "请选择提现方式",
                image: "/images/icon-warning.png"
            });
        } else getApp().core.showToast({
            title: "请输入提现金额",
            image: "/images/icon-warning.png"
        });
    }
});