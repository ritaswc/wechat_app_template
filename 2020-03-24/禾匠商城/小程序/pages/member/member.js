function setOnShowScene(t) {
    getApp().onShowData || (getApp().onShowData = {}), getApp().onShowData.scene = t;
}

Page({
    data: {
        list: ""
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var e = this;
        e.setData({
            my: "undefined" != typeof my
        }), getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.user.member,
            method: "POST",
            success: function(t) {
                getApp().core.hideLoading(), 0 == t.code && (e.setData(t.data), e.setData({
                    current_key: 0
                }), t.data.list && e.setData({
                    buy_price: t.data.list[0].price
                }));
            }
        });
    },
    showDialogBtn: function() {
        this.setData({
            showModal: !0
        });
    },
    preventTouchMove: function() {},
    hideModal: function() {
        this.setData({
            showModal: !1
        });
    },
    onCancel: function() {
        this.hideModal();
    },
    pay: function(t) {
        var e = t.currentTarget.dataset.key, a = this.data.list[e].id, n = t.currentTarget.dataset.payment;
        this.hideModal(), getApp().request({
            url: getApp().api.user.submit_member,
            data: {
                level_id: a,
                pay_type: n
            },
            method: "POST",
            success: function(t) {
                if (0 == t.code) {
                    if (setTimeout(function() {
                        getApp().core.hideLoading();
                    }, 1e3), "WECHAT_PAY" == n) return setOnShowScene("pay"), void getApp().core.requestPayment({
                        _res: t,
                        timeStamp: t.data.timeStamp,
                        nonceStr: t.data.nonceStr,
                        package: t.data.package,
                        signType: t.data.signType,
                        paySign: t.data.paySign,
                        complete: function(t) {
                            "requestPayment:fail" != t.errMsg && "requestPayment:fail cancel" != t.errMsg ? "requestPayment:ok" == t.errMsg && getApp().core.showModal({
                                title: "提示",
                                content: "充值成功",
                                showCancel: !1,
                                confirmText: "确认",
                                success: function(t) {
                                    getApp().core.navigateBack({
                                        delta: 1
                                    });
                                }
                            }) : getApp().core.showModal({
                                title: "提示",
                                content: "订单尚未支付",
                                showCancel: !1,
                                confirmText: "确认"
                            });
                        }
                    });
                    "BALANCE_PAY" == n && getApp().core.showModal({
                        title: "提示",
                        content: "充值成功",
                        showCancel: !1,
                        confirmText: "确认",
                        success: function(t) {
                            getApp().core.navigateBack({
                                delta: 1
                            });
                        }
                    });
                } else getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1
                }), getApp().core.hideLoading();
            }
        });
    },
    changeTabs: function(t) {
        if ("undefined" == typeof my) var e = t.detail.currentItemId; else e = this.data.list[t.detail.current].id;
        for (var a = t.detail.current, n = parseFloat(this.data.list[0].price), i = this.data.list, o = 0; o < a; o++) n += parseFloat(i[o + 1].price);
        this.setData({
            current_id: e,
            current_key: a,
            buy_price: parseFloat(n)
        });
    },
    det: function(t) {
        var e = t.currentTarget.dataset.index, a = t.currentTarget.dataset.idxs;
        if (e != this.data.ids) {
            var n = t.currentTarget.dataset.content;
            this.setData({
                ids: e,
                cons: !0,
                idx: a,
                content: n
            });
        } else this.setData({
            ids: -1,
            cons: !1,
            idx: a
        });
    }
});