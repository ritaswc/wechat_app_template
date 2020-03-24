Page({
    data: {
        settle_type: "",
        settleList: [],
        page: 1,
        loading: !1,
        no_more: !1
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        this.setData({
            settle_type: t.settle_type
        }), this.getSettleList();
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
        this.getSettleList();
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
    },
    getSettleList: function() {
        var e = this;
        if (!e.data.loading && !e.data.no_more) {
            e.setData({
                loading: !0
            });
            var t = e.data.settle_type, a = e.data.page;
            getApp().core.showLoading({
                title: "正在加载",
                mask: !0
            }), getApp().request({
                url: getApp().api.mch.user.settle_log,
                data: {
                    settle_type: t,
                    page: a
                },
                success: function(t) {
                    0 == t.code ? 0 < t.data.list.length ? e.setData({
                        settleList: e.data.settleList.concat(t.data.list),
                        page: a + 1
                    }) : e.setData({
                        no_more: !0
                    }) : getApp().core.showModal({
                        title: "提示",
                        content: t.msg,
                        showCancel: !1,
                        success: function(t) {
                            t.confirm && getApp().core.navigateBack();
                        }
                    });
                },
                complete: function() {
                    getApp().core.hideLoading(), e.setData({
                        loading: !1
                    });
                }
            });
        }
    }
});