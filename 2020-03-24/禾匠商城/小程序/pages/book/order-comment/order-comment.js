Page({
    data: {
        goods_list: []
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var o = this;
        o.setData({
            order_id: t.id
        }), getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.book.comment_preview,
            data: {
                order_id: t.id
            },
            success: function(t) {
                if (getApp().core.hideLoading(), 1 == t.code && getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    success: function(t) {
                        t.confirm && getApp().core.navigateBack();
                    }
                }), 0 == t.code) {
                    for (var e in t.data.goods_list) t.data.goods_list[e].score = 3, t.data.goods_list[e].content = "", 
                    t.data.goods_list[e].pic_list = [], t.data.goods_list[e].uploaded_pic_list = [];
                    o.setData({
                        goods_list: t.data.goods_list
                    });
                }
            }
        });
    },
    setScore: function(t) {
        var e = t.currentTarget.dataset.index, o = t.currentTarget.dataset.score, a = this.data.goods_list;
        a[e].score = o, this.setData({
            goods_list: a
        });
    },
    contentInput: function(t) {
        var e = this, o = t.currentTarget.dataset.index;
        e.data.goods_list[o].content = t.detail.value, e.setData({
            goods_list: e.data.goods_list
        });
    },
    chooseImage: function(t) {
        var e = this, o = t.currentTarget.dataset.index, a = e.data.goods_list, i = a[o].pic_list.length;
        getApp().core.chooseImage({
            count: 6 - i,
            success: function(t) {
                a[o].pic_list = a[o].pic_list.concat(t.tempFilePaths), e.setData({
                    goods_list: a
                });
            }
        });
    },
    deleteImage: function(t) {
        var e = t.currentTarget.dataset.index, o = t.currentTarget.dataset.picIndex, a = this.data.goods_list;
        a[e].pic_list.splice(o, 1), this.setData({
            goods_list: a
        });
    },
    commentSubmit: function(t) {
        var e = this;
        getApp().core.showLoading({
            title: "正在提交",
            mask: !0
        });
        var n = e.data.goods_list;
        !function a(i) {
            if (i == n.length) return void getApp().request({
                url: getApp().api.book.submit_comment,
                method: "post",
                data: {
                    order_id: e.data.order_id,
                    goods_list: JSON.stringify(n)
                },
                success: function(t) {
                    getApp().core.hideLoading(), 0 == t.code && getApp().core.showModal({
                        title: "提示",
                        content: t.msg,
                        showCancel: !1,
                        success: function(t) {
                            t.confirm && getApp().core.redirectTo({
                                url: "/pages/book/order/order?status=2"
                            });
                        }
                    }), 1 == t.code && getApp().core.showToast({
                        title: t.msg,
                        image: "/images/icon-warning.png"
                    });
                }
            });
            var s = 0;
            if (!n[i].pic_list.length || 0 == n[i].pic_list.length) return a(i + 1);
            for (var t in n[i].pic_list) !function(o) {
                getApp().core.uploadFile({
                    url: getApp().api.default.upload_image,
                    name: "image",
                    filePath: n[i].pic_list[o],
                    complete: function(t) {
                        if (t.data) {
                            var e = JSON.parse(t.data);
                            0 == e.code && (n[i].uploaded_pic_list[o] = e.data.url);
                        }
                        if (++s == n[i].pic_list.length) return a(i + 1);
                    }
                });
            }(t);
        }(0);
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