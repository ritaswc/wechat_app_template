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
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        getApp().core.removeStorageSync(getApp().const.INPUT_DATA);
        var self = this;
        var goods_info = options.goods_info;
        var goods = JSON.parse(goods_info);
        var offline;
        if (goods.deliver_type == 3 || goods.deliver_type == 1) {
            offline = 1;
        } else {
            offline = 2;
        }
        self.setData({
            options: options,
            type: goods.type,
            offline: offline,
            parent_id: goods.parent_id ? goods.parent_id : 0,
        });
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

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function(options) {
        getApp().page.onHide(this);
        this.getInputData();
    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function(options) {
        getApp().page.onUnload(this);
        getApp().core.removeStorageSync(getApp().const.INPUT_DATA);
    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function(options) {
        getApp().page.onPullDownRefresh(this);

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function(options) {
        getApp().page.onReachBottom(this);

    },
    /**
     * 获取提交订单信息
     */
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
            //   var goods_info = options.goods_info;
            //   var goods = JSON.parse(goods_info);
            getApp().request({
                url: getApp().api.group.submit_preview,
                data: {
                    goods_info: options.goods_info,
                    group_id: options.group_id,
                    address_id: address_id,
                    type: self.data.type,
                    longitude: longitude,
                    latitude: latitude
                },
                success: function(res) {
                    getApp().core.hideLoading();
                    if (res.code == 0) {
                        var level_price = 0;
                        for (var keys in res.data.list) {
                            level_price = res.data.list[keys]['level_price'];
                        }

                        if (self.data.offline == 2) {
                            var total_price_1 = parseFloat((level_price - res.data.colonel) > 0 ? (level_price - res.data.colonel) : 0.01);
                            var express_price = 0;
                        } else {
                            var total_price_1 = parseFloat((level_price - res.data.colonel) > 0 ? (level_price - res.data.colonel) : 0.01) + res.data.express_price;
                            var express_price = parseFloat(res.data.express_price);
                        }

                        var input_data = getApp().core.getStorageSync(getApp().const.INPUT_DATA);
                        getApp().core.removeStorageSync(getApp().const.INPUT_DATA);
                        if (!input_data) {
                            input_data = {
                                address: res.data.address,
                                name: res.data.name ? res.data.name : '',
                                mobile: res.data.mobile ? res.data.mobile : '',
                            };
                            if (res.data.pay_type_list.length > 0) {
                                input_data.payment = res.data.pay_type_list[0].payment
                                if (res.data.pay_type_list.length > 1) {
                                    input_data.payment = -1;
                                }
                            }
                            if (res.data.shop) {
                                input_data.shop = res.data.shop;
                            }
                            if (res.data.shop_list && res.data.shop_list.length == 1) {
                                input_data.shop = res.data.shop_list[0];
                            }
                        }
                        

                        input_data.total_price = res.data.total_price;
                        input_data.total_price_1 = total_price_1.toFixed(2);
                        input_data.goods_list = res.data.list;
                        input_data.goods_info = res.data.goods_info;
                        input_data.express_price = express_price;
                        input_data.send_type = res.data.send_type;
                        input_data.colonel = res.data.colonel;
                        input_data.pay_type_list = res.data.pay_type_list;
                        input_data.shop_list = res.data.shop_list;
                        input_data.res = res.data;
                        input_data.is_area = res.data.is_area;
                        self.setData(input_data);
                        self.getInputData();
                    }
                    if (res.code == 1) {
                        getApp().core.showModal({
                            title: "提示",
                            content: res.msg,
                            showCancel: false,
                            confirmText: "返回",
                            success: function(res) {
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
        }
    },
    /**
     * 绑定参数
     */
    bindkeyinput: function(e) {
        this.setData({
            content: e.detail.value
        });
    },
    /**
     * 订单提交
     */
    orderSubmit: function(e) {
        var self = this;
        var data = {};
        var offline = self.data.offline;
        data.offline = offline;
        if (offline == 1) {
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
                getApp().core.showToast({
                    title: "请选择核销门店",
                    image: "/images/icon-warning.png",
                });
                return;
            }
            if (!data.address_name || data.address_name == undefined) {
                getApp().core.showToast({
                    title: "请填写收货人",
                    image: "/images/icon-warning.png",
                });
                return;
            }
            if (!data.address_mobile || data.address_mobile == undefined) {
                getApp().core.showToast({
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
                //     });
                //     return;
                // }
            }
        }

        if (self.data.payment == -1) {
            self.setData({
                show_payment: true
            });
            return false;
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
        if (self.data.type) {
            data.type = self.data.type;
        }

        if (self.data.parent_id) {
            data.parent_id = self.data.parent_id;
        }

        data.payment = self.data.payment;
        data.formId = e.detail.formId;
        self.order_submit(data, 'pt');
        return;
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
    /**
     * 送货方式切换
     */
    getOffline: function(e) {
        var self = this;
        var offline = e.target.dataset.index;
        var total_price_1 = parseFloat((self.data.res.total_price - self.data.res.colonel) > 0 ? (self.data.res.total_price - self.data.res.colonel) : 0.01) + self.data.res.express_price;
        if (offline == 1) {
            this.setData({
                offline: 1,
                express_price: self.data.res.express_price,
                total_price_1: total_price_1.toFixed(2),
            });

        } else {
            var total_price = (self.data.total_price_1 - self.data.express_price).toFixed(2);
            this.setData({
                offline: 2,
                express_price: 0,
                total_price_1: total_price,
            });
        }
        // self.getPrice();
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
    dingwei: function() {
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
    pickShop: function(e) {
        var self = this;
        var input_data = getApp().core.getStorageSync(getApp().const.INPUT_DATA);
        var index = e.currentTarget.dataset.index;
        input_data.show_shop = false;
        if (index == '-1' || index == -1) {
            input_data.shop = false;
        } else {
            input_data.shop = self.data.shop_list[index];
        }
        self.setData(input_data);
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
            payment: self.data.payment,
            content: self.data.content,
            shop: self.data.shop
        };
        getApp().core.setStorageSync(getApp().const.INPUT_DATA, data);
    }
})