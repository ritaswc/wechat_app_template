var app = getApp(), api = getApp().api, longitude = "", latitude = "", util = getApp().helper, is_loading_show = !1;

Page({
    data: {
        total_price: 0,
        address: null,
        express_price: 0,
        express_price_1: 0,
        integral_radio: 1,
        new_total_price: 0,
        show_card: !1,
        payment: -1,
        show_payment: !1,
        show_more: !1,
        index: -1,
        mch_offline: !0
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var e = util.formatData(new Date());
        getApp().core.removeStorageSync(getApp().const.INPUT_DATA), this.setData({
            options: t,
            time: e
        }), is_loading_show = !1;
    },
    bindContentInput: function(t) {
        this.data.mch_list[t.currentTarget.dataset.index].content = t.detail.value, this.setData({
            mch_list: this.data.mch_list
        });
    },
    KeyName: function(t) {
        var e = this.data.mch_list;
        e[t.currentTarget.dataset.index].offline_name = t.detail.value, this.setData({
            mch_list: e
        });
    },
    KeyMobile: function(t) {
        var e = this.data.mch_list;
        e[t.currentTarget.dataset.index].offline_mobile = t.detail.value, this.setData({
            mch_list: e
        });
    },
    getOffline: function(t) {
        var e = this, a = t.currentTarget.dataset.offline, i = t.currentTarget.dataset.index, o = e.data.mch_list;
        o[i].offline = a, e.setData({
            mch_list: o
        }), 1 == o.length && 0 == o[0].mch_id && 1 == o[0].offline ? e.setData({
            mch_offline: !1
        }) : e.setData({
            mch_offline: !0
        }), e.getPrice();
    },
    dingwei: function() {
        var e = this;
        getApp().getauth({
            content: "需要获取您的地理位置授权，请到小程序设置中打开授权",
            author: "scope.userLocation",
            success: function(t) {
                t && (t.authSetting["scope.userLocation"] ? getApp().core.chooseLocation({
                    success: function(t) {
                        longitude = t.longitude, latitude = t.latitude, e.setData({
                            location: t.address
                        }), e.getOrderData(e.data.options);
                    }
                }) : getApp().core.showToast({
                    title: "您取消了授权",
                    image: "/images/icon-warning.png"
                }));
            }
        });
    },
    orderSubmit: function(t) {
        var e = this, a = {}, i = e.data.mch_list;
        for (var o in i) {
            var s = i[o].form;
            if (s && 1 == s.is_form && 0 == i[o].mch_id) {
                var n = s.list;
                for (var r in n) if (1 == n[r].required) if ("radio" == n[r].type || "checkbox" == n[r].type) {
                    var c = !1;
                    for (var p in n[r].default_list) 1 == n[r].default_list[p].is_selected && (c = !0);
                    if (!c) return getApp().core.showModal({
                        title: "提示",
                        content: "请填写" + s.name + "，加‘*’为必填项",
                        showCancel: !1
                    }), !1;
                } else if (!n[r].default || null == n[r].default) return getApp().core.showModal({
                    title: "提示",
                    content: "请填写" + s.name + "，加‘*’为必填项",
                    showCancel: !1
                }), !1;
            }
            if (1 == i.length && 0 == i[o].mch_id && 1 == i[o].offline) ; else {
                if (!e.data.address) return getApp().core.showModal({
                    title: "提示",
                    content: "请选择收货地址",
                    showCancel: !1
                }), !1;
                a.address_id = e.data.address.id;
            }
        }
        if (a.mch_list = JSON.stringify(i), 0 < e.data.pond_id) {
            if (0 < e.data.express_price && -1 == e.data.payment) return e.setData({
                show_payment: !0
            }), !1;
        } else if (-1 == e.data.payment) return e.setData({
            show_payment: !0
        }), !1;
        1 == e.data.integral_radio ? a.use_integral = 1 : a.use_integral = 2, a.payment = e.data.payment, 
        a.formId = t.detail.formId, e.order_submit(a, "s");
    },
    onReady: function() {},
    onShow: function(t) {
        if (!is_loading_show) {
            is_loading_show = !0, getApp().page.onShow(this);
            var e = this, a = getApp().core.getStorageSync(getApp().const.PICKER_ADDRESS);
            a && e.setData({
                address: a
            }), getApp().core.removeStorageSync(getApp().const.PICKER_ADDRESS), e.getOrderData(e.data.options);
        }
    },
    getOrderData: function(t) {
        var _ = this, e = {}, a = "";
        _.data.address && _.data.address.id && (a = _.data.address.id), e.address_id = a, 
        e.longitude = longitude, e.latitude = latitude, getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), e.mch_list = t.mch_list, getApp().request({
            url: getApp().api.order.new_submit_preview,
            method: "POST",
            data: e,
            success: function(t) {
                if (getApp().core.hideLoading(), 0 == t.code) {
                    var e = getApp().core.getStorageSync(getApp().const.INPUT_DATA), a = t.data, i = -1, o = 1, s = a.mch_list, n = [];
                    for (var r in e && (n = e.mch_list, i = e.payment, o = e.integral_radio), a.integral_radio = o, 
                    a.pay_type_list) {
                        if (i == a.pay_type_list[r].payment) {
                            a.payment = i;
                            break;
                        }
                        if (1 == a.pay_type_list.length) {
                            a.payment = a.pay_type_list[r].payment;
                            break;
                        }
                    }
                    for (var r in s) {
                        var c = {}, p = {};
                        if (s[r].show = !1, s[r].show_length = s[r].goods_list.length - 1, 0 != n.length) for (var d in n) s[r].mch_id == n[d].mch_id && (s[r].content = n[d].content, 
                        s[r].form = n[d].form, c = n[d].shop, p = n[d].picker_coupon, s[r].offline_name = n[d].offline_name, 
                        s[r].offline_mobile = n[d].offline_mobile);
                        for (var d in s[r].shop_list) {
                            if (c && c.id == s[r].shop_list[d].id) {
                                s[r].shop = c;
                                break;
                            }
                            if (1 == s[r].shop_list.length) {
                                s[r].shop = s[r].shop_list[d];
                                break;
                            }
                            if (1 == s[r].shop_list[d].is_default) {
                                s[r].shop = s[r].shop_list[d];
                                break;
                            }
                        }
                        if (p) for (var d in s[r].coupon_list) if (p.id == s[r].coupon_list[d].id) {
                            s[r].picker_coupon = p;
                            break;
                        }
                        s[r].send_type && 2 == s[r].send_type ? (s[r].offline = 1, _.setData({
                            mch_offline: !1
                        })) : s[r].offline = 0;
                    }
                    a.mch_list = s;
                    var l = _.data.index;
                    -1 != l && s[l].shop_list && 0 < s[l].shop_list.length && _.setData({
                        show_shop: !0,
                        shop_list: s[l].shop_list
                    }), _.setData(a), _.getPrice();
                }
                1 == t.code && getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    confirmText: "返回",
                    success: function(t) {
                        t.confirm && getApp().core.navigateBack({
                            delta: 1
                        });
                    }
                });
            }
        });
    },
    showCouponPicker: function(t) {
        var e = t.currentTarget.dataset.index, a = this.data.mch_list;
        this.getInputData(), a[e].coupon_list && 0 < a[e].coupon_list.length && this.setData({
            show_coupon_picker: !0,
            coupon_list: a[e].coupon_list,
            index: e
        });
    },
    pickCoupon: function(t) {
        var e = t.currentTarget.dataset.index, a = this.data.index, i = getApp().core.getStorageSync(getApp().const.INPUT_DATA);
        getApp().core.removeStorageSync(getApp().const.INPUT_DATA);
        var o = i.mch_list;
        o[a].picker_coupon = "-1" != e && -1 != e && this.data.coupon_list[e], i.show_coupon_picker = !1, 
        i.mch_list = o, i.index = -1, this.setData(i), this.getPrice();
    },
    showShop: function(t) {
        var e = t.currentTarget.dataset.index;
        this.getInputData(), this.setData({
            index: e
        }), this.dingwei();
    },
    pickShop: function(t) {
        var e = t.currentTarget.dataset.index, a = this.data.index, i = getApp().core.getStorageSync(getApp().const.INPUT_DATA), o = i.mch_list;
        o[a].shop = "-1" != e && -1 != e && this.data.shop_list[e], i.show_shop = !1, i.mch_list = o, 
        i.index = -1, this.setData(i), this.getPrice();
    },
    integralSwitchChange: function(t) {
        0 != t.detail.value ? this.setData({
            integral_radio: 1
        }) : this.setData({
            integral_radio: 2
        }), this.getPrice();
    },
    integration: function(t) {
        var e = this.data.integral.integration;
        getApp().core.showModal({
            title: "积分使用规则",
            content: e,
            showCancel: !1,
            confirmText: "我知道了",
            confirmColor: "#ff4544",
            success: function(t) {
                t.confirm;
            }
        });
    },
    contains: function(t, e) {
        for (var a = t.length; a--; ) if (t[a] == e) return a;
        return -1;
    },
    getPrice: function() {
        var o = this, t = o.data.mch_list, e = o.data.integral_radio, a = (o.data.integral, 
        0), i = 0, s = {}, n = 0;
        for (var r in t) {
            var c = t[r], p = (parseFloat(c.total_price), parseFloat(c.level_price)), d = t[r].goods_list;
            n = 0, c.picker_coupon && 0 < c.picker_coupon.sub_price && (1 == c.picker_coupon.appoint_type && null != c.picker_coupon.cat_id_list ? d.forEach(function(t, e, a) {
                for (var i in t.cat_id) {
                    -1 != o.contains(c.picker_coupon.cat_id_list, t.cat_id[i]) && (n += parseFloat(t.level_price));
                }
            }) : 2 == c.picker_coupon.appoint_type && null != c.picker_coupon.goods_id_list && d.forEach(function(t, e, a) {
                -1 != o.contains(c.picker_coupon.goods_id_list, t.goods_id) && (n += parseFloat(t.level_price));
            }), c.picker_coupon.sub_price > n && 0 < n ? p -= parseFloat(n) : p -= c.picker_coupon.sub_price), 
            c.integral && 0 < c.integral.forehead && 1 == e && (p -= parseFloat(c.integral.forehead)), 
            p = 0 <= p ? p : 0, 0 == c.offline && (c.express_price && (p += c.express_price), 
            c.offer_rule && 1 == c.offer_rule.is_allowed && (s = c.offer_rule), 1 == c.is_area && (i = 1)), 
            a += parseFloat(p);
        }
        a = 0 <= a ? a : 0, o.setData({
            new_total_price: a.toFixed(2),
            offer_rule: s,
            is_area: i
        });
    },
    cardDel: function() {
        this.setData({
            show_card: !1
        }), getApp().core.redirectTo({
            url: "/pages/order/order?status=1"
        });
    },
    cardTo: function() {
        this.setData({
            show_card: !1
        }), getApp().core.redirectTo({
            url: "/pages/card/card"
        });
    },
    formInput: function(t) {
        var e = t.currentTarget.dataset.index, a = t.currentTarget.dataset.formId, i = this.data.mch_list, o = i[e].form, s = o.list;
        s[a].default = t.detail.value, o.list = s, this.setData({
            mch_list: i
        });
    },
    selectForm: function(t) {
        var e = this.data.mch_list, a = t.currentTarget.dataset.index, i = t.currentTarget.dataset.formId, o = t.currentTarget.dataset.k, s = e[a].form, n = s.list, r = n[i].default_list;
        if ("radio" == n[i].type) {
            for (var c in r) c == o ? r[o].is_selected = 1 : r[c].is_selected = 0;
            n[i].default_list = r;
        }
        "checkbox" == n[i].type && (1 == r[o].is_selected ? r[o].is_selected = 0 : r[o].is_selected = 1, 
        n[i].default_list = r), s.list = n, e[a].form = s, this.setData({
            mch_list: e
        });
    },
    showPayment: function() {
        this.setData({
            show_payment: !0
        });
    },
    payPicker: function(t) {
        var e = t.currentTarget.dataset.index;
        this.setData({
            payment: e,
            show_payment: !1
        });
    },
    payClose: function() {
        this.setData({
            show_payment: !1
        });
    },
    getInputData: function() {
        var t = this.data.mch_list, e = {
            integral_radio: this.data.integral_radio,
            payment: this.data.payment,
            mch_list: t
        };
        getApp().core.setStorageSync(getApp().const.INPUT_DATA, e);
    },
    onHide: function() {
        getApp().page.onHide(this);
        this.getInputData();
    },
    onUnload: function() {
        getApp().page.onUnload(this), getApp().core.removeStorageSync(getApp().const.INPUT_DATA);
    },
    uploadImg: function(t) {
        var e = this, a = t.currentTarget.dataset.index, i = t.currentTarget.dataset.formId, o = e.data.mch_list, s = o[a].form;
        is_loading_show = !0, getApp().uploader.upload({
            start: function() {
                getApp().core.showLoading({
                    title: "正在上传",
                    mask: !0
                });
            },
            success: function(t) {
                0 == t.code ? (s.list[i].default = t.data.url, e.setData({
                    mch_list: o
                })) : e.showToast({
                    title: t.msg
                });
            },
            error: function(t) {
                e.showToast({
                    title: t
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    goToAddress: function() {
        is_loading_show = !1, getApp().core.navigateTo({
            url: "/pages/address-picker/address-picker"
        });
    },
    showMore: function(t) {
        var e = this.data.mch_list, a = t.currentTarget.dataset.index;
        e[a].show = !e[a].show, this.setData({
            mch_list: e
        });
    }
});