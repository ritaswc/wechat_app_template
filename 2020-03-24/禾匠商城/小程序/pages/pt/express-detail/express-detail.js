Page({
    data: {},
    onLoad: function(o) {
        getApp().page.onLoad(this, o), this.loadData(o);
    },
    loadData: function(o) {
        var t = this;
        getApp().core.showLoading({
            title: "正在加载"
        }), getApp().request({
            url: getApp().api.group.order.express_detail,
            data: {
                order_id: o.id
            },
            success: function(o) {
                getApp().core.hideLoading(), 0 == o.code && t.setData({
                    data: o.data
                }), 1 == o.code && getApp().core.showModal({
                    title: "提示",
                    content: o.msg,
                    showCancel: !1,
                    success: function(o) {
                        o.confirm && getApp().core.navigateBack();
                    }
                });
            }
        });
    },
    onReady: function(o) {
        getApp().page.onReady(this);
    },
    onShow: function(o) {
        getApp().page.onShow(this);
    },
    onHide: function(o) {
        getApp().page.onHide(this);
    },
    onUnload: function(o) {
        getApp().page.onUnload(this);
    },
    onPullDownRefresh: function(o) {
        getApp().page.onPullDownRefresh(this);
    },
    onReachBottom: function(o) {
        getApp().page.onReachBottom(this);
    }
});