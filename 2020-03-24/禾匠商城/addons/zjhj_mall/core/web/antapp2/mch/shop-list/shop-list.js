if (typeof wx === 'undefined') var wx = getApp().core;
var app = getApp();
var api = getApp().api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        cat_id: '',
        keyword: '',
        list: [],
        page: 1,
        no_more: false,
        loading: false,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        if (options.cat_id) {
            this.data.cat_id = options.cat_id;
        }
        this.loadShopList();
    },

    loadShopList: function (cb) {
        var self = this;
        if (self.data.no_more) {
            if (typeof cb === 'function')
                cb();
            return;
        }
        if (self.data.loading) {
            return;
        }
        self.setData({
            loading: true,
        });
        getApp().request({
            url: getApp().api.mch.shop_list,
            data: {
                keyword: self.data.keyword,
                cat_id: self.data.cat_id,
                page: self.data.page,
            },
            success: function (res) {
                if (res.code == 0) {
                    if (!res.data.list || !res.data.list.length) {
                        self.setData({
                            no_more: true,
                            cat_list: res.data.cat_list,
                        });
                        return;
                    }
                    if (!self.data.list)
                        self.data.list = [];
                    self.data.list = self.data.list.concat(res.data.list);
                    self.setData({
                        list: self.data.list,
                        page: self.data.page + 1,
                        cat_list: res.data.cat_list,
                    });
                }
            },
            complete: function () {
                self.setData({
                    loading: false,
                });
                if (typeof cb === 'function')
                    cb();
            },
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
    onReachBottom: function () {
        var self = this;
        self.loadShopList();
    },

    searchSubmit: function (e) {
        var self = this;
        var keyword = e.detail.value;
        self.setData({
            list: [],
            keyword: keyword,
            page: 1,
            no_more: false,
        });
        self.loadShopList(function () {

        });
    },

    showCatList: function () {
        var self = this;
        self.setData({
            show_cat_list: true,
        });
    },
    hideCatList: function () {
        var self = this;
        self.setData({
            show_cat_list: false,
        });
    },

});