var app = getApp(), api = app.api, is_no_more = !1, is_loading = !1, p = 2;

Page({
    data: {
        status: -1,
        list: [],
        hidden: -1,
        is_no_more: !1,
        is_loading: !1
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        is_loading = is_no_more = !1, p = 2, this.GetList(t.status || -1);
    },
    GetList: function(t) {
        var a = this;
        a.setData({
            status: parseInt(t || -1)
        }), getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.share.get_order,
            data: {
                status: a.data.status
            },
            success: function(t) {
                a.setData({
                    list: t.data
                }), 0 == t.data.length && a.setData({
                    is_no_more: !0
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
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
    click: function(t) {
        var a = t.currentTarget.dataset.index;
        this.setData({
            hidden: this.data.hidden == a ? -1 : a
        });
    },
    onReachBottom: function() {
        var e = this;
        is_loading || is_no_more || (is_loading = !0, e.setData({
            is_loading: is_loading
        }), getApp().request({
            url: getApp().api.share.get_order,
            data: {
                status: e.data.status,
                page: p
            },
            success: function(t) {
                if (0 == t.code) {
                    var a = e.data.list.concat(t.data);
                    e.setData({
                        list: a
                    }), 0 == t.data.length && (is_no_more = !0, e.setData({
                        is_no_more: is_no_more
                    }));
                }
                p++;
            },
            complete: function() {
                is_loading = !1, e.setData({
                    is_loading: is_loading
                });
            }
        }));
    }
});