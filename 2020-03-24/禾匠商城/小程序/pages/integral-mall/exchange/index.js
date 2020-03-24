var is_no_more = !1, is_loading = !1;

Page({
    data: {
        p: 1
    },
    onLoad: function(o) {
        getApp().page.onLoad(this, o), is_loading = is_no_more = !1;
    },
    onReady: function(o) {
        getApp().page.onReady(this);
    },
    onShow: function(o) {
        getApp().page.onShow(this);
        this.loadData();
    },
    onHide: function(o) {
        getApp().page.onHide(this);
    },
    onUnload: function(o) {
        getApp().page.onUnload(this);
    },
    onPullDownRefresh: function(o) {
        getApp().page.onPullDownRefresh(this);
    },
    onReachBottom: function(o) {
        getApp().page.onReachBottom(this);
    },
    loadData: function() {
        var n = this, a = n.data.p;
        if (!is_loading) {
            is_loading = !0, getApp().core.showLoading({
                title: "加载中"
            });
            var i = Math.round(new Date().getTime() / 1e3).toString();
            getApp().request({
                url: getApp().api.integral.exchange,
                data: {
                    page: a
                },
                success: function(o) {
                    if (0 == o.code) {
                        var t = o.data.list[0].userCoupon;
                        if (t) for (var e in t) parseInt(t[e].end_time) < parseInt(i) ? t[e].status = 2 : t[e].status = "", 
                        1 == t[e].is_use && (t[e].status = 1);
                        n.setData({
                            goods: o.data.list[0].goodsDetail,
                            coupon: t,
                            page: a + 1,
                            is_no_more: is_no_more
                        });
                    }
                },
                complete: function(o) {
                    is_loading = !1, getApp().core.hideLoading();
                }
            });
        }
    }
});