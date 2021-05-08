var is_loading = !1, is_no_more = !0, interval_list = !1;

Page({
    data: {
        p: 1,
        naver: "index"
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
    },
    onShow: function() {
        getApp().page.onShow(this), getApp().core.showLoading({
            title: "加载中"
        });
        var e = this;
        e.data.p = 1, getApp().request({
            url: getApp().api.lottery.index,
            success: function(t) {
                if (0 == t.code) {
                    e.setData(t.data), null != t.data.new_list && 0 < t.data.new_list.length && (is_no_more = !1);
                    var i = [];
                    t.data.new_list.forEach(function(t, e, a) {
                        i.push(t.end_time);
                    }), e.setTimeStart(i);
                }
            },
            complete: function(t) {
                getApp().core.hideLoading();
            }
        }), getApp().request({
            url: getApp().api.lottery.setting,
            success: function(t) {
                if (0 == t.code) {
                    var e = t.data.title;
                    e && getApp().core.setNavigationBarTitle({
                        title: e
                    });
                }
            }
        });
    },
    onHide: function() {
        getApp().page.onHide(this), clearInterval(interval_list);
    },
    onUnload: function() {
        getApp().page.onUnload(this), clearInterval(interval_list);
    },
    setTimeStart: function(t) {
        var e = this, d = [];
        clearInterval(interval_list), interval_list = setInterval(function() {
            t.forEach(function(t, e, a) {
                var i = new Date(), n = parseInt(t - i.getTime() / 1e3);
                if (0 < n) var o = Math.floor(n / 86400), r = Math.floor(n / 3600) - 24 * o, s = Math.floor(n / 60) - 24 * o * 60 - 60 * r, l = Math.floor(n) - 24 * o * 60 * 60 - 60 * r * 60 - 60 * s;
                var p = {
                    day: o,
                    hour: r,
                    minute: s,
                    second: l
                };
                d[e] = p;
            }), e.setData({
                time_list: d
            });
        }, 1e3);
    },
    submit: function(t) {
        var e = t.detail.formId, a = t.currentTarget.dataset.lottery_id;
        getApp().core.navigateTo({
            url: "/lottery/detail/detail?lottery_id=" + a + "&form_id=" + e
        });
    },
    onReachBottom: function() {
        is_no_more || this.loadData();
    },
    loadData: function() {
        if (!is_loading) {
            is_loading = !0, getApp().core.showLoading({
                title: "加载中"
            });
            var a = this, n = a.data.p + 1;
            getApp().request({
                url: getApp().api.lottery.index,
                data: {
                    page: n
                },
                success: function(t) {
                    if (0 == t.code) {
                        var e = a.data.new_list;
                        if (null == t.data.new_list || 0 == t.data.new_list.length) return void (is_no_more = !0);
                        e = e.concat(t.data.new_list), a.setData({
                            new_list: e,
                            p: n
                        });
                        var i = [];
                        e.forEach(function(t, e, a) {
                            i.push(t.end_time);
                        }), a.setTimeStart(i);
                    } else a.showToast({
                        title: t.msg
                    });
                },
                complete: function(t) {
                    getApp().core.hideLoading(), is_loading = !1;
                }
            });
        }
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        return {
            path: "lottery/index/index?user_id=" + this.data.__user_info.id,
            success: function(t) {}
        };
    }
});