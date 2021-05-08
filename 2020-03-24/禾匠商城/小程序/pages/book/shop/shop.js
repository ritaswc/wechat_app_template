var utils = require("../../../utils/helper.js"), is_loading = !1, is_no_more = !1;

Page({
    data: {
        page: 1,
        page_count: 1,
        longitude: "",
        latitude: "",
        score: [ 1, 2, 3, 4, 5 ],
        keyword: ""
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var e = this;
        e.setData({
            ids: t.ids
        }), getApp().core.getLocation({
            success: function(t) {
                e.setData({
                    longitude: t.longitude,
                    latitude: t.latitude
                });
            },
            complete: function() {
                e.loadData();
            }
        });
    },
    onReady: function(t) {
        getApp().page.onReady(this);
    },
    onShow: function(t) {
        getApp().page.onShow(this);
    },
    loadData: function() {
        var e = this;
        getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.book.shop_list,
            method: "GET",
            data: {
                longitude: e.data.longitude,
                latitude: e.data.latitude,
                ids: e.data.ids
            },
            success: function(t) {
                0 == t.code && e.setData(t.data);
            },
            fail: function(t) {},
            complete: function() {
                getApp().core.hideLoading();
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
        var e = this;
        e.setData({
            keyword: "",
            page: 1
        }), getApp().core.getLocation({
            success: function(t) {
                e.setData({
                    longitude: t.longitude,
                    latitude: t.latitude
                });
            },
            complete: function() {
                e.loadData(), getApp().core.stopPullDownRefresh();
            }
        });
    },
    onReachBottom: function(t) {
        getApp().page.onReachBottom(this);
        var e = this;
        e.data.page >= e.data.page_count || e.loadMoreData();
    },
    loadMoreData: function() {
        var a = this, o = a.data.page;
        is_loading || (is_loading = !0, getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.book.shop_list,
            method: "GET",
            data: {
                page: o,
                longitude: a.data.longitude,
                latitude: a.data.latitude,
                ids: a.data.ids
            },
            success: function(t) {
                if (0 == t.code) {
                    var e = a.data.list.concat(t.data.list);
                    a.setData({
                        list: e,
                        page_count: t.data.page_count,
                        row_count: t.data.row_count,
                        page: o + 1
                    });
                }
            },
            complete: function() {
                getApp().core.hideLoading(), is_loading = !1;
            }
        }));
    },
    goto: function(e) {
        var a = this;
        "undefined" != typeof my ? a.location(e) : getApp().core.getSetting({
            success: function(t) {
                t.authSetting["scope.userLocation"] ? a.location(e) : getApp().getauth({
                    content: "需要获取您的地理位置授权，请到小程序设置中打开授权！",
                    cancel: !1,
                    author: "scope.userLocation",
                    success: function(t) {
                        t.authSetting["scope.userLocation"] && a.location(e);
                    }
                });
            }
        });
    },
    location: function(t) {
        var e = t.currentTarget.dataset.index, a = this.data.list;
        getApp().core.openLocation({
            latitude: parseFloat(a[e].latitude),
            longitude: parseFloat(a[e].longitude),
            name: a[e].name,
            address: a[e].address
        });
    },
    inputFocus: function(t) {
        this.setData({
            show: !0
        });
    },
    inputBlur: function(t) {
        this.setData({
            show: !1
        });
    },
    inputConfirm: function(t) {
        this.search();
    },
    input: function(t) {
        this.setData({
            keyword: t.detail.value
        });
    },
    search: function(t) {
        var e = this;
        getApp().core.showLoading({
            title: "搜索中"
        }), getApp().request({
            url: getApp().api.book.shop_list,
            method: "GET",
            data: {
                keyword: e.data.keyword,
                longitude: e.data.longitude,
                latitude: e.data.latitude,
                ids: e.data.ids
            },
            success: function(t) {
                0 == t.code && e.setData(t.data);
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    go: function(t) {
        var e = t.currentTarget.dataset.index, a = this.data.list;
        getApp().core.navigateTo({
            url: "/pages/shop-detail/shop-detail?shop_id=" + a[e].id
        });
    },
    navigatorClick: function(t) {
        var e = t.currentTarget.dataset.open_type, a = t.currentTarget.dataset.url;
        return "wxapp" != e || ((a = function(t) {
            var e = /([^&=]+)=([\w\W]*?)(&|$|#)/g, a = /^[^\?]+\?([\w\W]+)$/.exec(t), o = {};
            if (a && a[1]) for (var i, n = a[1]; null != (i = e.exec(n)); ) o[i[1]] = i[2];
            return o;
        }(a)).path = a.path ? decodeURIComponent(a.path) : "", getApp().core.navigateToMiniProgram({
            appId: a.appId,
            path: a.path,
            complete: function(t) {}
        }), !1);
    }
});