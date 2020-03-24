Page({
    data: {
        num: 0,
        args: !1,
        page: 1,
        load: !1
    },
    onLoad: function(a) {
        if (getApp().page.onLoad(this, a), a) {
            var t = this;
            t.setData(a), getApp().core.showLoading({
                title: "加载中"
            }), getApp().request({
                url: getApp().api.lottery.lucky_code,
                data: {
                    id: a.id
                },
                success: function(a) {
                    if (0 == a.code) {
                        t.setData(a.data);
                        a.data;
                    }
                },
                complete: function(a) {
                    getApp().core.hideLoading();
                }
            });
        }
    },
    onShow: function() {
        getApp().page.onShow(this);
    },
    userload: function() {
        var t = this;
        if (!t.data.args && !t.data.load) {
            t.setData({
                load: !0
            }), getApp().core.showLoading({
                title: "加载中"
            });
            var e = t.data.page + 1;
            getApp().request({
                url: getApp().api.lottery.lucky_code,
                data: {
                    id: t.data.id,
                    page: e
                },
                success: function(a) {
                    if (0 == a.code) {
                        if (null == a.data.parent || 0 == a.data.parent.length) return void t.setData({
                            args: !0
                        });
                        t.setData({
                            parent: t.data.parent.concat(a.data.parent),
                            page: e
                        });
                    } else t.showToast({
                        title: a.msg
                    });
                },
                complete: function() {
                    getApp().core.hideLoading(), t.setData({
                        load: !1
                    });
                }
            });
        }
    }
});