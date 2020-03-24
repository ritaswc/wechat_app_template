Page({
    data: {
        friend: !0,
        country: !1,
        avatar: "",
        name: "",
        noun: "",
        bg: "../image/topBG.png",
        id: 1,
        page: 2,
        money: "",
        loading: !1,
        unit_id: "",
        list: [],
        over: !1,
        ad: !1
    },
    adError: function(a) {
        console.log(a.detail);
    },
    onLoad: function(a) {
        getApp().page.onLoad(this, a);
        var p = this;
        this.data.list;
        getApp().core.showLoading({
            title: "数据加载中...",
            mask: !0
        }), getApp().request({
            url: getApp().api.step.ranking,
            data: {
                status: 1,
                page: 1
            },
            success: function(a) {
                getApp().core.hideLoading();
                var t = a.data.user, e = a.data.list, i = a.data.ad_data;
                if (3 < e.length) {
                    for (var n = 3; n < e.length; n++) e[n].noun = n + 1;
                    e[0].img = "../image/top1.png", e[1].img = "../image/top2.png", e[2].img = "../image/top3.png";
                } else 0 < e.length && e.length <= 3 && (e[0].img = "../image/top1.png", 1 < e.length && (e[1].img = "../image/top2.png"), 
                2 < e.length && (e[2].img = "../image/top3.png"));
                var g = !1, o = !1;
                null !== a.data.ad_data && (g = a.data.ad_data.unit_id, o = !0), p.setData({
                    list: e,
                    name: t.user.nickname,
                    avatar: t.user.avatar_url,
                    noun: t.raking,
                    money: t.step_currency,
                    unit_id: g,
                    ad_data: i,
                    ad: o
                });
            }
        });
    },
    onReachBottom: function() {
        var i = this, n = i.data.over;
        if (!n) {
            var g = this.data.id, o = this.data.list, p = this.data.page;
            this.setData({
                loading: !0
            }), getApp().request({
                url: getApp().api.step.ranking,
                data: {
                    status: g,
                    page: p
                },
                success: function(a) {
                    var t = a.data.list;
                    o = o.concat(t), this.data.loading = !1;
                    for (var e = 10 * (p - 1); e < o.length; e++) o[e].noun = e + 1;
                    t.length < 10 && (n = !0), i.setData({
                        list: o,
                        id: g,
                        page: p + 1,
                        loading: !1,
                        over: n
                    });
                }
            });
        }
    },
    change: function(a) {
        getApp().core.showLoading({
            title: "数据加载中...",
            mask: !0
        });
        var n = a.target.id, g = void 0, o = void 0, p = this;
        this.data.list;
        1 == n ? o = !(g = !0) : 2 == n && (o = !(g = !1)), getApp().request({
            url: getApp().api.step.ranking,
            data: {
                status: n
            },
            success: function(a) {
                getApp().core.hideLoading();
                var t = a.data.user, e = a.data.list;
                if (3 < e.length) {
                    for (var i = 3; i < e.length; i++) e[i].noun = i + 1;
                    e[0].img = "../image/top1.png", e[1].img = "../image/top2.png", e[2].img = "../image/top3.png";
                } else 0 < e.length && e.length <= 3 && (e[0].img = "../image/top1.png", 1 < e.length && (e[1].img = "../image/top2.png"), 
                2 < e.length && (e[2].img = "../image/top3.png"));
                p.setData({
                    list: e,
                    id: n,
                    name: t.user.nickname,
                    avatar: t.user.avatar_url,
                    noun: t.raking,
                    money: t.step_currency,
                    friend: g,
                    page: 2,
                    over: !1,
                    country: o
                });
            }
        });
    }
});