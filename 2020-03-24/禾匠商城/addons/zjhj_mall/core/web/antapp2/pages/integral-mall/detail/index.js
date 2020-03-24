if (typeof wx === 'undefined') var wx = getApp().core;
var is_no_more = false;
var is_loading = false;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        gain: true,
        p: 1,
        status: 1
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        is_no_more = false;
        is_loading = false;
        var self = this;
        if (options.status) {
            self.setData({
                status: options.status
            });
        }
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function (options) {
        getApp().page.onReady(this);

    },
    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function (options) {
        getApp().page.onShow(this);
        var self = this;
        self.loadData();
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function (options) {
        getApp().page.onHide(this);

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function (options) {
        getApp().page.onUnload(this);

    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function (options) {
        getApp().page.onPullDownRefresh(this);

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function (options) {
        getApp().page.onReachBottom(this);
        var self = this;
        if (is_no_more) {
            return;
        }
        self.loadData();
    },
    income: function () {
        getApp().core.redirectTo({
            url: '/pages/integral-mall/detail/index?status=1',
        })
    },
    expenditure: function () {
        getApp().core.redirectTo({
            url: '/pages/integral-mall/detail/index?status=2',
        })
    },
    loadData: function () {
        var self = this;
        if (is_loading) {
            return
        }
        is_loading = true;
        getApp().core.showLoading({
            title: '加载中',
        })
        var p = self.data.p;
        getApp().request({
            url: getApp().api.integral.integral_detail,
            data: {
                page: p,
                status: self.data.status
            },
            success: function (res) {
                if (res.code == 0) {
                    var list = self.data.list
                    if (list) {
                        list = list.concat(res.data.list)
                    } else {
                        list = res.data.list
                    }
                    if (res.data.list.length <= 0) {
                        is_no_more = true;
                    }
                    self.setData({
                        list: list,
                        is_no_more: is_no_more,
                        p: (p + 1),
                    });
                }
            },
            complete: function (res) {
                is_loading = false;
                getApp().core.hideLoading();
            }
        });
    }
})