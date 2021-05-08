var base = getApp();
Page({
    data: {
        pay: 1,
        brand: 0,
        pro: "",
        phone: "",
        phoneOk: false
    },
    onLoad: function (e) {
        var obj = {
            price: 0.01 || e.price,
            brand: e.type
        }
        if (e.pay && e.pay == "free") {
            wx.setNavigationBarTitle({ title: "领取奖励" });
            obj.pay = 0;
            obj.title = "获赠商品";
            obj.btn = "领取"
        } else {
            obj.pay = 1;
            obj.title = "购买商品";
            obj.btn = "确认并支付"
        }
        var s = "";
        if (e.type == 0) {
            s += "经典系列"
        } else if (e.type == 1) {
            s += "吉致系列"
        }
        obj.pro = s + obj.price + "元优惠券"
        this.setData(obj);
    },
    checkPhone: function (e) {
        var v = e.detail.value;
        if (v && v.length == 11) {
            this.setData({
                phone: v,
                phoneOk: true
            });
        } else {
            this.setData({
                phone: "",
                phoneOk: false
            });
        }
    },
    pay: function () {
        var _this = this;
        if (this.data.phoneOk) {
            base.loading.show({title:"验证信息..."});
            wx.login({
                success: function (res) {
                    console.log(res);
                    if (res.code) {
                        if (_this.data.pay == 1) {
                            base.get({ c: "ActApiCenter", m: "Apply", brand: _this.data.brand == 0 ? "ksk" : "jzcake", price: _this.data.price, phone: _this.data.phone, code: res.code }, function (res) {
                                var d = res.data;
                                if (d.Status == "ok") {
                                    wx.requestPayment({
                                        'timeStamp': d.Tag.timeStamp,
                                        'nonceStr': d.Tag.nonceStr,
                                        'package': d.Tag.package,
                                        'signType': d.Tag.signType,
                                        'paySign': d.Tag.sign,
                                        'success': function (res) {

                                            //if (res.requestPayment == "ok") {
                                            base.modal({
                                                title: '支付成功！',
                                                content: "优惠券密码已下发到手机号" + _this.data.phone + "，请查收!",
                                                showCancel: false,
                                                confirmText: "确认",
                                                success: function (res) {
                                                    if (res.confirm) {
                                                        wx.switchTab({
                                                            url: "../cake/cake"
                                                        })
                                                    }
                                                }
                                            })
                                            //}
                                        },
                                        'fail': function (res) {
                                            
                                        }
                                    })
                                } else {
                                    base.modal({
                                        title: '提交失败',
                                        content: d.Msg || ""
                                    });
                                }
                            },"提交中...");
                        } else {

                        }
                    } else {
                        base.modal({
                            title: '获取信息失败！'
                        });
                    }
                },
                fail: function () {
                    base.modal({
                        title: '获取信息失败！'
                    });
                }
            });



        }
    }
});