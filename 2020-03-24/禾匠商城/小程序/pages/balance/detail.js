var is_more = !1;

Page({
    data: {},
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var t = this;
        t.setData(e), getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.recharge.detail,
            method: "GET",
            data: {
                order_type: e.order_type,
                id: e.id
            },
            success: function(e) {
                getApp().core.hideLoading(), 0 == e.code ? t.setData({
                    list: e.data
                }) : getApp().core.showModal({
                    title: "提示",
                    content: e.msg
                });
            }
        });
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
    }
});