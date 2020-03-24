var integral_catId = 0, integral_index = -1, page = 1;

Page({
    data: {
        goods_list: []
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t), integral_catId = 0, integral_index = -1;
        page = 1, this.getGoodsList(integral_catId);
    },
    onReady: function(t) {
        getApp().page.onReady(this);
    },
    onShow: function(t) {
        getApp().page.onShow(this);
        var a = this;
        getApp().request({
            url: getApp().api.integral.index,
            data: {},
            success: function(t) {
                if (0 == t.code && (t.data.today && a.setData({
                    register_day: 1
                }), a.setData({
                    banner_list: t.data.banner_list,
                    coupon_list: t.data.coupon_list,
                    integral: t.data.user.integral,
                    catList: t.data.cat_list
                }), -1 != integral_index)) {
                    var e = [];
                    e.index = integral_index, e.catId = integral_catId, a.catGoods({
                        currentTarget: {
                            dataset: e
                        }
                    });
                }
            },
            complete: function(t) {
                getApp().core.hideLoading();
            }
        });
    },
    exchangeCoupon: function(t) {
        var a = this, n = a.data.coupon_list, e = t.currentTarget.dataset.index, o = n[e], i = a.data.integral;
        if (parseInt(o.integral) > parseInt(i)) a.setData({
            showModel: !0,
            content: "当前积分不足",
            status: 1
        }); else {
            if (0 < parseFloat(o.price)) var s = "需要" + o.integral + "积分+￥" + parseFloat(o.price); else s = "需要" + o.integral + "积分";
            if (parseInt(o.total_num) <= 0) return void a.setData({
                showModel: !0,
                content: "已领完,来晚一步",
                status: 1
            });
            if (parseInt(o.num) >= parseInt(o.user_num)) return o.type = 1, void a.setData({
                showModel: !0,
                content: "兑换次数已达上限",
                status: 1,
                coupon_list: n
            });
            getApp().core.showModal({
                title: "确认兑换",
                content: s,
                success: function(t) {
                    t.confirm && (0 < parseFloat(o.price) ? (getApp().core.showLoading({
                        title: "提交中"
                    }), getApp().request({
                        url: getApp().integral.exchange_coupon,
                        data: {
                            id: o.id,
                            type: 2
                        },
                        success: function(e) {
                            0 == e.code && getApp().core.requestPayment({
                                _res: e,
                                timeStamp: e.data.timeStamp,
                                nonceStr: e.data.nonceStr,
                                package: e.data.package,
                                signType: e.data.signType,
                                paySign: e.data.paySign,
                                complete: function(t) {
                                    "requestPayment:fail" != t.errMsg && "requestPayment:fail cancel" != t.errMsg ? "requestPayment:ok" == t.errMsg && (o.num = parseInt(o.num), 
                                    o.num += 1, o.total_num = parseInt(o.total_num), o.total_num -= 1, i = parseInt(i), 
                                    i -= parseInt(o.integral), a.setData({
                                        showModel: !0,
                                        status: 4,
                                        content: e.msg,
                                        coupon_list: n,
                                        integral: i
                                    })) : getApp().core.showModal({
                                        title: "提示",
                                        content: "订单尚未支付",
                                        showCancel: !1,
                                        confirmText: "确认"
                                    });
                                }
                            });
                        },
                        complete: function() {
                            getApp().core.hideLoading();
                        }
                    })) : (getApp().core.showLoading({
                        title: "提交中"
                    }), getApp().request({
                        url: getApp().api.integral.exchange_coupon,
                        data: {
                            id: o.id,
                            type: 1
                        },
                        success: function(t) {
                            0 == t.code && (o.num = parseInt(o.num), o.num += 1, o.total_num = parseInt(o.total_num), 
                            o.total_num -= 1, i = parseInt(i), i -= parseInt(o.integral), a.setData({
                                showModel: !0,
                                status: 4,
                                content: t.msg,
                                coupon_list: n,
                                integral: i
                            }));
                        },
                        complete: function() {
                            getApp().core.hideLoading();
                        }
                    })));
                }
            });
        }
    },
    hideModal: function() {
        this.setData({
            showModel: !1
        });
    },
    couponInfo: function(t) {
        var e = t.currentTarget.dataset;
        getApp().core.navigateTo({
            url: "/pages/integral-mall/coupon-info/index?coupon_id=" + e.id
        });
    },
    goodsAll: function() {
        var t = this.data.goods_list, e = [];
        for (var a in t) {
            var n = t[a].goods;
            for (var o in t[a].cat_checked = !1, n) e.push(n[o]);
        }
        this.setData({
            index_goods: e,
            cat_checked: !0,
            goods_list: t
        });
    },
    catGoods: function(t) {
        var e = t.currentTarget.dataset, a = this, n = a.data.catList;
        integral_catId = e.catId, integral_index = e.index;
        var o = e.index;
        if (-1 === o) {
            var i = !0;
            for (var s in n) n[s].cat_checked = !1;
        }
        if (0 <= o) for (var s in n) n[s].id == n[o].id ? i = !(n[s].cat_checked = !0) : n[s].cat_checked = !1;
        a.setData({
            cat_checked: i,
            catList: n,
            goods_list: []
        }), page = 1, a.getGoodsList(integral_catId);
    },
    getGoodsList: function(t) {
        var a = this;
        -1 === integral_index && a.setData({
            cat_checked: !0
        }), getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.integral.goods_list,
            data: {
                page: page,
                cat_id: t
            },
            success: function(t) {
                if (0 === t.code) {
                    var e = a.data.goods_list;
                    0 < t.data.list.length && (0 < e.length && (e = e.concat(t.data.list)), 0 === e.length && (e = t.data.list), 
                    page += 1), 0 === t.data.list.length && getApp().core.showToast({
                        title: "没有更多啦",
                        icon: "none"
                    }), a.setData({
                        goods_list: e
                    });
                }
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    goodsInfo: function(t) {
        var e = t.currentTarget.dataset.goodsId;
        getApp().core.navigateTo({
            url: "/pages/integral-mall/goods-info/index?goods_id=" + e + "&integral=" + this.data.integral
        });
    },
    onHide: function(t) {
        getApp().page.onHide(this);
    },
    onUnload: function(t) {
        getApp().page.onUnload(this);
    },
    onPullDownRefresh: function(t) {
        getApp().page.onPullDownRefresh(this);
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        var t = getApp().getUser(), e = "", a = getApp().core.getStorageSync(getApp().const.WX_BAR_TITLE);
        for (var n in a) if ("pages/integral-mall/index/index" === a[n].url) {
            e = a[n].title;
            break;
        }
        return {
            path: "/pages/integral-mall/index/index?user_id=" + t.id,
            title: e || "积分商城"
        };
    },
    onReachBottom: function(t) {
        getApp().page.onReachBottom(this);
        this.getGoodsList(integral_catId);
    },
    shuoming: function() {
        getApp().core.navigateTo({
            url: "/pages/integral-mall/shuoming/index"
        });
    },
    detail: function() {
        getApp().core.navigateTo({
            url: "/pages/integral-mall/detail/index"
        });
    },
    exchange: function() {
        getApp().core.navigateTo({
            url: "/pages/integral-mall/exchange/index"
        });
    },
    register: function() {
        getApp().core.navigateTo({
            url: "/pages/integral-mall/register/index"
        });
    }
});