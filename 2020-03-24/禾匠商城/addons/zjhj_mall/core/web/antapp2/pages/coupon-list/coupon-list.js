if (typeof wx === 'undefined') var wx = getApp().core;
var share_count = 0;
Page({

    /**
     * 页面的初始数据
     */
    data: {

    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
    },


    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        getApp().page.onShow(this);

        var self = this;
        getApp().core.showLoading({
            mask: true,
        });
        getApp().request({
            url: getApp().api.default.coupon_list,
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        coupon_list: res.data.list
                    });
                }
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },

    receive: function (e) {
        var self = this;
        var id = e.target.dataset.index;
        getApp().core.showLoading({
            mask: true,
        });
        if (!self.hideGetCoupon) {
            self.hideGetCoupon = function (e) {
                var url = e.currentTarget.dataset.url || false;
                self.setData({
                    get_coupon_list: null,
                });
                if (url) {
                    getApp().core.navigateTo({
                        url: url,
                    });
                }
            };
        }
        getApp().request({
            url: getApp().api.coupon.receive,
            data: { id: id },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        get_coupon_list: res.data.list,
                        coupon_list: res.data.coupon_list
                    });
                }
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },

    closeCouponBox: function (e) {
        this.setData({
            get_coupon_list: ""
        });
    },

    goodsList: function (e) {
        var goods = e.currentTarget.dataset.goods;
        var goods_id = [];
        for (var i in goods) {
            goods_id.push(goods[i]['id']);
        }
        getApp().core.navigateTo({
            url: '/pages/list/list?goods_id=' + goods_id,
        })
    }
})