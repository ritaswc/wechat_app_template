Page({
    data: {
        cid: 0
    },
    onLoad: function(n) {
        getApp().page.onLoad(this, n);
    },
    onReady: function(n) {
        getApp().page.onReady(this);
    },
    onShow: function(n) {},
    onHide: function(n) {
        getApp().page.onHide(this);
    },
    onUnload: function(n) {
        getApp().page.onUnload(this);
    },
    onPullDownRefresh: function(n) {
        getApp().page.onPullDownRefresh(this);
    },
    onReachBottom: function(n) {
        getApp().page.onReachBottom(this);
    },
    lower: function(n) {}
});