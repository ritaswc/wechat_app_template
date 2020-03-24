var is_loading = !1, is_no_more = !0;

Page({
    data: {
        naver: "prize",
        list: [],
        page: 1
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t), this.setData({
            status: t.status || 0
        });
        var a = this;
        getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.lottery.prize,
            data: {
                status: a.data.status,
                page: a.data.page
            },
            success: function(t) {
                0 == t.code && (a.setData({
                    list: t.data.list
                }), null != t.data.list && 0 < t.data.list.length && (is_no_more = !1));
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    onReachBottom: function() {
        is_no_more || this.loadData();
    },
    loadData: function() {
        if (!is_loading) {
            is_loading = !0, getApp().core.showLoading({
                title: "加载中"
            });
            var a = this, e = a.data.page + 1;
            getApp().request({
                url: getApp().api.lottery.prize,
                data: {
                    status: a.data.status,
                    page: e
                },
                success: function(t) {
                    if (0 == t.code) {
                        if (null == t.data.list || 0 == t.data.list.length) return void (is_no_more = !0);
                        a.setData({
                            list: a.data.list.concat(t.data.list),
                            page: e
                        });
                    } else a.showToast({
                        title: t.msg
                    });
                },
                complete: function() {
                    getApp().core.hideLoading(), is_loading = !1;
                }
            });
        }
    }
});