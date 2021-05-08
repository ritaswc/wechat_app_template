var app = getApp(), api = getApp().api;

function min(e, a) {
    return e = parseFloat(e), (a = parseFloat(a)) < e ? a : e;
}

Page({
    data: {
        price: 0,
        cash_max_day: -1,
        selected: -1
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
    },
    onShow: function() {
        getApp().page.onShow(this);
        var p = this, e = getApp().core.getStorageSync(getApp().const.SHARE_SETTING), a = getApp().core.getStorageSync(getApp().const.CUSTOM);
        p.setData({
            share_setting: e,
            custom: a
        }), getApp().request({
            url: getApp().api.share.get_price,
            success: function(e) {
                if (0 == e.code) {
                    var a = e.data.cash_last, t = "", i = "", n = "", s = "", o = p.data.selected;
                    a && (t = a.name, i = a.mobile, n = a.bank_name, s = a.type), p.setData({
                        price: e.data.price.price,
                        cash_max_day: e.data.cash_max_day,
                        pay_type: e.data.pay_type,
                        bank: e.data.bank,
                        remaining_sum: e.data.remaining_sum,
                        name: t,
                        mobile: i,
                        bank_name: n,
                        selected: o,
                        check: s,
                        cash_service_charge: e.data.cash_service_charge,
                        service_content: e.data.service_content,
                        pay_type_list: e.data.pay_type_list
                    });
                }
            }
        });
    },
    onPullDownRefresh: function() {},
    formSubmit: function(e) {
        var a = this, t = parseFloat(parseFloat(e.detail.value.cash).toFixed(2)), i = a.data.price;
        if (-1 != a.data.cash_max_day && (i = min(i, a.data.cash_max_day)), t) if (i < t) getApp().core.showToast({
            title: "提现金额不能超过" + i + "元",
            image: "/images/icon-warning.png"
        }); else if (t < parseFloat(a.data.share_setting.min_money)) getApp().core.showToast({
            title: "提现金额不能低于" + page.data.share_setting.min_money + "元",
            image: "/images/icon-warning.png"
        }); else {
            var n = a.data.selected;
            if (0 == n || 1 == n || 2 == n || 3 == n) {
                if (0 == n || 1 == n || 2 == n) {
                    if (!(p = e.detail.value.name) || null == p) return void getApp().core.showToast({
                        title: "姓名不能为空",
                        image: "/images/icon-warning.png"
                    });
                    if (!(o = e.detail.value.mobile) || null == o) return void getApp().core.showToast({
                        title: "账号不能为空",
                        image: "/images/icon-warning.png"
                    });
                }
                if (2 == n) {
                    if (!(s = e.detail.value.bank_name) || null == s) return void getApp().core.showToast({
                        title: "开户行不能为空",
                        image: "/images/icon-warning.png"
                    });
                } else var s = "";
                if (3 == n) {
                    s = "";
                    var o = "", p = "";
                }
                getApp().core.showLoading({
                    title: "正在提交",
                    mask: !0
                }), getApp().request({
                    url: getApp().api.share.apply,
                    method: "POST",
                    data: {
                        cash: t,
                        name: p,
                        mobile: o,
                        bank_name: s,
                        pay_type: n,
                        scene: "CASH",
                        form_id: e.detail.formId
                    },
                    success: function(a) {
                        getApp().core.hideLoading(), getApp().core.showModal({
                            title: "提示",
                            content: a.msg,
                            showCancel: !1,
                            success: function(e) {
                                e.confirm && 0 == a.code && getApp().core.redirectTo({
                                    url: "/pages/cash-detail/cash-detail"
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
    },
    showCashMaxDetail: function() {
        getApp().core.showModal({
            title: "提示",
            content: "今日剩余提现金额=平台每日可提现金额-今日所有用户提现金额"
        });
    },
    select: function(e) {
        var a = e.currentTarget.dataset.index;
        a != this.data.check && this.setData({
            name: "",
            mobile: "",
            bank_name: ""
        }), this.setData({
            selected: a
        });
    }
});