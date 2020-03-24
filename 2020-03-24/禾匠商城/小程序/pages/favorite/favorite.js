Page({
    data: {
        swiper_current: 0,
        goods: {
            list: null,
            is_more: !0,
            is_loading: !1,
            page: 1
        },
        topic: {
            list: null,
            is_more: !0,
            is_loading: !1,
            page: 1
        }
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t), this.loadGoodsList({
            reload: !0,
            page: 1
        }), this.loadTopicList({
            reload: !0,
            page: 1
        });
    },
    tabSwitch: function(t) {
        var a = t.currentTarget.dataset.index;
        this.setData({
            swiper_current: a
        });
    },
    swiperChange: function(t) {
        this.setData({
            swiper_current: t.detail.current
        });
    },
    loadGoodsList: function(a) {
        var o = this;
        o.data.goods.is_loading || a.loadmore && !o.data.goods.is_more || (o.data.goods.is_loading = !0, 
        o.setData({
            goods: o.data.goods
        }), getApp().request({
            url: getApp().api.user.favorite_list,
            data: {
                page: a.page
            },
            success: function(t) {
                0 == t.code && (a.reload && (o.data.goods.list = t.data.list), a.loadmore && (o.data.goods.list = o.data.goods.list.concat(t.data.list)), 
                o.data.goods.page = a.page, o.data.goods.is_more = 0 < t.data.list.length, o.setData({
                    goods: o.data.goods
                }));
            },
            complete: function() {
                o.data.goods.is_loading = !1, o.setData({
                    goods: o.data.goods
                });
            }
        }));
    },
    goodsScrollBottom: function() {
        this.loadGoodsList({
            loadmore: !0,
            page: this.data.goods.page + 1
        });
    },
    loadTopicList: function(a) {
        var o = this;
        o.data.topic.is_loading || a.loadmore && !o.data.topic.is_more || (o.data.topic.is_loading = !0, 
        o.setData({
            topic: o.data.topic
        }), getApp().request({
            url: getApp().api.user.topic_favorite_list,
            data: {
                page: a.page
            },
            success: function(t) {
                0 == t.code && (a.reload && (o.data.topic.list = t.data.list), a.loadmore && (o.data.topic.list = o.data.topic.list.concat(t.data.list)), 
                o.data.topic.page = a.page, o.data.topic.is_more = 0 < t.data.list.length, o.setData({
                    topic: o.data.topic
                }));
            },
            complete: function() {
                o.data.topic.is_loading = !1, o.setData({
                    topic: o.data.topic
                });
            }
        }));
    },
    topicScrollBottom: function() {
        this.loadTopicList({
            loadmore: !0,
            page: this.data.topic.page + 1
        });
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
    onReachBottom: function(t) {
        getApp().page.onReachBottom(this);
        this.loadMoreGoodsList();
    }
});