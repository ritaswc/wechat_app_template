if (typeof wx === 'undefined') var wx = getApp().core;
var longitude = "";
var latitude = "";
Page({

    /**
     * 页面的初始数据
     */
    data: {
        address: null,
        offline: 1,
        payment: -1,
        show_payment: false
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        var goods_info = options.goods_info;
        var goods = JSON.parse(goods_info);
        if (goods) {
            self.setData({
                goods_info: goods,
                offline: 1,
                id: goods['goods_id'],
            });
        }
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        getApp().page.onShow(this);
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        var self = this;
        var address = getApp().core.getStorageSync(getApp().const.PICKER_ADDRESS);
        if (address) {
            self.setData({
                address: address,
                name: address.name,
                mobile: address.mobile
            });
            getApp().core.removeStorageSync(getApp().const.PICKER_ADDRESS);
        }
        var address_id = "";
        if (self.data.address && self.data.address.id)
            var address_id = self.data.address.id;
        getApp().request({
            url: getApp().api.integral.submit_preview,
            data: {
                goods_info: JSON.stringify(self.data.goods_info),
                address_id: address_id
            },
            success: function (res) {
                getApp().core.hideLoading();
                if (res.code == 0) {
                    var shop_list = res.data.shop_list;
                    var shop = {};
                    if (shop_list && shop_list.length == 1) {
                        shop = shop_list[0];
                    }
                    if (res.data.is_shop) {
                        shop = res.data.is_shop;
                    }
                    var total_price = res.data.total_price
                    if (!res.data.express_price) {
                        var express_price = 0;
                    } else {
                        var express_price = res.data.express_price;
                    }
                    var goods = res.data.goods;
                    self.setData({
                        goods: goods,
                        address: res.data.address,
                        express_price: express_price,
                        shop_list: res.data.shop_list,
                        shop: shop,
                        name: res.data.address ? res.data.address.name : '',
                        mobile: res.data.address ? res.data.address.mobile : '',
                        total_price: parseFloat(total_price).toFixed(2),
                        send_type: res.data.send_type,
                        attr: goods['attr'],
                        attr_price: goods['attr_price'],
                        attr_integral: goods['attr_integral'],
                    });
                    if (res.data.send_type == 1) { //仅快递
                        self.setData({
                            offline: 1,
                        });
                    }
                    if (res.data.send_type == 2) { //仅自提
                        self.setData({
                            offline: 2,
                        });
                    }
                    self.getTotalPrice();
                } else {
                    getApp().core.showModal({
                        title: "提示",
                        content: res.msg,
                        showCancel: false,
                        confirmText: "返回",
                        success: function (res) {
                            if (res.confirm) {
                                getApp().core.navigateBack({
                                    delta: 1,
                                });
                            }
                        }
                    });
                }
            }
        });
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function () {

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function () {

    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {

    },
    /**
     * 送货方式切换
     */
    getOffline: function (e) {
        var self = this;
        var express = self.data.express_price;
        var total_price = self.data.total_price;
        var offline = e.currentTarget.dataset.index;
        if (offline == 1) {
            self.setData({
                offline: 1,
                total_price: total_price
            });
        } else {
            self.setData({
                offline: 2,
            });
        }
        self.getTotalPrice();
    },

    showShop: function (e) {
        var self = this;
        self.dingwei();
        if (self.data.shop_list && self.data.shop_list.length >= 1) {
            self.setData({
                show_shop: true,
            });
        }
    },
    dingwei: function () {
        var self = this;
        getApp().core.chooseLocation({
            success: function (e) {
                longitude = e.longitude;
                latitude = e.latitude;
                self.setData({
                    location: e.address,
                });
            },
            fail: function (res) {
                getApp().getauth({
                    content: "需要获取您的地理位置授权，请到小程序设置中打开授权",
                    author:'scope.userLocation',
                    success: function (e) {
                        if (e) {
                            if (e.authSetting["scope.userLocation"]) {
                                self.dingwei();
                            } else {
                                getApp().core.showToast({
                                    title: '您取消了授权',
                                    image: "/images/icon-warning.png",
                                })
                            }
                        }
                    }
                });
            }
        })
    },
    pickShop: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        if (index == '-1' || index == -1) {
            self.setData({
                shop: false,
                show_shop: false,
            });
        } else {
            self.setData({
                shop: self.data.shop_list[index],
                show_shop: false,
            });
        }
    },
    /**
     * 绑定参数
     */
    bindkeyinput: function (e) {
        this.setData({
            content: e.detail.value
        });
    },
    /**
     * 订单提交
     */
    KeyName: function (e) {
        this.setData({
            name: e.detail.value
        });
    },
    KeyMobile: function (e) {
        this.setData({
            mobile: e.detail.value
        });
    },
    orderSubmit: function (e) {
        var self = this;
        var offline = self.data.offline;
        var data = {};
        if (offline == 1) {
            if (!self.data.address || !self.data.address.id) {
                getApp().core.showToast({
                    title: "请选择收货地址",
                    image: "/images/icon-warning.png",
                });
                return;
            }
            data.address_id = self.data.address.id;

            if (self.data.total_price) {
                if (self.data.total_price > 0) {
                    var type = 2;
                } else {
                    var type = 1;
                }
                data.type = type;
            }

        } else {
            data.address_name = self.data.name;
            data.address_mobile = self.data.mobile;
            if (self.data.shop.id) {
                data.shop_id = self.data.shop.id;
            } else {
                getApp().core.showModal({
                    title: '警告',
                    content: '请选择门店',
                    showCancel: false
                });
                return;
            }
            if (!data.address_name || data.address_name == undefined) {
                self.showToast({
                    title: "请填写收货人",
                    image: "/images/icon-warning.png",
                });
                return;
            }
            if (!data.address_mobile || data.address_mobile == undefined) {
                self.showToast({
                    title: "请填写联系方式",
                    image: "/images/icon-warning.png",
                });
                return;
            }
        }
        data.offline = offline;
        if (self.data.content) {
            data.content = self.data.content;
        }
        if (self.data.goods_info) {
            var attr = self.data.attr
            var googs_attr = [];
            for (var i in attr) {
                var attrs = {
                    'attr_id': attr[i].attr_id,
                    'attr_name': attr[i].attr_name,
                }
                googs_attr.push(attrs)
            }
            self.data.goods_info.attr = googs_attr
            data.goods_info = JSON.stringify(self.data.goods_info);
        }
        if (self.data.express_price) {
            data.express_price = self.data.express_price
        }

        data.attr = JSON.stringify(attr);
        getApp().core.showLoading({
            title: "提交中",
            mask: true,
        });
        data.formId = e.detail.formId;
        getApp().request({
            url: getApp().api.integral.submit,
            method: "post",
            data: data,
            success: function (res) {
                getApp().core.hideLoading();
                if (res.code == 0) {
                    if (res.type == 1) {
                        getApp().core.redirectTo({
                            url: "/pages/integral-mall/order/order?status=1",
                        });
                    } else {
                        getApp().core.requestPayment({
                            _res: res,
                            timeStamp: res.data.timeStamp,
                            nonceStr: res.data.nonceStr,
                            package: res.data.package,
                            signType: res.data.signType,
                            paySign: res.data.paySign,
                            complete: function (e) {
                                if (e.errMsg == "requestPayment:fail" || e.errMsg == "requestPayment:fail cancel") {
                                    getApp().core.showModal({
                                        title: "提示",
                                        content: "订单尚未支付",
                                        showCancel: false,
                                        confirmText: "确认",
                                        success: function (res) {
                                            if (res.confirm) {
                                                getApp().core.redirectTo({
                                                    url: "/pages/integral-mall/order/order?status=0",
                                                });
                                            }
                                        }
                                    });
                                    return;
                                }
                                if (e.errMsg == "requestPayment:ok") {
                                    getApp().core.redirectTo({
                                        url: "/pages/integral-mall/order/order?status=1",
                                    });
                                }
                            },
                        });
                    }
                } else {
                    getApp().core.showToast({
                        title: res.msg,
                        image: "/images/icon-warning.png",
                    })
                }
            }

        });
    },

    getTotalPrice: function () {
        var self = this;
        var total_price = parseFloat(self.data.total_price);
        var offline = self.data.offline;
        var express_price = self.data.express_price;
        var new_total_price = 0;
        if (offline == 2) {
            new_total_price = total_price;
        } else {
            new_total_price = total_price + express_price;
        }

        self.setData({
            new_total_price: parseFloat(new_total_price).toFixed(2)
        });
    }
})