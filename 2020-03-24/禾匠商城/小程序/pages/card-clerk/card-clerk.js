Page({
    data: {},
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var t = getApp().getUser();
        this.setData({
            store: getApp().core.getStorageSync(getApp().const.STIRE),
            user_info: t
        });
        var o = "";
        if ("undefined" == typeof my) o = decodeURIComponent(e.scene); else if (null !== getApp().query) {
            var n = getApp().query;
            getApp().query = null, o = n.user_card_id;
        }
        getApp().core.showModal({
            title: "提示",
            content: "是否核销？",
            success: function(e) {
                e.confirm ? (getApp().core.showLoading({
                    title: "核销中"
                }), getApp().request({
                    url: getApp().api.user.card_clerk,
                    data: {
                        user_card_id: o
                    },
                    success: function(e) {
                        getApp().core.showModal({
                            title: "提示",
                            content: e.msg,
                            showCancel: !1,
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
                })) : e.cancel && getApp().core.redirectTo({
                    url: "/pages/index/index"
                });
            }
        });
    },
    onShow: function() {
        getApp().page.onShow(this);
    }
});