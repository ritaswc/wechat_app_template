if (typeof wx === 'undefined') var wx = getApp().core;
var app = getApp();
var api = getApp().api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        list: [],
        current_page: 0,
        loading: false,
        no_more: false,
    },

    getList: function () {
        var self = this;
        if (self.data.loading) {
            return;
        }
        if (self.data.no_more) {
            return;
        }
        self.setData({
            loading: true,
        });
        var target_page = self.data.current_page + 1;
        getApp().request({
            url: getApp().api.mch.user.account_log,
            data: {
                page: target_page,
                year: '',
                month: '',
            },
            success: function (res) {
                if (res.code == 0) {
                    if (!res.data.list || !res.data.list.length) {
                        self.setData({
                            no_more: true,
                        });
                    } else {
                        self.data.list = self.data.list.concat(res.data.list);
                        self.setData({
                            list: self.data.list,
                            current_page: target_page,
                        });
                    }
                }

                if (res.code == 1) {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                    });
                }
            },
            complete: function (res) {
                self.setData({
                    loading: false,
                });
            },
        });
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        this.getList();
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

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {
        this.getList();
    },
});