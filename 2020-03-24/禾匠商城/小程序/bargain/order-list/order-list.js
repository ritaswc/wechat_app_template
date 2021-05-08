var app = getApp(), api = getApp().api, is_loading = !1, is_no_more = !0, intval = null;

Page({
    data: {
        naver: "order",
        status: -1,
        intval: [],
        p: 1
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        null == t.status && (t.status = -1), this.setData(t), this.getList();
    },
    getList: function() {
        var a = this;
        getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.bargain.order_list,
            data: {
                status: a.data.status || -1
            },
            success: function(t) {
                0 == t.code ? (a.setData(t.data), a.setData({
                    p: 1
                }), a.getTimeList()) : a.showLoading({
                    title: t.msg
                });
            },
            complete: function(t) {
                getApp().core.hideLoading(), is_no_more = !1;
            }
        });
    },
    getTimeList: function() {
        clearInterval(intval);
        var i = this, s = i.data.list;
        intval = setInterval(function() {
            for (var t in s) if (0 < s[t].reset_time) {
                var a = s[t].reset_time - 1, e = i.setTimeList(a);
                s[t].reset_time = a, s[t].time_list = e;
            }
            i.setData({
                list: s
            });
        }, 1e3);
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
    onReachBottom: function() {
        is_no_more || this.loadData();
    },
    loadData: function() {
        var e = this;
        if (!is_loading) {
            is_loading = !0, getApp().core.showLoading({
                title: "加载中"
            });
            var i = e.data.p + 1;
            getApp().request({
                url: getApp().api.bargain.order_list,
                data: {
                    status: e.data.status,
                    page: i
                },
                success: function(t) {
                    if (0 == t.code) {
                        var a = e.data.list.concat(t.data.list);
                        e.setData({
                            list: a,
                            p: i
                        }), 0 == t.data.list.length && (is_no_more = !0), e.getTimeList();
                    } else e.showLoading({
                        title: t.msg
                    });
                },
                complete: function(t) {
                    getApp().core.hideLoading(), is_loading = !0;
                }
            });
        }
    },
    submit: function(t) {
        var a = [], e = [];
        e.push({
            bargain_order_id: t.currentTarget.dataset.index
        }), a.push({
            mch_id: 0,
            goods_list: e
        }), getApp().core.navigateTo({
            url: "/pages/new-order-submit/new-order-submit?mch_list=" + JSON.stringify(a)
        });
    }
});