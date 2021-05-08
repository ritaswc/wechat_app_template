Page({
    data: {
        cid: 0,
        scrollLeft: 600,
        scrollTop: 0,
        emptyGoods: 0,
        page_count: 0,
        pt_url: !1,
        page: 1,
        is_show: 0
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e), this.systemInfo = getApp().core.getSystemInfoSync();
        var a = getApp().core.getStorageSync(getApp().const.STORE);
        this.setData({
            store: a
        });
        var o = this;
        if (e.cid) {
            e.cid;
            return this.setData({
                pt_url: !1
            }), getApp().core.showLoading({
                title: "正在加载",
                mask: !0
            }), void getApp().request({
                url: getApp().api.group.index,
                method: "get",
                success: function(a) {
                    if (o.switchNav({
                        currentTarget: {
                            dataset: {
                                id: e.cid
                            }
                        }
                    }), 0 == a.code) {
                        var t = {
                            data: {
                                pic_list: a.data.ad
                            }
                        };
                        o.setData({
                            banner: a.data.banner,
                            ad: a.data.ad,
                            page: a.data.goods.page,
                            page_count: a.data.goods.page_count,
                            block: t
                        });
                    }
                }
            });
        }
        this.setData({
            pt_url: !0
        }), this.loadIndexInfo(this);
    },
    onReady: function(a) {
        getApp().page.onReady(this);
    },
    onShow: function(a) {
        getApp().page.onShow(this);
    },
    onHide: function(a) {
        getApp().page.onHide(this);
    },
    onUnload: function(a) {
        getApp().page.onUnload(this);
    },
    onPullDownRefresh: function(a) {
        getApp().page.onPullDownRefresh(this);
    },
    onReachBottom: function(a) {
        getApp().page.onReachBottom(this);
        var t = this;
        t.setData({
            show_loading_bar: 1
        }), t.data.page < t.data.page_count ? (t.setData({
            page: t.data.page + 1
        }), t.getGoods(t)) : t.setData({
            is_show: 1,
            emptyGoods: 1,
            show_loading_bar: 0
        });
    },
    loadIndexInfo: function(a) {
        var e = a;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.group.index,
            method: "get",
            data: {
                page: e.data.page
            },
            success: function(a) {
                if (0 == a.code) {
                    getApp().core.hideLoading();
                    var t = {
                        data: {
                            pic_list: a.data.ad
                        }
                    };
                    e.setData({
                        cat: a.data.cat,
                        banner: a.data.banner,
                        ad: a.data.ad,
                        goods: a.data.goods.list,
                        page: a.data.goods.page,
                        page_count: a.data.goods.page_count,
                        block: t
                    }), a.data.goods.row_count <= 0 && e.setData({
                        emptyGoods: 1
                    });
                }
            }
        });
    },
    getGoods: function(a) {
        var t = a;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.group.list,
            method: "get",
            data: {
                page: t.data.page,
                cid: t.data.cid
            },
            success: function(a) {
                0 == a.code && (getApp().core.hideLoading(), t.data.goods = t.data.goods.concat(a.data.list), 
                t.setData({
                    goods: t.data.goods,
                    page: a.data.page,
                    page_count: a.data.page_count,
                    show_loading_bar: 0
                }));
            }
        });
    },
    switchNav: function(a) {
        var e = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        });
        var t = a.currentTarget.dataset.id;
        if (e.setData({
            cid: t
        }), "undefined" == typeof my) {
            var o = this.systemInfo.windowWidth, d = a.currentTarget.offsetLeft, p = this.data.scrollLeft;
            p = o / 2 < d ? d : 0, e.setData({
                scrollLeft: p
            });
        } else {
            for (var s = e.data.cat, n = !0, g = 0; g < s.length; ++g) if (s[g].id === a.currentTarget.id) {
                n = !1, 1 <= g ? e.setData({
                    toView: s[g - 1].id
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
            cid: t,
            page: 1,
            scrollTop: 0,
            emptyGoods: 0,
            goods: [],
            show_loading_bar: 1,
            is_show: 0
        }), getApp().request({
            url: getApp().api.group.list,
            method: "get",
            data: {
                cid: t
            },
            success: function(a) {
                if (getApp().core.hideLoading(), 0 == a.code) {
                    var t = a.data.list;
                    a.data.page_count >= a.data.page ? e.setData({
                        goods: t,
                        page: a.data.page,
                        page_count: a.data.page_count,
                        row_count: a.data.row_count,
                        show_loading_bar: 0
                    }) : e.setData({
                        emptyGoods: 1
                    });
                }
            }
        });
    },
    pullDownLoading: function(a) {
        var e = this;
        if (1 != e.data.emptyGoods && 1 != e.data.show_loading_bar) {
            e.setData({
                show_loading_bar: 1
            });
            var t = parseInt(e.data.page + 1), o = e.data.cid;
            getApp().request({
                url: getApp().api.group.list,
                method: "get",
                data: {
                    page: t,
                    cid: o
                },
                success: function(a) {
                    if (0 == a.code) {
                        var t = e.data.goods;
                        a.data.page > e.data.page && Array.prototype.push.apply(t, a.data.list), a.data.page_count >= a.data.page ? e.setData({
                            goods: t,
                            page: a.data.page,
                            page_count: a.data.page_count,
                            row_count: a.data.row_count,
                            show_loading_bar: 0
                        }) : e.setData({
                            emptyGoods: 1
                        });
                    }
                }
            });
        }
    },
    navigatorClick: function(a) {
        var t = a.currentTarget.dataset.open_type, e = a.currentTarget.dataset.url;
        return "wxapp" != t || ((e = function(a) {
            var t = /([^&=]+)=([\w\W]*?)(&|$|#)/g, e = /^[^\?]+\?([\w\W]+)$/.exec(a), o = {};
            if (e && e[1]) for (var d, p = e[1]; null != (d = t.exec(p)); ) o[d[1]] = d[2];
            return o;
        }(e)).path = e.path ? decodeURIComponent(e.path) : "", getApp().core.navigateToMiniProgram({
            appId: e.appId,
            path: e.path,
            complete: function(a) {}
        }), !1);
    },
    to_dial: function() {
        var a = this.data.store.contact_tel;
        getApp().core.makePhoneCall({
            phoneNumber: a
        });
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        return {
            path: "/pages/pt/index/index?user_id=" + this.data.__user_info.id,
            success: function(a) {}
        };
    }
});