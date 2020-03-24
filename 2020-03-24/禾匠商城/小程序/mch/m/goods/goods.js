var app = getApp(), api = getApp().api;

Page({
    data: {
        status: 1,
        goods_list: [],
        no_goods: !1,
        no_more_goods: !1
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var o = this;
        o.setData({
            status: parseInt(t.status || 1),
            loading_more: !0
        }), o.loadGoodsList(function() {
            o.setData({
                loading_more: !1
            });
        });
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
    onReachBottom: function(t) {
        var o = this;
        o.data.loading_more || (o.setData({
            loading_more: !0
        }), o.loadGoodsList(function() {
            o.setData({
                loading_more: !1
            });
        }));
    },
    searchSubmit: function(t) {
        var o = this, a = t.detail.value;
        o.setData({
            keyword: a,
            loading_more: !0,
            goods_list: [],
            current_page: 0
        }), o.loadGoodsList(function() {
            o.setData({
                loading_more: !1
            });
        });
    },
    loadGoodsList: function(t) {
        var o = this;
        if (o.data.no_goods || o.data.no_more_goods) "function" == typeof t && t(); else {
            var a = (o.data.current_page || 0) + 1;
            getApp().request({
                url: getApp().api.mch.goods.list,
                data: {
                    page: a,
                    status: o.data.status,
                    keyword: o.data.keyword || ""
                },
                success: function(t) {
                    0 == t.code && (1 != a || t.data.list && t.data.list.length || o.setData({
                        no_goods: !0
                    }), t.data.list && t.data.list.length ? (o.data.goods_list = o.data.goods_list || [], 
                    o.data.goods_list = o.data.goods_list.concat(t.data.list), o.setData({
                        goods_list: o.data.goods_list,
                        current_page: a
                    })) : o.setData({
                        no_more_goods: !0
                    }));
                },
                complete: function() {
                    "function" == typeof t && t();
                }
            });
        }
    },
    showMoreAlert: function(o) {
        var a = this;
        setTimeout(function() {
            var t = o.currentTarget.dataset.index;
            a.data.goods_list[t].show_alert = !0, a.setData({
                goods_list: a.data.goods_list
            });
        }, 50);
    },
    hideMoreAlert: function(t) {
        var o = this, a = !1;
        for (var s in o.data.goods_list) o.data.goods_list[s].show_alert && (a = !(o.data.goods_list[s].show_alert = !1));
        a && setTimeout(function() {
            o.setData({
                goods_list: o.data.goods_list
            });
        }, 100);
    },
    setGoodsStatus: function(t) {
        var o = this, a = t.currentTarget.dataset.targetStatus, s = t.currentTarget.dataset.index;
        getApp().core.showLoading({
            title: "正在处理",
            mask: !0
        }), getApp().request({
            url: getApp().api.mch.goods.set_status,
            data: {
                id: o.data.goods_list[s].id,
                status: a
            },
            success: function(t) {
                0 == t.code && (o.data.goods_list[s].status = t.data.status, o.setData({
                    goods_list: o.data.goods_list
                })), o.showToast({
                    title: t.msg,
                    duration: 1500
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    goodsDelete: function(t) {
        var o = this, a = t.currentTarget.dataset.index;
        getApp().core.showModal({
            title: "警告",
            content: "确认删除该商品？",
            success: function(t) {
                t.confirm && (getApp().core.showLoading({
                    title: "正在处理",
                    mask: !0
                }), getApp().request({
                    url: getApp().api.mch.goods.delete,
                    data: {
                        id: o.data.goods_list[a].id
                    },
                    success: function(t) {
                        o.showToast({
                            title: t.msg
                        }), 0 == t.code && (o.data.goods_list.splice(a, 1), o.setData({
                            goods_list: o.data.goods_list
                        }));
                    },
                    complete: function() {
                        getApp().core.hideLoading();
                    }
                }));
            }
        });
    }
});