if (typeof wx === 'undefined') var wx = getApp().core;
function setOnShowScene(scene) {
    if (!getApp().onShowData)
        getApp().onShowData = {};
    getApp().onShowData['scene'] = scene;
}

Page({

    /**
     * 页面的初始数据
     */
    data: {
        selected: -1,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);

        var self = this;
        getApp().core.showLoading({
            title: '加载中',
        })
        getApp().request({
            url: getApp().api.recharge.list,
            success: function(res) {
                var data = res.data;
                if (!data.balance || data.balance.status == 0) {
                    getApp().core.showModal({
                        title: '提示',
                        content: '充值功能未开启，请联系管理员！',
                        showCancel: false,
                        success: function(res) {
                            if (res.confirm) {
                                getApp().core.navigateBack({
                                    delta: 1
                                })
                            }
                        }
                    })
                }
                self.setData(res.data);
            },
            complete: function(res) {
                getApp().core.hideLoading();
            }
        });
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function() {
        getApp().page.onReady(this);
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function() {
        getApp().page.onShow(this);
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function() {
        getApp().page.onHide(this);
    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function() {
        getApp().page.onUnload(this);
    },

    click: function(e) {
        this.setData({
            selected: e.currentTarget.dataset.index
        });
    },

    pay: function(e) {
        var self = this;
        var data = {};
        var selected = self.data.selected;
        if (selected == -1) {
            var money = self.data.money;
            if (money < 0.01) {
                getApp().core.showModal({
                    title: '提示',
                    content: '充值金额不能小于0.01',
                    showCancel: false
                });
                return;
            }
            data.pay_price = money;
            data.send_price = 0;
        } else {
            var list = self.data.list;
            data.pay_price = list[selected].pay_price;
            data.send_price = list[selected].send_price;
        }
        if (!data.pay_price) {
            getApp().core.showModal({
                title: '提示',
                content: '请选择充值金额',
                showCancel: false
            });
            return;
        }
        data.pay_type = "WECHAT_PAY";
        getApp().core.showLoading({
            title: '提交中',
        });
        getApp().request({
            url: getApp().api.recharge.submit,
            data: data,
            method: 'POST',
            success: function(res) {
                getApp().page.bindParent({
                    parent_id: getApp().core.getStorageSync(getApp().const.PARENT_ID), // TODO 从缓存中获取
                    condition: 1,//首次下单
                })
                if (res.code == 0) {
                    setTimeout(function() {
                        getApp().core.hideLoading();
                    }, 1000);
                    setOnShowScene('pay');
                    getApp().core.requestPayment({
                        _res: res,
                        timeStamp: res.data.timeStamp,
                        nonceStr: res.data.nonceStr,
                        package: res.data.package,
                        signType: res.data.signType,
                        paySign: res.data.paySign,
                        success: function(e) {},
                        fail: function(e) {},
                        complete: function(e) {
                            if (e.errMsg == "requestPayment:fail" || e.errMsg == "requestPayment:fail cancel") {
                                getApp().core.showModal({
                                    title: "提示",
                                    content: "订单尚未支付",
                                    showCancel: false,
                                    confirmText: "确认",
                                });
                                return;
                            } else {
                                getApp().page.bindParent({
                                    parent_id: getApp().core.getStorageSync(getApp().const.PARENT_ID), // TODO 从缓存中获取
                                    condition: 2,//首次付款
                                })
                                getApp().core.showModal({
                                    title: "提示",
                                    content: "充值成功",
                                    showCancel: false,
                                    confirmText: "确认",
                                    success: function(res) {
                                        if (res.confirm) {
                                            getApp().core.navigateBack({
                                                delta: 1
                                            })
                                        }
                                    }
                                });
                            }
                        },
                    });
                    return;
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false
                    });
                    getApp().core.hideLoading();
                }
            }
        });
    },

    input: function(e) {
        this.setData({
            money: e.detail.value
        });
    }
});