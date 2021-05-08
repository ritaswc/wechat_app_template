var is_no_more = !1, is_loading = !1, p = 2;

Page({
    data: {},
    onLoad: function(t) {
        getApp().page.onLoad(this, t), is_loading = is_no_more = !1, p = 2;
        var e = this;
        e.setData({
            gid: t.id
        }), getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.group.comment,
            data: {
                gid: t.id
            },
            success: function(t) {
                getApp().core.hideLoading(), 1 == t.code && getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    success: function(t) {
                        t.confirm && getApp().core.navigateBack();
                    }
                }), 0 == t.code && (0 == t.data.comment.length && getApp().core.showModal({
                    title: "提示",
                    content: "暂无评价",
                    showCancel: !1,
                    success: function(t) {
                        t.confirm && getApp().core.navigateBack();
                    }
                }), e.setData({
                    comment: t.data.comment
                })), e.setData({
                    show_no_data_tip: 0 == e.data.comment.length
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
        var o = this;
        is_loading || is_no_more || (is_loading = !0, getApp().request({
            url: getApp().api.group.comment,
            data: {
                gid: o.data.gid,
                page: p
            },
            success: function(t) {
                if (0 == t.code) {
                    var e = o.data.comment.concat(t.data.comment);
                    o.setData({
                        comment: e
                    }), 0 == t.data.comment.length && (is_no_more = !0);
                }
                p++;
            },
            complete: function() {
                is_loading = !1;
            }
        }));
    },
    bigToImage: function(t) {
        var e = this.data.comment[t.target.dataset.index].pic_list;
        getApp().core.previewImage({
            current: t.target.dataset.url,
            urls: e
        });
    }
});