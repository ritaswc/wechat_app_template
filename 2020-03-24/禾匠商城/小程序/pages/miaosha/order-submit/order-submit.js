var longitude = "", latitude = "", util = require("../../../utils/helper.js");

Page({
    data: {
        total_price: 0,
        address: null,
        express_price: 0,
        content: "",
        offline: 0,
        express_price_1: 0,
        name: "",
        mobile: "",
        integral_radio: 1,
        new_total_price: 0,
        show_card: !1,
        payment: -1,
        show_payment: !1
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var e = util.formatData(new Date());
        getApp().core.removeStorageSync(getApp().const.INPUT_DATA), this.setData({
            options: t,
            time: e
        });
    },
    bindkeyinput: function(t) {
        this.setData({
            content: t.detail.value
        });
    },
    KeyName: function(t) {
        this.setData({
            name: t.detail.value
        });
    },
    KeyMobile: function(t) {
        this.setData({
            mobile: t.detail.value
        });
    },
    getOffline: function(t) {
        var e = this.data.express_price, a = this.data.express_price_1;
        1 == t.target.dataset.index ? this.setData({
            offline: 1,
            express_price: 0,
            express_price_1: e
        }) : this.setData({
            offline: 0,
            express_price: a
        }), this.getPrice();
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
                        });
                    }
                }) : getApp().core.showToast({
                    title: "您取消了授权",
                    image: "/images/icon-warning.png"
                }));
            }
        });
    },
    orderSubmit: function(t) {
        var e = this, a = e.data.offline, i = {};
        if (0 == a) {
            if (!e.data.address || !e.data.address.id) return void getApp().core.showToast({
                title: "请选择收货地址",
                image: "/images/icon-warning.png"
            });
            i.address_id = e.data.address.id;
        } else {
            if (i.address_name = e.data.name, i.address_mobile = e.data.mobile, !e.data.shop.id) return void getApp().core.showModal({
                title: "警告",
                content: "请选择门店",
                showCancel: !1
            });
            if (i.shop_id = e.data.shop.id, !i.address_name || null == i.address_name) return void e.showToast({
                title: "请填写收货人",
                image: "/images/icon-warning.png"
            });
            if (!i.address_mobile || null == i.address_mobile) return void e.showToast({
                title: "请填写联系方式",
                image: "/images/icon-warning.png"
            });
        }
        if (i.offline = a, -1 == e.data.payment) return e.setData({
            show_payment: !0
        }), !1;
        e.data.cart_id_list && (i.cart_id_list = JSON.stringify(e.data.cart_id_list)), e.data.goods_info && (i.goods_info = JSON.stringify(e.data.goods_info)), 
        e.data.picker_coupon && (i.user_coupon_id = e.data.picker_coupon.user_coupon_id), 
        e.data.content && (i.content = e.data.content), 1 == e.data.integral_radio ? i.use_integral = 1 : i.use_integral = 2, 
        i.payment = e.data.payment, i.formId = t.detail.formId, e.order_submit(i, "ms");
    },
    onReady: function(t) {
        getApp().page.onReady(this);
    },
    onShow: function(t) {
        getApp().page.onShow(this);
        var e = this, a = getApp().core.getStorageSync(getApp().const.PICKER_ADDRESS);
        a && (e.setData({
            address: a,
            name: a.name,
            mobile: a.mobile
        }), getApp().core.removeStorageSync(getApp().const.PICKER_ADDRESS), e.getInputData()), 
        e.getOrderData(e.data.options);
    },
    getOrderData: function(t) {
        var o = this, e = "";
        o.data.address && o.data.address.id && (e = o.data.address.id), t.goods_info && (getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.miaosha.submit_preview,
            data: {
                goods_info: t.goods_info,
                address_id: e,
                longitude: longitude,
                latitude: latitude
            },
            success: function(t) {
                if (getApp().core.hideLoading(), 0 == t.code) {
                    var e = t.data.shop_list, a = {};
                    1 == e.length && (a = e[0]);
                    var i = getApp().core.getStorageSync(getApp().const.INPUT_DATA);
                    i || (i = {
                        address: t.data.address,
                        name: t.data.address ? t.data.address.name : "",
                        mobile: t.data.address ? t.data.address.mobile : "",
                        shop: a
                    }, 0 < t.data.pay_type_list.length && (i.payment = t.data.pay_type_list[0].payment, 
                    1 < t.data.pay_type_list.length && (i.payment = -1))), i.total_price = t.data.total_price, 
                    i.level_price = t.data.level_price, i.is_level = t.data.is_level, i.goods_list = t.data.list, 
                    i.goods_info = t.data.goods_info, i.express_price = parseFloat(t.data.express_price), 
                    i.coupon_list = t.data.coupon_list, i.shop_list = t.data.shop_list, i.send_type = t.data.send_type, 
                    i.level = t.data.level, i.integral = t.data.integral, i.new_total_price = t.data.level_price, 
                    i.is_payment = t.data.is_payment, i.is_coupon = t.data.list[0].coupon, i.is_discount = t.data.list[0].is_discount, 
                    i.is_area = t.data.is_area, i.pay_type_list = t.data.pay_type_list, o.setData(i), 
                    o.getInputData(), 1 == t.data.send_type && o.setData({
                        offline: 0
                    }), 2 == t.data.send_type && o.setData({
                        offline: 1
                    }), o.getPrice();
                }
                1 == t.code && getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    confirmText: "返回",
                    success: function(t) {
                        t.confirm && (1 == getCurrentPages().length ? getApp().core.redirectTo({
                            url: "/pages/index/index"
                        }) : getApp().core.navigateBack({
                            delta: 1
                        }));
                    }
                });
            }
        }));
    },
    copyText: function(t) {
        var e = t.currentTarget.dataset.text;
        e && getApp().core.setClipboardData({
            data: e,
            success: function() {
                self.showToast({
                    title: "已复制内容"
                });
            },
            fail: function() {
                self.showToast({
                    title: "复制失败",
                    image: "/images/icon-warning.png"
                });
            }
        });
    },
    showCouponPicker: function() {
        var t = this;
        t.getInputData(), t.data.coupon_list && 0 < t.data.coupon_list.length && t.setData({
            show_coupon_picker: !0
        });
    },
    pickCoupon: function(t) {
        var e = getApp().core.getStorageSync(getApp().const.INPUT_DATA);
        getApp().core.removeStorageSync(getApp().const.INPUT_DATA);
        var a = t.currentTarget.dataset.index;
        e.show_coupon_picker = !1, e.picker_coupon = "-1" != a && -1 != a && this.data.coupon_list[a], 
        this.setData(e), this.getPrice();
    },
    numSub: function(t, e, a) {
        return 100;
    },
    showShop: function(t) {
        var e = this;
        e.getInputData(), e.dingwei(), e.data.shop_list && 1 <= e.data.shop_list.length && e.setData({
            show_shop: !0
        });
    },
    pickShop: function(t) {
        var e = t.currentTarget.dataset.index, a = getApp().core.getStorageSync(getApp().const.INPUT_DATA);
        getApp().core.removeStorageSync(getApp().const.INPUT_DATA), a.show_shop = !1, a.shop = "-1" != e && -1 != e && this.data.shop_list[e], 
        this.setData(a), this.getPrice();
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
    getPrice: function() {
        var t = this, e = (t.data.total_price, parseFloat(t.data.level_price)), a = t.data.express_price, i = t.data.picker_coupon, o = t.data.integral, s = t.data.integral_radio, n = (t.data.level, 
        t.data.is_level, t.data.offline);
        i && (e -= i.sub_price), o && 1 == s && (e -= parseFloat(o.forehead)), e <= .01 && (e = .01), 
        0 == n && (console.log(a), e += a, console.log(e)), e = parseFloat(e), t.setData({
            new_total_price: e.toFixed(2)
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
        var e = t.currentTarget.dataset.index, a = this.data.form, i = a.list;
        i[e].default = t.detail.value, a.list = i, this.setData({
            form: a
        });
    },
    selectForm: function(t) {
        var e = t.currentTarget.dataset.index, a = t.currentTarget.dataset.k, i = this.data.form, o = i.list;
        if ("radio" == o[e].type) {
            var s = o[e].default_list;
            for (var n in s) n == a ? s[a].is_selected = 1 : s[n].is_selected = 0;
            o[e].default_list = s;
        }
        "checkbox" == o[e].type && (1 == (s = o[e].default_list)[a].is_selected ? s[a].is_selected = 0 : s[a].is_selected = 1, 
        o[e].default_list = s);
        i.list = o, this.setData({
            form: i
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
        var t = this, e = {
            address: t.data.address,
            name: t.data.name,
            mobile: t.data.mobile,
            content: t.data.content,
            payment: t.data.payment,
            shop: t.data.shop
        };
        getApp().core.setStorageSync(getApp().const.INPUT_DATA, e);
    },
    onHide: function(t) {
        getApp().page.onHide(this), this.getInputData();
    },
    onUnload: function(t) {
        getApp().page.onUnload(this), getApp().core.removeStorageSync(getApp().const.INPUT_DATA);
    }
});