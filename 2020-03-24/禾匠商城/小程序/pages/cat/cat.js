var _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
    return typeof t;
} : function(t) {
    return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t;
}, is_no_more = !1, is_loading_more = !1;

Page({
    data: {
        cat_list: [],
        sub_cat_list_scroll_top: 0,
        scrollLeft: 0,
        page: 1,
        cat_style: 0,
        height: 0,
        catheight: 120
    },
    onLoad: function(t) {
        var a = this;
        getApp().page.onLoad(a, t);
        var e = getApp().core.getStorageSync(getApp().const.STORE), o = t.cat_id;
        void 0 !== o && o && (a.data.cat_style = e.cat_style = -1, getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), a.childrenCat(o)), a.setData({
            store: e
        });
    },
    onShow: function() {
        getApp().page.onShow(this), getApp().core.hideLoading(), -1 !== this.data.cat_style && this.loadData();
    },
    loadData: function(t) {
        var a = this, e = getApp().core.getStorageSync(getApp().const.STORE);
        if ("" == a.data.cat_list || 5 != e.cat_style && 4 != e.cat_style && 2 != e.cat_style) {
            var o = getApp().core.getStorageSync(getApp().const.CAT_LIST);
            o && a.setData({
                cat_list: o,
                current_cat: null
            }), getApp().request({
                url: getApp().api.default.cat_list,
                success: function(t) {
                    0 == t.code && (a.data.cat_list = t.data.list, 5 === e.cat_style && a.goodsAll({
                        currentTarget: {
                            dataset: {
                                index: 0
                            }
                        }
                    }), 4 !== e.cat_style && 2 !== e.cat_style || a.catItemClick({
                        currentTarget: {
                            dataset: {
                                index: 0
                            }
                        }
                    }), 1 !== e.cat_style && 3 !== e.cat_style || (a.setData({
                        cat_list: t.data.list,
                        current_cat: null
                    }), getApp().core.setStorageSync(getApp().const.CAT_LIST, t.data.list)));
                },
                complete: function() {
                    getApp().core.stopPullDownRefresh();
                }
            });
        } else a.setData({
            cat_list: a.data.cat_list,
            current_cat: a.data.current_cat
        });
    },
    childrenCat: function(i) {
        var s = this;
        is_no_more = !1;
        s.data.page;
        getApp().request({
            url: getApp().api.default.cat_list,
            success: function(t) {
                if (0 == t.code) {
                    var a = !0;
                    for (var e in t.data.list) for (var o in t.data.list[e].id == i && (a = !1, s.data.current_cat = t.data.list[e], 
                    0 < t.data.list[e].list.length ? (s.setData({
                        catheight: 100
                    }), s.firstcat({
                        currentTarget: {
                            dataset: {
                                index: 0
                            }
                        }
                    })) : s.firstcat({
                        currentTarget: {
                            dataset: {
                                index: 0
                            }
                        }
                    }, !1)), t.data.list[e].list) t.data.list[e].list[o].id == i && (a = !1, s.data.current_cat = t.data.list[e], 
                    s.goodsItem({
                        currentTarget: {
                            dataset: {
                                index: o
                            }
                        }
                    }, !1));
                    a && s.setData({
                        show_no_data_tip: !0
                    });
                }
            },
            complete: function() {
                getApp().core.stopPullDownRefresh(), getApp().core.createSelectorQuery().select("#cat").boundingClientRect().exec(function(t) {
                    s.setData({
                        height: t[0].height
                    });
                });
            }
        });
    },
    catItemClick: function(t) {
        var a = t.currentTarget.dataset.index, e = this.data.cat_list, o = null;
        for (var i in e) i == a ? (!(e[i].active = !0), o = e[i]) : e[i].active = !1;
        this.setData({
            cat_list: e,
            sub_cat_list_scroll_top: 0,
            current_cat: o
        });
    },
    firstcat: function(t) {
        var a = !(1 < arguments.length && void 0 !== arguments[1]) || arguments[1], e = this, o = e.data.current_cat;
        e.setData({
            page: 1,
            goods_list: [],
            show_no_data_tip: !1,
            current_cat: a ? o : []
        }), e.list(o.id, 2);
    },
    goodsItem: function(t) {
        var a = !(1 < arguments.length && void 0 !== arguments[1]) || arguments[1], e = this, o = t.currentTarget.dataset.index, i = e.data.current_cat, s = 0;
        for (var c in i.list) o == c ? (i.list[c].active = !0, s = i.list[c].id) : i.list[c].active = !1;
        e.setData({
            page: 1,
            goods_list: [],
            show_no_data_tip: !1,
            current_cat: a ? i : []
        }), e.list(s, 2);
    },
    goodsAll: function(o) {
        var i = this, t = o.currentTarget.dataset.index, s = i.data.cat_list, a = null;
        for (var e in s) e == t ? (s[e].active = !0, a = s[e]) : s[e].active = !1;
        if (i.setData({
            page: 1,
            goods_list: [],
            show_no_data_tip: !1,
            cat_list: s,
            current_cat: a
        }), void 0 === ("undefined" == typeof my ? "undefined" : _typeof(my))) {
            var c = o.currentTarget.offsetLeft, n = i.data.scrollLeft;
            n = c - 80, i.setData({
                scrollLeft: n
            });
        } else s.forEach(function(t, a, e) {
            t.id == o.currentTarget.id && (1 <= a ? i.setData({
                toView: s[a - 1].id
            }) : i.setData({
                toView: s[a].id
            }));
        });
        i.list(a.id, 1), getApp().core.createSelectorQuery().select("#catall").boundingClientRect().exec(function(t) {
            i.setData({
                height: t[0].height
            });
        });
    },
    list: function(a, t) {
        var e = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), is_no_more = !1;
        var o = e.data.page || 2;
        getApp().request({
            url: getApp().api.default.goods_list,
            data: {
                cat_id: a,
                page: o
            },
            success: function(t) {
                0 == t.code && (getApp().core.hideLoading(), 0 == t.data.list.length && (is_no_more = !0), 
                e.setData({
                    page: o + 1
                }), e.setData({
                    goods_list: t.data.list
                }), e.setData({
                    cat_id: a
                })), e.setData({
                    show_no_data_tip: 0 == e.data.goods_list.length
                });
            },
            complete: function() {
                1 == t && getApp().core.createSelectorQuery().select("#catall").boundingClientRect().exec(function(t) {
                    e.setData({
                        height: t[0].height
                    });
                });
            }
        });
    },
    onReachBottom: function() {
        getApp().page.onReachBottom(this);
        is_no_more || 5 != getApp().core.getStorageSync(getApp().const.STORE).cat_style && -1 != this.data.cat_style || this.loadMoreGoodsList();
    },
    loadMoreGoodsList: function() {
        var e = this;
        if (!is_loading_more) {
            e.setData({
                show_loading_bar: !0
            }), is_loading_more = !0;
            var t = e.data.cat_id || "", o = e.data.page || 2;
            getApp().request({
                url: getApp().api.default.goods_list,
                data: {
                    page: o,
                    cat_id: t
                },
                success: function(t) {
                    0 == t.data.list.length && (is_no_more = !0);
                    var a = e.data.goods_list.concat(t.data.list);
                    e.setData({
                        goods_list: a,
                        page: o + 1
                    });
                },
                complete: function() {
                    is_loading_more = !1, e.setData({
                        show_loading_bar: !1
                    });
                }
            });
        }
    }
});