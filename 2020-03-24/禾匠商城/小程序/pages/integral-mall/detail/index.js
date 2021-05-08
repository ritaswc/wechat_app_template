var is_no_more = !1, is_loading = !1;

Page({
    data: {
        gain: !0,
        p: 1,
        status: 1
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t), is_loading = is_no_more = !1;
        t.status && this.setData({
            status: t.status
        });
    },
    onReady: function(t) {
        getApp().page.onReady(this);
    },
    onShow: function(t) {
        getApp().page.onShow(this);
        this.loadData();
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
    onReachBottom: function(t) {
        getApp().page.onReachBottom(this);
        is_no_more || this.loadData();
    },
    income: function() {
        getApp().core.redirectTo({
            url: "/pages/integral-mall/detail/index?status=1"
        });
    },
    expenditure: function() {
        getApp().core.redirectTo({
            url: "/pages/integral-mall/detail/index?status=2"
        });
    },
    loadData: function() {
        var e = this;
        if (!is_loading) {
            is_loading = !0, getApp().core.showLoading({
                title: "加载中"
            });
            var o = e.data.p;
            getApp().request({
                url: getApp().api.integral.integral_detail,
                data: {
                    page: o,
                    status: e.data.status
                },
                success: function(t) {
                    if (0 == t.code) {
                        var a = e.data.list;
                        a = a ? a.concat(t.data.list) : t.data.list, t.data.list.length <= 0 && (is_no_more = !0), 
                        e.setData({
                            list: a,
                            is_no_more: is_no_more,
                            p: o + 1
                        });
                    }
                },
                complete: function(t) {
                    is_loading = !1, getApp().core.hideLoading();
                }
            });
        }
    }
});