var pageNum = 1;

Page({
    data: {
        history_show: !1,
        search_val: "",
        list: [],
        history_info: [],
        show_loading_bar: !1,
        emptyGoods: !1,
        newSearch: !0
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
    },
    onReady: function(t) {
        getApp().page.onReady(this);
    },
    onShow: function(t) {
        getApp().page.onShow(this);
        var a = this;
        getApp().core.getStorage({
            key: "history_info",
            success: function(t) {
                0 < t.data.length && a.setData({
                    history_info: t.data,
                    history_show: !0
                });
            }
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
    onReachBottom: function(t) {
        getApp().page.onReachBottom(this);
        var a = this;
        a.data.emptyGoods || (a.data.page_count <= pageNum && a.setData({
            emptyGoods: !0
        }), pageNum++, a.getSearchGoods());
    },
    toSearch: function(t) {
        var a = t.detail.value, e = this;
        if (a) {
            var o = e.data.history_info;
            for (var s in o.unshift(a), o) {
                if (o.length <= 20) break;
                o.splice(s, 1);
            }
            getApp().core.setStorageSync(getApp().const.HISTORY_INFO, o), e.setData({
                history_info: o,
                history_show: !1,
                keyword: a,
                list: []
            }), e.getSearchGoods();
        }
    },
    cancelSearchValue: function(t) {
        getApp().core.navigateBack({
            delta: 1
        });
    },
    newSearch: function(t) {
        var a = !1;
        0 < this.data.history_info.length && (a = !0), pageNum = 1, this.setData({
            history_show: a,
            list: [],
            newSearch: [],
            emptyGoods: !1
        });
    },
    clearHistoryInfo: function(t) {
        var a = [];
        getApp().core.setStorageSync(getApp().const.HISTORY_INFO, a), this.setData({
            history_info: a,
            history_show: !1
        });
    },
    getSearchGoods: function() {
        var e = this, t = e.data.keyword;
        t && (e.setData({
            show_loading_bar: !0
        }), getApp().request({
            url: getApp().api.group.search,
            data: {
                keyword: t,
                page: pageNum
            },
            success: function(t) {
                if (0 == t.code) {
                    if (e.data.newSearch) var a = t.data.list; else a = e.data.list.concat(t.data.list);
                    e.setData({
                        list: a,
                        page_count: t.data.page_count,
                        emptyGoods: !0,
                        show_loading_bar: !1
                    }), t.data.page_count > pageNum && e.setData({
                        newSearch: !1,
                        emptyGoods: !1
                    });
                }
            },
            complete: function() {}
        }));
    },
    historyItem: function(t) {
        var a = t.currentTarget.dataset.keyword;
        this.setData({
            keyword: a,
            history_show: !1
        }), this.getSearchGoods();
    }
});