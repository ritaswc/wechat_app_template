if (typeof wx === 'undefined') var wx = getApp().core;
module.exports = {
    currentPage: null,
    /**
     * 注意！注意！！注意！！！
     * 由于组件的通用，部分变量名称需统一，在各自引用的xxx.js文件需定义，并给对应变量赋相应的值
     * 以下变量必须定义并赋值
     * 
     * 持续补充...
     */
    init: function(self) {
        var _this = this;
        _this.currentPage = self;

        if (typeof self.favoriteAdd === 'undefined') {
            self.favoriteAdd = function(e) {
                _this.favoriteAdd(e);
            }
        }
        if (typeof self.favoriteRemove === 'undefined') {
            self.favoriteRemove = function(e) {
                _this.favoriteRemove(e);
            }
        }
        if (typeof self.kfMessage === 'undefined') {
            self.kfMessage = function(e) {
                _this.kfMessage(e);
            }
        }
        if (typeof self.callPhone === 'undefined') {
            self.callPhone = function(e) {
                _this.callPhone(e);
            }
        }

        if (typeof self.addCart === 'undefined') {
            self.addCart = function(e) {
                _this.addCart(e);
            }
        }

        if (typeof self.buyNow === 'undefined') {
            self.buyNow = function(e) {
                _this.buyNow(e);
            }
        }

        if (typeof self.goHome === 'undefined') {
            self.goHome = function(e) {
                _this.goHome(e);
            }
        }


    },

    //收藏
    favoriteAdd: function() {
        var self = this.currentPage;
        getApp().request({
            url: getApp().api.user.favorite_add,
            method: "post",
            data: {
                goods_id: self.data.goods.id,
            },
            success: function(res) {
                if (res.code == 0) {
                    var goods = self.data.goods;
                    goods.is_favorite = 1;
                    self.setData({
                        goods: goods,
                    });
                }
            }
        });
    },

    //取消收藏
    favoriteRemove: function() {
        var self = this.currentPage;
        getApp().request({
            url: getApp().api.user.favorite_remove,
            method: "post",
            data: {
                goods_id: self.data.goods.id,
            },
            success: function(res) {
                if (res.code == 0) {
                    var goods = self.data.goods;
                    goods.is_favorite = 0;
                    self.setData({
                        goods: goods,
                    });
                }
            }
        });
    },

    //客服
    kfMessage: function() {
        let store = getApp().core.getStorageSync(getApp().const.STORE);
        if (!store.show_customer_service) {
            getApp().core.showToast({
                title: "未启用客服功能",
            });
        }

    },

    // 拨打电话
    callPhone: function(e) {
        getApp().core.makePhoneCall({
            phoneNumber: e.target.dataset.info
        })
    },

    //加入购物车
    addCart: function() {
        var self = this.currentPage;
        if (self.data.btn) {
            this.submit('ADD_CART');
        }
    },

    // 立即购买
    buyNow: function() {
        var self = this.currentPage;
        if (self.data.btn) {
            this.submit('BUY_NOW');
        }
    },

    submit: function(type) {
        var self = this.currentPage;
        if (!self.data.show_attr_picker) {
            self.setData({
                show_attr_picker: true,
            });
            return true;
        }
        if (self.data.miaosha_data && self.data.miaosha_data.rest_num > 0 && self.data.form.number > self.data.miaosha_data.rest_num) {
            getApp().core.showToast({
                title: "商品库存不足，请选择其它规格或数量",
                image: "/images/icon-warning.png",
            });
            return true;
        }

        if (self.data.form.number > self.data.goods.num) {
            getApp().core.showToast({
                title: "商品库存不足，请选择其它规格或数量",
                image: "/images/icon-warning.png",
            });
            return true;
        }
        var attr_group_list = self.data.attr_group_list;
        var checked_attr_list = [];
        for (var i in attr_group_list) {
            var attr = false;
            for (var j in attr_group_list[i].attr_list) {
                if (attr_group_list[i].attr_list[j].checked) {
                    attr = {
                        attr_id: attr_group_list[i].attr_list[j].attr_id,
                        attr_name: attr_group_list[i].attr_list[j].attr_name,
                    };
                    break;
                }
            }
            if (!attr) {
                getApp().core.showToast({
                    title: "请选择" + attr_group_list[i].attr_group_name,
                    image: "/images/icon-warning.png",
                });
                return true;
            } else {
                checked_attr_list.push({
                    attr_group_id: attr_group_list[i].attr_group_id,
                    attr_id: attr.attr_id,
                });
            }
        }
        if (type == 'ADD_CART') { //加入购物车
            getApp().core.showLoading({
                title: "正在提交",
                mask: true,
            });
            getApp().request({
                url: getApp().api.cart.add_cart,
                method: "POST",
                data: {
                    goods_id: self.data.goods.id,
                    attr: JSON.stringify(checked_attr_list),
                    num: self.data.form.number,
                },
                success: function(res) {
                    getApp().core.hideLoading();
                    getApp().core.showToast({
                        title: res.msg,
                        duration: 1500
                    });
                    self.setData({
                        show_attr_picker: false,
                    });

                }
            });
        }
        if (type == 'BUY_NOW') { //立即购买
            self.setData({
                show_attr_picker: false,
            });
            var goods_list = [];
            goods_list.push({
                goods_id: self.data.id,
                num: self.data.form.number,
                attr: checked_attr_list
            });
            var goods = self.data.goods;
            var mch_id = 0;
            if (goods.mch != null) {
                mch_id = goods.mch.id
            }
            var mch_list = [];
            mch_list.push({
                mch_id: mch_id,
                goods_list: goods_list
            });
            getApp().core.redirectTo({
                url: "/pages/new-order-submit/new-order-submit?mch_list=" + JSON.stringify(mch_list),
            });
        }

    },

    // 返回首页
    goHome: function(e) {
        var self = this.currentPage;
        var pageType = self.data.pageType;
        if (pageType === 'PINTUAN') {
            var url = '/pages/pt/index/index';
        } else if (pageType === 'BOOK') {
            var url = '/pages/book/index/index';
        } else {
            var url = '/pages/index/index';
        }

        getApp().core.redirectTo({
            url: url
        })
    },

    // buynow: function (e) {
    //     var self = this.currentPage;
    //     var carGoods = self.data.carGoods;
    //     var goodsModel = self.data.goodsModel;
    //     self.setData({
    //         goodsModel: false
    //     });
    //     var length = carGoods.length;
    //     var cart_list = [];
    //     var cart_list_goods = [];
    //     for (var a = 0; a < length; a++) {
    //         if (carGoods[a].num != 0) {
    //             cart_list_goods = {
    //                 goods_id: carGoods[a].goods_id,
    //                 num: carGoods[a].num,
    //                 attr: carGoods[a].attr
    //             }
    //             cart_list.push(cart_list_goods)
    //         }
    //     }
    //     var mch_list = [];
    //     mch_list.push({
    //         mch_id: 0,
    //         goods_list: cart_list
    //     });
    //     getApp().core.navigateTo({
    //         url: '/pages/new-order-submit/new-order-submit?mch_list=' + JSON.stringify(mch_list),
    //     });
    // },
}