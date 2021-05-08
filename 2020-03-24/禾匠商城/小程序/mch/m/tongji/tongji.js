var app = getApp(), api = getApp().api;

Page({
    data: {
        current_year: "",
        current_month: "",
        month_scroll_x: 1e5,
        year_list: [],
        daily_avg: "-",
        month_count: "-",
        up_rate: "-"
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var a = this;
        getApp().core.showNavigationBarLoading(), getApp().request({
            url: getApp().api.mch.user.tongji_year_list,
            success: function(t) {
                a.setData({
                    year_list: t.data.year_list,
                    current_year: t.data.current_year,
                    current_month: t.data.current_month
                }), a.setMonthScroll(), a.getMonthData();
            },
            complete: function() {
                getApp().core.hideNavigationBarLoading();
            }
        });
    },
    onReady: function() {
        getApp().page.onReady(this);
    },
    onShow: function() {
        getApp().page.onShow(this);
    },
    onHide: function() {
        getApp().page.onHide(this);
    },
    onUnload: function() {
        getApp().page.onUnload(this);
    },
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    changeMonth: function(t) {
        var a = this, e = t.currentTarget.dataset.yearIndex, r = t.currentTarget.dataset.monthIndex;
        for (var n in a.data.year_list) for (var o in n == e ? (a.data.year_list[n].active = !0, 
        a.data.current_year = a.data.year_list[n].year) : a.data.year_list[n].active = !1, 
        a.data.year_list[n].month_list) n == e && o == r ? (a.data.year_list[n].month_list[o].active = !0, 
        a.data.current_month = a.data.year_list[n].month_list[o].month) : a.data.year_list[n].month_list[o].active = !1;
        a.setData({
            year_list: a.data.year_list,
            current_year: a.data.current_year
        }), a.setMonthScroll(), a.getMonthData();
    },
    setMonthScroll: function() {
        var t = this, a = getApp().core.getSystemInfoSync().screenWidth / 5, e = 0;
        for (var r in t.data.year_list) {
            var n = !1;
            for (var o in t.data.year_list[r].month_list) {
                if (t.data.year_list[r].month_list[o].active) {
                    n = !0;
                    break;
                }
                e++;
            }
            if (n) break;
        }
        t.setData({
            month_scroll_x: (e - 0) * a
        });
    },
    setCurrentYear: function() {
        var t = this;
        for (var a in t.data.year_list) if (t.data.year_list[a].active) {
            t.data.current_year = t.data.year_list[a].year;
            break;
        }
        t.setData({
            current_year: t.data.current_year
        });
    },
    getMonthData: function() {
        var a = this;
        getApp().core.showNavigationBarLoading(), a.setData({
            daily_avg: "-",
            month_count: "-",
            up_rate: "-"
        }), getApp().request({
            url: getApp().api.mch.user.tongji_month_data,
            data: {
                year: a.data.current_year,
                month: a.data.current_month
            },
            success: function(t) {
                0 == t.code ? a.setData({
                    daily_avg: t.data.daily_avg,
                    month_count: t.data.month_count,
                    up_rate: t.data.up_rate
                }) : getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1
                });
            },
            complete: function() {
                getApp().core.hideNavigationBarLoading();
            }
        });
    }
});