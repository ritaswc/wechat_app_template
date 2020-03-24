var is_more = !1;

Page({
    data: {
        show: !1
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
    },
    getData: function() {
        var e = this;
        getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.recharge.record,
            data: {
                date: e.data.date_1 || ""
            },
            success: function(t) {
                e.setData({
                    list: t.data.list
                }), getApp().core.hideLoading(), is_more = !1;
            }
        });
    },
    onReady: function() {
        getApp().page.onReady(this);
    },
    onShow: function() {
        getApp().page.onShow(this);
        var e = this;
        getApp().core.showLoading({
            title: "加载中"
        });
        var a = getApp().core.getStorageSync(getApp().const.USER_INFO);
        getApp().request({
            url: getApp().api.recharge.index,
            success: function(t) {
                a.money = t.data.money, getApp().core.setStorageSync(getApp().const.USER_INFO, a), 
                e.setData({
                    user_info: a,
                    list: t.data.list,
                    setting: t.data.setting,
                    date_1: t.data.date,
                    date: t.data.date.replace("-", "年") + "月"
                }), getApp().core.hideLoading();
            }
        });
    },
    dateChange: function(t) {
        if (!is_more) {
            is_more = !0;
            var e = t.detail.value, a = e.replace("-", "年") + "月";
            this.setData({
                date: a,
                date_1: e
            }), this.getData();
        }
    },
    dateUp: function() {
        var t = this;
        if (!is_more) {
            is_more = !0;
            var e = t.data.date_1, a = (t.data.date, new Date(e));
            a.setMonth(a.getMonth() + 1);
            var o = a.getMonth() + 1;
            o = (o = o.toString())[1] ? o : "0" + o, t.setData({
                date: a.getFullYear() + "年" + o + "月",
                date_1: a.getFullYear() + "-" + o
            }), t.getData();
        }
    },
    dateDown: function() {
        var t = this;
        if (!is_more) {
            is_more = !0;
            var e = t.data.date_1, a = (t.data.date, new Date(e));
            a.setMonth(a.getMonth() - 1);
            var o = a.getMonth() + 1;
            o = (o = o.toString())[1] ? o : "0" + o, t.setData({
                date: a.getFullYear() + "年" + o + "月",
                date_1: a.getFullYear() + "-" + o
            }), t.getData();
        }
    },
    click: function() {
        this.setData({
            show: !0
        });
    },
    close: function() {
        this.setData({
            show: !1
        });
    },
    GoToDetail: function(t) {
        var e = t.currentTarget.dataset.index, a = this.data.list[e];
        getApp().core.navigateTo({
            url: "/pages/balance/detail?order_type=" + a.order_type + "&id=" + a.id
        });
    }
});