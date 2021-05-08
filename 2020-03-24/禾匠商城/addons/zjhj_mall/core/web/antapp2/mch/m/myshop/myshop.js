if (typeof wx === 'undefined') var wx = getApp().core;
var app = getApp();
var api = getApp().api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        is_show: false
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        getApp().core.showLoading({
            title: '加载中',
        });

        getApp().request({
            url: getApp().api.mch.user.myshop,
            success: function (res) {
                getApp().core.hideLoading();
                if (res.code == 0) {
                    if (res.data.mch.is_open === 0) {
                        getApp().core.showModal({
                            title: '提示',
                            content: '店铺已被关闭！请联系管理员',
                            showCancel: false,
                            success: function (e) {
                                if (e.confirm) {
                                    getApp().core.navigateBack();
                                }
                            }
                        });
                    }
                    self.setData(res.data);
                    self.setData({
                        is_show: true
                    })
                }
                //未申请入驻
                if (res.code == 1) {
                    getApp().core.redirectTo({
                        url: '/mch/apply/apply',
                    });
                }
            }
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

    navigatorSubmit: function (e) {
        getApp().request({
            url: getApp().api.user.save_form_id + "&form_id=" + e.detail.formId,
        });
        getApp().core.navigateTo({
            url: e.detail.value.url,
        });
    },

    showPcUrl: function () {
        var self = this;
        self.setData({
            show_pc_url: true,
        });
    },

    hidePcUrl: function () {
        var self = this;
        self.setData({
            show_pc_url: false,
        });
    },

    copyPcUrl: function () {
        var self = this;
        getApp().core.setClipboardData({
            data: self.data.pc_url,
            success: function (res) {
                self.showToast({
                    title: '内容已复制',
                });
            },
        });
    },

});