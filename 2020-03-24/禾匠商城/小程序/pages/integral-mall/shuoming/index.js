Page({
    data: {},
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
    },
    onReady: function(t) {
        getApp().page.onReady(this);
    },
    onShow: function(t) {
        getApp().page.onShow(this);
        var n = this;
        getApp().request({
            url: getApp().api.integral.explain,
            data: {},
            success: function(t) {
                0 == t.code && n.setData({
                    integral_shuoming: t.data.setting.integral_shuoming
                });
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
    }
});