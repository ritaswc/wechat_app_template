if (typeof wx === 'undefined') var wx = getApp().core;
var is_no_more = false;
var is_loading = false;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        p: 1
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        is_no_more = false;
        is_loading = false;
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
        if (is_no_more) {
            return;
        }
    },

    loadData: function () {
        var self = this;
        var p = self.data.p;
        if (is_loading) {
            return;
        }
        is_loading = true;
        getApp().core.showLoading({
            title: '加载中',
        });
        var ts = Math.round(new Date().getTime() / 1000).toString();
        getApp().request({
            url: getApp().api.integral.exchange,
            data: {
                page: p
            },
            success: function (res) {
                if (res.code == 0) {
                    var coupon = res.data.list[0].userCoupon;
                    if (coupon) {
                        for (var i in coupon) {
                            if (parseInt(coupon[i].end_time) < parseInt(ts)) {
                                coupon[i].status = 2;
                            } else {
                                coupon[i].status = '';
                            }
                            if (coupon[i].is_use == 1) {
                                coupon[i].status = 1;
                            }
                        }
                    }
                    self.setData({
                        goods: res.data.list[0].goodsDetail,
                        coupon: coupon,
                        page: (p + 1),
                        is_no_more: is_no_more
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