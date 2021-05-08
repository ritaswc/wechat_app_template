Page({
    data: {},
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var t = this;
        getApp().getConfig(function(e) {
            t.setData({
                store: e.store
            });
        });
        var o = getApp().getUser();
        getApp().setUser(o), t.showToast({
            title: "提示"
        });
        var n = getApp().core.getStorageSync(getApp().const.STORE);
        getApp().core.setStorageSync(getApp().const.STORE, n), getApp().trigger.add(getApp().trigger.events.login, "测试e", function(e) {
            console.log("add--\x3e添加了一个事件"), console.log("传递的参数--\x3e" + e);
        }), getApp().trigger.run(getApp().trigger.events.login, function() {
            console.log("callback--\x3e这里执行延时事件的回调函数");
        }, 1);
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
    onPullDownRefresh: function() {
        getApp().page.onPullDownRefresh(this);
    },
    onReachBottom: function() {
        getApp().page.onReachBottom(this);
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
    },
    myBtnClick: function(e) {
        console.log("myBtnClick", e);
    }
});