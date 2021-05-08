function secondToTimeStr(t) {
    if (t < 60) return "00:00:" + ((i = t) < 10 ? "0" + i : i);
    if (t < 3600) return "00:" + ((a = parseInt(t / 60)) < 10 ? "0" + a : a) + ":" + ((i = t % 60) < 10 ? "0" + i : i);
    if (3600 <= t) {
        var a, i, e = parseInt(t / 3600);
        return (e < 10 ? "0" + e : e) + ":" + ((a = parseInt(t % 3600 / 60)) < 10 ? "0" + a : a) + ":" + ((i = t % 60) < 10 ? "0" + i : i);
    }
}

Page({
    data: {
        time_list: null,
        goods_list: null,
        page: 1,
        loading_more: !1,
        status: !0
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t), this.loadData(t);
    },
    quickNavigation: function() {
        this.setData({
            quick_icon: !this.data.quick_icon
        });
        this.data.store;
        var t = getApp().core.createAnimation({
            duration: 300,
            timingFunction: "ease-out"
        });
        this.data.quick_icon ? t.opacity(0).step() : t.translateY(-55).opacity(1).step(), 
        this.setData({
            animationPlus: t.export()
        });
    },
    loadData: function(t) {
        var a = this;
        getApp().request({
            url: getApp().api.miaosha.list,
            success: function(t) {
                if (0 == t.code) if (0 == t.data.list.length) {
                    if (0 == t.data.next_list.length) return void getApp().core.showModal({
                        content: "暂无秒杀活动",
                        showCancel: !1,
                        confirmText: "返回首页",
                        success: function(t) {
                            t.confirm && getApp().core.navigateBack({
                                url: "/pages/index/index"
                            });
                        }
                    });
                    a.setData({
                        goods_list: t.data.next_list.list,
                        ms_active: !0,
                        time_list: t.data.list,
                        next_list: t.data.next_list.list,
                        next_time: t.data.next_list.time
                    });
                } else a.setData({
                    time_list: t.data.list,
                    next_list: "" == t.data.next_list ? [] : t.data.next_list.list,
                    next_time: "" == t.data.next_list ? [] : t.data.next_list.time,
                    ms_active: !1
                }), a.topBarScrollCenter(), a.setTimeOver(), a.loadGoodsList(!1);
                1 == t.code && getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    success: function() {
                        getApp().core.navigateBack({
                            url: "/pages/index/index"
                        });
                    },
                    showCancel: !1
                });
            }
        });
    },
    setTimeOver: function() {
        var e = this;
        function t() {
            for (var t in e.data.time_list) {
                var a = e.data.time_list[t].begin_time - e.data.time_list[t].now_time, i = e.data.time_list[t].end_time - e.data.time_list[t].now_time;
                a = 0 < a ? a : 0, i = 0 < i ? i : 0, e.data.time_list[t].begin_time_over = secondToTimeStr(a), 
                e.data.time_list[t].end_time_over = secondToTimeStr(i), e.data.time_list[t].now_time = e.data.time_list[t].now_time + 1;
            }
            e.setData({
                time_list: e.data.time_list
            });
        }
        t(), setInterval(function() {
            t();
        }, 1e3);
    },
    miaosha_next: function() {
        var t = this, e = t.data.time_list;
        e.forEach(function(t, a, i) {
            e[a].active = !1;
        }), t.setData({
            goods_list: null,
            ms_active: !0,
            time_list: e
        }), setTimeout(function() {
            t.setData({
                goods_list: t.data.next_list
            });
        }, 500);
    },
    topBarScrollCenter: function() {
        var t = this, a = 0;
        for (var i in t.data.time_list) if (t.data.time_list[i].active) {
            a = i;
            break;
        }
        t.setData({
            top_bar_scroll: 2 <= a ? a - 2 : 0
        });
    },
    topBarItemClick: function(t) {
        var a = this, i = t.currentTarget.dataset.index;
        for (var e in a.data.time_list) a.data.time_list[e].active = i == e;
        a.setData({
            time_list: a.data.time_list,
            loading_more: !1,
            page: 1,
            ms_active: !1
        }), a.topBarScrollCenter(), a.loadGoodsList(!1);
    },
    loadGoodsList: function(a) {
        var i = this, t = !1;
        for (var e in i.data.time_list) {
            if (i.data.time_list[e].active) {
                t = i.data.time_list[e].start_time;
                break;
            }
            i.data.time_list.length == parseInt(e) + 1 && 0 == t && (t = i.data.time_list[0].start_time, 
            i.data.time_list[0].active = !0);
        }
        a ? i.setData({
            loading_more: !0
        }) : i.setData({
            goods_list: null
        }), getApp().request({
            url: getApp().api.miaosha.goods_list,
            data: {
                time: t,
                page: i.data.page
            },
            success: function(t) {
                0 == t.code && (i.data.goods_list = a ? i.data.goods_list.concat(t.data.list) : t.data.list, 
                i.setData({
                    loading_more: !1,
                    goods_list: i.data.goods_list,
                    page: t.data.list && 0 != t.data.list.length ? i.data.page + 1 : -1
                }));
            }
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
    onPullDownRefresh: function(t) {
        getApp().page.onPullDownRefresh(this);
    },
    onReachBottom: function(t) {
        getApp().page.onReachBottom(this);
        -1 != this.data.page && this.loadGoodsList(!0);
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        return {
            path: "/pages/miaosha/miaosha?user_id=" + this.data.__user_info.id,
            success: function(t) {}
        };
    }
});