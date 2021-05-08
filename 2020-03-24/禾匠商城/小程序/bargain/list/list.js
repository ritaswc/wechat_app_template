var app = getApp(), api = getApp().api, is_loading = !1, is_no_more = !0;

Page({
    data: {
        p: 1,
        naver: "list"
    },
    onLoad: function(o) {
        app.page.onLoad(this, o);
        void 0 !== o.order_id && getApp().core.navigateTo({
            url: "/bargain/activity/activity?order_id=" + o.order_id + "&user_id=" + o.user_id
        }), void 0 !== o.goods_id && getApp().core.navigateTo({
            url: "/bargain/goods/goods?goods_id=" + o.goods_id + "&user_id=" + o.user_id
        }), this.loadDataFirst(o);
    },
    loadDataFirst: function(t) {
        var a = this;
        getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.bargain.index,
            type: "get",
            success: function(o) {
                0 == o.code && (a.setData(o.data), a.setData({
                    p: 2
                }), 0 < o.data.goods_list.length && (is_no_more = !1));
            },
            complete: function(o) {
                void 0 === t.order_id && getApp().core.hideLoading(), getApp().core.stopPullDownRefresh();
            }
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
    onPullDownRefresh: function() {
        this.loadDataFirst({});
    },
    onReachBottom: function() {
        is_no_more || this.loadData();
    },
    loadData: function() {
        if (!is_loading) {
            is_loading = !0, getApp().core.showLoading({
                title: "加载中"
            });
            var a = this, i = a.data.p;
            app.request({
                url: api.bargain.index,
                data: {
                    page: i
                },
                success: function(o) {
                    if (0 == o.code) {
                        var t = a.data.goods_list;
                        0 == o.data.goods_list.length && (is_no_more = !0), t = t.concat(o.data.goods_list), 
                        a.setData({
                            goods_list: t,
                            p: i + 1
                        });
                    } else a.showToast({
                        title: o.msg
                    });
                },
                complete: function(o) {
                    getApp().core.hideLoading(), is_loading = !1;
                }
            });
        }
    },
    goToGoods: function(o) {
        var t = o.currentTarget.dataset.index;
        getApp().core.navigateTo({
            url: "/bargain/goods/goods?goods_id=" + t
        });
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        return {
            path: "/bargain/list/list?user_id=" + this.data.__user_info.id,
            success: function(o) {}
        };
    }
});