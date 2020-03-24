if (typeof wx === 'undefined') var wx = getApp().core;
var longitude = "";
var latitude = "";
var util = require('../../../utils/helper.js');
Page({

    /**
     * 页面的初始数据
     */
    data: {
        total_price: 0,
        address: null,
        express_price: 0.00,
        content: '',
        offline: 0,
        express_price_1: 0.00,
        name: "",
        mobile: "",
        integral_radio: 1,
        new_total_price: 0,
        show_card: false,
        payment: -1,
        show_payment: false
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        var self = this;
        var time = util.formatData(new Date());
        getApp().core.removeStorageSync(getApp().const.INPUT_DATA);
        self.setData({
            options: options,
            time: time
        });
    },
    bindkeyinput: function(e) {
        this.setData({
            content: e.detail.value
        });
    },
    KeyName: function(e) {
        this.setData({
            name: e.detail.value
        });
    },
    KeyMobile: function(e) {
        this.setData({
            mobile: e.detail.value
        });
    },
    getOffline: function(e) {
        var self = this;
        var express = this.data.express_price;
        var express_1 = this.data.express_price_1;
        var offline = e.target.dataset.index;
        if (offline == 1) {
            this.setData({
                offline: 1,
                express_price: 0,
                express_price_1: express,
            });
        } else {
            this.setData({
                offline: 0,
                express_price: express_1
            });
        }
        self.getPrice();
    },
    dingwei: function() {
        var self = this;
        getApp().getauth({
            content: "需要获取您的地理位置授权，请到小程序设置中打开授权",
            author:'scope.userLocation',
            success: function(e) {
                if (e) {
                    if (e.authSetting["scope.userLocation"]) {
                        getApp().core.chooseLocation({
                            success: function(e) {
                                longitude = e.longitude;
                                latitude = e.latitude;
                                self.setData({
                                    location: e.address,
                                });
                            }
                        })
                    } else {
                        getApp().core.showToast({
                            title: '您取消了授权',
                            image: "/images/icon-warning.png",
                        })
                    }
                }
            }
        });
    },

    orderSubmit: function(e) {
        var self = this;
        var offline = self.data.offline;
        var data = {};

        if (offline == 0) {
            if (!self.data.address || !self.data.address.id) {
                getApp().core.showToast({
                    title: "请选择收货地址",
                    image: "/images/icon-warning.png",
                });
                return;
            }
            data.address_id = self.data.address.id;
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
            } else {
                // var check_mobile = /^\+?\d[\d -]{8,12}\d/;
                // if (!check_mobile.test(data.address_mobile)) {
                //     getApp().core.showModal({
                //         title: '提示',
                //         content: '手机号格式不正确',
                //         showCancel: false
                //     });
                //     return;
                // }
            }
        }
        data.offline = offline;

        if (self.data.payment == -1) {
            self.setData({
                show_payment: true
            });
            return false;
        }

        if (self.data.cart_id_list) {
            data.cart_id_list = JSON.stringify(self.data.cart_id_list);
        }
        if (self.data.goods_info) {
            data.goods_info = JSON.stringify(self.data.goods_info);
        }
        if (self.data.picker_coupon) {
            data.user_coupon_id = self.data.picker_coupon.user_coupon_id;
        }
        if (self.data.content) {
            data.content = self.data.content
        }
        self.data.integral_radio == 1 ? data.use_integral = 1 : data.use_integral = 2;
        data.payment = self.data.payment;
        data.formId = e.detail.formId;

        self.order_submit(data, 'ms');
        return;
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function(options) {
        getApp().page.onReady(this);

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function(options) {
        getApp().page.onShow(this);
        var self = this;
        var address = getApp().core.getStorageSync(getApp().const.PICKER_ADDRESS);
        if (address) {
            self.setData({
                address: address,
                name: address.name,
                mobile: address.mobile
            });
            getApp().core.removeStorageSync(getApp().const.PICKER_ADDRESS);
            self.getInputData();
        }
        self.getOrderData(self.data.options);
    },

    getOrderData: function(options) {
        var self = this;
        var address_id = "";
        if (self.data.address && self.data.address.id)
            address_id = self.data.address.id;
        if (options.goods_info) {
            getApp().core.showLoading({
                title: "正在加载",
                mask: true,
            });
            getApp().request({
                url: getApp().api.miaosha.submit_preview,
                data: {
                    goods_info: options.goods_info,
                    address_id: address_id,
                    longitude: longitude,
                    latitude: latitude
                },
                success: function(res) {
                    getApp().core.hideLoading();
                    if (res.code == 0) {
                        var shop_list = res.data.shop_list;
                        var shop = {};
                        if (shop_list.length == 1) {
                            shop = shop_list[0];
                        }
                        var input_data = getApp().core.getStorageSync(getApp().const.INPUT_DATA);
                        if (!input_data) {
                            input_data = {
                                address: res.data.address,
                                name: res.data.address ? res.data.address.name : '',
                                mobile: res.data.address ? res.data.address.mobile : '',
                                shop: shop,
                            }
                            if (res.data.pay_type_list.length > 0) {
                                input_data.payment = res.data.pay_type_list[0].payment;
                                if (res.data.pay_type_list.length > 1) {
                                    input_data.payment = -1;
                                }
                            }
                        }
                        input_data.total_price = res.data.total_price;
                        input_data.level_price = res.data.level_price;
                        input_data.is_level = res.data.is_level;
                        input_data.goods_list = res.data.list;
                        input_data.goods_info = res.data.goods_info;
                        input_data.express_price = parseFloat(res.data.express_price);
                        input_data.coupon_list = res.data.coupon_list;
                        input_data.shop_list = res.data.shop_list;
                        input_data.send_type = res.data.send_type;
                        input_data.level = res.data.level;
                        input_data.integral = res.data.integral;
                        input_data.new_total_price = res.data.level_price;
                        input_data.is_payment = res.data.is_payment;
                        input_data.is_coupon = res.data.list[0].coupon;
                        input_data.is_discount = res.data.list[0].is_discount;
                        input_data.is_area = res.data.is_area;
                        input_data.pay_type_list = res.data.pay_type_list;
                        self.setData(input_data);
                        self.getInputData();
                        if (res.data.send_type == 1) { //仅快递
                            self.setData({
                                offline: 0,
                            });
                        }
                        if (res.data.send_type == 2) { //仅自提
                            self.setData({
                                offline: 1,
                            });
                        }
                        self.getPrice();
                    }
                    if (res.code == 1) {
                        getApp().core.showModal({
                            title: "提示",
                            content: res.msg,
                            showCancel: false,
                            confirmText: "返回",
                            success: function(res) {
                                if (res.confirm) {
                                    var pages = getCurrentPages();
                                    if (pages.length == 1) {
                                        getApp().core.redirectTo({
                                            url: '/pages/index/index',
                                        });
                                    } else {
                                        getApp().core.navigateBack({
                                            delta: 1,
                                        });
                                    }
                                }
                            }
                        });
                    }
                }
            });
        }
    },

    copyText: function(e) {
        var text = e.currentTarget.dataset.text;
        if (!text)
            return;
        getApp().core.setClipboardData({
            data: text,
            success: function() {
                self.showToast({
                    title: "已复制内容",
                });
            },
            fail: function() {
                self.showToast({
                    title: "复制失败",
                    image: "/images/icon-warning.png",
                });
            },
        });
    },

    showCouponPicker: function() {
        var self = this;
        self.getInputData();
        if (self.data.coupon_list && self.data.coupon_list.length > 0) {
            self.setData({
                show_coupon_picker: true,
            });
        }
    },

    pickCoupon: function(e) {
        var self = this;
        var input_data = getApp().core.getStorageSync(getApp().const.INPUT_DATA);
        getApp().core.removeStorageSync(getApp().const.INPUT_DATA);
        var index = e.currentTarget.dataset.index;
        input_data.show_coupon_picker = false;
        if (index == '-1' || index == -1) {
            input_data.picker_coupon = false;
        } else {
            input_data.picker_coupon = self.data.coupon_list[index]
        }
        self.setData(input_data);
        self.getPrice();
    },

    numSub: function(num1, num2, length) {
        return 100;
    },
    showShop: function(e) {
        var self = this;
        self.getInputData();
        self.dingwei();
        if (self.data.shop_list && self.data.shop_list.length >= 1) {
            self.setData({
                show_shop: true,
            });
        }
    },
    pickShop: function(e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var input_data = getApp().core.getStorageSync(getApp().const.INPUT_DATA);
        getApp().core.removeStorageSync(getApp().const.INPUT_DATA);
        input_data.show_shop = false;
        if (index == '-1' || index == -1) {
            input_data.shop = false;
        } else {
            input_data.shop = self.data.shop_list[index];
        }
        self.setData(input_data);
        self.getPrice();
    },
    integralSwitchChange: function(e) {
        var self = this;
        if (e.detail.value != false) {
            self.setData({
                integral_radio: 1,
            });
        } else {
            self.setData({
                integral_radio: 2,
            });
        }
        self.getPrice();
    },
    integration: function(e) {
        var self = this;
        var integration = self.data.integral.integration;
        getApp().core.showModal({
            title: '积分使用规则',
            content: integration,
            showCancel: false,
            confirmText: '我知道了',
            confirmColor: '#ff4544',
            success: function(res) {
                if (res.confirm) {}
            }
        });
    },
    /**
     * 计算总价
     */
    getPrice: function() {
        var self = this;
        var total_price = self.data.total_price;
        // var new_total_price = total_price;
        var new_total_price = parseFloat(self.data.level_price);
        var express_price = self.data.express_price;
        var picker_coupon = self.data.picker_coupon;
        var integral = self.data.integral;
        var integral_radio = self.data.integral_radio;
        var level = self.data.level;
        var is_level = self.data.is_level;
        var offline = self.data.offline;

        if (picker_coupon) {
            new_total_price = new_total_price - picker_coupon.sub_price;
        }

        if (integral && integral_radio == 1) {
            new_total_price = new_total_price - parseFloat(integral.forehead);
        }

        // if (level && is_level === true) {
            // new_total_price = new_total_price * level.discount / 10;
            // new_total_price = new_total_price
        // }
        if (new_total_price <= 0.01) {
            new_total_price = 0.01;
        }

        if (offline == 0) {
            console.log(express_price)
            new_total_price = new_total_price + express_price;
            console.log(new_total_price)
        }
        new_total_price = parseFloat(new_total_price)
        self.setData({
            new_total_price: new_total_price.toFixed(2)
        });

    },
    cardDel: function() {
        var self = this;
        self.setData({
            show_card: false
        });
        getApp().core.redirectTo({
            url: '/pages/order/order?status=1',
        })
    },
    cardTo: function() {
        var self = this;
        self.setData({
            show_card: false
        });
        getApp().core.redirectTo({
            url: '/pages/card/card'
        })
    },
    formInput: function(e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var form = self.data.form;
        var form_list = form.list;
        form_list[index].default = e.detail.value;
        form.list = form_list;
        self.setData({
            form: form
        });
    },
    selectForm: function(e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var k = e.currentTarget.dataset.k;
        var form = self.data.form;
        var form_list = form.list;
        if (form_list[index].type == 'radio') {
            var default_list = form_list[index].default_list;
            for (var i in default_list) {
                if (i == k) {
                    default_list[k].is_selected = 1;
                } else {
                    default_list[i].is_selected = 0;
                }
            }
            form_list[index].default_list = default_list;
        }
        if (form_list[index].type == 'checkbox') {
            var default_list = form_list[index].default_list;
            if (default_list[k].is_selected == 1) {
                default_list[k].is_selected = 0;
            } else {
                default_list[k].is_selected = 1;
            }
            form_list[index].default_list = default_list;
        }
        form.list = form_list;
        self.setData({
            form: form
        });
    },
    showPayment: function() {
        this.setData({
            show_payment: true
        });
    },
    payPicker: function(e) {
        var index = e.currentTarget.dataset.index;
        this.setData({
            payment: index,
            show_payment: false
        });
    },
    payClose: function() {
        this.setData({
            show_payment: false
        });
    },

    getInputData: function() {
        var self = this;
        var data = {
            address: self.data.address,
            name: self.data.name,
            mobile: self.data.mobile,
            content: self.data.content,
            payment: self.data.payment,
            shop: self.data.shop,
        };
        getApp().core.setStorageSync(getApp().const.INPUT_DATA, data);
    },

    onHide: function(options) {
        getApp().page.onHide(this);
        this.getInputData();
    },

    onUnload: function(options) {
        getApp().page.onUnload(this);
        getApp().core.removeStorageSync(getApp().const.INPUT_DATA);
    }
});