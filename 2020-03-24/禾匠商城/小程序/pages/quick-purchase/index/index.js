var shoppingCart = require("../../../components/shopping_cart/shopping_cart.js"), specificationsModel = require("../../../components/specifications_model/specifications_model.js");

Page({
    data: {
        quick_list: [],
        goods_list: [],
        carGoods: [],
        currentGood: {},
        checked_attr: [],
        checkedGood: [],
        attr_group_list: [],
        temporaryGood: {
            price: 0,
            num: 0,
            use_attr: 1
        },
        check_goods_price: 0,
        showModal: !1,
        checked: !1,
        cat_checked: !1,
        color: "",
        total: {
            total_price: 0,
            total_num: 0
        }
    },
    onLoad: function(o) {
        getApp().page.onLoad(this, o);
    },
    onShow: function() {
        getApp().page.onShow(this), shoppingCart.init(this), specificationsModel.init(this, shoppingCart), 
        this.loadData();
    },
    onHide: function() {
        getApp().page.onHide(this), shoppingCart.saveItemData(this);
    },
    onUnload: function() {
        getApp().page.onUnload(this), shoppingCart.saveItemData(this);
    },
    loadData: function(o) {
        var h = this, u = getApp().core.getStorageSync(getApp().const.ITEM);
        h.setData({
            total: void 0 !== u.total ? u.total : {
                total_num: 0,
                total_price: 0
            },
            carGoods: void 0 !== u.carGoods ? u.carGoods : []
        }), getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.quick.quick,
            success: function(o) {
                if (getApp().core.hideLoading(), 0 == o.code) {
                    var t = o.data.list, a = [], e = [], s = [];
                    for (var i in t) if (0 < t[i].goods.length) for (var c in e.push(t[i]), t[i].goods) {
                        var n = !0;
                        if (getApp().helper.inArray(t[i].goods[c].id, s) && (t[i].goods.splice(c, 1), n = !1), 
                        n) {
                            var d = h.data.carGoods;
                            for (var r in d) u.carGoods[r].goods_id === parseInt(t[i].goods[c].id) && (t[i].goods[c].num = t[i].goods[c].num ? t[i].goods[c].num : 0, 
                            t[i].goods[c].num += u.carGoods[r].num);
                            if (parseInt(t[i].goods[c].hot_cakes)) {
                                var p = !0;
                                for (var g in a) a[g].id == t[i].goods[c].id && (p = !1);
                                p && a.push(t[i].goods[c]);
                            }
                            s.push(t[i].goods[c].id);
                        }
                    }
                    console.log(e), h.setData({
                        quick_hot_goods_lists: a,
                        quick_list: e
                    });
                }
            }
        });
    },
    get_goods_info: function(o) {
        var t = this, a = t.data.carGoods, e = t.data.total, s = t.data.quick_hot_goods_lists, i = t.data.quick_list, c = {
            carGoods: a,
            total: e,
            quick_hot_goods_lists: s,
            check_num: t.data.check_num,
            quick_list: i
        };
        getApp().core.setStorageSync(getApp().const.ITEM, c);
        var n = o.currentTarget.dataset.id;
        getApp().core.navigateTo({
            url: "/pages/goods/goods?id=" + n + "&quick=1"
        });
    },
    selectMenu: function(o) {
        var t = o.currentTarget.dataset, a = this.data.quick_list;
        if ("hot_cakes" == t.tag) for (var e = !0, s = a.length, i = 0; i < s; i++) a[i].cat_checked = !1; else {
            var c = t.index;
            for (s = a.length, i = 0; i < s; i++) a[i].cat_checked = !1, a[i].id == a[c].id && (a[i].cat_checked = !0);
            e = !1;
        }
        this.setData({
            toView: t.tag,
            quick_list: a,
            cat_checked: e
        });
    },
    onShareAppMessage: function(o) {
        getApp().page.onShareAppMessage(this);
        var t = this;
        return {
            path: "/pages/quick-purchase/index/index?user_id=" + getApp().core.getStorageSync(getApp().const.USER_INFO).id,
            success: function(o) {
                share_count++, 1 == share_count && t.shareSendCoupon(t);
            }
        };
    },
    close_box: function(o) {
        this.setData({
            showModal: !1
        });
    },
    hideModal: function() {
        this.setData({
            showModal: !1
        });
    }
});