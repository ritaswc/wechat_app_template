if (typeof wx === 'undefined') var wx = getApp().core;
// bargain/list/list.js
var app = getApp();
var api = getApp().api;
var is_loading = false;
var is_no_more = true;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        p: 1,
        naver: 'list'
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        app.page.onLoad(this, options);
        var self = this;
        if (typeof options.order_id !== 'undefined') {
            getApp().core.navigateTo({
                url: '/bargain/activity/activity?order_id=' + options.order_id + '&user_id=' + options.user_id,
            });
        }
        if (typeof options.goods_id !== 'undefined') {
            getApp().core.navigateTo({
                url: '/bargain/goods/goods?goods_id=' + options.goods_id + '&user_id=' + options.user_id,
            });
        }
        self.loadDataFirst(options);
    },

    loadDataFirst:function(options){
        var self = this;
        getApp().core.showLoading({
            title: '加载中',
        });
        getApp().request({
            url: getApp().api.bargain.index,
            type: 'get',
            success: function (res) {
                if (res.code == 0) {
                    self.setData(res.data);
                    self.setData({
                        p: 2
                    });
                    if (res.data.goods_list.length > 0) {
                        is_no_more = false;
                    }
                }
            },
            complete: function (res) {
                if (typeof options.order_id === 'undefined') {
                    getApp().core.hideLoading();
                }
                getApp().core.stopPullDownRefresh();
            }
        });
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
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {
        this.loadDataFirst({});
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
        var p = self.data.p;
        app.request({
            url: api.bargain.index,
            data: {
                page: p
            },
            success: function(res) {
                if (res.code == 0) {
                    var goods_list = self.data.goods_list;
                    if (res.data.goods_list.length == 0) {
                        is_no_more = true;
                    }
                    goods_list = goods_list.concat(res.data.goods_list);
                    self.setData({
                        goods_list: goods_list,
                        p: p + 1
                    });
                } else {
                    self.showToast({
                        title: res.msg,
                    });
                }
            },
            complete: function (res) {
                getApp().core.hideLoading();
                is_loading = false;
            }
        });

    },

    // 跳转到商品详情
    goToGoods: function(e) {
        var goods_id = e.currentTarget.dataset.index;
        getApp().core.navigateTo({
            url: '/bargain/goods/goods?goods_id=' + goods_id,
        })
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {
        getApp().page.onShareAppMessage(this);
        var self = this;
        var res = {
            path: "/bargain/list/list?user_id=" + self.data.__user_info.id,
            success: function (e) { },
        };
        return res;
    },
})