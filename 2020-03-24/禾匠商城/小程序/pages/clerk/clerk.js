Page({
    data: {
        order: null,
        getGoodsTotalPrice: function() {
            return this.data.order.total_price;
        }
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var t = this, o = "";
        if ("undefined" == typeof my) o = e.scene; else if (null !== getApp().query) {
            var r = getApp().query;
            getApp().query = null, o = r.order_no;
        }
        t.setData({
            store: getApp().core.getStorageSync(getApp().const.STORE),
            user_info: getApp().getUser()
        }), getApp().core.showLoading({
            title: "正在加载"
        }), getApp().request({
            url: getApp().api.order.clerk_detail,
            data: {
                order_no: o
            },
            success: function(e) {
                0 == e.code ? t.setData({
                    order: e.data
                }) : getApp().core.showModal({
                    title: "警告！",
                    showCancel: !1,
                    content: e.msg,
                    confirmText: "确认",
                    success: function(e) {
                        e.confirm && getApp().core.redirectTo({
                            url: "/pages/index/index"
                        });
                    }
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    clerk: function(e) {
        var t = this;
        getApp().core.showModal({
            title: "提示",
            content: "是否确认核销？",
            success: function(e) {
                e.confirm && (getApp().core.showLoading({
                    title: "正在加载"
                }), getApp().request({
                    url: getApp().api.order.clerk,
                    data: {
                        order_no: t.data.order.order_no
                    },
                    success: function(e) {
                        0 == e.code ? getApp().core.redirectTo({
                            url: "/pages/user/user"
                        }) : getApp().core.showModal({
                            title: "警告！",
                            showCancel: !1,
                            content: e.msg,
                            confirmText: "确认",
                            success: function(e) {
                                e.confirm && getApp().core.redirectTo({
                                    url: "/pages/index/index"
                                });
                            }
                        });
                    },
                    complete: function() {
                        getApp().core.hideLoading();
                    }
                }));
            }
        });
    }
});