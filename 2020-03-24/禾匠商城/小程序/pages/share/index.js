var app = getApp(), api = app.api;

Page({
    data: {
        total_price: 0,
        price: 0,
        cash_price: 0,
        total_cash: 0,
        team_count: 0,
        order_money: 0
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        this.setData({
            custom: getApp().core.getStorageSync(getApp().const.CUSTOM)
        });
    },
    onReady: function() {
        getApp().page.onReady(this);
    },
    onShow: function() {
        getApp().page.onShow(this);
        var e = this, t = getApp().core.getStorageSync(getApp().const.SHARE_SETTING), a = e.data.__user_info;
        e.setData({
            share_setting: t,
            custom: e.data.store.share_custom_data
        }), a && 1 == a.is_distributor ? e.checkUser() : e.loadData();
    },
    checkUser: function() {
        var t = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.share.get_info,
            success: function(e) {
                0 == e.code && (t.setData({
                    total_price: e.data.price.total_price,
                    price: e.data.price.price,
                    cash_price: e.data.price.cash_price,
                    total_cash: e.data.price.total_cash,
                    team_count: e.data.team_count,
                    order_money: e.data.order_money,
                    custom: e.data.custom,
                    order_money_un: e.data.order_money_un
                }), getApp().core.setStorageSync(getApp().const.CUSTOM, e.data.custom), t.loadData()), 
                1 == e.code && (__user_info.is_distributor = e.data.is_distributor, t.setData({
                    __user_info: __user_info
                }), getApp().setUser(__user_info));
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    loadData: function() {
        var a = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.share.index,
            success: function(e) {
                if (0 == e.code) {
                    if (e.data.share_setting) var t = e.data.share_setting; else t = e.data;
                    getApp().core.setStorageSync(getApp().const.SHARE_SETTING, t), a.setData({
                        share_setting: t
                    });
                }
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    onHide: function() {
        getApp().page.onHide(this);
    },
    onUnload: function() {
        getApp().page.onUnload(this);
    },
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    apply: function(t) {
        var a = getApp().core.getStorageSync(getApp().const.SHARE_SETTING), o = getApp().getUser();
        1 == a.share_condition ? getApp().core.navigateTo({
            url: "/pages/add-share/index"
        }) : 0 != a.share_condition && 2 != a.share_condition || (0 == o.is_distributor ? getApp().core.showModal({
            title: "申请成为" + (this.data.custom.words.share_name.name || "分销商"),
            content: "是否申请？",
            success: function(e) {
                e.confirm && (getApp().core.showLoading({
                    title: "正在加载",
                    mask: !0
                }), getApp().request({
                    url: getApp().api.share.join,
                    method: "POST",
                    data: {
                        form_id: t.detail.formId
                    },
                    success: function(e) {
                        0 == e.code && (0 == a.share_condition ? (o.is_distributor = 2, getApp().core.navigateTo({
                            url: "/pages/add-share/index"
                        })) : (o.is_distributor = 1, getApp().core.redirectTo({
                            url: "/pages/share/index"
                        })), getApp().setUser(o));
                    },
                    complete: function() {
                        getApp().core.hideLoading();
                    }
                }));
            }
        }) : getApp().core.navigateTo({
            url: "/pages/add-share/index"
        }));
    }
});