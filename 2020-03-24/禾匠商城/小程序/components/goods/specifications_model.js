module.exports = {
    currentPage: null,
    init: function(t) {
        var a = this;
        void 0 === (a.currentPage = t).previewImage && (t.previewImage = function(t) {
            a.previewImage(t);
        }), void 0 === t.showAttrPicker && (t.showAttrPicker = function(t) {
            a.showAttrPicker(t);
        }), void 0 === t.hideAttrPicker && (t.hideAttrPicker = function(t) {
            a.hideAttrPicker(t);
        }), void 0 === t.storeAttrClick && (t.storeAttrClick = function(t) {
            a.storeAttrClick(t);
        }), void 0 === t.numberAdd && (t.numberAdd = function(t) {
            a.numberAdd(t);
        }), void 0 === t.numberSub && (t.numberSub = function(t) {
            a.numberSub(t);
        }), void 0 === t.numberBlur && (t.numberBlur = function(t) {
            a.numberBlur(t);
        }), void 0 === t.selectDefaultAttr && (t.selectDefaultAttr = function(t) {
            a.selectDefaultAttr(t);
        });
    },
    previewImage: function(t) {
        var a = t.currentTarget.dataset.url;
        getApp().core.previewImage({
            urls: [ a ]
        });
    },
    hideAttrPicker: function() {
        this.currentPage.setData({
            show_attr_picker: !1
        });
    },
    showAttrPicker: function() {
        this.currentPage.setData({
            show_attr_picker: !0
        });
    },
    storeAttrClick: function(t) {
        var e = this.currentPage, a = t.target.dataset.groupId, r = parseInt(t.target.dataset.id), i = JSON.parse(JSON.stringify(e.data.attr_group_list)), o = e.data.goods.attr, s = [];
        for (var n in "string" == typeof o && (o = JSON.parse(o)), i) if (i[n].attr_group_id == a) for (var d in i[n].attr_list) {
            var p = i[n].attr_list[d];
            parseInt(p.attr_id) === r && p.checked ? p.checked = !1 : p.checked = parseInt(p.attr_id) === r;
        }
        for (var n in i) for (var d in i[n].attr_list) i[n].attr_list[d].checked && s.push(i[n].attr_list[d].attr_id);
        for (var n in i) for (var d in i[n].attr_list) {
            if ((p = i[n].attr_list[d]).attr_id === r && !0 === p.attr_num_0) return;
        }
        var u = [];
        for (var n in o) {
            var c = [], _ = 0;
            for (var d in o[n].attr_list) getApp().helper.inArray(o[n].attr_list[d].attr_id, s) || (_ += 1), 
            c.push(o[n].attr_list[d].attr_id);
            0 === o[n].num && _ <= 1 && u.push(c);
        }
        var g = s.length, l = [];
        if (i.length - g <= 1) for (var n in s) for (var d in u) if (getApp().helper.inArray(s[n], u[d])) for (var f in u[d]) u[d][f] !== s[n] && l.push(u[d][f]);
        for (var n in i) for (var d in i[n].attr_list) {
            var m = i[n].attr_list[d];
            getApp().helper.inArray(m.attr_id, l) && !getApp().helper.inArray(m.attr_id, s) ? m.attr_num_0 = !0 : m.attr_num_0 = !1;
        }
        e.setData({
            attr_group_list: i
        });
        var h = [], A = !0;
        for (var n in i) {
            var v = !1;
            for (var d in i[n].attr_list) if (i[n].attr_list[d].checked) {
                if ("INTEGRAL" !== e.data.pageType) {
                    h.push(i[n].attr_list[d].attr_id), v = !0;
                    break;
                }
                o = {
                    attr_id: i[n].attr_list[d].attr_id,
                    attr_name: i[n].attr_list[d].attr_name
                };
                h.push(o);
            }
            if ("INTEGRAL" !== e.data.pageType && !v) {
                A = !1;
                break;
            }
        }
        if ("INTEGRAL" === e.data.pageType || A) {
            getApp().core.showLoading({
                title: "正在加载",
                mask: !0
            });
            var b = e.data.pageType;
            v = 0;
            if ("STORE" === b) var k = getApp().api.default.goods_attr_info; else if ("PINTUAN" === b) {
                k = getApp().api.group.goods_attr_info;
                v = e.data.group_checked;
            } else {
                if ("INTEGRAL" === b) return getApp().core.hideLoading(), void this.integralMallAttrClick(h);
                if ("BOOK" === b) k = getApp().api.book.goods_attr_info; else if ("STEP" === b) k = getApp().api.default.goods_attr_info; else {
                    if ("MIAOSHA" !== b) return getApp().core.showModal({
                        title: "提示",
                        content: "pageType变量未定义或变量值不是预期的"
                    }), void getApp().core.hideLoading();
                    k = getApp().api.default.goods_attr_info;
                }
            }
            getApp().request({
                url: k,
                data: {
                    goods_id: "MIAOSHA" === b ? e.data.id : e.data.goods.id,
                    group_id: e.data.group_checked,
                    attr_list: JSON.stringify(h),
                    type: "MIAOSHA" === b ? "ms" : "",
                    group_checked: v
                },
                success: function(t) {
                    if (getApp().core.hideLoading(), 0 == t.code) {
                        var a = e.data.goods;
                        if (a.price = t.data.price, a.num = t.data.num, a.attr_pic = t.data.pic, a.is_member_price = t.data.is_member_price, 
                        a.single_price = t.data.single_price ? t.data.single_price : 0, a.group_price = t.data.price, 
                        "MIAOSHA" === b) {
                            var r = t.data.miaosha;
                            a.price = r.price, a.num = r.miaosha_num, a.is_member_price = r.is_member_price, 
                            a.attr_pic = r.pic, e.setData({
                                miaosha_data: r
                            });
                        }
                        "BOOK" === b && (a.price = 0 < a.price ? a.price : "免费预约"), e.setData({
                            goods: a
                        });
                    }
                }
            });
        }
    },
    attrClick: function(t) {
        var r = this, a = t.target.dataset.groupId, e = t.target.dataset.id, i = r.data.attr_group_list;
        for (var o in i) if (i[o].attr_group_id == a) for (var s in i[o].attr_list) i[o].attr_list[s].attr_id == e ? i[o].attr_list[s].checked = !0 : i[o].attr_list[s].checked = !1;
        r.setData({
            attr_group_list: i
        });
        var n = [], d = !0;
        for (var o in i) {
            var p = !1;
            for (var s in i[o].attr_list) if (i[o].attr_list[s].checked) {
                n.push(i[o].attr_list[s].attr_id), p = !0;
                break;
            }
            if (!p) {
                d = !1;
                break;
            }
        }
        d && (getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.default.goods_attr_info,
            data: {
                goods_id: r.data.id,
                attr_list: JSON.stringify(n),
                type: "ms"
            },
            success: function(t) {
                if (getApp().core.hideLoading(), 0 == t.code) {
                    var a = r.data.goods;
                    a.price = t.data.price, a.num = t.data.num, a.attr_pic = t.data.pic, r.setData({
                        goods: a,
                        miaosha_data: t.data.miaosha
                    });
                }
            }
        }));
    },
    integralMallAttrClick: function(t) {
        var a = this.currentPage, r = a.data.goods, e = r.attr, i = 0, o = 0;
        for (var s in e) JSON.stringify(e[s].attr_list) == JSON.stringify(t) && (i = 0 < parseFloat(e[s].price) ? e[s].price : r.price, 
        o = 0 < parseInt(e[s].integral) ? e[s].integral : r.integral, r.attr_pic = e[s].pic, 
        r.num = e[s].num, a.setData({
            attr_integral: o,
            attr_num: e[s].num,
            attr_price: i,
            status: "attr",
            goods: r
        }));
    },
    numberSub: function() {
        var t = this.currentPage, a = t.data.form.number;
        if (a <= 1) return !0;
        a--, t.setData({
            form: {
                number: a
            }
        });
    },
    numberAdd: function() {
        var t = this.currentPage, a = t.data.form.number, r = t.data.pageType;
        if (!(++a > t.data.goods.one_buy_limit && 0 != t.data.goods.one_buy_limit)) return "MIAOSHA" === r && a > t.data.goods.miaosha.buy_max && 0 != t.data.goods.miaosha.buy_max ? (getApp().core.showToast({
            title: "一单限购" + t.data.goods.miaosha.buy_max,
            image: "/images/icon-warning.png"
        }), !0) : void t.setData({
            form: {
                number: a
            }
        });
        getApp().core.showModal({
            title: "提示",
            content: "数量超过最大限购数",
            showCancel: !1,
            success: function(t) {}
        });
    },
    numberBlur: function(t) {
        var a = this.currentPage, r = t.detail.value, e = a.data.pageType;
        if (r = parseInt(r), isNaN(r) && (r = 1), r <= 0 && (r = 1), r > a.data.goods.one_buy_limit && 0 != a.data.goods.one_buy_limit && (getApp().core.showModal({
            title: "提示",
            content: "数量超过最大限购数",
            showCancel: !1,
            success: function(t) {}
        }), r = a.data.goods.one_buy_limit), "MIAOSHA" === e && r > a.data.goods.miaosha.buy_max && 0 != a.data.goods.miaosha.buy_max) return getApp().core.showToast({
            title: "一单限购" + a.data.goods.miaosha.buy_max,
            image: "/images/icon-warning.png"
        }), !0;
        a.setData({
            form: {
                number: r
            }
        });
    },
    selectDefaultAttr: function() {
        var t = this.currentPage;
        if (t.data.goods && 0 == t.data.goods.use_attr) {
            for (var a in t.data.attr_group_list) for (var r in t.data.attr_group_list[a].attr_list) 0 == a && 0 == r && (t.data.attr_group_list[a].attr_list[r].checked = !0);
            t.setData({
                attr_group_list: t.data.attr_group_list
            });
        }
    }
};