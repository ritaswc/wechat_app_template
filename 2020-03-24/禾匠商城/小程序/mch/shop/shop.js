var utils = getApp().helper, app = getApp(), api = getApp().api;

Page({
    data: {
        tab: 1,
        sort: 1,
        coupon_list: [],
        copy: !1
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var a = this;
        if ("undefined" == typeof my) {
            if (t.scene) {
                var o = decodeURIComponent(t.scene);
                o && (o = utils.scene_decode(o)).mch_id && (t.mch_id = o.mch_id);
            }
        } else if (null !== getApp().query) {
            var e = getApp().query;
            getApp().query = null, t.mch_id = e.mch_id;
        }
        a.setData({
            tab: t.tab || 1,
            sort: t.sort || 1,
            mch_id: t.mch_id || !1,
            cat_id: t.cat_id || ""
        }), a.data.mch_id || getApp().core.showModal({
            title: "提示",
            content: "店铺不存在！店铺id为空"
        }), setInterval(function() {
            a.onScroll();
        }, 40), this.getShopData();
    },
    quickNavigation: function() {
        this.setData({
            quick_icon: !this.data.quick_icon
        });
        this.data.store;
        var t = getApp().core.createAnimation({
            duration: 300,
            timingFunction: "ease-out"
        });
        this.data.quick_icon ? t.opacity(0).step() : t.translateY(-55).opacity(1).step(), 
        this.setData({
            animationPlus: t.export()
        });
    },
    onReady: function() {
        getApp().page.onReady(this);
    },
    onShow: function() {
        getApp().page.onShow(this);
    },
    onHide: function() {
        getApp().page.onHide(this);
    },
    onUnload: function() {
        getApp().page.onUnload(this);
    },
    onPullDownRefresh: function() {},
    onReachBottom: function() {
        this.getGoodsList();
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        var t = this;
        return {
            path: "/mch/shop/shop?user_id=" + getApp().getUser().id + "&mch_id=" + t.data.mch_id,
            title: t.data.shop ? t.data.shop.name : "商城首页"
        };
    },
    kfuStart: function() {
        this.setData({
            copy: !0
        });
    },
    kfuEnd: function() {
        this.setData({
            copy: !1
        });
    },
    copyinfo: function(t) {
        getApp().core.setClipboardData({
            data: t.target.dataset.info,
            success: function(t) {
                getApp().core.showToast({
                    title: "复制成功！",
                    icon: "success",
                    duration: 2e3,
                    mask: !0
                });
            }
        });
    },
    callPhone: function(t) {
        getApp().core.makePhoneCall({
            phoneNumber: t.target.dataset.info
        });
    },
    onScroll: function(t) {
        var o = this;
        getApp().core.createSelectorQuery().selectViewport(".after-navber").scrollOffset(function(t) {
            var a = 2 == o.data.tab ? 136.5333 : 85.3333;
            t.scrollTop >= a ? o.setData({
                fixed: !0
            }) : o.setData({
                fixed: !1
            });
        }).exec();
    },
    getShopData: function() {
        var a = this, o = (a.data.current_page || 0) + 1, e = "shop_data_mch_id_" + a.data.mch_id, t = getApp().core.getStorageSync(e);
        t && a.setData({
            shop: t.shop
        }), getApp().core.showNavigationBarLoading(), a.setData({
            loading: !0
        }), getApp().request({
            url: getApp().api.mch.shop,
            data: {
                mch_id: a.data.mch_id,
                tab: a.data.tab,
                sort: a.data.sort,
                page: o,
                cat_id: a.data.cat_id
            },
            success: function(t) {
                1 != t.code ? 0 == t.code && (a.setData({
                    shop: t.data.shop,
                    coupon_list: t.data.coupon_list,
                    hot_list: t.data.goods_list,
                    goods_list: t.data.goods_list,
                    new_list: t.data.new_list,
                    current_page: o,
                    cs_icon: t.data.shop.cs_icon
                }), getApp().core.setStorageSync(e, t.data)) : getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    success: function(t) {
                        t.confirm && getApp().core.redirectTo({
                            url: "/pages/index/index"
                        });
                    }
                });
            },
            complete: function() {
                getApp().core.hideNavigationBarLoading(), a.setData({
                    loading: !1
                });
            }
        });
    },
    getGoodsList: function() {
        var a = this;
        if (3 != a.data.tab && !a.data.loading && !a.data.no_more) {
            a.setData({
                loading: !0
            });
            var o = (a.data.current_page || 0) + 1;
            getApp().request({
                url: getApp().api.mch.shop,
                data: {
                    mch_id: a.data.mch_id,
                    tab: a.data.tab,
                    sort: a.data.sort,
                    page: o,
                    cat_id: a.data.cat_id
                },
                success: function(t) {
                    0 == t.code && (1 == a.data.tab && (t.data.goods_list && t.data.goods_list.length ? (a.data.hot_list = a.data.hot_list.concat(t.data.goods_list), 
                    a.setData({
                        hot_list: a.data.hot_list,
                        current_page: o
                    })) : a.setData({
                        no_more: !0
                    })), 2 == a.data.tab && (t.data.goods_list && t.data.goods_list.length ? (a.data.goods_list = a.data.goods_list.concat(t.data.goods_list), 
                    a.setData({
                        goods_list: a.data.goods_list,
                        current_page: o
                    })) : a.setData({
                        no_more: !0
                    })));
                },
                complete: function() {
                    a.setData({
                        loading: !1
                    });
                }
            });
        }
    }
});