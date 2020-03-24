var app = getApp(), api = getApp().api;

Page({
    data: {},
    onLoad: function(t) {
        if (getApp().page.onLoad(this, t), t.inId) var e = {
            order_id: t.inId,
            type: "IN"
        }; else e = {
            order_id: t.id,
            type: "mall"
        };
        this.loadData(e);
    },
    loadData: function(t) {
        var e = this;
        getApp().core.showLoading({
            title: "正在加载"
        }), getApp().request({
            url: getApp().api.order.express_detail,
            data: t,
            success: function(t) {
                getApp().core.hideLoading(), 0 == t.code && e.setData({
                    data: t.data
                }), 1 == t.code && getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    success: function(t) {
                        t.confirm && getApp().core.navigateBack();
                    }
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
    },
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    copyText: function(t) {
        var e = t.currentTarget.dataset.text;
        getApp().core.setClipboardData({
            data: e,
            success: function() {
                getApp().core.showToast({
                    title: "已复制"
                });
            }
        });
    }
});