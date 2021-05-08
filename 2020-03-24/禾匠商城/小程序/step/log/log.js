var util = require("../../utils/helper.js"), utils = getApp().helper;

Page({
    data: {
        currency: 0,
        bout_ratio: 0,
        total_bout: 0,
        bout: 0,
        page: 2,
        list: [ {
            name: ""
        }, {
            step_num: 0
        }, {
            user_currency: 0
        }, {
            user_num: 0
        }, {
            status: 0
        } ]
    },
    onReachBottom: function() {
        var e = this, i = e.data.over;
        if (!i) {
            var o = this.data.list, r = this.data.page;
            this.setData({
                loading: !0
            }), getApp().request({
                url: getApp().api.step.activity_log,
                data: {
                    page: r
                },
                success: function(t) {
                    for (var a = 0; a < t.data.list.length; a++) o.push(t.data.list[a]);
                    t.data.list.length < 10 && (i = !0), e.setData({
                        list: o,
                        page: r + 1,
                        loading: !1,
                        over: i
                    });
                }
            });
        }
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var r = this, a = util.formatTime(new Date()), s = a[0] + a[1] + a[2] + a[3] + a[5] + a[6] + a[8] + a[9];
        getApp().core.showLoading({
            title: "数据加载中...",
            mask: !0
        }), getApp().request({
            url: getApp().api.step.activity_log,
            success: function(t) {
                getApp().core.hideLoading();
                var a = t.data.info, e = 0;
                0 < a.currency && (e = a.currency);
                for (var i = t.data.list, o = 0; o < i.length; o++) null != i[o].open_date && (i[o].date = i[o].open_date.replace("-", "").replace("-", ""));
                r.setData({
                    currency: e,
                    bout_ratio: a.bout_ratio,
                    total_bout: a.total_bout,
                    bout: a.bout,
                    time: s,
                    list: i
                });
            }
        });
    }
});