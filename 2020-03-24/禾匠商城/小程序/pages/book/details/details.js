var utils = require("../../../utils/helper.js"), WxParse = require("../../../wxParse/wxParse.js"), gSpecificationsModel = require("../../../components/goods/specifications_model.js"), goodsBanner = require("../../../components/goods/goods_banner.js"), goodsInfo = require("../../../components/goods/goods_info.js"), goodsBuy = require("../../../components/goods/goods_buy.js"), p = 1, is_loading_comment = !1, is_more_comment = !0;

Page({
    data: {
        pageType: "BOOK",
        hide: "hide",
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
        }
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var e = t.user_id, o = decodeURIComponent(t.scene);
        if (void 0 !== e) e; else if (void 0 !== o) {
            var a = utils.scene_decode(o);
            a.uid && a.gid ? (a.uid, t.id = a.gid) : o;
        } else if (null !== getApp().query) {
            var i = getApp().query;
            getApp().query = null, t.id = i.gid, i.uid;
        }
        this.setData({
            id: t.id
        }), p = 1, this.getGoodsInfo(t), this.getCommentList(!1);
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
        this.getCommentList(!0);
    },
    onShareAppMessage: function(t) {
        getApp().page.onShareAppMessage(this);
        var e = this, o = getApp().core.getStorageSync(getApp().const.USER_INFO);
        return {
            title: e.data.goods.name,
            path: "/pages/book/details/details?id=" + e.data.goods.id + "&user_id=" + o.id,
            imageUrl: e.data.goods.pic_list[0],
            success: function(t) {}
        };
    },
    getGoodsInfo: function(t) {
        var e = t.id, i = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.book.details,
            method: "get",
            data: {
                gid: e
            },
            success: function(t) {
                if (0 == t.code) {
                    var e = t.data.info.detail;
                    WxParse.wxParse("detail", "html", e, i);
                    var o = parseInt(t.data.info.virtual_sales) + parseInt(t.data.info.sales);
                    t.data.attr_group_list.length <= 0 && (t.data.attr_group_list = [ {
                        attr_group_name: "规格",
                        attr_list: [ {
                            attr_id: 0,
                            attr_name: "默认",
                            checked: !0
                        } ]
                    } ]);
                    var a = t.data.info;
                    a.num = t.data.info.stock, a.min_price = .01 < t.data.info.price ? t.data.info.price : "免费预约", 
                    a.price = .01 < t.data.info.price ? t.data.info.price : "免费预约", a.sales_volume = t.data.info.sales, 
                    a.service_list = t.data.info.service, i.setData({
                        goods: t.data.info,
                        shop: t.data.shopList,
                        sales: o,
                        attr_group_list: t.data.attr_group_list
                    }), i.selectDefaultAttr();
                } else getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    success: function(t) {
                        t.confirm && getApp().core.redirectTo({
                            url: "/pages/book/index/index"
                        });
                    }
                });
            },
            complete: function(t) {
                getApp().core.hideLoading();
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
    bespeakNow: function(t) {
        var e = this;
        if (!e.data.show_attr_picker) return e.setData({
            show_attr_picker: !0
        }), !0;
        for (var o = [], a = !0, i = e.data.attr_group_list, s = 0; s < i.length; s++) {
            var n = i[s].attr_list;
            a = !0;
            for (var r = 0; r < n.length; r++) n[r].checked && (o.push({
                attr_group_id: i[s].attr_group_id,
                attr_id: n[r].attr_id,
                attr_group_name: i[s].attr_group_name,
                attr_name: n[r].attr_name
            }), a = !1);
            if (a) return void getApp().core.showModal({
                title: "提示",
                content: "请选择" + i[s].attr_group_name,
                showCancel: !1
            });
        }
        var d = [ {
            id: e.data.goods.id,
            attr: o
        } ];
        getApp().core.redirectTo({
            url: "/pages/book/submit/submit?goods_info=" + JSON.stringify(d)
        });
    },
    goToShopList: function(t) {
        getApp().core.navigateTo({
            url: "/pages/book/shop/shop?ids=" + this.data.goods.shop_id,
            success: function(t) {},
            fail: function(t) {},
            complete: function(t) {}
        });
    },
    getCommentList: function(e) {
        var o = this;
        e && "active" != o.data.tab_comment || is_loading_comment || (is_loading_comment = !0, 
        getApp().request({
            url: getApp().api.book.goods_comment,
            data: {
                goods_id: o.data.id,
                page: p
            },
            success: function(t) {
                0 == t.code && (is_loading_comment = !1, p++, o.setData({
                    comment_count: t.data.comment_count,
                    comment_list: e ? o.data.comment_list.concat(t.data.list) : t.data.list
                }), 0 == t.data.list.length && (is_more_comment = !1));
            }
        }));
    }
});