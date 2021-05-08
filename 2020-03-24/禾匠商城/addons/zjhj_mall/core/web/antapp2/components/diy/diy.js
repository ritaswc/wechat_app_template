if (typeof wx === 'undefined') var wx = getApp().core;
module.exports = {
    currentPage: null,
    gSpecificationsModel: null,
    init: function(self) {
        this.currentPage = self;
        var _this = this;
        this.gSpecificationsModel = require('../../components/goods/specifications_model.js'); //商城多规格选择
        this.gSpecificationsModel.init(self);
        if (typeof self.showNotice === 'undefined') {
            self.showNotice = function() {
                _this.showNotice();
            };
        }
        if (typeof self.closeNotice === 'undefined') {
            self.closeNotice = function() {
                _this.closeNotice();
            };
        }
        if (typeof self.play === 'undefined') {
            self.play = function(e) {
                _this.play(e);
            };
        }
        if (typeof self.receive === 'undefined') {
            self.receive = function(e) {
                _this.receive(e);
            };
        }
        if (typeof self.closeCouponBox === 'undefined') {
            self.closeCouponBox = function(e) {
                _this.closeCouponBox(e);
            };
        }
        if (typeof self.catBind === 'undefined') {
            self.catBind = function(e) {
                _this.catBind(e);
            };
        }
        if (typeof self.modalShowGoods === 'undefined') {
            self.modalShowGoods = function(e) {
                _this.modalShowGoods(e);
            };
        }
        if (typeof self.modalConfirmGoods === 'undefined') {
            self.modalConfirmGoods = function(e) {
                _this.modalConfirmGoods(e);
            };
        }
        if (typeof self.modalCloseGoods === 'undefined') {
            self.modalCloseGoods = function(e) {
                _this.modalCloseGoods(e);
            };
        }
        if (typeof self.setTime === 'undefined') {
            self.setTime = function(e) {
                _this.setTime(e);
            };
        }
        if (typeof self.closeActModal === 'undefined') {
            self.closeActModal = function(e) {
                _this.closeActModal(e);
            };
        }
        if (typeof self.goto === 'undefined') {
            self.goto = function(e) {
                _this.goto(e);
            }
        }
        if (typeof self.go === 'undefined') {
            self.go = function (e) {
                _this.go(e);
            }
        }
        if (typeof self.couponNavigator === 'undefined') {
            self.couponNavigator = function (e) {
                _this.couponNavigator(e);
            }
        }
    },

    showNotice: function() {
        var self = this.currentPage
        self.setData({
            show_notice: true
        });
    },

    closeNotice: function() {
        var self = this.currentPage
        self.setData({
            show_notice: false
        });
    },
    play: function(e) {
        var self = this.currentPage;
        self.setData({
            play: e.currentTarget.dataset.index
        });
    },

    receive: function(e) {
        var self = this.currentPage;
        var id = e.currentTarget.dataset.index;
        getApp().core.showLoading({
            title: '领取中',
            mask: true,
        });
        if (!self.hideGetCoupon) {
            self.hideGetCoupon = function(e) {
                var url = e.currentTarget.dataset.url || false;
                self.setData({
                    get_coupon_list: null,
                });
                wx.navigateTo({
                    url: url || '/pages/list/list',
                });
            };
        }
        getApp().request({
            url: getApp().api.coupon.receive,
            data: {
                id: id
            },
            success: function(res) {
                getApp().core.hideLoading();
                if (res.code == 0) {
                    self.setData({
                        get_coupon_list: res.data.list,
                        coupon_list: res.data.coupon_list
                    });
                } else {
                    getApp().core.showToast({
                        title: res.msg,
                        duration: 2000
                    })
                    self.setData({
                        coupon_list: res.data.coupon_list
                    });
                }
            },
        });
    },

    closeCouponBox: function(e) {
        this.currentPage.setData({
            get_coupon_list: ""
        });
    },

    catBind: function(e) {
        var self = this.currentPage;
        var template_index = e.currentTarget.dataset.template;
        var index = e.currentTarget.dataset.index;
        var template = self.data.template;
        template[template_index].param.cat_index = index;
        self.setData({
            template: template
        });
    },

    modalShowGoods: function(e) {
        var self = this.currentPage;
        var template_list = self.data.template;
        var template_index = e.currentTarget.dataset.template;
        var cat_index = e.currentTarget.dataset.cat;
        var index = e.currentTarget.dataset.goods;
        var goods = template_list[template_index].param.list[cat_index].goods_list[index];
        var pageType = "STORE";
        if (template_list[template_index].type == 'goods') {
            goods.id = goods.goods_id;
            self.setData({
                goods: goods,
                show_attr_picker: true,
                attr_group_list: goods.attr_group_list,
                pageType: pageType,
                id: goods.id
            });
            this.gSpecificationsModel.selectDefaultAttr();
        } else {
            getApp().core.navigateTo({
                url: goods.page_url
            });
            return;
        }
    },

    modalConfirmGoods: function(e) {
        var self = this.currentPage;
        var pageType = self.data.pageType;
        var goodsBuy = require('../../components/goods/goods_buy.js');
        goodsBuy.currentPage = self;
        goodsBuy.submit('ADD_CART');
        self.setData({
            form: {
                number: 1
            }
        });
    },

    modalCloseGoods: function(e) {
        var self = this.currentPage;
        self.setData({
            show_attr_picker: false,
            form: {
                number: 1
            }
        });
    },

    template_time: null,
    setTime: function(e) {
        var _this = this;
        var self = this.currentPage;
        var list = self.data.time_all;
        if (_this['template_time_' + self.data.options.page_id]) {
            clearInterval(_this['template_time_' + self.data.options.page_id]);
        }
        _this['template_time_' + self.data.options.page_id] = setInterval(function () {
            for (var i in list) {
                if (list[i].type == 'time') {
                    if (list[i].param.start_time > 0) {
                        list[i].param.start_time--;
                        list[i].param.end_time--;
                        list[i].param.time_list = self.setTimeList(list[i].param.start_time);
                    } else if (list[i].param.end_time > 0) {
                        list[i].param.end_time--;
                        list[i].param.time_list = self.setTimeList(list[i].param.end_time);
                    }
                }
                if (list[i].type == 'miaosha' || list[i].type == 'bargain' || list[i].type == 'lottery') {
                    var cat_index = list[i].param.cat_index;
                    for (var j in list[i].param.list[cat_index].goods_list) {
                        if (list[i].param.list[cat_index].goods_list[j].time > 0) {
                            list[i].param.list[cat_index].goods_list[j].time--;
                            list[i].param.list[cat_index].goods_list[j].time_list = self.setTimeList(list[i].param.list[cat_index].goods_list[j].time);
                            if (list[i].param.list[cat_index].goods_list[j].time_end > 0) {
                                list[i].param.list[cat_index].goods_list[j].time_end--;
                                if (list[i].param.list[cat_index].goods_list[j].time == 1) {
                                    list[i].param.list[cat_index].goods_list[j].is_start = 1;
                                    list[i].param.list[cat_index].goods_list[j].time = list[i].param.list[cat_index].goods_list[j].time_end;
                                    list[i].param.list[cat_index].goods_list[j].time_end = 0;
                                    list[i].param.list[cat_index].goods_list[j].time_content = list[i].param.list_style == 1 ? "仅剩" : "距结束仅剩";
                                }
                            }
                        } else {
                            list[i].param.list[cat_index].goods_list[j].is_start = 1;
                            list[i].param.list[cat_index].goods_list[j].time = 0;
                            list[i].param.list[cat_index].goods_list[j].time_content = "活动已结束";
                            list[i].param.list[cat_index].goods_list[j].time_list = {};
                        }
                    }
                }
            }
            self.setData({
                time_all: list
            });
        }, 1000);
    },

    closeActModal: function() {
        var self = this.currentPage;
        var act_modal_list = self.data.act_modal_list;
        var show_next = true;
        var next_i;
        for (var i in act_modal_list) {
            var index = parseInt(i);
            if (act_modal_list[index].show) {
                act_modal_list[index].show = false;
                next_i = index + 1;
                if (typeof act_modal_list[next_i] != 'undefined' && show_next) {
                    show_next = false;
                    setTimeout(function() {
                        self.data.act_modal_list[next_i].show = true;
                        self.setData({
                            act_modal_list: self.data.act_modal_list
                        });
                    }, 500);
                }
            }
        }
        self.setData({
            act_modal_list: act_modal_list,
        });
    },

    goto: function (e) {
        var self = this;
        if (typeof my !== 'undefined') {
            self.location(e);
        } else {
            getApp().core.getSetting({
                success: function (res) {
                    if (!res.authSetting['scope.userLocation']) {
                        getApp().getauth({
                            content: '需要获取您的地理位置授权，请到小程序设置中打开授权！',
                            cancel: false,
                            author: 'scope.userLocation',
                            success: function (res) {
                                console.log(res);
                                if (res.authSetting['scope.userLocation']) {
                                    self.location(e);
                                }
                            }
                        });
                    } else {
                        self.location(e);
                    }
                }
            })
        }
    },

    location: function (e) {
        var self = this.currentPage;
        var _this = this;
        var shop_list = [];
        var template = e.currentTarget.dataset.template;
        if (typeof template !== 'undefined') {
            shop_list = self.data.template[template].param.list;
        } else {
            shop_list = self.data.list;
        }
        var index = e.currentTarget.dataset.index;
        getApp().core.openLocation({
            latitude: parseFloat(shop_list[index].latitude),
            longitude: parseFloat(shop_list[index].longitude),
            name: shop_list[index].name,
            address: shop_list[index].address,
        })
    },

    go: function (e) {
        var self = this.currentPage;
        var template = e.currentTarget.dataset.template;
        var shop_list = [];
        if (typeof template !== 'undefined') {
            shop_list = self.data.template[template].param.list;
        } else {
            shop_list = self.data.list;
        }
        var index = e.currentTarget.dataset.index;
        getApp().core.navigateTo({
            url: '/pages/shop-detail/shop-detail?shop_id=' + shop_list[index].id,
        })
    },
    
    couponNavigator: function (e) {
        var id = e.currentTarget.dataset.index;
        getApp().core.navigateTo({
            url: '/pages/integral-mall/coupon-info/index?coupon_id=' + id
        });
    }
};