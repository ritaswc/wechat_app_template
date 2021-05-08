var WxParse = require("../../wxParse/wxParse.js"), shoppingCart = require("../../components/shopping_cart/shopping_cart.js"), specificationsModel = require("../../components/specifications_model/specifications_model.js"), gSpecificationsModel = require("../../components/goods/specifications_model.js"), goodsBanner = require("../../components/goods/goods_banner.js"), goodsInfo = require("../../components/goods/goods_info.js"), goodsBuy = require("../../components/goods/goods_buy.js"), quickNavigation = require("../../components/quick-navigation/quick-navigation.js"), p = 1, is_loading_comment = !1, is_more_comment = !0, share_count = 0;

Page({
    data: {
        pageType: "STEP",
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
        var e = this;
        share_count = 0, is_more_comment = !(is_loading_comment = !(p = 1));
        var o = t.quick;
        if (o) {
            var a = getApp().core.getStorageSync(getApp().const.ITEM);
            if (a) var i = a.total, s = a.carGoods; else i = {
                total_price: 0,
                total_num: 0
            }, s = [];
            e.setData({
                quick: o,
                quick_list: a.quick_list,
                total: i,
                carGoods: s,
                quick_hot_goods_lists: a.quick_hot_goods_lists
            });
        }
        if ("undefined" == typeof my) {
            var r = decodeURIComponent(t.scene);
            if (void 0 !== r) {
                var n = getApp().helper.scene_decode(r);
                n.uid && n.gid && (t.id = n.gid);
            }
        } else if (null !== getApp().query) {
            var d = app.query;
            getApp().query = null, t.id = d.gid;
        }
        e.setData({
            id: t.goods_id,
            user_id: t.user_id
        }), e.getGoods();
    },
    onReady: function() {
        getApp().page.onReady(this);
    },
    onShow: function() {
        getApp().page.onShow(this), shoppingCart.init(this), specificationsModel.init(this, shoppingCart), 
        gSpecificationsModel.init(this), goodsBanner.init(this), goodsInfo.init(this), goodsBuy.init(this), 
        quickNavigation.init(this);
        var t = getApp().core.getStorageSync(getApp().const.ITEM);
        if (t) var e = t.total, o = t.carGoods, a = this.data.goods_num; else e = {
            total_price: 0,
            total_num: 0
        }, o = [], a = 0;
        this.setData({
            total: e,
            carGoods: o,
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
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        var e = this, t = getApp().getUser();
        return {
            path: "/step/goods/goods?goods_id=" + this.data.id + "&user_id=" + t.id,
            success: function(t) {
                1 == ++share_count && e.shareSendCoupon(e);
            },
            title: e.data.goods.name,
            imageUrl: e.data.goods.pic_list[0]
        };
    },
    play: function(t) {
        var e = t.target.dataset.url;
        this.setData({
            url: e,
            hide: "",
            show: !0
        }), getApp().core.createVideoContext("video").play();
    },
    close: function(t) {
        if ("video" == t.target.id) return !0;
        this.setData({
            hide: "hide",
            show: !1
        }), getApp().core.createVideoContext("video").pause();
    },
    closeCouponBox: function(t) {
        this.setData({
            get_coupon_list: ""
        });
    },
    to_dial: function(t) {
        var e = this.data.store.contact_tel;
        getApp().core.makePhoneCall({
            phoneNumber: e
        });
    },
    getGoods: function() {
        var r = this;
        if (r.data.quick) {
            var t = r.data.carGoods;
            if (t) {
                for (var e = t.length, o = 0, a = 0; a < e; a++) t[a].goods_id == r.data.id && (o += parseInt(t[a].num));
                r.setData({
                    goods_num: o
                });
            }
        }
        getApp().request({
            url: getApp().api.step.goods,
            data: {
                goods_id: r.data.id,
                user_id: r.data.user_id
            },
            success: function(t) {
                if (0 == t.code) {
                    var e = t.data.goods.detail;
                    WxParse.wxParse("detail", "html", e, r);
                    var o = t.data.goods;
                    o.attr_pic = t.data.goods.attr_pic, o.cover_pic = t.data.goods.pic_list[0].pic_url;
                    var a = o.pic_list, i = [];
                    for (var s in a) i.push(a[s].pic_url);
                    o.pic_list = i, r.setData({
                        goods: o,
                        attr_group_list: t.data.goods.attr_group_list,
                        btn: !0
                    }), r.selectDefaultAttr();
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
        var e = t.currentTarget.dataset.index, o = t.currentTarget.dataset.picIndex;
        getApp().core.previewImage({
            current: this.data.comment_list[e].pic_list[o],
            urls: this.data.comment_list[e].pic_list
        });
    },
    exchangeGoods: function() {
        var t = this;
        if (!t.data.show_attr_picker) return t.setData({
            show_attr_picker: !0
        }), !0;
        var e = t.data.attr_group_list, o = [];
        for (var a in e) {
            var i = !1;
            for (var s in e[a].attr_list) if (e[a].attr_list[s].checked) {
                i = {
                    attr_id: e[a].attr_list[s].attr_id,
                    attr_name: e[a].attr_list[s].attr_name
                };
                break;
            }
            if (!i) return getApp().core.showToast({
                title: "请选择" + e[a].attr_group_name,
                image: "/images/icon-warning.png"
            }), !0;
            o.push({
                attr_group_id: e[a].attr_group_id,
                attr_group_name: e[a].attr_group_name,
                attr_id: i.attr_id,
                attr_name: i.attr_name
            });
        }
        var r = t.data.form.number, n = t.data.goods;
        if (r <= 0 || r > n.num) return getApp().core.showToast({
            title: "商品库存不足!",
            image: "/images/icon-warning.png"
        }), !0;
        t.setData({
            show_attr_picker: !1
        }), getApp().core.navigateTo({
            url: "/pages/order-submit/order-submit?step_id=" + n.id + "&goods_info=" + JSON.stringify({
                goods_id: n.id,
                attr: o,
                num: r
            })
        });
    }
});