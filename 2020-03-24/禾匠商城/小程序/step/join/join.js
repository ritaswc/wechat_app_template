Page({
    data: {
        name: 0,
        open_date: "",
        step_num: 0,
        bail_currency: 0,
        join: !1
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var a = this, i = void 0;
        null == e.id ? getApp().core.reLaunch({
            url: "../index/index"
        }) : i = e.id, getApp().core.showLoading({
            title: "数据加载中...",
            mask: !0
        }), getApp().request({
            url: getApp().api.step.activity_detail,
            data: {
                activity_id: i
            },
            success: function(e) {
                getApp().core.hideLoading();
                var t = e.data.list.open_date.replace(".", "/").replace(".", "/");
                a.setData({
                    id: i,
                    name: e.data.list.name,
                    open_date: t,
                    step_num: e.data.list.step_num,
                    bail_currency: e.data.list.bail_currency
                });
            }
        });
    },
    onShareAppMessage: function(e) {
        return getApp().page.onShareAppMessage(this), {
            path: "/step/dare/dare?user_id=" + getApp().getUser().id,
            title: this.data.title ? this.data.title : "步数挑战"
        };
    },
    apply: function() {
        var a = this;
        getApp().request({
            url: getApp().api.step.activity_join,
            data: {
                activity_id: a.data.id
            },
            success: function(e) {
                var t = a.data.open_date.slice(5);
                0 == e.code ? getApp().core.redirectTo({
                    url: "../dare/dare?open_date=" + t + "&join=true"
                }) : "活力币不足" == e.msg && a.data.store.option.step.currency_name ? getApp().core.showModal({
                    content: a.data.store.option.step.currency_name + "不足",
                    showCancel: !1
                }) : getApp().core.showModal({
                    content: e.msg,
                    showCancel: !1
                });
            }
        });
    }
});