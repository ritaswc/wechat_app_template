var app = getApp(), api = getApp().api;

Page({
    onLoad: function(t) {
        var e = this;
        getApp().page.onLoad(this, t), getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.bargain.setting,
            success: function(t) {
                0 == t.code ? e.setData(t.data) : e.showLoading({
                    title: t.msg
                });
            },
            complete: function(t) {
                getApp().core.hideLoading();
            }
        });
    }
});