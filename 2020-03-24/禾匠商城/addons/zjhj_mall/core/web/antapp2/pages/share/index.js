if (typeof wx === 'undefined') var wx = getApp().core;
// pages/share/index.js
var app = getApp();
var api = app.api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        total_price: 0,
        price: 0,
        cash_price: 0,
        total_cash: 0,
        team_count: 0,
        order_money: 0
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        self.setData({
            custom: getApp().core.getStorageSync(getApp().const.CUSTOM)
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
        var self = this;
        var share_setting = getApp().core.getStorageSync(getApp().const.SHARE_SETTING);
        var __user_info = self.data.__user_info;
        self.setData({
            share_setting: share_setting,
            custom: self.data.store.share_custom_data
        });
        if (!__user_info || __user_info.is_distributor != 1) {
            self.loadData();
        } else {
            self.checkUser();
        }
    },

    checkUser:function(){
        var self = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.share.get_info,
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        total_price: res.data.price.total_price,
                        price: res.data.price.price,
                        cash_price: res.data.price.cash_price,
                        total_cash: res.data.price.total_cash,
                        team_count: res.data.team_count,
                        order_money: res.data.order_money,
                        custom: res.data.custom,
                        order_money_un: res.data.order_money_un
                    });
                    getApp().core.setStorageSync(getApp().const.CUSTOM, res.data.custom);
                    self.loadData();
                }
                if (res.code == 1) {
                    __user_info.is_distributor = res.data.is_distributor;
                    self.setData({
                        __user_info: __user_info
                    });
                    getApp().setUser(__user_info);
                }
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });

    },

    loadData:function(){
        var self = this;
        getApp().core.showLoading({
            title: '正在加载',
            mask: true,
        });
        getApp().request({
            url: getApp().api.share.index,
            success: function (res) {
                if (res.code == 0) {
                    if (res.data.share_setting) {
                        var share_setting = res.data.share_setting;
                    } else {
                        var share_setting = res.data;
                    }
                    getApp().core.setStorageSync(getApp().const.SHARE_SETTING, share_setting);
                    self.setData({
                        share_setting: share_setting
                    });
                }
            },
            complete: function () {
                getApp().core.hideLoading();
            },
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

    apply: function (e) {
        var self = this;
        var share_setting = getApp().core.getStorageSync(getApp().const.SHARE_SETTING);
        var user_info = getApp().getUser();
        if (share_setting.share_condition == 1) {
            getApp().core.navigateTo({
                url: '/pages/add-share/index',
            })
        } else if (share_setting.share_condition == 0 || share_setting.share_condition == 2) {
            if (user_info.is_distributor == 0) {
                getApp().core.showModal({
                    title: "申请成为" + (self.data.custom.words.share_name.name || '分销商'),
                    content: "是否申请？",
                    success: function (r) {
                        if (r.confirm) {
                            getApp().core.showLoading({
                                title: "正在加载",
                                mask: true,
                            });
                            getApp().request({
                                url: getApp().api.share.join,
                                method: "POST",
                                data: {
                                    form_id: e.detail.formId
                                },
                                success: function (res) {
                                    if (res.code == 0) {
                                        if (share_setting.share_condition == 0) {
                                            user_info.is_distributor = 2;
                                            getApp().core.navigateTo({
                                                url: '/pages/add-share/index',
                                            })
                                        } else {
                                            user_info.is_distributor = 1;
                                            getApp().core.redirectTo({
                                                url: '/pages/share/index',
                                            })
                                        }
                                        getApp().setUser(user_info);
                                    }
                                },
                                complete: function () {
                                    getApp().core.hideLoading();
                                }
                            });
                        }
                    },
                })
            } else {
                getApp().core.navigateTo({
                    url: '/pages/add-share/index',
                })
            }
        }
    },

})