if (typeof wx === 'undefined') var wx = getApp().core;
var WxParse = require('../../wxParse/wxParse.js');
Page({

    /**
     * 页面的初始数据
     */
    data: {
        score: [1, 2, 3, 4, 5],
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);

        var self = this;
        self.setData({
            shop_id: options.shop_id
        });
        getApp().core.showLoading({
            title: '加载中',
        });
        getApp().request({
            url: getApp().api.default.shop_detail,
            method: 'GET',
            data: {
                shop_id: options.shop_id
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData(res.data);
                    var detail = res.data.shop.content ? res.data.shop.content : "<span>暂无信息</span>"
                    WxParse.wxParse("detail", "html", detail, self);
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function (e) {
                            if (e.confirm) {
                                getApp().core.redirectTo({
                                    url: '/pages/shop/shop',
                                })
                            }
                        }
                    })
                }
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        getApp().page.onShow(this);
    },

    mobile: function () {
        var self = this;
        getApp().core.makePhoneCall({
            phoneNumber: self.data.shop.mobile,
        })
    },

    goto: function () {
        var self = this;
        if (typeof my !== 'undefined') {
            self.location();
        } else {
            getApp().core.getSetting({
                success: function (res) {
                    if (!res.authSetting['scope.userLocation']) {
                        getApp().getauth({
                            content: '需要获取您的地理位置授权，请到小程序设置中打开授权！',
                            cancel: false,
                            author: 'scope.userLocation',
                            success: function (res) {
                                if (res.authSetting['scope.userLocation']) {
                                    self.location();
                                }
                            }
                        });
                    } else {
                        self.location();
                    }
                }
            })
        }
    },

    location: function () {
        var shop = this.data.shop;
        getApp().core.openLocation({
            latitude: parseFloat(shop.latitude),
            longitude: parseFloat(shop.longitude),
            name: shop.name,
            address: shop.address
        })
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function (options) {
        getApp().page.onShareAppMessage(this);
        var user_info = getApp().core.getStorageSync(getApp().const.USER_INFO);
        var shop_id = this.data.shop_id;
        return {
            path: "/pages/shop-detail/shop-detail?shop_id="+shop_id+"&user_id=" + user_info.id,
        };
    },
})