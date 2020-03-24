if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        article_list: [],
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);

        var self = this;
        getApp().core.showLoading();
        getApp().request({
            url: getApp().api.default.article_list,
            data: {
                cat_id: 2,
            },
            success: function (res) {
                getApp().core.hideLoading();
                self.setData({
                    article_list: res.data.list,
                });
            }
        });
    },
})