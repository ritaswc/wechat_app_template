if (typeof wx === 'undefined') var wx = getApp().core;
var app = getApp();
var api = getApp().api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        cash_val: '',
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        var cache_key = 'mch_account_data';
        var data = getApp().core.getStorageSync(cache_key);
        if (data) {
            self.setData(data);
        }
        getApp().core.showNavigationBarLoading();
        getApp().request({
            url: getApp().api.mch.user.account,
            success: function (res) {
                getApp().core.hideNavigationBarLoading();
                if (res.code == 0) {
                    self.setData(res.data);
                    getApp().core.setStorageSync(cache_key, res.data);
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        success: function () {

                        },
                    });
                }
            },
            complete: function () {
                getApp().core.hideNavigationBarLoading();
            },
        });
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {
        getApp().page.onReady(this);
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        getApp().page.onShow(this);
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function () {
        getApp().page.onHide(this);
    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function () {
        getApp().page.onUnload(this);
    },

    showDesc: function () {
        var self = this;
        getApp().core.showModal({
            title: '交易手续费说明',
            content: self.data.desc,
            showCancel: false,
        });
    },

    showCash: function () {
        getApp().core.navigateTo({
            url: '/mch/m/cash/cash',
        });
        return;
        var self = this;
        if (self.data.account_money < 1) {
            self.showToast({
                title: '账户余额低于1元，无法提现。',
            });
            return;
        }
        self.setData({
            show_cash: true,
            cash_val: '',
        });
    },

    hideCash: function () {
        var self = this;
        self.setData({
            show_cash: false,
        });
    },

    cashInput: function (e) {
        var self = this;
        var val = e.detail.value;
        val = parseFloat(val);
        if (isNaN(val))
            val = 0;
        val = val.toFixed(2);
        self.setData({
            cash_val: val ? val : '',
        });
    },

    cashSubmit: function (e) {
        var self = this;
        if (!self.data.cash_val) {
            self.showToast({
                title: '请输入提现金额。',
            });
            return;
        }
        if (self.data.cash_val <= 0) {
            self.showToast({
                title: '请输入提现金额。',
            });
            return;
        }
        getApp().core.showLoading({
            title: '正在提交',
            mask: true,
        });
        getApp().request({
            url: getApp().api.mch.user.cash,
            method: 'POST',
            data: {
                cash_val: self.data.cash_val,
            },
            success: function (res) {
                getApp().core.showModal({
                    title: '提示',
                    content: res.msg,
                    showCancel: false,
                    success: function () {
                        if (res.code == 0) {
                            getApp().core.redirectTo({
                                url: '/mch/m/account/account',
                            });
                        }
                    },
                });
            },
            complete: function (res) {
                getApp().core.hideLoading();
            },
        });
    },

});