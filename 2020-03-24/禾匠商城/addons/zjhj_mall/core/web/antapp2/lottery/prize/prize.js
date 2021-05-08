if (typeof wx === 'undefined') var wx = getApp().core;
var is_loading = false;
var is_no_more = true;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        naver: 'prize',
        list: [],
        page: 1,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) { 
        getApp().page.onLoad(this, options);
        this.setData({
            status: options.status || 0,
        });

        var self = this;
        getApp().core.showLoading({
            title: "加载中",
        });
        getApp().request({
            url: getApp().api.lottery.prize,
            data: {
                status: self.data.status,
                page: self.data.page,
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        list: res.data.list,
                    });
                    if (res.data.list != null && res.data.list.length > 0) {
                        is_no_more = false;
                    }
                }
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });

    },


    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function() {
        if (is_no_more) {
            return;
        }
        this.loadData();
    },

    // 上拉加载数据
    loadData: function() {
        if (is_loading) {
            return;
        }
        is_loading = true;
        getApp().core.showLoading({
            title: '加载中',
        });
        var self = this;
        var page = self.data.page + 1;
        getApp().request({
            url: getApp().api.lottery.prize,
            data: {
                status: self.data.status,
                page: page,
            },
            success: function (res) {
                if (res.code == 0) {
                    if (res.data.list == null || res.data.list.length == 0) {
                        is_no_more = true;
                        return;
                    }
                    self.setData({
                        list: self.data.list.concat(res.data.list),
                        page: page
                    });
                } else {
                    self.showToast({
                        title: res.msg,
                    });
                }
            },
            complete: function () {
                getApp().core.hideLoading();
                is_loading = false;
            }
        });
    },
});