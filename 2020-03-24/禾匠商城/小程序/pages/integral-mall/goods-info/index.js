var WxParse = require("../../../wxParse/wxParse.js"), gSpecificationsModel = require("../../../components/goods/specifications_model.js"), goodsBanner = require("../../../components/goods/goods_banner.js"), goodsInfo = require("../../../components/goods/goods_info.js"), goodsBuy = require("../../../components/goods/goods_buy.js");

Page({
    data: {
        pageType: "INTEGRAL",
        hide: "hide",
        tab_detail: "active",
        tab_comment: ""
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var a = this;
        t.integral && a.setData({
            user_integral: t.integral
        }), t.goods_id && (a.setData({
            id: t.goods_id
        }), a.getGoods());
    },
    getGoods: function() {
        var o = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.integral.goods_info,
            data: {
                id: o.data.id
            },
            success: function(t) {
                if (0 == t.code) {
                    var a = t.data.goods.detail;
                    WxParse.wxParse("detail", "html", a, o), getApp().core.setNavigationBarTitle({
                        title: t.data.goods.name
                    });
                    var e = t.data.goods;
                    e.num = e.goods_num, e.pic_list = e.goods_pic_list, e.min_price = 0 < e.price ? e.integral + "积分 ￥" + e.price : e.integral + "积分", 
                    o.setData({
                        goods: t.data.goods,
                        attr_group_list: t.data.attr_group_list
                    }), o.selectDefaultAttr();
                } else getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    success: function(t) {
                        t.confirm && getApp().core.navigateTo({
                            url: "/pages/integral-mall/index/index"
                        });
                    }
                });
            },
            complete: function(t) {
                setTimeout(function() {
                    getApp().core.hideLoading();
                }, 500);
            }
        });
    },
    showShareModal: function() {
        this.setData({
            share_modal_active: "active",
            no_scroll: !0
        });
    },
    shareModalClose: function() {
        this.setData({
            share_modal_active: "",
            no_scroll: !1
        });
    },
    exchangeGoods: function() {
        var t = this;
        if (!t.data.show_attr_picker) return t.setData({
            show_attr_picker: !0
        }), !0;
        var a = t.data.attr_group_list, e = [];
        for (var o in a) {
            var i = !1;
            for (var n in a[o].attr_list) if (a[o].attr_list[n].checked) {
                i = {
                    attr_id: a[o].attr_list[n].attr_id,
                    attr_name: a[o].attr_list[n].attr_name
                };
                break;
            }
            if (!i) return getApp().core.showToast({
                title: "请选择" + a[o].attr_group_name,
                image: "/images/icon-warning.png"
            }), !0;
            e.push({
                attr_group_id: a[o].attr_group_id,
                attr_group_name: a[o].attr_group_name,
                attr_id: i.attr_id,
                attr_name: i.attr_name
            });
        }
        var r = t.data.user_integral, s = t.data.attr_integral, g = t.data.attr_num;
        if (parseInt(r) < parseInt(s)) return getApp().core.showToast({
            title: "积分不足!",
            image: "/images/icon-warning.png"
        }), !0;
        if (g <= 0) return getApp().core.showToast({
            title: "商品库存不足!",
            image: "/images/icon-warning.png"
        }), !0;
        var d = t.data.goods, p = t.data.attr_price;
        s = t.data.attr_integral;
        t.setData({
            show_attr_picker: !1
        }), getApp().core.navigateTo({
            url: "/pages/integral-mall/order-submit/index?goods_info=" + JSON.stringify({
                goods_id: d.id,
                attr: e,
                attr_price: p,
                attr_integral: s
            })
        });
    },
    onReady: function(t) {
        getApp().page.onReady(this);
    },
    onShow: function(t) {
        getApp().page.onShow(this), gSpecificationsModel.init(this), goodsBanner.init(this), 
        goodsInfo.init(this), goodsBuy.init(this);
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