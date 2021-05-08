Page({
    data: {
        number: 0,
        _num: 1,
        page: 2,
        list: [],
        over: !1
    },
    tab: function(t) {
        var e = this, s = t.target.dataset.num;
        getApp().core.showLoading({
            title: "数据加载中...",
            mask: !0
        }), getApp().request({
            url: getApp().api.step.log,
            data: {
                status: s
            },
            success: function(t) {
                getApp().core.hideLoading();
                var a = t.data.log;
                e.setData({
                    number: t.data.user.step_currency,
                    list: a,
                    _num: s,
                    page: 2
                });
            }
        });
    },
    onReachBottom: function() {
        var e = this, s = e.data.over;
        if (!s) {
            this.data.id;
            var p = this.data.list, t = this.data._num, o = this.data.page;
            this.setData({
                loading: !0
            }), getApp().request({
                url: getApp().api.step.log,
                data: {
                    status: t,
                    page: o
                },
                success: function(t) {
                    for (var a = 0; a < t.data.log.length; a++) p.push(t.data.log[a]);
                    t.data.log.length < 6 && (s = !0), e.setData({
                        list: p,
                        page: o + 1,
                        loading: !1,
                        over: s
                    });
                }
            });
        }
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var e = this;
        getApp().core.showLoading({
            title: "数据加载中...",
            mask: !0
        }), getApp().request({
            url: getApp().api.step.log,
            data: {
                status: 1,
                page: 1
            },
            success: function(t) {
                getApp().core.hideLoading();
                var a = t.data.log;
                e.setData({
                    number: t.data.user.step_currency,
                    list: a
                });
            }
        });
    }
});