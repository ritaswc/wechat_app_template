if (typeof wx === 'undefined') var wx = getApp().core;
// pages/share-team/share-team.js
var app = getApp();
var api = app.api;
var is_no_more = false;
var is_loading = false;
var p = 2;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        status: 1,
        first_count: 0,
        second_count: 0,
        third_count: 0,
        list: Array,
        no_more: false
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        var share_setting = getApp().core.getStorageSync(getApp().const.SHARE_SETTING);
        self.setData({
            share_setting: share_setting,
        });
        is_loading = false;
        is_no_more = false;
        p = 2;
        self.GetList(options.status || 1);
    },
    GetList: function (status) {
        var self = this;
        if (is_loading) {
            return;
        }
        is_loading = true;
        self.setData({
            status: parseInt(status || 1),
        });
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.share.get_team,
            data: {
                status: self.data.status,
                page: 1
            },
            success: function (res) {
                self.setData({
                    first_count: res.data.first,
                    second_count: res.data.second,
                    third_count: res.data.third,
                    list: res.data.list,
                });
                if (res.data.list.length == 0) {
                    is_no_more = true;
                    self.setData({
                        no_more: true
                    });
                }
            },
            complete: function () {
                getApp().core.hideLoading();
                is_loading = false;
            }
        });
    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {
        if (is_no_more) {
            return;
        }
        this.loadData();
    },

    loadData: function () {
        if (is_loading) {
            return;
        }
        is_loading = true;
        var self = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.share.get_team,
            data: {
                status: self.data.status,
                page: p
            },
            success: function (res) {
                self.setData({
                    first_count: res.data.first,
                    second_count: res.data.second,
                    third_count: res.data.third,
                    list: self.data.list.concat(res.data.list),
                });
                if (res.data.list.length == 0) {
                    is_no_more = true;
                    self.setData({
                        no_more: true
                    });
                }
            },
            complete: function () {
                getApp().core.hideLoading();
                is_loading = false;
                p++;
            }
        });
    }
})