function setOnShowScene(e) {
    getApp().onShowData || (getApp().onShowData = {}), getApp().onShowData.scene = e;
}

Page({
    data: {
        selected: -1
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var n = this;
        getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.recharge.list,
            success: function(e) {
                var t = e.data;
                t.balance && 0 != t.balance.status || getApp().core.showModal({
                    title: "提示",
                    content: "充值功能未开启，请联系管理员！",
                    showCancel: !1,
                    success: function(e) {
                        e.confirm && getApp().core.navigateBack({
                            delta: 1
                        });
                    }
                }), n.setData(e.data);
            },
            complete: function(e) {
                getApp().core.hideLoading();
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
    click: function(e) {
        this.setData({
            selected: e.currentTarget.dataset.index
        });
    },
    pay: function(e) {
        var t = this, n = {}, a = t.data.selected;
        if (-1 == a) {
            var o = t.data.money;
            if (o < .01) return void getApp().core.showModal({
                title: "提示",
                content: "充值金额不能小于0.01",
                showCancel: !1
            });
            n.pay_price = o, n.send_price = 0;
        } else {
            var p = t.data.list;
            n.pay_price = p[a].pay_price, n.send_price = p[a].send_price;
        }
        n.pay_price ? (n.pay_type = "WECHAT_PAY", getApp().core.showLoading({
            title: "提交中"
        }), getApp().request({
            url: getApp().api.recharge.submit,
            data: n,
            method: "POST",
            success: function(e) {
                if (getApp().page.bindParent({
                    parent_id: getApp().core.getStorageSync(getApp().const.PARENT_ID),
                    condition: 1
                }), 0 == e.code) return setTimeout(function() {
                    getApp().core.hideLoading();
                }, 1e3), setOnShowScene("pay"), void getApp().core.requestPayment({
                    _res: e,
                    timeStamp: e.data.timeStamp,
                    nonceStr: e.data.nonceStr,
                    package: e.data.package,
                    signType: e.data.signType,
                    paySign: e.data.paySign,
                    success: function(e) {},
                    fail: function(e) {},
                    complete: function(e) {
                        "requestPayment:fail" != e.errMsg && "requestPayment:fail cancel" != e.errMsg ? (getApp().page.bindParent({
                            parent_id: getApp().core.getStorageSync(getApp().const.PARENT_ID),
                            condition: 2
                        }), getApp().core.showModal({
                            title: "提示",
                            content: "充值成功",
                            showCancel: !1,
                            confirmText: "确认",
                            success: function(e) {
                                e.confirm && getApp().core.navigateBack({
                                    delta: 1
                                });
                            }
                        })) : getApp().core.showModal({
                            title: "提示",
                            content: "订单尚未支付",
                            showCancel: !1,
                            confirmText: "确认"
                        });
                    }
                });
                getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1
                }), getApp().core.hideLoading();
            }
        })) : getApp().core.showModal({
            title: "提示",
            content: "请选择充值金额",
            showCancel: !1
        });
    },
    input: function(e) {
        this.setData({
            money: e.detail.value
        });
    }
});