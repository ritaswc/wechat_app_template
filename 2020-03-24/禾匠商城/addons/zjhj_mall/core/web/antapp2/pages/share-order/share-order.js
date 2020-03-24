if (typeof wx === 'undefined') var wx = getApp().core;
// pages/share-order/share-order.js
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
        status: -1,
        list: [],
        hidden: -1,
        is_no_more: false,
        is_loading: false
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        is_no_more = false;
        is_loading = false;
        p = 2;
        self.GetList(options.status || -1);
    },

    GetList: function (status) {
        var self = this;
        self.setData({
            status: parseInt(status || -1),
        });
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.share.get_order,
            data: {
                status: self.data.status
            },
            success: function (res) {
                self.setData({
                    list: res.data
                });
                if (res.data.length == 0) {
                    self.setData({
                        is_no_more: true
                    });
                }
            },
            complete: function () {
                getApp().core.hideLoading();
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

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {

    },
    click: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        self.setData({
            hidden: self.data.hidden == index ? -1 : index
        });
    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {

        var self = this;
        if (is_loading || is_no_more)
            return;
        is_loading = true;
        self.setData({
            is_loading: is_loading
        });
        getApp().request({
            url: getApp().api.share.get_order,
            data: {
                status: self.data.status,
                page: p,
            },
            success: function (res) {
                if (res.code == 0) {

                    var list = self.data.list.concat(res.data);
                    self.setData({
                        list: list,
                    });
                    if (res.data.length == 0) {
                        is_no_more = true;
                        self.setData({
                            is_no_more: is_no_more
                        });
                    }
                }
                p++;
            },
            complete: function () {
                is_loading = false;
                self.setData({
                    is_loading: is_loading
                });
            }
        });

    }
})