if (typeof wx === 'undefined') var wx = getApp().core;
// pages/add-share/index.js
var app = getApp();
var api = app.api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        form: {
            name: '',
            mobile: '',
        },
        img: "/images/img-share-un.png",
        agree: 0,
        show_modal: false
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
        var user_info = getApp().getUser();
        var share_setting = getApp().core.getStorageSync(getApp().const.SHARE_SETTING);
        getApp().getConfig(function (config) {
            let store = config.store;
            let share_name = '分销商'
            if (store && store.share_custom_data) {
                share_name = store.share_custom_data.words.share_name.name
            }
            self.setData({
                share_name: share_name
            });
            wx.setNavigationBarTitle({
                title: '申请成为' + share_name,
            })
        });
        getApp().core.showLoading({
            title: '加载中',
        });
        self.setData({
            share_setting: share_setting
        });
        getApp().request({
            url: getApp().api.share.check,
            method: 'POST',
            success: function (res) {
                if (res.code == 0) {
                    user_info.is_distributor = res.data;
                    getApp().setUser(user_info)
                    if (res.data == 1) {
                        getApp().core.redirectTo({
                            url: '/pages/share/index',
                        })
                    }
                }
                self.setData({
                    __user_info: user_info,
                });
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });

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
        getApp().page.onUnload(this)
    },
    formSubmit: function (e) {
        var self = this;
        var user_info = getApp().getUser();
        self.data.form = e.detail.value;
        if (self.data.form.name == undefined || self.data.form.name == '') {
            getApp().core.showToast({
                title: "请填写姓名！",
                image: "/images/icon-warning.png",
            });
            return;
        }
        if (self.data.form.mobile == undefined || self.data.form.mobile == '') {
            getApp().core.showToast({
                title: "请填写联系方式！",
                image: "/images/icon-warning.png",
            });
            return;
        }
        var check_mobile = /^\+?\d[\d -]{8,12}\d/;
        if (!check_mobile.test(self.data.form.mobile)) {
            getApp().core.showModal({
                title: '提示',
                content: '手机号格式不正确',
                showCancel: false
            });
            return;
        }
        var data = e.detail.value;
        data.form_id = e.detail.formId;
        if (self.data.agree == 0) {
            getApp().core.showToast({
                title: "请先阅读并确认分销申请协议！！",
                image: "/images/icon-warning.png",
            });
            return;
        }
        getApp().core.showLoading({
            title: "正在提交",
            mask: true,
        });
        getApp().request({
            url: getApp().api.share.join,
            method: 'POST',
            data: data,
            success: function (res) {
                if (res.code == 0) {
                    user_info.is_distributor = 2;
                    getApp().setUser(user_info);
                    getApp().core.redirectTo({
                        url: '/pages/add-share/index',
                    })
                } else {
                    getApp().core.showToast({
                        title: res.msg,
                        image: "/images/icon-warning.png",
                    });
                }
            }
        });
    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {

    },
    agreement: function () {
        var share_setting = getApp().core.getStorageSync(getApp().const.SHARE_SETTING);
        var self = this;
        self.setData({
            show_modal: true,
        });
    },
    agree: function () {
        var self = this;
        var agree = self.data.agree;
        if (agree == 0) {
            agree = 1;
            self.setData({
                img: "/images/img-share-agree.png",
                agree: agree
            });
        }
        else if (agree == 1) {
            agree = 0;
            self.setData({
                img: "/images/img-share-un.png",
                agree: agree
            });
        }
    },

    close: function () {
        var self = this;
        self.setData({
            show_modal: false,
            img: "/images/img-share-agree.png",
            agree: 1
        });
    }
})