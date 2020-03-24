Page({
    data: {
        page_num: 1,
        is_loading: !1
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var i = this;
        getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.lottery.detail,
            data: {
                id: t.id ? t.id : 0,
                lottery_id: t.lottery_id ? t.lottery_id : 0,
                form_id: t.form_id,
                page_num: 1
            },
            success: function(t) {
                0 == t.code && i.setData(t.data);
            },
            complete: function(t) {
                getApp().core.hideLoading();
            }
        }), getApp().request({
            url: getApp().api.lottery.setting,
            success: function(t) {
                if (0 == t.code) {
                    var e = t.data.title;
                    e && (getApp().core.setNavigationBarTitle({
                        title: e
                    }), i.setData({
                        title: e
                    }));
                }
            }
        });
    },
    submit: function(t) {
        var e = this.data.list.goods_id, i = JSON.parse(this.data.list.attr);
        getApp().core.navigateTo({
            url: "/pages/order-submit/order-submit?lottery_id=" + this.data.list.id + "&goods_info=" + JSON.stringify({
                goods_id: e,
                attr: i,
                num: 1
            })
        });
    },
    userload: function() {
        var e = this;
        if (!e.data.is_loading) {
            e.data;
            var i = e.data.page_num + 1;
            getApp().core.showLoading({
                title: "加载中"
            }), getApp().request({
                url: getApp().api.lottery.detail,
                data: {
                    id: this.data.list.id,
                    page_num: i
                },
                success: function(t) {
                    if (0 == t.code) {
                        if (null == t.data.user_list || 0 == t.data.user_list) return void e.setData({
                            is_loading: !0
                        });
                        e.setData({
                            user_list: e.data.user_list.concat(t.data.user_list),
                            page_num: i
                        });
                    }
                },
                complete: function(t) {
                    getApp().core.hideLoading();
                }
            });
        }
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this), getApp().core.hideLoading();
        var t = getApp().getUser(), e = this.data.list.lottery_id;
        return {
            path: "/lottery/goods/goods?user_id=" + t.id + "&id=" + e,
            title: this.data.title ? this.data.title : "抽奖"
        };
    }
});