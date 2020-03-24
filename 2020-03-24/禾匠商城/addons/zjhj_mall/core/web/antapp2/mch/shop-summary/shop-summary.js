if (typeof wx === 'undefined') var wx = getApp().core;
Page({
    /**
     * 页面的初始数据
     */
    data: {
        markers: []
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        
        if(!options.mch_id){
            return;
        }
        this.setData({
            mch_id:options.mch_id,
        });
        this.getShopData();
    },  

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function() {
        getApp().page.onShow(this);
    },

    getShopData: function() {
        var self = this;
        getApp().core.showLoading({
            title: '加载中',
        });
        getApp().request({
            url: getApp().api.mch.shop,
            data: {
                mch_id: self.data.mch_id,
                tab: 0,
                cat_id: 0,
            },
            success: function(res) {
                if (res.code == 0) {
                    let shop = res.data.shop;
                    let markers = [{
                        iconPath: "/mch/images/img-map.png",
                        id: 0,
                        width: 20,
                        height: 43,
                        longitude:shop.longitude,
                        latitude:shop.latitude,
                    }];
                    self.setData({
                        markers:markers,
                        shop: res.data.shop,
                    });
                }
            },
            complete: function() {
                getApp().core.hideLoading();
                self.setData({
                    loading: false,
                });
            }
        });
    },

    callPhone: function(e) {
        getApp().core.makePhoneCall({
            phoneNumber: e.target.dataset.info
        })
    },

    map_power: function () {
        var self = this;
        getApp().getConfig(function (config) {
            var shop = self.data.shop;

            if (typeof shop !== 'undefined') {
                self.map_goto(shop);
            } else {
                getApp().core.getSetting({
                    success: function(res) {
                        if (!res.authSetting['scope.userLocation']) {
                            getApp().getauth({
                                content: '需要获取您的地理位置授权，请到小程序设置中打开授权！',
                                cancel: false,
                                author:'scope.userLocation',
                                success: function(res) {
                                    if (res.authSetting['scope.userLocation']) {
                                        self.map_goto(shop);
                                    }
                                }
                            });
                        } else {
                            self.map_goto(shop);
                        }
                    }
                })
            }

        });
    },

    map_goto: function (shop){
        getApp().core.openLocation({
            latitude:  parseFloat(shop.latitude),
            longitude:  parseFloat(shop.longitude),
            address: shop.address,
        })
    },

    /**
     * 用户点击右上角分享
     */
    // onShareAppMessage: function () {
    //     getApp().page.onShareAppMessage(this);
    //     var self = this;
    //     var user_info = getApp().getUser();
    //     return {
    //         path: "/mch/shop-summary/shop-summary?user_id=" + user_info.id + 'mch_id=' + self.data.mch_id,
    //         title: self.data.shop ? self.data.shop.name : '商城首页',
    //     };
    // },
});