Page({
    data: {
        markers: []
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t), t.mch_id && (this.setData({
            mch_id: t.mch_id
        }), this.getShopData());
    },
    onShow: function() {
        getApp().page.onShow(this);
    },
    getShopData: function() {
        var a = this;
        getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.mch.shop,
            data: {
                mch_id: a.data.mch_id,
                tab: 0,
                cat_id: 0
            },
            success: function(t) {
                if (0 == t.code) {
                    var e = t.data.shop, o = [ {
                        iconPath: "/mch/images/img-map.png",
                        id: 0,
                        width: 20,
                        height: 43,
                        longitude: e.longitude,
                        latitude: e.latitude
                    } ];
                    a.setData({
                        markers: o,
                        shop: t.data.shop
                    });
                }
            },
            complete: function() {
                getApp().core.hideLoading(), a.setData({
                    loading: !1
                });
            }
        });
    },
    callPhone: function(t) {
        getApp().core.makePhoneCall({
            phoneNumber: t.target.dataset.info
        });
    },
    map_power: function() {
        var o = this;
        getApp().getConfig(function(t) {
            var e = o.data.shop;
            void 0 !== e ? o.map_goto(e) : getApp().core.getSetting({
                success: function(t) {
                    t.authSetting["scope.userLocation"] ? o.map_goto(e) : getApp().getauth({
                        content: "需要获取您的地理位置授权，请到小程序设置中打开授权！",
                        cancel: !1,
                        author: "scope.userLocation",
                        success: function(t) {
                            t.authSetting["scope.userLocation"] && o.map_goto(e);
                        }
                    });
                }
            });
        });
    },
    map_goto: function(t) {
        getApp().core.openLocation({
            latitude: parseFloat(t.latitude),
            longitude: parseFloat(t.longitude),
            address: t.address
        });
    }
});