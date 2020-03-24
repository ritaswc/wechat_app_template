Page({
    data: {
        load_more_count: 0,
        last_load_more_time: 0,
        is_loading: !1,
        loading_class: "",
        cat_id: !1,
        keyword: !1,
        page: 1,
        limit: 20,
        pageCount: 0,
        goods_list: [],
        show_history: !0,
        show_result: !1,
        history_list: [],
        is_search: !0,
        is_show: !1,
        cats: [],
        default_cat: []
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t), this.cats();
    },
    onReady: function(t) {
        getApp().page.onReady(this);
    },
    onShow: function(t) {
        getApp().page.onShow(this);
        this.setData({
            history_list: this.getHistoryList(!0)
        });
    },
    onReachBottom: function() {
        getApp().page.onReachBottom(this);
        var t = this, a = t.data.page + 1;
        a <= t.data.pageCount && (t.setData({
            page: a
        }), t.getGoodsList());
    },
    cats: function() {
        var a = this;
        getApp().request({
            url: getApp().api.default.cats,
            success: function(t) {
                0 == t.code && a.setData({
                    cats: t.data.list,
                    default_cat: t.data.default_cat
                });
            }
        });
    },
    change_cat: function(t) {
        var a = this.data.cats, e = t.currentTarget.dataset.id;
        for (var s in a) if (e === a[s].id) var i = {
            id: a[s].id,
            name: a[s].name,
            key: a[s].key,
            url: a[s].url
        };
        this.setData({
            default_cat: i
        });
    },
    pullDown: function() {
        var t = this, a = t.data.cats, e = t.data.default_cat;
        for (var s in a) a[s].id === e.id ? a[s].is_active = !0 : a[s].is_active = !1;
        t.setData({
            is_show: !t.data.is_show,
            cats: a
        });
    },
    inputFocus: function() {
        this.setData({
            show_history: !0,
            show_result: !1
        });
    },
    inputBlur: function() {
        var t = this;
        0 < t.data.goods_list.length && setTimeout(function() {
            t.setData({
                show_history: !1,
                show_result: !0
            });
        }, 300);
    },
    inputConfirm: function(t) {
        var a = this, e = t.detail.value;
        0 != e.length && (a.setData({
            page: 1,
            keyword: e,
            goods_list: []
        }), a.setHistory(e), a.getGoodsList());
    },
    searchCancel: function() {
        getApp().core.navigateBack({
            delta: 1
        });
    },
    historyClick: function(t) {
        var a = t.currentTarget.dataset.value;
        0 != a.length && (this.setData({
            page: 1,
            keyword: a,
            goods_list: []
        }), this.getGoodsList());
    },
    getGoodsList: function() {
        var e = this;
        e.setData({
            show_history: !1,
            show_result: !0,
            is_search: !0
        });
        var t = {};
        e.data.cat_id && (t.cat_id = e.data.cat_id, e.setActiveCat(t.cat_id)), e.data.keyword && (t.keyword = e.data.keyword), 
        t.defaultCat = JSON.stringify(e.data.default_cat), t.page = e.data.page, e.showLoadingBar(), 
        e.is_loading = !0, getApp().request({
            url: getApp().api.default.search,
            data: t,
            success: function(t) {
                if (0 == t.code) {
                    var a = e.data.goods_list.concat(t.data.list);
                    e.setData({
                        goods_list: a,
                        pageCount: t.data.page_count
                    }), 0 == t.data.list.length ? e.setData({
                        is_search: !1
                    }) : e.setData({
                        is_search: !0
                    });
                }
                t.code;
            },
            complete: function() {
                e.hideLoadingBar(), e.is_loading = !1;
            }
        });
    },
    getHistoryList: function(t) {
        t = t || !1;
        var a = getApp().core.getStorageSync(getApp().const.SEARCH_HISTORY_LIST);
        if (!a) return [];
        if (!t) return a;
        for (var e = [], s = a.length - 1; 0 <= s; s--) e.push(a[s]);
        return e;
    },
    setHistory: function(t) {
        var a = this.getHistoryList();
        for (var e in a.push({
            keyword: t
        }), a) {
            if (a.length <= 20) break;
            a.splice(e, 1);
        }
        getApp().core.setStorageSync(getApp().const.SEARCH_HISTORY_LIST, a);
    },
    getMoreGoodsList: function() {
        var s = this, i = {};
        s.data.cat_id && (i.cat_id = s.data.cat_id, s.setActiveCat(i.cat_id)), s.data.keyword && (i.keyword = s.data.keyword), 
        i.page = s.data.page || 1, s.showLoadingMoreBar(), s.setData({
            is_loading: !0
        }), s.setData({
            load_more_count: s.data.load_more_count + 1
        }), i.page = s.data.page + 1, i.defaultCat = s.data.default_cat, s.setData({
            page: i.page
        }), i.defaultCat = JSON.stringify(s.data.default_cat), getApp().request({
            url: getApp().api.default.search,
            data: i,
            success: function(t) {
                if (0 == t.code) {
                    var a = s.data.goods_list;
                    if (0 < t.data.list.length) {
                        for (var e in t.data.list) a.push(t.data.list[e]);
                        s.setData({
                            goods_list: a
                        });
                    } else s.setData({
                        page: i.page - 1
                    });
                }
                t.code;
            },
            complete: function() {
                s.setData({
                    is_loading: !1
                }), s.hideLoadingMoreBar();
            }
        });
    },
    showLoadingBar: function() {
        this.setData({
            loading_class: "active"
        });
    },
    hideLoadingBar: function() {
        this.setData({
            loading_class: ""
        });
    },
    showLoadingMoreBar: function() {
        this.setData({
            loading_more_active: "active"
        });
    },
    hideLoadingMoreBar: function() {
        this.setData({
            loading_more_active: ""
        });
    },
    deleteSearchHistory: function() {
        this.setData({
            history_list: null
        }), getApp().core.removeStorageSync(getApp().const.SEARCH_HISTORY_LIST);
    }
});