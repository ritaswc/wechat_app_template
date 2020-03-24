if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        total_price: 0.00,
        cart_check_all: false,
        cart_list: [],
        mch_list: [],
        loading: true,
        check_all_self: false,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function() {

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function() {
        getApp().page.onShow(this);
        var self = this;
        self.setData({
            cart_check_all: false,
            show_cart_edit: false,
            check_all_self: false,
        });
        self.getCartList();
    },

    getCartList: function() {
        var self = this;
        getApp().core.showNavigationBarLoading();
        self.setData({
            show_no_data_tip: false,
            loading: true,
        });
        getApp().request({
            url: getApp().api.cart.list,
            success: function(res) {
                if (res.code == 0) {
                    self.setData({
                        cart_list: res.data.list,
                        mch_list: res.data.mch_list,
                        total_price: 0.00,
                        cart_check_all: false,
                        show_cart_edit: false,
                    });
                }
                self.setData({
                    show_no_data_tip: (self.data.cart_list.length == 0),
                });
            },
            complete: function() {
                getApp().core.hideNavigationBarLoading();
                self.setData({
                    loading: false,
                });
            }
        });
    },

    //购物车减少
    cartLess: function(index) {
        var self = this;
        if (index.currentTarget.dataset.type && index.currentTarget.dataset.type == 'mch') {
            var mch_index = index.currentTarget.dataset.mchIndex;
            var item_index = index.currentTarget.dataset.index;
            self.data.mch_list[mch_index].list[item_index].num = self.data.mch_list[mch_index].list[item_index].num - 1;
            self.data.mch_list[mch_index].list[item_index].price = self.data.mch_list[mch_index].list[item_index].num * self.data.mch_list[mch_index].list[item_index].unitPrice;
            self.setData({
                mch_list: self.data.mch_list,
            });
        } else {
            var cart_list = self.data.cart_list;
            for (var i in cart_list) {
                if (index.currentTarget.id == cart_list[i]['cart_id']) {
                    cart_list[i]['num'] = self.data.cart_list[i]['num'] - 1;
                    cart_list[i]['price'] = self.data.cart_list[i]['unitPrice'] * cart_list[i]['num'];
                    self.setData({
                        cart_list: cart_list,
                    });
                }
            }
        }
        self.updateTotalPrice();

    },
    //购物车添加
    cartAdd: function(index) {
        var self = this;
        if (index.currentTarget.dataset.type && index.currentTarget.dataset.type == 'mch') {
            var mch_index = index.currentTarget.dataset.mchIndex;
            var item_index = index.currentTarget.dataset.index;
            self.data.mch_list[mch_index].list[item_index].num = self.data.mch_list[mch_index].list[item_index].num + 1;
            self.data.mch_list[mch_index].list[item_index].price = self.data.mch_list[mch_index].list[item_index].num * self.data.mch_list[mch_index].list[item_index].unitPrice;
            self.setData({
                mch_list: self.data.mch_list,
            });
        } else {
            var cart_list = self.data.cart_list;
            for (var i in cart_list) {
                if (index.currentTarget.id == cart_list[i]['cart_id']) {
                    cart_list[i]['num'] = self.data.cart_list[i]['num'] + 1;
                    cart_list[i]['price'] = self.data.cart_list[i]['unitPrice'] * cart_list[i]['num'];
                    self.setData({
                        cart_list: cart_list,
                    });
                }
            }
        }
        self.updateTotalPrice();
    },
    cartCheck: function(e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var type = e.currentTarget.dataset.type;
        var mch_index = e.currentTarget.dataset.mchIndex;
        if (type == 'self') {
            self.data.cart_list[index].checked = self.data.cart_list[index].checked ? false : true;
            self.setData({
                cart_list: self.data.cart_list,
            });
        }
        if (type == 'mch') {
            self.data.mch_list[mch_index].list[index].checked = self.data.mch_list[mch_index].list[index].checked ? false : true;
            self.setData({
                mch_list: self.data.mch_list,
            });
        }
        self.updateTotalPrice();
    },

    cartCheckAll: function() {
        var self = this;
        var cart_list = self.data.cart_list;
        var checked = false;
        if (self.data.cart_check_all) {
            checked = false;
        } else {
            checked = true;
        }
        for (var i in cart_list) {
            if (!cart_list[i].disabled || self.data.show_cart_edit)
                cart_list[i].checked = checked;
        }

        if (self.data.mch_list && self.data.mch_list.length) {
            for (var i in self.data.mch_list) {
                for (var j in self.data.mch_list[i].list) {
                    self.data.mch_list[i].list[j].checked = checked;
                }
            }
        }

        self.setData({
            cart_check_all: checked,
            cart_list: cart_list,
            mch_list: self.data.mch_list,
        });
        self.updateTotalPrice();

    },

    updateTotalPrice: function() {
        var self = this;
        var total_price = 0.00;
        var cart_list = self.data.cart_list;
        for (var i in cart_list) {
            if (cart_list[i].checked)
                total_price += cart_list[i].price;
        }
        for (var i in self.data.mch_list) {
            for (var j in self.data.mch_list[i].list) {
                if (self.data.mch_list[i].list[j].checked)
                    total_price += self.data.mch_list[i].list[j].price;
            }
        }
        self.setData({
            total_price: total_price.toFixed(2),
        });
    },

    /**
     * 提交
     *
     */
    cartSubmit: function() {
        var self = this;
        var cart_list = self.data.cart_list;
        var mch_list = self.data.mch_list;
        var cart_id_list = [];
        var mch_id_list = [];
        var _mch_list = [];
        var goods_list = [];
        for (var i in cart_list) {
            if (cart_list[i].checked) {
                cart_id_list.push(cart_list[i].cart_id);
                goods_list.push({
                    cart_id: cart_list[i].cart_id
                });
            }
        }
        if (cart_id_list.length > 0) {
            _mch_list.push({
                mch_id: 0,
                goods_list: goods_list
            });
        }
        for (var i in mch_list) {
            var id_list = [];
            var _goods_list = [];
            if (mch_list[i].list && mch_list[i].list.length) {
                for (var j in mch_list[i].list) {
                    if (mch_list[i].list[j].checked) {
                        id_list.push(mch_list[i].list[j].cart_id);
                        _goods_list.push({
                            cart_id: mch_list[i].list[j].cart_id
                        });
                    }
                }
            }
            if (id_list.length) {
                mch_id_list.push({
                    id: mch_list[i].id,
                    cart_id_list: id_list
                });
                _mch_list.push({
                    mch_id: mch_list[i].id,
                    goods_list: _goods_list
                });
            }
        }
        if (cart_id_list.length == 0 && mch_id_list.length == 0) {
            return true;
        }
        getApp().core.showLoading({
            title: '正在提交',
            mask: true,
        });
        self.saveCart(function() {
            getApp().core.navigateTo({
                url: '/pages/new-order-submit/new-order-submit?mch_list=' + JSON.stringify(_mch_list)
            });
        });
        getApp().core.hideLoading();
    },

    cartEdit: function() {
        var self = this;
        var cart_list = self.data.cart_list;
        for (var i in cart_list) {
            cart_list[i].checked = false;
        }
        self.setData({
            cart_list: cart_list,
            show_cart_edit: true,
            cart_check_all: false,
        });
        self.updateTotalPrice();
    },

    cartDone: function() {
        var self = this;
        var cart_list = self.data.cart_list;
        for (var i in cart_list) {
            cart_list[i].checked = false;
        }
        self.setData({
            cart_list: cart_list,
            show_cart_edit: false,
            cart_check_all: false,
        });
        self.updateTotalPrice();
    },

    cartDelete: function() {
        var self = this;
        var cart_list = self.data.cart_list;
        var cart_id_list = [];
        for (var i in cart_list) {
            if (cart_list[i].checked)
                cart_id_list.push(cart_list[i].cart_id);
        }
        if (self.data.mch_list && self.data.mch_list.length) {
            for (var i in self.data.mch_list) {
                for (var j in self.data.mch_list[i].list) {
                    if (self.data.mch_list[i].list[j].checked) {
                        cart_id_list.push(self.data.mch_list[i].list[j].cart_id);
                    }
                }
            }
        }
        if (cart_id_list.length == 0) {
            return true;
        }
        getApp().core.showModal({
            title: "提示",
            content: "确认删除" + cart_id_list.length + "项内容？",
            success: function(res) {
                if (res.cancel)
                    return true;
                getApp().core.showLoading({
                    title: "正在删除",
                    mask: true,
                });
                getApp().request({
                    url: getApp().api.cart.delete,
                    data: {
                        cart_id_list: JSON.stringify(cart_id_list),
                    },
                    success: function(res) {
                        getApp().core.hideLoading();
                        getApp().core.showToast({
                            title: res.msg,
                        });
                        if (res.code == 0) {
                            //self.cartDone();
                            self.getCartList();
                        }
                        if (res.code == 1) {}
                    }
                });
            }
        });
    },
    onHide: function() {
        var self = this;
        self.saveCart();
    },
    onUnload: function() {
        var self = this;
        self.saveCart();
    },

    saveCart: function(callback) {
        var self = this;
        var cart = JSON.stringify(self.data.cart_list);
        getApp().request({
            url: getApp().api.cart.cart_edit,
            method: 'post',
            data: {
                list: cart,
                mch_list: JSON.stringify(self.data.mch_list),
            },
            success: function(res) {
                if (res.code == 0) {}
            },
            complete: function() {
                if (typeof callback == 'function')
                    callback();
            }
        });
    },

    checkGroup: function(e) {
        var self = this;
        var type = e.currentTarget.dataset.type;
        var index = e.currentTarget.dataset.index;
        if (type == 'self') {
            for (var i in self.data.cart_list) {
                self.data.cart_list[i].checked = !self.data.check_all_self;
            }
            self.setData({
                check_all_self: !self.data.check_all_self,
                cart_list: self.data.cart_list,
            });
        }
        if (type == 'mch') {
            for (var i in self.data.mch_list[index].list) {
                self.data.mch_list[index].list[i].checked = self.data.mch_list[index].checked_all ? false : true;
            }
            self.data.mch_list[index].checked_all = self.data.mch_list[index].checked_all ? false : true;
            self.setData({
                mch_list: self.data.mch_list,
            });
        }
        self.updateTotalPrice();
    }

});