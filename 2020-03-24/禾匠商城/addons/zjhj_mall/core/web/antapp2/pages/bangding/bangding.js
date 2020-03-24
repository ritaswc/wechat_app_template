if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        second: 60,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);

        var self = this;
        getApp().request({
            url: getApp().api.user.sms_setting,
            method: 'get',
            data: {
                page: 1,
            },
            success: function (res) {
                self.setData({
                    status: res.code == 0,
                });
            },
        });
    },
    gainPhone: function () {
        this.setData({
            gainPhone: true,
            handPhone: false,
        });
    },
    handPhone: function () {
        this.setData({
            gainPhone: false,
            handPhone: true,
        });
    },

    nextStep: function () {
        var self = this;
        var phone = this.data.handphone;
        if (!phone || phone.length != 11) {
            getApp().core.showToast({
                title: '手机号码错误',
            });
            return
        }

        getApp().request({
            url: getApp().api.user.user_hand_binding,
            method: 'POST',
            data: {
                content: phone,
            },
            success: function (res) {
                if (res.code == 0) {
                    self.timer()
                    self.setData({
                        content: res.msg,
                        timer: true,
                    })
                } else if (res.code == 2) {
                    getApp().core.showToast({
                        title: res.msg,
                    });
                } else {
                    getApp().core.showToast({
                        title: res.msg,
                    });
                }
            },
        });
    },
    timer: function () {
        let promise = new Promise((resolve, reject) => {
            let setTimer = setInterval(
                () => {
                    this.setData({
                        second: this.data.second - 1
                    })
                    if (this.data.second <= 0) {
                        this.setData({
                            timer: false,
                        })
                        resolve(setTimer)
                    }
                }, 1000)
        })
        promise.then((setTimer) => {
            clearInterval(setTimer)
        })
    },

    HandPhoneInput: function (e) {
        this.setData({
            handphone: e.detail.value
        })
    },
    CodeInput: function (e) {
        this.setData({
            code: e.detail.value
        })
    },
    PhoneInput: function (e) {
        this.setData({
            phoneNum: e.detail.value
        })
    },

    onSubmit: function () {
        var self = this;
        var gainPhone = self.data.gainPhone;
        var handPhone = self.data.handPhone;
        var bind_type = gainPhone ? 1 : (handPhone ? 2 : 0);
        if (gainPhone) {
            var phoneNum = self.data.phoneNum;
            if (phoneNum) {
                if (phoneNum.length != 11) {
                    getApp().core.showToast({
                        title: '手机号码错误',
                    });
                    return
                }
                var phone = phoneNum;
            } else {
                var phone = self.data.PhoneNumber;
                if (!phone) {
                    getApp().core.showToast({
                        title: '手机号码错误',
                    });
                    return
                }
            }
        } else {
            var phone = self.data.handphone;
            var preg = /^\+?\d[\d -]{8,12}\d/;
            if (!preg.test(phone)) {
                getApp().core.showToast({
                    title: '手机号码错误',
                });
                return
            }
            var code = self.data.code;
            if (!code) {
                getApp().core.showToast({
                    title: '请输入验证码',
                });
                return
            }
        }

        getApp().request({
            url: getApp().api.user.user_empower,
            method: 'POST',
            data: {
                phone: phone,
                phone_code: code,
                bind_type: bind_type
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        binding: true,
                        binding_num: phone
                    })
                } else if (res.code == 1) {
                    getApp().core.showToast({
                        title: res.msg,
                    });
                }
            },
        })
    },

    renewal: function () {
        this.setData({
            binding: false,
            gainPhone: true,
            handPhone: false,
        });
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        getApp().page.onShow(this);

        var self = this;
        var user_info = self.data.__user_info;
        if (user_info && user_info.binding) {
            self.setData({
                binding_num: user_info.binding,
                binding: true
            });
        } else {
            self.setData({
                gainPhone: true,
                handPhone: false,
            });
        }
    },
})