if (typeof wx === 'undefined') var wx = getApp().core;
// bargain/order_list/order_list.js
var app = getApp();
var api = getApp().api;
var is_loading = false;
var is_no_more = true;
var intval = null;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        naver: 'order',
        status: -1,
        intval: [],
        p: 1
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        var self = this;
        if (options.status == undefined) {
            options.status = -1;
        }
        self.setData(options);
        self.getList();
    },

    getList: function() {
        var self = this;
        getApp().core.showLoading({
            title: '加载中',
        });
        getApp().request({
            url: getApp().api.bargain.order_list,
            data: {
                status: self.data.status || -1
            },
            success: function(res) {
                if (res.code == 0) {
                    self.setData(res.data);
                    self.setData({
                        p: 1
                    });
                    self.getTimeList();
                } else {
                    self.showLoading({
                        title: res.msg
                    });
                }
            },
            complete: function(res) {
                getApp().core.hideLoading();
                is_no_more = false;
            }
        });
    },

    // 批量设置定时
    getTimeList: function() {
        clearInterval(intval);
        var self = this;
        var list = self.data.list;
        intval = setInterval(function() {
            for (var i in list) {
                if (list[i].reset_time > 0) {
                    var reset_time = list[i].reset_time - 1;
                    var time_list = self.setTimeList(reset_time);
                    list[i].reset_time = reset_time;
                    list[i].time_list = time_list;
                }
            }
            self.setData({
                list: list
            });
        }, 1000);
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function() {
        getApp().page.onReady(this);
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function() {
        getApp().page.onShow(this);
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function() {
        getApp().page.onHide(this);
    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function() {
        getApp().page.onUnload(this);
    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function() {
        var self = this;
        if (is_no_more) {
            return;
        }
        self.loadData();
    },

    loadData: function() {
        var self = this;
        if (is_loading) {
            return;
        }
        is_loading = true;
        getApp().core.showLoading({
            title: '加载中',
        });
        var p = self.data.p + 1;
        getApp().request({
            url: getApp().api.bargain.order_list,
            data: {
                status: self.data.status,
                page: p
            },
            success: function(res) {
                if (res.code == 0) {
                    var list = self.data.list.concat(res.data.list);
                    self.setData({
                        list: list,
                        p: p
                    });
                    if (res.data.list.length == 0) {
                        is_no_more = true;
                    }
                    self.getTimeList();
                } else {
                    self.showLoading({
                        title: res.msg
                    });
                }
            },
            complete: function(res) {
                getApp().core.hideLoading();
                is_loading = true;
            }
        });
    },
    submit: function (e) {
        var self = this;
        var mch_list = [];
        var goods_list = [];
        goods_list.push({
            bargain_order_id: e.currentTarget.dataset.index
        });
        mch_list.push({
            mch_id: 0,
            goods_list: goods_list
        });
        getApp().core.navigateTo({
            url: "/pages/new-order-submit/new-order-submit?mch_list=" + JSON.stringify(mch_list),
        })
    }
})