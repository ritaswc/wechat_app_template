var is_loading = !1, is_no_more = !1;

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
        t.user_id;
        getApp().core.getLocation({
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
    onShow: function() {
        getApp().page.onShow(this);
    },
    loadData: function() {
        var e = this;
        getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.default.shop_list,
            method: "GET",
            data: {
                longitude: e.data.longitude,
                latitude: e.data.latitude
            },
            success: function(t) {
                0 == t.code && e.setData(t.data);
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    onPullDownRefresh: function() {
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
    onReachBottom: function() {
        var t = this;
        t.data.page >= t.data.page_count || t.loadMoreData();
    },
    loadMoreData: function() {
        var a = this, o = a.data.page;
        is_loading || (is_loading = !0, getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.default.shop_list,
            method: "GET",
            data: {
                page: o,
                longitude: a.data.longitude,
                latitude: a.data.latitude
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
        var e = this.data.list, a = t.currentTarget.dataset.index;
        getApp().core.openLocation({
            latitude: parseFloat(e[a].latitude),
            longitude: parseFloat(e[a].longitude),
            name: e[a].name,
            address: e[a].address
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
            url: getApp().api.default.shop_list,
            method: "GET",
            data: {
                keyword: e.data.keyword,
                longitude: e.data.longitude,
                latitude: e.data.latitude
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
            if (a && a[1]) for (var n, i = a[1]; null != (n = e.exec(i)); ) o[n[1]] = n[2];
            return o;
        }(a)).path = a.path ? decodeURIComponent(a.path) : "", getApp().core.navigateToMiniProgram({
            appId: a.appId,
            path: a.path,
            complete: function(t) {}
        }), !1);
    },
    onShareAppMessage: function(t) {
        return getApp().page.onShareAppMessage(this), {
            path: "/pages/shop/shop?user_id=" + getApp().core.getStorageSync(getApp().const.USER_INFO).id
        };
    }
});