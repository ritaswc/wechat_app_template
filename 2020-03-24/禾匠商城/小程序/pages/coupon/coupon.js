Page({
    data: {
        list: []
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t), this.setData({
            status: t.status || 0
        }), this.loadData(t);
    },
    loadData: function(t) {
        var a = this;
        getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.coupon.index,
            data: {
                status: a.data.status
            },
            success: function(t) {
                0 == t.code && a.setData({
                    list: t.data.list
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    goodsList: function(t) {
        var a = t.currentTarget.dataset.goods_id, s = t.currentTarget.dataset.id, e = this.data.list;
        for (var i in e) if (parseInt(e[i].user_coupon_id) === parseInt(s)) return void (2 == e[i].appoint_type && 0 < e[i].goods.length && getApp().core.navigateTo({
            url: "/pages/list/list?goods_id=" + a
        }));
    },
    xia: function(t) {
        var a = t.target.dataset.index;
        this.setData({
            check: a
        });
    },
    shou: function() {
        this.setData({
            check: -1
        });
    }
});