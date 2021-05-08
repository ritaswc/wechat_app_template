var app = getApp(), api = getApp().api;

Page({
    data: {
        list: [],
        current_page: 0,
        loading: !1,
        no_more: !1
    },
    getList: function() {
        var a = this;
        if (!a.data.loading && !a.data.no_more) {
            a.setData({
                loading: !0
            });
            var e = a.data.current_page + 1;
            getApp().request({
                url: getApp().api.mch.user.cash_log,
                data: {
                    page: e,
                    year: "",
                    month: ""
                },
                success: function(t) {
                    0 == t.code && (t.data.list && t.data.list.length ? (a.data.list = a.data.list.concat(t.data.list), 
                    a.setData({
                        list: a.data.list,
                        current_page: e
                    })) : a.setData({
                        no_more: !0
                    })), 1 == t.code && getApp().core.showModal({
                        title: "提示",
                        content: t.msg,
                        showCancel: !1
                    });
                },
                complete: function(t) {
                    a.setData({
                        loading: !1
                    });
                }
            });
        }
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        this.getList();
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
        this.getList();
    }
});