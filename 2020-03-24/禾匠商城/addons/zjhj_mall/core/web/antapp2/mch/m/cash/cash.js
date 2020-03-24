if (typeof wx === 'undefined') var wx = getApp().core;
// mch/m/cash/cash.js
var app = getApp();
var api = getApp().api;

Page({

    /**
     * 页面的初始数据
     */
    data: {
        price: 0.00,
        cash_max_day: -1,
        selected: 0
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
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
        var self = this;
        getApp().core.showLoading({
            title: "正在提交",
            mask: true,
        });
        getApp().request({
            url: getApp().api.mch.user.cash_preview,
            success: function (res) {
                if (res.code == 0) {
                    var data = {};
                    data.price = res.data.money;
                    data.type_list = res.data.type_list
                    self.setData(data);
                }
            },
            complete:function(res){
                getApp().core.hideLoading();
            }
        })
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function () {

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function () {

    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {

    },

    showCashMaxDetail: function () {
        getApp().core.showModal({
            title: "提示",
            content: "今日剩余提现金额=平台每日可提现金额-今日所有用户提现金额"
        });
    },
    select: function (e) {
        var index = e.currentTarget.dataset.index;
        var check = this.data.check;
        if (index != check) {
            this.setData({
                name: '',
                mobile: '',
                bank_name: '',
            });
        }
        this.setData({
            selected: index
        });
    },
    formSubmit: function (e) {
        var self = this;
        var cash = parseFloat(parseFloat(e.detail.value.cash).toFixed(2));
        var cash_max = self.data.price;
        if (!cash) {
            getApp().core.showToast({
                title: "请输入提现金额",
                image: "/images/icon-warning.png",
            });
            return;
        }
        if (cash > cash_max) {
            getApp().core.showToast({
                title: "提现金额不能超过" + cash_max + "元",
                image: "/images/icon-warning.png",
            });
            return;
        }
        if (cash < 1){
            getApp().core.showToast({
                title: "提现金额不能低于1元",
                image: "/images/icon-warning.png",
            });
            return;
        }
        var selected = self.data.selected;
        if (selected != 0 && selected != 1 && selected != 2 && selected != 3 && selected != 4) {
            getApp().core.showToast({
                title: '请选择提现方式',
                image: "/images/icon-warning.png",
            });
            return;
        }
        if(self.data.__platform=='my' && selected==0){
            selected = 2;
        }

        if (selected == 1 || selected == 2 || selected == 3) {
            var name = e.detail.value.name;
            if (!name || name == undefined) {
                getApp().core.showToast({
                    title: '姓名不能为空',
                    image: "/images/icon-warning.png",
                });
                return;
            }
            var mobile = e.detail.value.mobile;
            if (!mobile || mobile == undefined) {
                getApp().core.showToast({
                    title: '账号不能为空',
                    image: "/images/icon-warning.png",
                });
                return;
            }
        }
        if (selected == 3) {
            var bank_name = e.detail.value.bank_name;
            if (!bank_name || bank_name == undefined) {
                getApp().core.showToast({
                    title: '开户行不能为空',
                    image: "/images/icon-warning.png",
                });
                return;
            }
        } else {
            var bank_name = '';
        }
        if (selected == 4 || selected == 0) {
            var bank_name = '';
            var mobile = '';
            var name = '';
        }
        getApp().core.showLoading({
            title: "正在提交",
            mask: true,
        });
        getApp().request({
            url: getApp().api.mch.user.cash,
            method: 'POST',
            data: {
                cash_val: cash,
                nickname: name,
                account: mobile,
                bank_name: bank_name,
                type: selected,
                scene: 'CASH',
                form_id: e.detail.formId,
            },
            success: function (res) {
                getApp().core.hideLoading();
                getApp().core.showModal({
                    title: "提示",
                    content: res.msg,
                    showCancel: false,
                    success: function (e) {
                        if (e.confirm) {
                            if (res.code == 0) {
                                getApp().core.redirectTo({
                                    url: '/mch/m/cash-log/cash-log',
                                })
                            }
                        }
                    }
                });
            }
        });
    }
    ,
})