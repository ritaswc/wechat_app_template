module.exports = {
    currentPage: null,
    gSpecificationsModel: null,
    init: function(t) {
        this.currentPage = t;
        var o = this;
        this.gSpecificationsModel = require("../../components/goods/specifications_model.js"), 
        this.gSpecificationsModel.init(t), void 0 === t.showNotice && (t.showNotice = function() {
            o.showNotice();
        }), void 0 === t.closeNotice && (t.closeNotice = function() {
            o.closeNotice();
        }), void 0 === t.play && (t.play = function(t) {
            o.play(t);
        }), void 0 === t.receive && (t.receive = function(t) {
            o.receive(t);
        }), void 0 === t.closeCouponBox && (t.closeCouponBox = function(t) {
            o.closeCouponBox(t);
        }), void 0 === t.catBind && (t.catBind = function(t) {
            o.catBind(t);
        }), void 0 === t.modalShowGoods && (t.modalShowGoods = function(t) {
            o.modalShowGoods(t);
        }), void 0 === t.modalConfirmGoods && (t.modalConfirmGoods = function(t) {
            o.modalConfirmGoods(t);
        }), void 0 === t.modalCloseGoods && (t.modalCloseGoods = function(t) {
            o.modalCloseGoods(t);
        }), void 0 === t.setTime && (t.setTime = function(t) {
            o.setTime(t);
        }), void 0 === t.closeActModal && (t.closeActModal = function(t) {
            o.closeActModal(t);
        }), void 0 === t.goto && (t.goto = function(t) {
            o.goto(t);
        }), void 0 === t.go && (t.go = function(t) {
            o.go(t);
        }), void 0 === t.couponNavigator && (t.couponNavigator = function(t) {
            o.couponNavigator(t);
        });
    },
    showNotice: function() {
        this.currentPage.setData({
            show_notice: !0
        });
    },
    closeNotice: function() {
        this.currentPage.setData({
            show_notice: !1
        });
    },
    play: function(t) {
        this.currentPage.setData({
            play: t.currentTarget.dataset.index
        });
    },
    receive: function(t) {
        var e = this.currentPage, o = t.currentTarget.dataset.index;
        getApp().core.showLoading({
            title: "领取中",
            mask: !0
        }), e.hideGetCoupon || (e.hideGetCoupon = function(t) {
            var o = t.currentTarget.dataset.url || !1;
            e.setData({
                get_coupon_list: null
            }), wx.navigateTo({
                url: o || "/pages/list/list"
            });
        }), getApp().request({
            url: getApp().api.coupon.receive,
            data: {
                id: o
            },
            success: function(t) {
                getApp().core.hideLoading(), 0 == t.code ? e.setData({
                    get_coupon_list: t.data.list,
                    coupon_list: t.data.coupon_list
                }) : (getApp().core.showToast({
                    title: t.msg,
                    duration: 2e3
                }), e.setData({
                    coupon_list: t.data.coupon_list
                }));
            }
        });
    },
    closeCouponBox: function(t) {
        this.currentPage.setData({
            get_coupon_list: ""
        });
    },
    catBind: function(t) {
        var o = this.currentPage, e = t.currentTarget.dataset.template, a = t.currentTarget.dataset.index, i = o.data.template;
        i[e].param.cat_index = a, o.setData({
            template: i
        });
    },
    modalShowGoods: function(t) {
        var o = this.currentPage, e = o.data.template, a = t.currentTarget.dataset.template, i = t.currentTarget.dataset.cat, s = t.currentTarget.dataset.goods, n = e[a].param.list[i].goods_list[s];
        "goods" == e[a].type ? (n.id = n.goods_id, o.setData({
            goods: n,
            show_attr_picker: !0,
            attr_group_list: n.attr_group_list,
            pageType: "STORE",
            id: n.id
        }), this.gSpecificationsModel.selectDefaultAttr()) : getApp().core.navigateTo({
            url: n.page_url
        });
    },
    modalConfirmGoods: function(t) {
        var o = this.currentPage, e = (o.data.pageType, require("../../components/goods/goods_buy.js"));
        e.currentPage = o, e.submit("ADD_CART"), o.setData({
            form: {
                number: 1
            }
        });
    },
    modalCloseGoods: function(t) {
        this.currentPage.setData({
            show_attr_picker: !1,
            form: {
                number: 1
            }
        });
    },
    template_time: null,
    setTime: function(t) {
        var a = this.currentPage, i = a.data.time_all;
        this["template_time_" + a.data.options.page_id] && clearInterval(this["template_time_" + a.data.options.page_id]), 
        this["template_time_" + a.data.options.page_id] = setInterval(function() {
            for (var t in i) if ("time" == i[t].type && (0 < i[t].param.start_time ? (i[t].param.start_time--, 
            i[t].param.end_time--, i[t].param.time_list = a.setTimeList(i[t].param.start_time)) : 0 < i[t].param.end_time && (i[t].param.end_time--, 
            i[t].param.time_list = a.setTimeList(i[t].param.end_time))), "miaosha" == i[t].type || "bargain" == i[t].type || "lottery" == i[t].type) {
                var o = i[t].param.cat_index;
                for (var e in i[t].param.list[o].goods_list) 0 < i[t].param.list[o].goods_list[e].time ? (i[t].param.list[o].goods_list[e].time--, 
                i[t].param.list[o].goods_list[e].time_list = a.setTimeList(i[t].param.list[o].goods_list[e].time), 
                0 < i[t].param.list[o].goods_list[e].time_end && (i[t].param.list[o].goods_list[e].time_end--, 
                1 == i[t].param.list[o].goods_list[e].time && (i[t].param.list[o].goods_list[e].is_start = 1, 
                i[t].param.list[o].goods_list[e].time = i[t].param.list[o].goods_list[e].time_end, 
                i[t].param.list[o].goods_list[e].time_end = 0, i[t].param.list[o].goods_list[e].time_content = 1 == i[t].param.list_style ? "仅剩" : "距结束仅剩"))) : (i[t].param.list[o].goods_list[e].is_start = 1, 
                i[t].param.list[o].goods_list[e].time = 0, i[t].param.list[o].goods_list[e].time_content = "活动已结束", 
                i[t].param.list[o].goods_list[e].time_list = {});
            }
            a.setData({
                time_all: i
            });
        }, 1e3);
    },
    closeActModal: function() {
        var t, o = this.currentPage, e = o.data.act_modal_list, a = !0;
        for (var i in e) {
            var s = parseInt(i);
            e[s].show && (e[s].show = !1, void 0 !== e[t = s + 1] && a && (a = !1, setTimeout(function() {
                o.data.act_modal_list[t].show = !0, o.setData({
                    act_modal_list: o.data.act_modal_list
                });
            }, 500)));
        }
        o.setData({
            act_modal_list: e
        });
    },
    goto: function(o) {
        var e = this;
        "undefined" != typeof my ? e.location(o) : getApp().core.getSetting({
            success: function(t) {
                t.authSetting["scope.userLocation"] ? e.location(o) : getApp().getauth({
                    content: "需要获取您的地理位置授权，请到小程序设置中打开授权！",
                    cancel: !1,
                    author: "scope.userLocation",
                    success: function(t) {
                        console.log(t), t.authSetting["scope.userLocation"] && e.location(o);
                    }
                });
            }
        });
    },
    location: function(t) {
        var o = this.currentPage, e = [], a = t.currentTarget.dataset.template;
        e = void 0 !== a ? o.data.template[a].param.list : o.data.list;
        var i = t.currentTarget.dataset.index;
        getApp().core.openLocation({
            latitude: parseFloat(e[i].latitude),
            longitude: parseFloat(e[i].longitude),
            name: e[i].name,
            address: e[i].address
        });
    },
    go: function(t) {
        var o = this.currentPage, e = t.currentTarget.dataset.template, a = [];
        a = void 0 !== e ? o.data.template[e].param.list : o.data.list;
        var i = t.currentTarget.dataset.index;
        getApp().core.navigateTo({
            url: "/pages/shop-detail/shop-detail?shop_id=" + a[i].id
        });
    },
    couponNavigator: function(t) {
        var o = t.currentTarget.dataset.index;
        getApp().core.navigateTo({
            url: "/pages/integral-mall/coupon-info/index?coupon_id=" + o
        });
    }
};