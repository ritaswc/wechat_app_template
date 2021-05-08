var app = getApp(), api = getApp().api, is_no_more = !1, is_loading = !1, p = 2;

Page({
    data: {
        status: -1,
        cash_list: [],
        show_no_data_tip: !1
    },
    onLoad: function(t) {
        app.page.onLoad(this, t);
        is_loading = is_no_more = !1, p = 2, this.LoadCashList(t.status || -1);
    },
    onReady: function() {},
    onShow: function() {
        getApp().page.onShow(this);
    },
    LoadCashList: function(t) {
        var a = this;
        a.setData({
            status: parseInt(t || -1)
        }), getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.share.cash_detail,
            data: {
                status: a.data.status
            },
            success: function(t) {
                0 == t.code && a.setData({
                    cash_list: t.data.list
                }), a.setData({
                    show_no_data_tip: 0 == a.data.cash_list.length
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    onHide: function() {
        getApp().page.onHide(this);
    },
    onUnload: function() {
        getApp().page.onUnload(this);
    },
    onPullDownRefresh: function() {},
    onReachBottom: function() {
        var s = this;
        is_loading || is_no_more || (is_loading = !0, getApp().request({
            url: getApp().api.share.cash_detail,
            data: {
                status: s.data.status,
                page: p
            },
            success: function(t) {
                if (0 == t.code) {
                    var a = s.data.cash_list.concat(t.data.list);
                    s.setData({
                        cash_list: a
                    }), 0 == t.data.list.length && (is_no_more = !0);
                }
                p++;
            },
            complete: function() {
                is_loading = !1;
            }
        }));
    }
});