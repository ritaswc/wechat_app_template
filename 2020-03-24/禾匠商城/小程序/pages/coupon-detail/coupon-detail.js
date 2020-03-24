Page({
    data: {},
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var e = this, o = t.user_coupon_id ? t.user_coupon_id : 0, a = t.coupon_id ? t.coupon_id : 0;
        (o || a) && (getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.coupon.coupon_detail,
            data: {
                user_conpon_id: o,
                coupon_id: a
            },
            success: function(t) {
                0 == t.code && e.setData({
                    list: t.data.list
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        }));
    },
    goodsList: function(t) {
        var e = t.currentTarget.dataset.goods_id, o = t.currentTarget.dataset.id, a = this.data.list;
        parseInt(a.id) !== parseInt(o) || 2 == a.appoint_type && 0 < a.goods.length && getApp().core.navigateTo({
            url: "/pages/list/list?goods_id=" + e
        });
    },
    receive: function(t) {
        var o = this, e = t.target.dataset.index;
        getApp().core.showLoading({
            mask: !0
        }), o.hideGetCoupon || (o.hideGetCoupon = function(t) {
            var e = t.currentTarget.dataset.url || !1;
            o.setData({
                get_coupon_list: []
            }), e && getApp().core.navigateTo({
                url: e
            });
        }), getApp().request({
            url: getApp().api.coupon.receive,
            data: {
                id: e
            },
            success: function(t) {
                if (0 == t.code) {
                    var e = o.data.list;
                    e.is_receive = 1, o.setData({
                        list: e,
                        get_coupon_list: t.data.list
                    });
                }
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    closeCouponBox: function(t) {
        this.setData({
            get_coupon_list: ""
        });
    }
});