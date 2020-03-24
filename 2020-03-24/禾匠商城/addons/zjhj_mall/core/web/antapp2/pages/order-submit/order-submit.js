if (typeof wx === 'undefined') var wx = getApp().core;
// order-submit.js
var app = getApp();
var api = getApp().api;
var longitude = "";
var latitude = "";
var util = getApp().helper;
var loadingImg = false;
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
        show_payment: false,
        pond_id: false,
        scratch_id: false,
        lottery_id: false,
        setp_id: false,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        var time = util.formatData(new Date());
        getApp().core.removeStorageSync(getApp().const.INPUT_DATA);
        //抽奖
        if (options.pond_id) {
            self.setData({
                pond_id: options.pond_id
            })
        }
        if (options.scratch_id) {
            self.setData({
                scratch_id: options.scratch_id
            })
        }
        if (options.lottery_id){
            self.setData({
                lottery_id: options.lottery_id,
            })
        }
        if (options.step_id) {
          self.setData({
            step_id: options.step_id,
          })
        }
        self.setData({
            options: options,
            time: time
        });
    },
    bindkeyinput: function (e) {
        var mch_index = e.currentTarget.dataset.mchIndex;
        if (mch_index == -1) {
            this.setData({
                content: e.detail.value
            });
        } else {
            if (this.data.mch_list[mch_index]) {
                this.data.mch_list[mch_index].content = e.detail.value;
            }
            this.setData({
                mch_list: this.data.mch_list
            });
        }
    },
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
    getOffline: function (e) {
        var self = this;
        var express = this.data.express_price;
        var express_1 = this.data.express_price_1;
        var offline = e.currentTarget.dataset.index;
        if (offline == 1) {
            this.setData({
                offline: 1,
                express_price: 0,
                express_price_1: express,

            });
        } else {
            this.setData({
                offline: 0,
                express_price: express_1,
            });
        }
        self.getPrice();
    },
    dingwei: function () {
        var self = this;
        getApp().getauth({
            content: "需要获取您的地理位置授权，请到小程序设置中打开授权",
            author: "scope.userLocation",
            success: function (e) {
                if (e) {
                    if (e.authSetting["scope.userLocation"]) {
                        getApp().core.chooseLocation({
                            success: function (e) {
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

    orderSubmit: function (e) {
        var self = this;
        var offline = self.data.offline;
        var data = {};
        if (offline == 0) {
            if (self.data.is_area == 1) {
                getApp().core.showToast({
                    title: "所选地区无货",
                    image: "/images/icon-warning.png",
                });
                return
            }
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
                    title: "请填写联系人",
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
        var form = self.data.form;
        if (form.is_form == 1 && self.data.goods_list && self.data.goods_list.length) {
            var form_list = form.list;
            for (var i in form_list) {
                if (form_list[i].type == 'date') {
                    form_list[i].default = form_list[i].default ? form_list[i].default : self.data.time;
                }
                if (form_list[i].type == 'time') {
                    form_list[i].default = form_list[i].default ? form_list[i].default : '00:00';
                }
                if (form_list[i].required == 1) {
                    if (form_list[i].type == 'radio' || form_list[i].type == 'checkboxc') {
                        var is_true = false;
                        for (var j in form_list[i].default_list) {
                            if (form_list[i].default_list[j].is_selected == 1) {
                                is_true = true;
                            }
                        }
                        if (!is_true) {
                            getApp().core.showModal({
                                title: '提示',
                                content: '请填写' + form.name + '，加‘*’为必填项',
                                showCancel: false
                            })
                            return false;
                        }
                    } else {
                        if (!form_list[i].default || form_list[i].default == undefined) {
                            getApp().core.showModal({
                                title: '提示',
                                content: '请填写' + form.name + '，加‘*’为必填项',
                                showCancel: false
                            })
                            return false;
                        }
                    }
                }
            }
        }


      if (self.data.pond_id > 0 || self.data.scratch_id > 0 || self.data.lottery_id > 0 || self.data.step_id > 0) {
            if (self.data.express_price > 0 && self.data.payment == -1) {
                self.setData({
                    show_payment: true
                });
                return false;
            }
        } else {
            if (self.data.payment == -1) {
                self.setData({
                    show_payment: true
                });
                return false;
            }
        }

        data.form = JSON.stringify(form);
        if (self.data.cart_id_list) {
            data.cart_id_list = JSON.stringify(self.data.cart_id_list);
        }
        if (self.data.mch_list && self.data.mch_list.length) {
            var mch_list = [];
            for (var i in self.data.mch_list) {
                if (!self.data.mch_list[i].cart_id_list)
                    continue;
                var mch_data = {
                    id: self.data.mch_list[i].id,
                    cart_id_list: self.data.mch_list[i].cart_id_list,
                };
                if (self.data.mch_list[i].content) {
                    mch_data.content = self.data.mch_list[i].content;
                }
                mch_list.push(mch_data);
            }
            if (mch_list.length)
                data.mch_list = JSON.stringify(mch_list);
            else
                data.mch_list = '';
        }
        if (self.data.goods_info) {
            data.goods_info = JSON.stringify(self.data.goods_info);
        }

        if (self.data.picker_coupon) {
            data.user_coupon_id = self.data.picker_coupon.user_coupon_id;
        }
        if (self.data.content) {
            data.content = self.data.content;
        }
        if (self.data.cart_list) {
            data.cart_list = JSON.stringify(self.data.cart_list);
        }
        self.data.integral_radio == 1 ? data.use_integral = 1 : data.use_integral = 2;

        //单个商品下单时，DuoShangHu订单的备注
        if ((!self.data.goods_list || !self.data.goods_list.length) && self.data.mch_list && self.data.mch_list.length == 1) {
            data.content = self.data.mch_list[0].content ? self.data.mch_list[0].content : '';
        }

        data.payment = self.data.payment;
        data.formId = e.detail.formId;
        data.pond_id = self.data.pond_id;
        data.scratch_id = self.data.scratch_id;
        data.step_id = self.data.step_id;
        data.lottery_id = self.data.lottery_id;

        if (data.pond_id) {
            self.order_submit(data, 'pond');
        } else if (data.scratch_id) {
            self.order_submit(data, 'scratch');
        } else if (data.lottery_id) {
            self.order_submit(data, 'lottery');
        } else if (data.step_id) {
          self.order_submit(data, 'step');
        } else {
            self.order_submit(data, 's');
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
    onShow: function (e) {
        if (getApp().onShowData && getApp().onShowData.scene && (getApp().onShowData.scene == 1034 || getApp().onShowData.scene == 'pay')) {
            //从支付完成页面返回的不再执行onShow的内容
            return;
        }
        if (loadingImg) {
            loadingImg = false;
            return;
        }
        var pages = getCurrentPages();
        getApp().page.onShow(this);
        var self = this;
        var address = getApp().core.getStorageSync(getApp().const.PICKER_ADDRESS);
        if (address) {
            var is_area_city_id = self.data.is_area_city_id;
            var data = {};
            data.address = address;
            data.name = address.name;
            data.mobile = address.mobile;
            getApp().core.removeStorageSync(getApp().const.PICKER_ADDRESS);
            self.setData(data);
            self.getInputData();
        }
        self.getOrderData(self.data.options);
    },


    getOrderData: function (options) {
        var self = this;
        var data = {};
        var address_id = "";
        if (self.data.address && self.data.address.id)
            address_id = self.data.address.id;
        data.address_id = address_id;
        data.longitude = longitude;
        data.latitude = latitude;

        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        if (options.cart_list) {
            var cart_list = JSON.parse(options.cart_list);
            data.cart_list = options.cart_list;
        }

        if (options.cart_id_list) {
            var cart_id_list = JSON.parse(options.cart_id_list);
            data.cart_id_list = cart_id_list;
        }
        if (options.mch_list) {
            var mch_list = JSON.parse(options.mch_list);
            data.mch_list = mch_list;
        }
        if (options.goods_info) {
            data.goods_info = options.goods_info;
        }
        if (options.bargain_order_id){
            data.bargain_order_id = options.bargain_order_id;
        }
        if (options.step_id) {
            data.step_id  = options.step_id;
        }
        getApp().request({
            url: getApp().api.order.submit_preview,
            data: data,
            success: function (res) {
                getApp().core.hideLoading();
                if (res.code == 0) {
                    var input_data = getApp().core.getStorageSync(getApp().const.INPUT_DATA);
                    getApp().core.removeStorageSync(getApp().const.INPUT_DATA);
                    var coupon_lists = [];
                    var coupon_list = res.data.coupon_list;
                    for (var i in coupon_list) {
                        if (coupon_list[i] != null) {
                            coupon_lists.push(coupon_list[i])
                        }
                    }
                    var shop_list = res.data.shop_list;
                    var shop = {};
                    if (shop_list && shop_list.length == 1) {
                        shop = shop_list[0];
                    }
                    if (res.data.is_shop) {
                        shop = res.data.is_shop;
                    }
                    if (!input_data) {
                        input_data = {
                            shop: shop,
                            address: res.data.address || null,
                            name: res.data.address ? res.data.address.name : '',
                            mobile: res.data.address ? res.data.address.mobile : '',
                            pay_type_list: res.data.pay_type_list,
                            form: res.data.form
                        }
                        if (input_data.pay_type_list.length > 1) {
                            input_data.payment = -1;
                        } else {
                            input_data.payment = input_data.pay_type_list[0].payment
                        }
                    }

                    input_data.total_price = res.data.total_price || 0;
                    input_data.goods_list = res.data.list || null;
                    input_data.express_price = parseFloat(res.data.express_price);
                    input_data.coupon_list = coupon_list;
                    input_data.shop_list = shop_list;
                    input_data.send_type = res.data.send_type;
                    input_data.level = res.data.level;
                    input_data.new_total_price = res.data.total_price || 0;
                    input_data.integral = res.data.integral;
                    input_data.goods_card_list = res.data.goods_card_list || [];
                    input_data.is_payment = res.data.is_payment;
                    input_data.mch_list = res.data.mch_list || null;
                    input_data.is_area_city_id = res.data.is_area_city_id;
                    input_data.pay_type_list = res.data.pay_type_list;
                    input_data.offer_rule = res.data.offer_rule;
                    input_data.is_area = res.data.is_area;

                    self.setData(input_data);
                    self.getInputData();
                    if (res.data.goods_info) {
                        self.setData({
                            goods_info: res.data.goods_info,
                        });
                    }
                    if (res.data.cart_id_list) {
                        self.setData({
                            cart_id_list: res.data.cart_id_list,
                        });
                    }
                    if (res.data.cart_list) {
                        self.setData({
                            cart_list: res.data.cart_list,
                        });
                    }
                    if (res.data.send_type == 1) {//仅快递
                        self.setData({
                            offline: 0,
                        });
                    }
                    if (res.data.send_type == 2) {//仅自提
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

    copyText: function (e) {
        var text = e.currentTarget.dataset.text;
        if (!text)
            return;
        getApp().core.setClipboardData({
            data: text,
            success: function () {
                self.showToast({
                    title: "已复制内容",
                });
            },
            fail: function () {
                self.showToast({
                    title: "复制失败",
                    image: "/images/icon-warning.png",
                });
            },
        });
    },

    showCouponPicker: function () {
        var self = this;
        self.getInputData();
        if (self.data.coupon_list && self.data.coupon_list.length > 0) {
            self.setData({
                show_coupon_picker: true,
            });
        }
    },

    pickCoupon: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var data = getApp().core.getStorageSync(getApp().const.INPUT_DATA);
        getApp().core.removeStorageSync(getApp().const.INPUT_DATA);
        if (index == '-1' || index == -1) {
            data.picker_coupon = false;
            data.show_coupon_picker = false;
        } else {
            data.picker_coupon = self.data.coupon_list[index];
            data.show_coupon_picker = false;
        }
        self.setData(data);
        self.getPrice();
    },

    numSub: function (num1, num2, length) {
        return 100;
    },
    showShop: function (e) {
        var self = this;
        self.getInputData();
        self.dingwei();
        if (self.data.shop_list && self.data.shop_list.length >= 1) {
            self.setData({
                show_shop: true,
            });
        }
    },
    pickShop: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var data = getApp().core.getStorageSync(getApp().const.INPUT_DATA);
        getApp().core.removeStorageSync(getApp().const.INPUT_DATA);
        if (index == '-1' || index == -1) {
            data.shop = false;
            data.show_shop = false;
        } else {
            data.shop = self.data.shop_list[index];
            data.show_shop = false;
        }
        self.setData(data);
        self.getPrice();
    },
    integralSwitchChange: function (e) {
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
    integration: function (e) {
        var self = this;
        var integration = self.data.integral.integration;
        getApp().core.showModal({
            title: '积分使用规则',
            content: integration,
            showCancel: false,
            confirmText: '我知道了',
            confirmColor: '#ff4544',
            success: function (res) {
                if (res.confirm) {
                }
            }
        });
    },
    /**
     * 计算总价
     */
    getPrice: function () {
        var self = this;
        var total_price = self.data.total_price;
        var new_total_price = total_price;
        var express_price = self.data.express_price;
        var picker_coupon = self.data.picker_coupon;
        var integral = self.data.integral;
        var integral_radio = self.data.integral_radio;
        var level = self.data.level;
        var offline = self.data.offline;

        if (self.data.goods_list && self.data.goods_list.length > 0) {//有自营商品时
            if (picker_coupon) {//选择优惠券
                new_total_price = new_total_price - picker_coupon.sub_price;
            }

            if (integral && integral_radio == 1) {//选择积分
                new_total_price = new_total_price - parseFloat(integral.forehead);
            }

            if (level) {
                new_total_price = new_total_price * level.discount / 10;
            }

            if (new_total_price <= 0.01) {
                new_total_price = 0.01;
            }

            if (offline == 0) {
                new_total_price = new_total_price + express_price;
            }
        }


        if (self.data.mch_list && self.data.mch_list.length) {
            for (var i in self.data.mch_list) {
                new_total_price += self.data.mch_list[i].total_price + self.data.mch_list[i].express_price;
            }
        }

        self.setData({
            new_total_price: parseFloat(new_total_price.toFixed(2))
        });

    },
    cardDel: function () {
        var self = this;
        self.setData({
            show_card: false
        });
        getApp().core.redirectTo({
            url: '/pages/order/order?status=1',
        })
    },
    cardTo: function () {
        var self = this;
        self.setData({
            show_card: false
        });
        getApp().core.redirectTo({
            url: '/pages/card/card'
        })
    },
    formInput: function (e) {
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
    selectForm: function (e) {
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
    showPayment: function () {
        this.setData({
            show_payment: true
        });
    },
    payPicker: function (e) {
        var index = e.currentTarget.dataset.index;
        this.setData({
            payment: index,
            show_payment: false
        });
    },
    payClose: function () {
        this.setData({
            show_payment: false
        });
    },
    getInputData: function () {
        var self = this;
        var data = {
            address: self.data.address,
            content: self.data.content,
            name: self.data.name,
            mobile: self.data.mobile,
            integral_radio: self.data.integral_radio,
            payment: self.data.payment,
            shop: self.data.shop,
            form: self.data.form,
            picker_coupon: self.data.picker_coupon
        }
        getApp().core.setStorageSync(getApp().const.INPUT_DATA, data);
    },

    onHide: function () {
        getApp().page.onHide(this);
        var self = this;
        self.getInputData();
    },

    onUnload: function () {
        getApp().page.onUnload(this);
        getApp().onShowData['scene'] = '';
        getApp().core.removeStorageSync(getApp().const.INPUT_DATA);
    },
    uploadImg: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var form = self.data.form;
        loadingImg = true;
        getApp().uploader.upload({
            start: function () {
                getApp().core.showLoading({
                    title: '正在上传',
                    mask: true,
                });
            },
            success: function (res) {
                if (res.code == 0) {
                    form.list[index].default = res.data.url
                    self.setData({
                        form: form,
                    });
                } else {
                    self.showToast({
                        title: res.msg,
                    });
                }
            },
            error: function (e) {
                self.showToast({
                    title: e,
                });
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        })
    }
});