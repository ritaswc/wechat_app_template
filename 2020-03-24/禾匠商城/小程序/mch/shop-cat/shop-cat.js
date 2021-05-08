var app = getApp(), api = getApp().api;

Page({
    data: {
        list: [ {
            id: 1,
            name: "上衣"
        }, {
            id: 2,
            name: "下装",
            list: [ {
                id: 3,
                name: "长裤"
            }, {
                id: 4,
                name: "长裤"
            }, {
                id: 5,
                name: "九分裤"
            }, {
                id: 6,
                name: "短裤"
            } ]
        }, {
            id: 7,
            name: "帽子"
        } ]
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var a = this;
        a.setData({
            mch_id: t.mch_id,
            cat_id: t.cat_id || ""
        });
        var e = "shop_cat_list_mch_id_" + a.data.mch_id, i = getApp().core.getStorageSync(e);
        i && a.setData({
            list: i
        }), getApp().core.showNavigationBarLoading(), getApp().request({
            url: getApp().api.mch.shop_cat,
            data: {
                mch_id: a.data.mch_id
            },
            success: function(t) {
                0 == t.code && (a.setData({
                    list: t.data.list
                }), getApp().core.setStorageSync(e, t.data.list));
            },
            complete: function() {
                getApp().core.hideNavigationBarLoading();
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
    onReachBottom: function() {}
});