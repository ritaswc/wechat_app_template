module.exports = {
    currentPage: null,
    init: function(o) {
        var t = this;
        void 0 === (t.currentPage = o).goods_recommend && (o.goods_recommend = function(o) {
            t.goods_recommend(o);
        });
    },
    goods_recommend: function(a) {
        var e = this.currentPage;
        e.setData({
            is_loading: !0
        });
        var d = e.data.page || 2;
        getApp().request({
            url: getApp().api.default.goods_recommend,
            data: {
                goods_id: a.goods_id,
                page: d
            },
            success: function(o) {
                if (0 == o.code) {
                    if (a.reload) var t = o.data.list;
                    if (a.loadmore) t = e.data.goods_list.concat(o.data.list);
                    e.data.drop = !0, e.setData({
                        goods_list: t
                    }), e.setData({
                        page: d + 1
                    });
                }
            },
            complete: function() {
                e.setData({
                    is_loading: !1
                });
            }
        });
    }
};