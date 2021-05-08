var longitude = "", latitude = "";

Page({
    data: {
        address: null,
        offline: 1,
        payment: -1,
        show_payment: !1
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var e = t.goods_info, a = JSON.parse(e);
        a && this.setData({
            goods_info: a,
            offline: 1,
            id: a.goods_id
        });
    },
    onReady: function() {},
    onShow: function() {
        getApp().page.onShow(this), getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        });
        var n = this, t = getApp().core.getStorageSync(getApp().const.PICKER_ADDRESS);
        t && (n.setData({
            address: t,
            name: t.name,
            mobile: t.mobile
        }), getApp().core.removeStorageSync(getApp().const.PICKER_ADDRESS));
        var e = "";
        if (n.data.address && n.data.address.id) e = n.data.address.id;
        getApp().request({
            url: getApp().api.integral.submit_preview,
            data: {
                goods_info: JSON.stringify(n.data.goods_info),
                address_id: e
            },
            success: function(t) {
                if (getApp().core.hideLoading(), 0 == t.code) {
                    var e = t.data.shop_list, a = {};
                    e && 1 == e.length && (a = e[0]), t.data.is_shop && (a = t.data.is_shop);
                    var o = t.data.total_price;
                    if (t.data.express_price) s = t.data.express_price; else var s = 0;
                    var i = t.data.goods;
                    n.setData({
                        goods: i,
                        address: t.data.address,
                        express_price: s,
                        shop_list: t.data.shop_list,
                        shop: a,
                        name: t.data.address ? t.data.address.name : "",
                        mobile: t.data.address ? t.data.address.mobile : "",
                        total_price: parseFloat(o).toFixed(2),
                        send_type: t.data.send_type,
                        attr: i.attr,
                        attr_price: i.attr_price,
                        attr_integral: i.attr_integral
                    }), 1 == t.data.send_type && n.setData({
                        offline: 1
                    }), 2 == t.data.send_type && n.setData({
                        offline: 2
                    }), n.getTotalPrice();
                } else getApp().core.showModal({
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
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    getOffline: function(t) {
        var e = this, a = (e.data.express_price, e.data.total_price);
        1 == t.currentTarget.dataset.index ? e.setData({
            offline: 1,
            total_price: a
        }) : e.setData({
            offline: 2
        }), e.getTotalPrice();
    },
    showShop: function(t) {
        var e = this;
        e.dingwei(), e.data.shop_list && 1 <= e.data.shop_list.length && e.setData({
            show_shop: !0
        });
    },
    dingwei: function() {
        var e = this;
        getApp().core.chooseLocation({
            success: function(t) {
                longitude = t.longitude, latitude = t.latitude, e.setData({
                    location: t.address
                });
            },
            fail: function(t) {
                getApp().getauth({
                    content: "需要获取您的地理位置授权，请到小程序设置中打开授权",
                    author: "scope.userLocation",
                    success: function(t) {
                        t && (t.authSetting["scope.userLocation"] ? e.dingwei() : getApp().core.showToast({
                            title: "您取消了授权",
                            image: "/images/icon-warning.png"
                        }));
                    }
                });
            }
        });
    },
    pickShop: function(t) {
        var e = this, a = t.currentTarget.dataset.index;
        "-1" == a || -1 == a ? e.setData({
            shop: !1,
            show_shop: !1
        }) : e.setData({
            shop: e.data.shop_list[a],
            show_shop: !1
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
    orderSubmit: function(t) {
        var e = this, a = e.data.offline, o = {};
        if (1 == a) {
            if (!e.data.address || !e.data.address.id) return void getApp().core.showToast({
                title: "请选择收货地址",
                image: "/images/icon-warning.png"
            });
            if (o.address_id = e.data.address.id, e.data.total_price) {
                if (0 < e.data.total_price) var s = 2; else s = 1;
                o.type = s;
            }
        } else {
            if (o.address_name = e.data.name, o.address_mobile = e.data.mobile, !e.data.shop.id) return void getApp().core.showModal({
                title: "警告",
                content: "请选择门店",
                showCancel: !1
            });
            if (o.shop_id = e.data.shop.id, !o.address_name || null == o.address_name) return void e.showToast({
                title: "请填写收货人",
                image: "/images/icon-warning.png"
            });
            if (!o.address_mobile || null == o.address_mobile) return void e.showToast({
                title: "请填写联系方式",
                image: "/images/icon-warning.png"
            });
        }
        if (o.offline = a, e.data.content && (o.content = e.data.content), e.data.goods_info) {
            var i = e.data.attr, n = [];
            for (var r in i) {
                var d = {
                    attr_id: i[r].attr_id,
                    attr_name: i[r].attr_name
                };
                n.push(d);
            }
            e.data.goods_info.attr = n, o.goods_info = JSON.stringify(e.data.goods_info);
        }
        e.data.express_price && (o.express_price = e.data.express_price), o.attr = JSON.stringify(i), 
        getApp().core.showLoading({
            title: "提交中",
            mask: !0
        }), o.formId = t.detail.formId, getApp().request({
            url: getApp().api.integral.submit,
            method: "post",
            data: o,
            success: function(t) {
                getApp().core.hideLoading(), 0 == t.code ? 1 == t.type ? getApp().core.redirectTo({
                    url: "/pages/integral-mall/order/order?status=1"
                }) : getApp().core.requestPayment({
                    _res: t,
                    timeStamp: t.data.timeStamp,
                    nonceStr: t.data.nonceStr,
                    package: t.data.package,
                    signType: t.data.signType,
                    paySign: t.data.paySign,
                    complete: function(t) {
                        "requestPayment:fail" != t.errMsg && "requestPayment:fail cancel" != t.errMsg ? "requestPayment:ok" == t.errMsg && getApp().core.redirectTo({
                            url: "/pages/integral-mall/order/order?status=1"
                        }) : getApp().core.showModal({
                            title: "提示",
                            content: "订单尚未支付",
                            showCancel: !1,
                            confirmText: "确认",
                            success: function(t) {
                                t.confirm && getApp().core.redirectTo({
                                    url: "/pages/integral-mall/order/order?status=0"
                                });
                            }
                        });
                    }
                }) : getApp().core.showToast({
                    title: t.msg,
                    image: "/images/icon-warning.png"
                });
            }
        });
    },
    getTotalPrice: function() {
        var t = parseFloat(this.data.total_price), e = this.data.offline, a = this.data.express_price, o = 0;
        o = 2 == e ? t : t + a, this.setData({
            new_total_price: parseFloat(o).toFixed(2)
        });
    }
});