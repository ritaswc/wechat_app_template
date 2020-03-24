module.exports = {
    currentPage: null,
    init: function(t) {
        var a = this;
        void 0 === (a.currentPage = t).favoriteAdd && (t.favoriteAdd = function(t) {
            a.favoriteAdd(t);
        }), void 0 === t.favoriteRemove && (t.favoriteRemove = function(t) {
            a.favoriteRemove(t);
        }), void 0 === t.kfMessage && (t.kfMessage = function(t) {
            a.kfMessage(t);
        }), void 0 === t.callPhone && (t.callPhone = function(t) {
            a.callPhone(t);
        }), void 0 === t.addCart && (t.addCart = function(t) {
            a.addCart(t);
        }), void 0 === t.buyNow && (t.buyNow = function(t) {
            a.buyNow(t);
        }), void 0 === t.goHome && (t.goHome = function(t) {
            a.goHome(t);
        });
    },
    favoriteAdd: function() {
        var e = this.currentPage;
        getApp().request({
            url: getApp().api.user.favorite_add,
            method: "post",
            data: {
                goods_id: e.data.goods.id
            },
            success: function(t) {
                if (0 == t.code) {
                    var a = e.data.goods;
                    a.is_favorite = 1, e.setData({
                        goods: a
                    });
                }
            }
        });
    },
    favoriteRemove: function() {
        var e = this.currentPage;
        getApp().request({
            url: getApp().api.user.favorite_remove,
            method: "post",
            data: {
                goods_id: e.data.goods.id
            },
            success: function(t) {
                if (0 == t.code) {
                    var a = e.data.goods;
                    a.is_favorite = 0, e.setData({
                        goods: a
                    });
                }
            }
        });
    },
    kfMessage: function() {
        getApp().core.getStorageSync(getApp().const.STORE).show_customer_service || getApp().core.showToast({
            title: "未启用客服功能"
        });
    },
    callPhone: function(t) {
        getApp().core.makePhoneCall({
            phoneNumber: t.target.dataset.info
        });
    },
    addCart: function() {
        this.currentPage.data.btn && this.submit("ADD_CART");
    },
    buyNow: function() {
        this.currentPage.data.btn && this.submit("BUY_NOW");
    },
    submit: function(t) {
        var a = this.currentPage;
        if (!a.data.show_attr_picker) return a.setData({
            show_attr_picker: !0
        }), !0;
        if (a.data.miaosha_data && 0 < a.data.miaosha_data.rest_num && a.data.form.number > a.data.miaosha_data.rest_num) return getApp().core.showToast({
            title: "商品库存不足，请选择其它规格或数量",
            image: "/images/icon-warning.png"
        }), !0;
        if (a.data.form.number > a.data.goods.num) return getApp().core.showToast({
            title: "商品库存不足，请选择其它规格或数量",
            image: "/images/icon-warning.png"
        }), !0;
        var e = a.data.attr_group_list, o = [];
        for (var r in e) {
            var i = !1;
            for (var s in e[r].attr_list) if (e[r].attr_list[s].checked) {
                i = {
                    attr_id: e[r].attr_list[s].attr_id,
                    attr_name: e[r].attr_list[s].attr_name
                };
                break;
            }
            if (!i) return getApp().core.showToast({
                title: "请选择" + e[r].attr_group_name,
                image: "/images/icon-warning.png"
            }), !0;
            o.push({
                attr_group_id: e[r].attr_group_id,
                attr_id: i.attr_id
            });
        }
        if ("ADD_CART" == t && (getApp().core.showLoading({
            title: "正在提交",
            mask: !0
        }), getApp().request({
            url: getApp().api.cart.add_cart,
            method: "POST",
            data: {
                goods_id: a.data.goods.id,
                attr: JSON.stringify(o),
                num: a.data.form.number
            },
            success: function(t) {
                getApp().core.hideLoading(), getApp().core.showToast({
                    title: t.msg,
                    duration: 1500
                }), a.setData({
                    show_attr_picker: !1
                });
            }
        })), "BUY_NOW" == t) {
            a.setData({
                show_attr_picker: !1
            });
            var d = [];
            d.push({
                goods_id: a.data.id,
                num: a.data.form.number,
                attr: o
            });
            var n = a.data.goods, g = 0;
            null != n.mch && (g = n.mch.id);
            var u = [];
            u.push({
                mch_id: g,
                goods_list: d
            }), getApp().core.redirectTo({
                url: "/pages/new-order-submit/new-order-submit?mch_list=" + JSON.stringify(u)
            });
        }
    },
    goHome: function(t) {
        var a = this.currentPage.data.pageType;
        if ("PINTUAN" === a) var e = "/pages/pt/index/index"; else if ("BOOK" === a) e = "/pages/book/index/index"; else e = "/pages/index/index";
        getApp().core.redirectTo({
            url: e
        });
    }
};