var is_no_more = !1, is_loading = !1, p = 2;

Page({
    data: {
        status: 0,
        order_list: [],
        show_no_data_tip: !1,
        hide: 1,
        qrcode: ""
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        is_loading = is_no_more = !1, p = 2, this.loadOrderList(t.status || 0), getCurrentPages().length < 2 && this.setData({
            show_index: !0
        });
    },
    loadOrderList: function(t) {
        null == t && (t = -1);
        var e = this;
        e.setData({
            status: t
        }), getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.miaosha.order_list,
            data: {
                status: e.data.status
            },
            success: function(t) {
                0 == t.code && e.setData({
                    order_list: t.data.list
                }), e.setData({
                    show_no_data_tip: 0 == e.data.order_list.length
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    onReachBottom: function(t) {
        getApp().page.onReachBottom(this);
        var o = this;
        is_loading || is_no_more || (is_loading = !0, getApp().request({
            url: getApp().api.miaosha.order_list,
            data: {
                status: o.data.status,
                page: p
            },
            success: function(t) {
                if (0 == t.code) {
                    var e = o.data.order_list.concat(t.data.list);
                    o.setData({
                        order_list: e
                    }), 0 == t.data.list.length && (is_no_more = !0);
                }
                p++;
            },
            complete: function() {
                is_loading = !1;
            }
        }));
    },
    orderRevoke: function(e) {
        var o = this;
        getApp().core.showModal({
            title: "提示",
            content: "是否取消该订单？",
            cancelText: "否",
            confirmText: "是",
            success: function(t) {
                if (t.cancel) return !0;
                t.confirm && (getApp().core.showLoading({
                    title: "操作中"
                }), getApp().request({
                    url: getApp().api.miaosha.order_revoke,
                    data: {
                        order_id: e.currentTarget.dataset.id
                    },
                    success: function(t) {
                        getApp().core.hideLoading(), getApp().core.showModal({
                            title: "提示",
                            content: t.msg,
                            showCancel: !1,
                            success: function(t) {
                                t.confirm && o.loadOrderList(o.data.status);
                            }
                        });
                    }
                }));
            }
        });
    },
    orderConfirm: function(e) {
        var o = this;
        getApp().core.showModal({
            title: "提示",
            content: "是否确认已收到货？",
            cancelText: "否",
            confirmText: "是",
            success: function(t) {
                if (t.cancel) return !0;
                t.confirm && (getApp().core.showLoading({
                    title: "操作中"
                }), getApp().request({
                    url: getApp().api.miaosha.confirm,
                    data: {
                        order_id: e.currentTarget.dataset.id
                    },
                    success: function(t) {
                        getApp().core.hideLoading(), getApp().core.showToast({
                            title: t.msg
                        }), 0 == t.code && o.loadOrderList(3);
                    }
                }));
            }
        });
    },
    orderQrcode: function(t) {
        var e = this, o = e.data.order_list, a = t.target.dataset.index;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), e.data.order_list[a].offline_qrcode ? (e.setData({
            hide: 0,
            qrcode: e.data.order_list[a].offline_qrcode
        }), getApp().core.hideLoading()) : getApp().request({
            url: getApp().api.order.get_qrcode,
            data: {
                order_no: o[a].order_no
            },
            success: function(t) {
                0 == t.code ? e.setData({
                    hide: 0,
                    qrcode: t.data.url
                }) : getApp().core.showModal({
                    title: "提示",
                    content: t.msg
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    hide: function(t) {
        this.setData({
            hide: 1
        });
    },
    onShow: function(t) {
        getApp().page.onShow(this);
    }
});