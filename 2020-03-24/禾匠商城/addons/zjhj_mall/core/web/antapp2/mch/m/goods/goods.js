if (typeof wx === 'undefined') var wx = getApp().core;
var app = getApp();
var api = getApp().api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        status: 1,
        goods_list: [],
        no_goods: false,
        no_more_goods: false,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        self.setData({
            status: parseInt(options.status || 1),
            loading_more: true,
        });
        self.loadGoodsList(function () {
            self.setData({
                loading_more: false,
            });
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

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function (e) {
        var self = this;
        if (self.data.loading_more) {
            return;
        }
        self.setData({
            loading_more: true,
        });
        self.loadGoodsList(function () {
            self.setData({
                loading_more: false,
            });
        });
    },

    searchSubmit: function (e) {
        var self = this;
        var keyword = e.detail.value;
        self.setData({
            keyword: keyword,
            loading_more: true,
            goods_list: [],
            current_page: 0,
        });
        self.loadGoodsList(function () {
            self.setData({
                loading_more: false,
            });
        });

    },

    loadGoodsList: function (callback) {
        var self = this;
        if (self.data.no_goods || self.data.no_more_goods) {
            if (typeof callback == 'function')
                callback();
            return;
        }
        var current_page = self.data.current_page || 0;
        var target_page = current_page + 1;
        getApp().request({
            url: getApp().api.mch.goods.list,
            data: {
                page: target_page,
                status: self.data.status,
                keyword: self.data.keyword || '',
            },
            success: function (res) {
                if (res.code == 0) {
                    if (target_page == 1 && (!res.data.list || !res.data.list.length)) {
                        self.setData({
                            no_goods: true,
                        });
                    }
                    if (!res.data.list || !res.data.list.length) {
                        self.setData({
                            no_more_goods: true,
                        });
                    } else {
                        self.data.goods_list = self.data.goods_list || [];
                        self.data.goods_list = self.data.goods_list.concat(res.data.list);
                        self.setData({
                            goods_list: self.data.goods_list,
                            current_page: target_page,
                        });
                    }
                }
            },
            complete: function () {
                if (typeof callback == 'function')
                    callback();
            },
        });

    },

    showMoreAlert: function (e) {
        var self = this;
        setTimeout(function () {
            var index = e.currentTarget.dataset.index;
            self.data.goods_list[index].show_alert = true;
            self.setData({
                goods_list: self.data.goods_list,
            });
        }, 50);
    },

    hideMoreAlert: function (e) {
        var self = this;
        var has_show = false;
        for (var i in self.data.goods_list) {
            if (self.data.goods_list[i].show_alert) {
                self.data.goods_list[i].show_alert = false;
                has_show = true;
            }
        }
        if (has_show) {
            setTimeout(function () {
                self.setData({
                    goods_list: self.data.goods_list,
                });
            }, 100);
        }
    },

    setGoodsStatus: function (e) {
        var self = this;
        var status = e.currentTarget.dataset.targetStatus;
        var index = e.currentTarget.dataset.index;
        getApp().core.showLoading({
            title: '正在处理',
            mask: true,
        });
        getApp().request({
            url: getApp().api.mch.goods.set_status,
            data: {
                id: self.data.goods_list[index].id,
                status: status,
            },
            success: function (res) {
                if (res.code == 0) {
                    self.data.goods_list[index].status = res.data.status;
                    self.setData({goods_list: self.data.goods_list});
                }
                self.showToast({
                    title: res.msg,
                    duration: 1500,
                });
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },

    goodsDelete: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        getApp().core.showModal({
            title: '警告',
            content: '确认删除该商品？',
            success: function (e) {
                if (e.confirm) {
                    getApp().core.showLoading({
                        title: '正在处理',
                        mask: true,
                    });
                    getApp().request({
                        url: getApp().api.mch.goods.delete,
                        data: {
                            id: self.data.goods_list[index].id,
                        },
                        success: function (res) {
                            self.showToast({
                                title: res.msg,
                            });
                            if (res.code == 0) {
                                self.data.goods_list.splice(index, 1);
                                self.setData({
                                    goods_list: self.data.goods_list,
                                });
                            }
                        },
                        complete: function () {
                            getApp().core.hideLoading();
                        }
                    });
                }
            }
        });
    },

});