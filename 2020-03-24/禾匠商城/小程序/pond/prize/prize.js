Page({
    data: {
        args: !1,
        page: 1
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
    },
    onShow: function() {
        getApp().page.onShow(this);
        var a = this;
        getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.pond.prize,
            data: {
                page: 1
            },
            success: function(t) {
                0 != t.code || a.setData({
                    list: t.data
                });
            },
            complete: function(t) {
                getApp().core.hideLoading();
            }
        });
    },
    onReachBottom: function() {
        getApp().page.onReachBottom(this);
        var a = this;
        if (!a.data.args) {
            var e = a.data.page + 1;
            getApp().request({
                url: getApp().api.pond.prize,
                data: {
                    page: e
                },
                success: function(t) {
                    0 == t.code ? a.setData({
                        list: a.data.list.concat(t.data),
                        page: e
                    }) : a.data.args = !0;
                }
            });
        }
    },
    submit: function(t) {
        var a = t.currentTarget.dataset.gift, e = JSON.parse(t.currentTarget.dataset.attr), o = t.currentTarget.dataset.id;
        getApp().core.navigateTo({
            url: "/pages/order-submit/order-submit?pond_id=" + o + "&goods_info=" + JSON.stringify({
                goods_id: a,
                attr: e,
                num: 1
            })
        });
    }
});