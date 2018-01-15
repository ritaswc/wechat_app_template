var base = getApp();
Page({
    data: {
        tab: 1,
        btnStatus: true,//倒计时已结束
        sec: 0,
        phone: "",
        phoneOk: false,
        code: "",
        codeOk: false,
        pwd: ""

    },
    key: "",
    onLoad: function () {
        var _this = this;
        // wx.request({
        //     url: base.path.www + 'control/messageCodeNew.ashx?v=' + Math.random(),
        //     success: function (res) {
        //         _this.key = res.data;
        //     },
        // })
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
    checkCode: function (e) {
        var v = e.detail.value;
        if (v && v.length > 2) {
            this.setData({
                code: v,
                codeOk: true
            });
        } else {
            this.setData({
                code: "",
                codeOk: false
            });
        }
    },
    sendCode: function () {
        var _this = this;
        // if (this.key) {
        if (this.data.phoneOk) {
            this.setData({
                sec: 90,
                btnStatus: false
            });
            var tm = setInterval(function () {
                if (_this.data.sec > 0) {
                    _this.setData({ sec: _this.data.sec - 1 });
                    if (_this.data.sec == 0) {
                        _this.setData({ btnStatus: true });
                        clearInterval(tm);
                    }
                }
            }, 1000);
            base.get({ m: "SendPhoneCode", c: "User", phone: this.data.phone, ImageCode: this.key }, function (res) {
                var data = res.data;
                if (data.Status == "ok") {
                    base.toast({ tilte: "已发送", icon: "success", duration: 2000 });
                }
            })
        }
        //  }
    },
    changeTab: function (e) {
        var d = e.currentTarget.dataset.index;
        this.setData({ tab: d });
    },
    changepwd: function (e) {
        this.setData({
            pwd: e.detail.value
        });
    },
    login: function () {
        //   if (this.key) {
        var flag = true;
        var err = "";
        if (this.data.phoneOk) {
            if (this.data.tab == 1) {
                if (!this.data.pwd) {
                    flag = false;
                    err = "请输入密码！";
                }
            }
            else {
                if (!this.data.code) {
                    flag = false;
                    err = "请输入手机验证码";
                }

            }
            if (flag) {
                base.post({ c: "User", m: "Login", phone: this.data.phone, pwd: this.data.pwd, code: this.data.code, types: this.data.tab }, function (res) {
                    var dt = res.data;
                    if (dt.Status == "ok") {
                        base.user.userid = dt.Tag.Uid;
                        base.user.sessionid = dt.Tag.SessionId;
                        base.user.jzb = dt.Tag.Money;
                        base.user.exp = dt.Tag.Exp;
                        base.user.phone = dt.Tag.Phone;
                        base.user.levels = dt.Tag.Levels;
                        base.user.headimg = dt.Tag.HeadImgPath;
                        var objuser = {};
                        objuser.userid = dt.Tag.Uid;
                        objuser.sessionid = dt.Tag.SessionId;
                        objuser.jzb = dt.Tag.Money;
                        objuser.exp = dt.Tag.Exp;
                        objuser.phone = dt.Tag.Phone;
                        objuser.levels = dt.Tag.Levels;
                        objuser.headimg = dt.Tag.HeadImgPath;
                        base.user.setCache(objuser);
                        wx.switchTab({
                            url: '../user/user'
                        })
                    } else {
                        base.modal({ title: dt.Msg })
                    }
                })
            } else {
                base.modal({ title: err })
            }
        }
    }

});