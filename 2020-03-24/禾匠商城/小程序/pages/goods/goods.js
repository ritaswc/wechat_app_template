var WxParse = require("../../wxParse/wxParse.js"), shoppingCart = require("../../components/shopping_cart/shopping_cart.js"), specificationsModel = require("../../components/specifications_model/specifications_model.js"), gSpecificationsModel = require("../../components/goods/specifications_model.js"), goodsBanner = require("../../components/goods/goods_banner.js"), goodsInfo = require("../../components/goods/goods_info.js"), goodsBuy = require("../../components/goods/goods_buy.js"), goodsRecommend = require("../../components/goods/goods_recommend.js"), p = 1, is_loading_comment = !1, is_more_comment = !0, share_count = 0;

Page({
    data: {
        pageType: "STORE",
        id: null,
        goods: {},
        show_attr_picker: !1,
        form: {
            number: 1
        },
        tab_detail: "active",
        tab_comment: "",
        comment_list: [],
        comment_count: {
            score_all: 0,
            score_3: 0,
            score_2: 0,
            score_1: 0
        },
        autoplay: !1,
        hide: "hide",
        show: !1,
        x: getApp().core.getSystemInfoSync().windowWidth,
        y: getApp().core.getSystemInfoSync().windowHeight - 20,
        page: 1,
        drop: !1,
        goodsModel: !1,
        goods_num: 0,
        temporaryGood: {
            price: 0,
            num: 0,
            use_attr: 1
        },
        goodNumCount: 0
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var o = this;
        share_count = 0, is_more_comment = !(is_loading_comment = !(p = 1));
        var e = t.quick;
        if (e) {
            var a = getApp().core.getStorageSync(getApp().const.ITEM);
            if (a) var i = a.total, s = a.carGoods; else i = {
                total_price: 0,
                total_num: 0
            }, s = [];
            o.setData({
                quick: e,
                quick_list: a.quick_list,
                total: i,
                carGoods: s,
                quick_hot_goods_lists: a.quick_hot_goods_lists
            });
        }
        if ("undefined" == typeof my) {
            var n = decodeURIComponent(t.scene);
            if (void 0 !== n) {
                var d = getApp().helper.scene_decode(n);
                d.uid && d.gid && (t.id = d.gid);
            }
        } else if (null !== getApp().query) {
            var c = getApp().query;
            getApp().query = null, t.id = c.gid;
        }
        o.setData({
            id: t.id
        }), o.getGoods(), o.getCommentList();
    },
    onReady: function() {
        getApp().page.onReady(this);
    },
    onShow: function() {
        getApp().page.onShow(this), shoppingCart.init(this), specificationsModel.init(this, shoppingCart), 
        gSpecificationsModel.init(this), goodsBanner.init(this), goodsInfo.init(this), goodsBuy.init(this), 
        goodsRecommend.init(this);
        var t = getApp().core.getStorageSync(getApp().const.ITEM);
        if (t) var o = t.total, e = t.carGoods, a = this.data.goods_num; else o = {
            total_price: 0,
            total_num: 0
        }, e = [], a = 0;
        this.setData({
            total: o,
            carGoods: e,
            goods_num: a
        });
    },
    onHide: function() {
        getApp().page.onHide(this), shoppingCart.saveItemData(this);
    },
    onUnload: function() {
        getApp().page.onUnload(this), shoppingCart.saveItemData(this);
    },
    onPullDownRefresh: function() {
        getApp().page.onPullDownRefresh(this);
    },
    onReachBottom: function() {
        getApp().page.onReachBottom(this);
        var t = this;
        "active" == t.data.tab_detail && t.data.drop ? (t.data.drop = !1, t.goods_recommend({
            goods_id: t.data.goods.id,
            loadmore: !0
        })) : "active" == t.data.tab_comment && t.getCommentList(!0);
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        var o = this, t = getApp().getUser();
        return {
            path: "/pages/goods/goods?id=" + this.data.id + "&user_id=" + t.id,
            success: function(t) {
                1 == ++share_count && o.shareSendCoupon(o);
            },
            title: o.data.goods.name,
            imageUrl: o.data.goods.pic_list[0]
        };
    },
    closeCouponBox: function(t) {
        this.setData({
            get_coupon_list: ""
        });
    },
    to_dial: function(t) {
        var o = this.data.store.contact_tel;
        getApp().core.makePhoneCall({
            phoneNumber: o
        });
    },
    getGoods: function() {
        var n = this;
        if (n.data.quick) {
            var t = n.data.carGoods;
            if (t) {
                for (var o = t.length, e = 0, a = 0; a < o; a++) t[a].goods_id == n.data.id && (e += parseInt(t[a].num));
                n.setData({
                    goods_num: e
                });
            }
        }
        getApp().request({
            url: getApp().api.default.goods,
            data: {
                id: n.data.id
            },
            success: function(t) {
                if (0 == t.code) {
                    var o = t.data.detail;
                    WxParse.wxParse("detail", "html", o, n);
                    var e = t.data;
                    e.attr_pic = t.data.attr_pic, e.cover_pic = t.data.pic_list[0].pic_url;
                    var a = e.pic_list, i = [];
                    for (var s in a) i.push(a[s].pic_url);
                    e.pic_list = i, n.setData({
                        goods: e,
                        attr_group_list: t.data.attr_group_list,
                        btn: !0
                    }), n.goods_recommend({
                        goods_id: t.data.id,
                        reload: !0
                    }), n.selectDefaultAttr();
                }
                1 == t.code && getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    success: function(t) {
                        t.confirm && getApp().core.switchTab({
                            url: "/pages/index/index"
                        });
                    }
                });
            }
        });
    },
    getCommentList: function(o) {
        var e = this;
        o && "active" != e.data.tab_comment || is_loading_comment || is_more_comment && (is_loading_comment = !0, 
        getApp().request({
            url: getApp().api.default.comment_list,
            data: {
                goods_id: e.data.id,
                page: p
            },
            success: function(t) {
                0 == t.code && (is_loading_comment = !1, p++, e.setData({
                    comment_count: t.data.comment_count,
                    comment_list: o ? e.data.comment_list.concat(t.data.list) : t.data.list
                }), 0 == t.data.list.length && (is_more_comment = !1));
            }
        }));
    },
    tabSwitch: function(t) {
        "detail" == t.currentTarget.dataset.tab ? this.setData({
            tab_detail: "active",
            tab_comment: ""
        }) : this.setData({
            tab_detail: "",
            tab_comment: "active"
        });
    },
    commentPicView: function(t) {
        var o = t.currentTarget.dataset.index, e = t.currentTarget.dataset.picIndex;
        getApp().core.previewImage({
            current: this.data.comment_list[o].pic_list[e],
            urls: this.data.comment_list[o].pic_list
        });
    }
});