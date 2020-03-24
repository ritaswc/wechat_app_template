module.exports = {
    currentPage: null,
    shoppingCart: null,
    init: function(t, r) {
        var a = this;
        a.currentPage = t, a.shoppingCart = r, void 0 === t.showDialogBtn && (t.showDialogBtn = function(t) {
            a.showDialogBtn(t);
        }), void 0 === t.attrClick && (t.attrClick = function(t) {
            a.attrClick(t);
        }), void 0 === t.onConfirm && (t.onConfirm = function(t) {
            a.onConfirm(t);
        }), void 0 === t.guigejian && (t.guigejian = function(t) {
            a.guigejian(t);
        }), void 0 === t.close_box && (t.close_box = function(t) {
            a.close_box(t);
        }), void 0 === t.hideModal && (t.hideModal = function(t) {
            a.hideModal(t);
        });
    },
    showDialogBtn: function(t) {
        var r = this.currentPage, a = this, i = t.currentTarget.dataset;
        getApp().request({
            url: getApp().api.default.goods,
            data: {
                id: i.id
            },
            success: function(t) {
                0 == t.code && (r.setData({
                    currentGood: t.data,
                    goods_name: t.data.name,
                    attr_group_list: t.data.attr_group_list,
                    showModal: !0
                }), a.resetData(), a.updateData(), a.checkAttrNum());
            }
        });
    },
    resetData: function() {
        this.currentPage.setData({
            checked_attr: [],
            check_num: 0,
            check_goods_price: 0,
            temporaryGood: {
                price: "0.00",
                num: 0
            }
        });
    },
    updateData: function() {
        var t = this.currentPage, r = t.data.currentGood, a = t.data.carGoods, i = JSON.parse(r.attr), o = r.attr_group_list;
        for (var e in i) {
            var n = [];
            for (var s in i[e].attr_list) n.push([ i[e].attr_list[s].attr_id, r.id ]);
            for (var d in a) {
                var c = [];
                for (var u in a[d].attr) c.push([ a[d].attr[u].attr_id, a[d].goods_id ]);
                if (n.sort().join() === c.sort().join()) {
                    for (var _ in o) for (var p in o[_].attr_list) for (var h in n) {
                        if (parseInt(o[_].attr_list[p].attr_id) === parseInt(n[h])) {
                            o[_].attr_list[p].checked = !0;
                            break;
                        }
                        o[_].attr_list[p].checked = !1;
                    }
                    var g = {
                        price: a[d].price
                    };
                    return void t.setData({
                        attr_group_list: o,
                        check_num: a[d].num,
                        check_goods_price: a[d].goods_price,
                        checked_attr: n,
                        temporaryGood: g
                    });
                }
            }
        }
    },
    checkUpdateData: function(t) {
        var r = this.currentPage, a = r.data.carGoods;
        for (var i in a) {
            var o = [];
            for (var e in a[i].attr) o.push([ a[i].attr[e].attr_id, a[i].goods_id ]);
            o.sort().join() === t.sort().join() && r.setData({
                check_num: a[i].num,
                check_goods_price: a[i].goods_price
            });
        }
    },
    attrClick: function(t) {
        var r = this.currentPage, a = parseInt(t.target.dataset.groupId), i = parseInt(t.target.dataset.id), o = r.data.attr_group_list, e = r.data.currentGood, n = JSON.parse(e.attr), s = [];
        for (var d in o) if (o[d].attr_group_id == a) for (var c in o[d].attr_list) {
            (G = o[d].attr_list[c]).attr_id == i && !0 !== G.checked ? G.checked = !0 : G.checked = !1;
        }
        var u = [];
        for (var d in o) for (var c in o[d].attr_list) {
            !0 === (G = o[d].attr_list[c]).checked && (u.push([ G.attr_id, e.id ]), s.push(G.attr_id));
        }
        var _ = JSON.parse(e.attr), p = r.data.temporaryGood;
        for (var h in _) {
            var g = [];
            for (var l in _[h].attr_list) g.push([ _[h].attr_list[l].attr_id, e.id ]);
            if (g.sort().join() === u.sort().join()) {
                if (0 === parseInt(_[h].num)) return;
                p = parseFloat(_[h].price) ? {
                    price: parseFloat(_[h].price).toFixed(2),
                    num: _[h].num
                } : {
                    price: parseFloat(e.price).toFixed(2),
                    num: _[h].num
                };
            }
        }
        var v = [];
        for (var d in console.log(s), n) {
            g = [];
            var f = 0;
            for (var c in n[d].attr_list) getApp().helper.inArray(n[d].attr_list[c].attr_id, s) || (f += 1), 
            g.push(n[d].attr_list[c].attr_id);
            0 === n[d].num && f <= 1 && v.push(g);
        }
        var m = s.length, k = [];
        if (o.length - m <= 1) for (var d in s) for (var c in v) if (getApp().helper.inArray(s[d], v[c])) for (var h in v[c]) v[c][h] !== s[d] && k.push(v[c][h]);
        for (var d in console.log(k), console.log(s), o) for (var c in o[d].attr_list) {
            var G = o[d].attr_list[c];
            getApp().helper.inArray(G.attr_id, k) && !getApp().helper.inArray(G.attr_id, s) ? G.is_attr_num = !0 : G.is_attr_num = !1;
        }
        this.resetData(), this.checkUpdateData(u), r.setData({
            attr_group_list: o,
            temporaryGood: p,
            checked_attr: u
        });
    },
    checkAttrNum: function() {
        var t = this.currentPage, r = t.data.attr_group_list, a = JSON.parse(t.data.currentGood.attr), i = t.data.checked_attr, o = [];
        for (var e in i) o.push(i[e][0]);
        for (var e in a) {
            var n = [], s = 0;
            for (var d in a[e].attr_list) {
                var c = a[e].attr_list[d].attr_id;
                getApp().helper.inArray(c, o) ? s += 1 : n.push(c);
            }
            if (r.length - s == 1 && 0 == a[e].num) for (var u in r) for (var _ in r[u].attr_list) {
                var p = r[u].attr_list[_];
                getApp().helper.inArray(p.attr_id, n) && (p.is_attr_num = !0);
            }
        }
        t.setData({
            attr_group_list: r
        });
    },
    onConfirm: function(t) {
        var r = this.currentPage, a = r.data.attr_group_list, i = r.data.checked_attr, o = r.data.currentGood;
        if (i.length === a.length) {
            var e = r.data.check_num ? r.data.check_num + 1 : 1, n = JSON.parse(o.attr);
            for (var s in n) {
                var d = [];
                for (var c in n[s].attr_list) if (d.push([ n[s].attr_list[c].attr_id, o.id ]), d.sort().join() === i.sort().join()) {
                    var u = n[s].price ? n[s].price : o.price, _ = n[s].attr_list;
                    if (e > n[s].num) return void wx.showToast({
                        title: "商品库存不足",
                        image: "/images/icon-warning.png"
                    });
                }
            }
            var p = r.data.carGoods, h = 1, g = parseFloat(u * e).toFixed(2);
            for (var l in p) {
                var v = [];
                for (var f in p[l].attr) v.push([ p[l].attr[f].attr_id, p[l].goods_id ]);
                if (v.sort().join() === i.sort().join()) {
                    h = 0, p[l].num = p[l].num + 1, p[l].goods_price = parseFloat(u * p[l].num).toFixed(2);
                    break;
                }
            }
            1 !== h && 0 !== p.length || p.push({
                goods_id: o.id,
                attr: _,
                goods_name: o.name,
                goods_price: u,
                num: 1,
                price: u
            }), r.setData({
                carGoods: p,
                check_goods_price: g,
                check_num: e
            }), this.shoppingCart.carStatistics(r), this.attrGoodStatistics(), this.shoppingCart.updateGoodNum();
        } else wx.showToast({
            title: "请选择规格",
            image: "/images/icon-warning.png"
        });
    },
    guigejian: function(t) {
        var r = this.currentPage, a = r.data.checked_attr, i = r.data.carGoods, o = r.data.check_num ? --r.data.check_num : 1;
        r.data.currentGood;
        for (var e in i) {
            var n = [];
            for (var s in i[e].attr) n.push([ i[e].attr[s].attr_id, i[e].goods_id ]);
            if (n.sort().join() === a.sort().join()) return 0 < i[e].num && (i[e].num -= 1, 
            i[e].goods_price = parseFloat(i[e].num * i[e].price).toFixed(2)), r.setData({
                carGoods: i,
                check_goods_price: i[e].goods_price,
                check_num: o
            }), this.shoppingCart.carStatistics(r), this.attrGoodStatistics(), void this.shoppingCart.updateGoodNum();
        }
    },
    attrGoodStatistics: function() {
        var t = this.currentPage, r = t.data.currentGood, a = t.data.carGoods, i = t.data.quick_list, o = t.data.quick_hot_goods_lists, e = 0;
        for (var n in a) a[n].goods_id === r.id && (e += a[n].num);
        for (var n in i) for (var s in i[n].goods) parseInt(i[n].goods[s].id) === r.id && (i[n].goods[s].num = e);
        for (var n in o) parseInt(o[n].id) === r.id && (o[n].num = e);
        t.setData({
            quick_list: i,
            quick_hot_goods_lists: o
        });
    },
    close_box: function(t) {
        this.currentPage.setData({
            showModal: !1
        });
    },
    hideModal: function() {
        this.currentPage.setData({
            showModal: !1
        });
    }
};