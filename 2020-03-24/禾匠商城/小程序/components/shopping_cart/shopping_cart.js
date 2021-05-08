module.exports = {
    currentPage: null,
    init: function(t) {
        var o = this;
        void 0 === (o.currentPage = t).shoppingCartListModel && (t.shoppingCartListModel = function(t) {
            o.shoppingCartListModel(t);
        }), void 0 === t.hideShoppingCart && (t.hideShoppingCart = function(t) {
            o.hideShoppingCart(t);
        }), void 0 === t.clearShoppingCart && (t.clearShoppingCart = function(t) {
            o.clearShoppingCart(t);
        }), void 0 === t.jia && (t.jia = function(t) {
            o.jia(t);
        }), void 0 === t.jian && (t.jian = function(t) {
            o.jian(t);
        }), void 0 === t.goodNumChange && (t.goodNumChange = function(t) {
            o.goodNumChange(t);
        }), void 0 === t.buynow && (t.buynow = function(t) {
            o.buynow(t);
        });
    },
    carStatistics: function(t) {
        var o = t.data.carGoods, a = 0, i = 0;
        for (var r in o) a += o[r].num, i = parseFloat(i) + parseFloat(o[r].goods_price);
        var s = {
            total_num: a,
            total_price: i.toFixed(2)
        };
        0 === a && this.hideShoppingCart(t), t.setData({
            total: s
        });
    },
    hideShoppingCart: function() {
        this.currentPage.setData({
            shoppingCartModel: !1
        });
    },
    shoppingCartListModel: function() {
        var t = this.currentPage, o = (t.data.carGoods, t.data.shoppingCartModel);
        console.log(o), o ? t.setData({
            shoppingCartModel: !1
        }) : t.setData({
            shoppingCartModel: !0
        });
    },
    clearShoppingCart: function(t) {
        var o = (t = this.currentPage).data.quick_hot_goods_lists, a = t.data.quick_list;
        for (var i in o) for (var r in o[i]) o[i].num = 0;
        for (var s in a) for (var n in a[s].goods) a[s].goods[n].num = 0;
        t.setData({
            goodsModel: !1,
            carGoods: [],
            total: {
                total_num: 0,
                total_price: 0
            },
            check_num: 0,
            quick_hot_goods_lists: o,
            quick_list: a,
            currentGood: [],
            checked_attr: [],
            check_goods_price: 0,
            temporaryGood: {},
            goodNumCount: 0,
            goods_num: 0
        }), t.setData({
            shoppingCartModel: !1
        }), getApp().core.removeStorageSync(getApp().const.ITEM);
    },
    saveItemData: function(t) {
        var o = {
            quick_list: t.data.quick_list,
            carGoods: t.data.carGoods,
            total: t.data.total,
            quick_hot_goods_lists: t.data.quick_hot_goods_lists,
            checked_attr: t.data.checked_attr
        };
        getApp().core.setStorageSync(getApp().const.ITEM, o);
    },
    jia: function(t) {
        var o = this.currentPage, a = t.currentTarget.dataset, i = o.data.quick_list;
        for (var r in i) for (var s in i[r].goods) {
            var n = i[r].goods[s];
            if (parseInt(n.id) === parseInt(a.id)) {
                var e = n.num ? n.num + 1 : 1;
                if (e > JSON.parse(n.attr)[0].num) return void wx.showToast({
                    title: "商品库存不足",
                    image: "/images/icon-warning.png"
                });
                n.num = e;
                var d = o.data.carGoods, c = 1, u = a.price ? a.price : n.price;
                for (var g in d) {
                    if (parseInt(d[g].goods_id) === parseInt(n.id) && 1 === JSON.parse(n.attr).length) {
                        c = 0, d[g].num = e, d[g].goods_price = (d[g].num * d[g].price).toFixed(2);
                        break;
                    }
                    var p = a.index;
                    if (d[p]) {
                        c = 0, d[p].num = d[p].num + 1, d[p].goods_price = (d[p].num * d[p].price).toFixed(2);
                        break;
                    }
                }
                if (1 === c || 0 === d.length) {
                    var h = JSON.parse(i[r].goods[s].attr);
                    d.push({
                        goods_id: parseInt(i[r].goods[s].id),
                        attr: h[0].attr_list,
                        goods_name: i[r].goods[s].name,
                        goods_price: u,
                        num: 1,
                        price: u
                    });
                }
            }
        }
        o.setData({
            carGoods: d,
            quick_list: i
        }), this.carStatistics(o), this.quickHotStatistics(), this.updateGoodNum();
    },
    jian: function(t) {
        var o = this.currentPage, a = t.currentTarget.dataset, i = o.data.quick_list;
        for (var r in i) for (var s in i[r].goods) {
            var n = i[r].goods[s];
            if (parseInt(n.id) === parseInt(a.id)) {
                var e = 0 < n.num ? n.num - 1 : n.num;
                n.num = e;
                var d = o.data.carGoods;
                for (var c in d) {
                    a.price ? a.price : n.price;
                    if (parseInt(d[c].goods_id) === parseInt(n.id) && 1 === JSON.parse(n.attr).length) {
                        d[c].num = e, d[c].goods_price = (d[c].num * d[c].price).toFixed(2);
                        break;
                    }
                    var u = a.index;
                    if (d[u] && 0 < d[u].num) {
                        d[u].num = d[u].num - 1, d[u].goods_price = (d[u].num * d[u].price).toFixed(2);
                        break;
                    }
                }
            }
        }
        o.setData({
            carGoods: d,
            quick_list: i
        }), this.carStatistics(o), this.quickHotStatistics(), this.updateGoodNum();
    },
    goodNumChange: function(t) {
        var o = this.currentPage, a = parseInt(t.detail.value) ? parseInt(t.detail.value) : 0, i = t.target.dataset.id ? parseInt(t.target.dataset.id) : o.data.currentGood.id, r = o.data.carGoods, s = o.data.quick_list, n = o.data.quick_hot_goods_lists, e = a, d = 0, c = "";
        for (var u in s) for (var g in s[u].goods) {
            var p = parseInt(s[u].goods[g].use_attr);
            if ((C = parseInt(s[u].goods[g].id)) === i && 0 === p) {
                var h = parseInt(s[u].goods[g].goods_num);
                h < a && (wx.showToast({
                    title: "商品库存不足",
                    image: "/images/icon-warning.png"
                }), e = h), s[u].goods[g].num = e, d = p;
            }
            if (C === i && 1 === p) {
                var _ = o.data.temporaryGood;
                _.num < a && (wx.showToast({
                    title: "商品库存不足",
                    image: "/images/icon-warning.png"
                }), e = _.num), d = p, c = s[u].goods[g], o.setData({
                    check_goods_price: (e * _.price).toFixed(2)
                });
            }
        }
        var m = 0;
        for (var l in r) {
            if ((C = parseInt(r[l].goods_id)) === i && 0 === d && (r[l].num = e, r[l].goods_price = (e * r[l].price).toFixed(2)), 
            C === i && 1 === d) {
                var v = o.data.checked_attr, f = r[l].attr, k = [];
                for (var u in f) k.push([ f[u].attr_id, i ]);
                k.sort().join() === v.sort().join() && (r[l].num = e, r[l].goods_price = (e * r[l].price).toFixed(2));
            }
            C === i && (m += r[l].num);
        }
        for (var S in 1 === d && (c.num = m), n) {
            var C;
            (C = parseInt(n[S].id)) === i && 0 === d && (n[S].num = e), C === i && 1 === d && (n[S].num = m);
        }
        o.setData({
            carGoods: r,
            quick_list: s,
            quick_hot_goods_lists: n
        }), this.carStatistics(o);
    },
    quickHotStatistics: function() {
        var t = this.currentPage, o = t.data.quick_hot_goods_lists, a = t.data.quick_list;
        for (var i in o) for (var r in a) for (var s in a[r].goods) parseInt(a[r].goods[s].id) === parseInt(o[i].id) && (o[i].num = a[r].goods[s].num);
        t.setData({
            quick_hot_goods_lists: o
        });
    },
    updateGoodNum: function() {
        var t = this.currentPage, o = t.data.quick_list, a = t.data.goods;
        if (o && a) for (var i in o) for (var r in o[i].goods) if (parseInt(o[i].goods[r].id) === parseInt(a.id)) {
            var s = o[i].goods[r].num, n = o[i].goods[r].num;
            t.setData({
                goods_num: n,
                goodNumCount: s
            });
            break;
        }
    },
    buynow: function(t) {
        var o = this.currentPage, a = o.data.carGoods;
        o.data.goodsModel;
        o.setData({
            goodsModel: !1
        });
        for (var i = a.length, r = [], s = [], n = 0; n < i; n++) 0 != a[n].num && (s = {
            goods_id: a[n].goods_id,
            num: a[n].num,
            attr: a[n].attr
        }, r.push(s));
        var e = [];
        e.push({
            mch_id: 0,
            goods_list: r
        }), getApp().core.navigateTo({
            url: "/pages/new-order-submit/new-order-submit?mch_list=" + JSON.stringify(e)
        }), this.clearShoppingCart();
    }
};