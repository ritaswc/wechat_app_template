Page({
    data: {
        currentDate: "",
        dayList: "",
        currentDayList: "",
        currentObj: "",
        currentDay: "",
        selectCSS: "bk-color-day",
        weeks: [ {
            day: "日"
        }, {
            day: "一"
        }, {
            day: "二"
        }, {
            day: "三"
        }, {
            day: "四"
        }, {
            day: "五"
        }, {
            day: "六"
        } ]
    },
    doDay: function(t) {
        var e = this, a = e.data.currentObj, r = a.getFullYear(), n = a.getMonth() + 1, i = a.getDate(), o = "";
        o = "left" == t.currentTarget.dataset.key ? (n -= 1) <= 0 ? r - 1 + "/12/" + i : r + "/" + n + "/" + i : (n += 1) <= 12 ? r + "/" + n + "/" + i : r + 1 + "/1/" + i, 
        a = new Date(o), this.setData({
            currentDate: a.getFullYear() + "年" + (a.getMonth() + 1) + "月",
            currentObj: a,
            currentYear: a.getFullYear(),
            currentMonth: a.getMonth() + 1
        });
        var s = a.getFullYear() + "/" + (a.getMonth() + 1) + "/";
        this.setSchedule(a);
        var g = getApp().core.getStorageSync(getApp().const.CURRENT_DAY_LIST);
        for (var u in g) ;
        var c = [], d = e.data.registerTime;
        for (var u in g) g[u] && c.push(s + g[u]);
        var h = function(t, e) {
            for (var a = 0, r = 0, n = new Array(); a < t.length && r < e.length; ) {
                var i = new Date(t[a]).getTime(), o = new Date(e[r]).getTime();
                i < o ? a++ : (o < i || (n.push(e[r]), a++), r++);
            }
            return n;
        }(c, d), p = [];
        for (var u in g) g[u] && (g[u] = {
            date: g[u],
            is_re: 0
        });
        for (var u in h) for (var u in p = h[u].split("/"), g) g[u] && g[u].date == p[2] && (g[u].is_re = 1);
        e.setData({
            currentDayList: g
        });
    },
    setSchedule: function(t) {
        for (var e = t.getMonth() + 1, a = t.getFullYear(), r = t.getDate(), n = (t.getDate(), 
        new Date(a, e, 0).getDate()), i = t.getUTCDay() + 1 - (r % 7 - 1), o = i <= 0 ? 7 + i : i, s = [], g = 0, u = 0; u < 42; u++) {
            u < o ? s[u] = "" : g < n ? (s[u] = g + 1, g = s[u]) : n <= g && (s[u] = "");
        }
        getApp().core.setStorageSync(getApp().const.CURRENT_DAY_LIST, s);
    },
    selectDay: function(t) {
        var e = this;
        e.setData({
            currentDay: t.target.dataset.day,
            currentDa: t.target.dataset.day,
            currentDate: e.data.currentYear + "年" + e.data.currentMonth + "月",
            checkDay: e.data.currentYear + "" + e.data.currentMonth + t.target.dataset.day
        });
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var e = this.getCurrentDayString();
        this.setData({
            currentDate: e.getFullYear() + "年" + (e.getMonth() + 1) + "月",
            today: e.getFullYear() + "/" + (e.getMonth() + 1) + "/" + e.getDate(),
            yearmonth: e.getFullYear() + "/" + (e.getMonth() + 1) + "/",
            today_time: e.getFullYear() + "" + (e.getMonth() + 1) + e.getDate(),
            currentDay: e.getDate(),
            currentObj: e,
            currentYear: e.getFullYear(),
            currentMonth: e.getMonth() + 1
        }), this.setSchedule(e);
    },
    getCurrentDayString: function() {
        var t = this.data.currentObj;
        if ("" != t) return t;
        var e = new Date(), a = e.getFullYear() + "/" + (e.getMonth() + 1) + "/" + e.getDate();
        return new Date(a);
    },
    onReady: function(t) {
        getApp().page.onReady(this);
    },
    onShow: function(t) {
        getApp().page.onShow(this);
        var s = this;
        getApp().request({
            url: getApp().api.integral.explain,
            data: {
                today: s.data.today
            },
            success: function(t) {
                if (0 == t.code) {
                    if (t.data.register) e = t.data.register.continuation; else var e = 0;
                    s.setData({
                        register: t.data.setting,
                        continuation: e,
                        registerTime: t.data.registerTime
                    }), t.data.today && s.setData({
                        status: 1
                    });
                    var a = getApp().core.getStorageSync(getApp().const.CURRENT_DAY_LIST), r = [];
                    for (var n in a) r.push(s.data.yearmonth + a[n]);
                    var i = function(t, e) {
                        for (var a = 0, r = 0, n = new Array(); a < t.length && r < e.length; ) {
                            var i = new Date(t[a]).getTime(), o = new Date(e[r]).getTime();
                            i < o || isNaN(i) ? a++ : (o < i || (n.push(e[r]), a++), r++);
                        }
                        return n;
                    }(r, t.data.registerTime), o = [];
                    for (var n in a) a[n] && (a[n] = {
                        date: a[n],
                        is_re: 0
                    });
                    for (var n in i) for (var n in o = i[n].split("/"), a) a[n] && a[n].date == o[2] && (a[n].is_re = 1);
                    s.setData({
                        currentDayList: a
                    });
                }
            }
        });
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
    },
    register_rule: function() {
        this.setData({
            register_rule: !0,
            status_show: 2
        });
    },
    hideModal: function() {
        this.setData({
            register_rule: !1
        });
    },
    calendarSign: function() {
        var r = this, t = r.data.today_time, n = r.data.today, i = r.data.currentDay, e = r.data.checkDay;
        if (e && parseInt(t) != parseInt(e)) getApp().core.showToast({
            title: "日期不对哦",
            image: "/images/icon-warning.png"
        }); else {
            var o = r.data.currentDayList;
            getApp().request({
                url: getApp().api.integral.register,
                data: {
                    today: n
                },
                success: function(t) {
                    if (0 == t.code) {
                        r.data.registerTime.push(n);
                        var e = t.data.continuation;
                        for (var a in o) o[a] && o[a].date == i && (o[a].is_re = 1);
                        r.setData({
                            register_rule: !0,
                            status_show: 1,
                            continuation: e,
                            status: 1,
                            currentDayList: o,
                            registerTime: r.data.registerTime
                        }), parseInt(e) >= parseInt(r.data.register.register_continuation) && r.setData({
                            jiangli: 1
                        });
                    } else getApp().core.showToast({
                        title: t.msg,
                        image: "/images/icon-warning.png"
                    });
                }
            });
        }
    }
});