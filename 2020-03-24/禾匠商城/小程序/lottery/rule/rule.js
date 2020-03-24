Page({
    data: {},
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var e = this;
        getApp().request({
            url: getApp().api.lottery.setting,
            success: function(t) {
                0 == t.code && e.setData({
                    rule: t.data.rule
                });
            }
        });
    }
});