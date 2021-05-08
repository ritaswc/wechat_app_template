Page({
    data: {
        cid: 0,
        scrollLeft: 600,
        scrollTop: 0,
        emptyGoods: 0,
        page: 1,
        pageCount: 0,
        cat_show: 1,
        cid_url: !1
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        if (this.systemInfo = getApp().core.getSystemInfoSync(), t.cid) {
            t.cid;
            return this.setData({
                cid_url: !1
            }), void this.switchNav({
                currentTarget: {
                    dataset: {
                        id: t.cid
                    }
                }
            });
        }
        this.setData({
            cid_url: !0
        }), this.loadIndexInfo(this);
    },
    onReady: function(t) {
        getApp().page.onReady(this);
    },
    onShow: function(t) {
        getApp().page.onShow(this);
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
    loadIndexInfo: function() {
        var a = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.book.index,
            method: "get",
            success: function(t) {
                0 == t.code && (getApp().core.hideLoading(), a.setData({
                    cat: t.data.cat,
                    goods: t.data.goods.list,
                    cat_show: t.data.cat_show,
                    page: t.data.goods.page,
                    pageCount: t.data.goods.page_count
                }), 0 < !t.data.goods.list.length && a.setData({
                    emptyGoods: 1
                }));
            }
        });
    },
    switchNav: function(t) {
        var e = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        });
        var a = 0;
        if (a != t.currentTarget.dataset.id || 0 == t.currentTarget.dataset.id) {
            if (a = t.currentTarget.dataset.id, "wx" == this.data.__platform) {
                var o = e.systemInfo.windowWidth, s = t.currentTarget.offsetLeft, d = e.data.scrollLeft;
                d = o / 2 < s ? s : 0, e.setData({
                    scrollLeft: d
                });
            }
            if ("my" == this.data.__platform) {
                for (var i = e.data.cat, n = !0, p = 0; p < i.length; ++p) if (i[p].id === t.currentTarget.id) {
                    n = !1, 1 <= p ? e.setData({
                        toView: i[p - 1].id
                    }) : e.setData({
                        toView: "0"
                    });
                    break;
                }
                n && e.setData({
                    toView: "0"
                });
            }
            e.setData({
                cid: a,
                page: 1,
                scrollTop: 0,
                emptyGoods: 0,
                goods: [],
                show_loading_bar: 1
            }), getApp().request({
                url: getApp().api.book.list,
                method: "get",
                data: {
                    cid: a
                },
                success: function(t) {
                    if (0 == t.code) {
                        getApp().core.hideLoading();
                        var a = t.data.list;
                        t.data.page_count >= t.data.page ? e.setData({
                            goods: a,
                            page: t.data.page,
                            pageCount: t.data.page_count,
                            show_loading_bar: 0
                        }) : e.setData({
                            emptyGoods: 1
                        });
                    }
                }
            });
        }
    },
    onReachBottom: function(t) {
        var e = this, a = e.data.page, o = e.data.pageCount, s = e.data.cid;
        e.setData({
            show_loading_bar: 1
        }), ++a > o ? e.setData({
            emptyGoods: 1,
            show_loading_bar: 0
        }) : getApp().request({
            url: getApp().api.book.list,
            method: "get",
            data: {
                page: a,
                cid: s
            },
            success: function(t) {
                if (0 == t.code) {
                    var a = e.data.goods;
                    Array.prototype.push.apply(a, t.data.list), e.setData({
                        show_loading_bar: 0,
                        goods: a,
                        page: t.data.page,
                        pageCount: t.data.page_count,
                        emptyGoods: 0
                    });
                }
            }
        });
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        return {
            path: "/pages/book/index/index?user_id=" + this.data.__user_info.id + "&cid=",
            success: function(t) {}
        };
    }
});