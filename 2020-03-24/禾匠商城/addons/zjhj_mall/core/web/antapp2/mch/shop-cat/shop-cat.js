if (typeof wx === 'undefined') var wx = getApp().core;
var app = getApp();
var api = getApp().api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        list: [
            {
                id: 1,
                name: '上衣',
            },
            {
                id: 2,
                name: '下装',
                list: [
                    {
                        id: 3,
                        name: '长裤',
                    },
                    {
                        id: 4,
                        name: '长裤',
                    },
                    {
                        id: 5,
                        name: '九分裤',
                    },
                    {
                        id: 6,
                        name: '短裤',
                    },
                ],
            },
            {
                id: 7,
                name: '帽子',
            },
        ],
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        self.setData({
            mch_id: options.mch_id,
            cat_id: options.cat_id || '',
        });
        var cache_key = 'shop_cat_list_mch_id_' + self.data.mch_id;
        var list = getApp().core.getStorageSync(cache_key);
        if (list) {
            self.setData({
                list: list,
            });
        }
        getApp().core.showNavigationBarLoading();
        getApp().request({
            url: getApp().api.mch.shop_cat,
            data: {
                mch_id: self.data.mch_id
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        list: res.data.list,
                    });
                    getApp().core.setStorageSync(cache_key, res.data.list);
                }
            },
            complete: function () {
                getApp().core.hideNavigationBarLoading();
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

    },
});