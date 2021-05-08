var calendarSignData, date, calendarSignDay, app = getApp();

Page({
    data: {},
    onLoad: function(a) {
        var e = new Date(), n = e.getFullYear(), t = e.getMonth() + 1;
        date = e.getDate();
        var r, c = e.getDay(), g = 7 - (date - c) % 7;
        1 == t || 3 == t || 5 == t || 7 == t || 8 == t || 10 == t || 12 == t ? r = 31 : 4 == t || 6 == t || 9 == t || 11 == t ? r = 30 : 2 == t && (r = (n - 2e3) % 4 == 0 ? 29 : 28), 
        null != getApp().core.getStorageSync("calendarSignData") && "" != getApp().core.getStorageSync("calendarSignData") || getApp().core.setStorageSync("calendarSignData", new Array(r)), 
        null != getApp().core.getStorageSync("calendarSignDay") && "" != getApp().core.getStorageSync("calendarSignDay") || getApp().core.setStorageSync("calendarSignDay", 0), 
        calendarSignData = getApp().core.getStorageSync("calendarSignData"), calendarSignDay = getApp().core.getStorageSync("calendarSignDay"), 
        this.setData({
            year: n,
            month: t,
            nbsp: g,
            monthDaySize: r,
            date: date,
            calendarSignData: calendarSignData,
            calendarSignDay: calendarSignDay
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    register_rule: function() {
        this.setData({
            register_rule: !0
        });
    },
    hideModal: function() {
        this.setData({
            register_rule: !1
        });
    },
    calendarSign: function() {
        calendarSignData[date] = date, calendarSignDay += 1, getApp().core.setStorageSync("calendarSignData", calendarSignData), 
        getApp().core.setStorageSync("calendarSignDay", calendarSignDay), getApp().core.showToast({
            title: "签到成功",
            icon: "success",
            duration: 2e3
        }), this.setData({
            calendarSignData: calendarSignData,
            calendarSignDay: calendarSignDay
        });
    }
});