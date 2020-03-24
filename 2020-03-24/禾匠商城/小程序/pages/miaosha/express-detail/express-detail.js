Page({
    data: {},
    onLoad: function(t) {
        getApp().page.onLoad(this, t), this.loadData(t);
    },
    loadData: function(t) {
        var o = this;
        getApp().core.showLoading({
            title: "正在加载"
        }), getApp().request({
            url: getApp().api.miaosha.express_detail,
            data: {
                order_id: t.id
            },
            success: function(t) {
                getApp().core.hideLoading(), 0 == t.code && o.setData({
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
    onReady: function(t) {
        getApp().page.onReady(this);
    },
    onShow: function(t) {
        getApp().page.onShow(this);
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