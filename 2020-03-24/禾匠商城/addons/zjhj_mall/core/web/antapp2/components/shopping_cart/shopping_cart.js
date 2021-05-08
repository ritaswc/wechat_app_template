if (typeof wx === 'undefined') var wx = getApp().core;
module.exports = {
    currentPage: null,

    init: function (self) {
        var _this = this;
        _this.currentPage = self;
        if (typeof self.shoppingCartListModel === 'undefined') {
            self.shoppingCartListModel = function (e) {
                _this.shoppingCartListModel(e);
            }
        }
        if (typeof self.hideShoppingCart === 'undefined') {
            self.hideShoppingCart = function (e) {
                _this.hideShoppingCart(e);
            }
        }
        if (typeof self.clearShoppingCart === 'undefined') {
            self.clearShoppingCart = function (e) {
                _this.clearShoppingCart(e);
            }
        }
        if (typeof self.jia === 'undefined') {
            self.jia = function (e) {
                _this.jia(e);
            }
        }
        if (typeof self.jian === 'undefined') {
            self.jian = function (e) {
                _this.jian(e);
            }
        }
        if (typeof self.goodNumChange === 'undefined') {
            self.goodNumChange = function (e) {
                _this.goodNumChange(e);
            }
        }

        if(typeof self.buynow === 'undefined') {
            self.buynow = function (e) {
                _this.buynow(e);
            }
        }
    },


    /**
     * 购物车总价及数量统计，根据购物车的商品总价及总数之和
     */
    carStatistics: function (self) {
        var carGoods = self.data.carGoods;
        var total_num = 0
        var total_price = 0.00
        for (var n in carGoods) {
            total_num = total_num + carGoods[n].num;
            total_price = parseFloat(total_price) + parseFloat(carGoods[n].goods_price);
        }
        var total = {
            'total_num': total_num,
            'total_price': total_price.toFixed(2)
        };

        // 如果购物车为空，则将购物车列表隐藏
        if (total_num === 0) {
            this.hideShoppingCart(self);
        }

        self.setData({
            total: total,
        })
    },

    /**
     * 无商品时、购物车变灰
     */
    hideShoppingCart: function () {
        var self = this.currentPage;
        self.setData({
            shoppingCartModel: false
        });
    },

    /**
     * 购物车商品列表弹框
     */
    shoppingCartListModel: function () {
        var self = this.currentPage;
        var carGoods = self.data.carGoods;
        var shoppingCartModel = self.data.shoppingCartModel;
        console.log(shoppingCartModel)
        if (shoppingCartModel) {
            self.setData({
                shoppingCartModel: false
            });
        } else {
            self.setData({
                shoppingCartModel: true
            });
        }
    },

    /**
     * 清空购物车
     */
    clearShoppingCart: function (self) {
        var self = this.currentPage;
        var quickHots = self.data.quick_hot_goods_lists;
        var quickList = self.data.quick_list;

        // 清除热销商品列表，商品的总数
        for (var i in quickHots) {
            for (var i2 in quickHots[i]) {
                quickHots[i].num = 0
            }
        }

        // 清除商品列表，商品的总数
        for (var j in quickList) {
            for (var j2 in quickList[j].goods) {
                quickList[j].goods[j2].num = 0
            }
        }
        self.setData({
            goodsModel: false,
            carGoods: [],
            total: {
                total_num: 0,
                total_price: 0.00
            },
            check_num: 0,
            quick_hot_goods_lists: quickHots,
            quick_list: quickList,
            currentGood: [],
            checked_attr: [],
            check_goods_price: 0.00,
            temporaryGood: {},
            //此两属性用在快速购买商品详情,可合并两属性优化
            goodNumCount: 0,
            goods_num: 0,

        });
        self.setData({
            shoppingCartModel: false
        });
        getApp().core.removeStorageSync(getApp().const.ITEM)
    },

    /**
     * 当快速购买页面隐藏、卸载时，缓存用户已选择数据
     */
    saveItemData: function (self) {
        var item = {
            'quick_list': self.data.quick_list,
            'carGoods': self.data.carGoods,
            'total': self.data.total,
            'quick_hot_goods_lists': self.data.quick_hot_goods_lists,
            'checked_attr': self.data.checked_attr
        }
        getApp().core.setStorageSync(getApp().const.ITEM, item)
    },


    // TODO 暂时先将下列方法写这
    /**
     * +购物车
     */
    jia: function (e) {
        var self = this.currentPage;
        var current_good = e.currentTarget.dataset;
        var quick_list = self.data.quick_list;
        for (var i in quick_list) {
            for (var i2 in quick_list[i].goods) {
                var qGoods = quick_list[i].goods[i2];
                if (parseInt(qGoods.id) === parseInt(current_good.id)) {
                    //记录商品添加数量
                    var num = qGoods.num ? qGoods.num + 1 : 1;
                    var goods_num = JSON.parse(qGoods.attr);
                    if (num > goods_num[0].num) {
                        wx.showToast({
                            title: "商品库存不足",
                            image: "/images/icon-warning.png",
                        });
                        return
                    }
                    qGoods.num = num;
                    //商品基本数据
                    var carGoods = self.data.carGoods;
                    var sign = 1; // 如果为0购物车列表则不添加新商品记录
                    var gPrice = current_good.price ? current_good.price : qGoods.price;
                    for (var j in carGoods) {
                        // 如果商品已存在则不需要重复添加
                        if (parseInt(carGoods[j].goods_id) === parseInt(qGoods.id) && JSON.parse(qGoods.attr).length === 1) {
                            sign = 0;
                            // 购物车列表 单规格商品增加
                            carGoods[j].num = num;
                            carGoods[j].goods_price = (carGoods[j].num * carGoods[j].price).toFixed(2);
                            break;
                        } else {
                            var carIndex = current_good.index;
                            // 购物车列表 多规格商品增加
                            if (carGoods[carIndex]) {
                                sign = 0;
                                carGoods[carIndex].num = carGoods[carIndex].num + 1;
                                carGoods[carIndex].goods_price = (carGoods[carIndex].num * carGoods[carIndex].price).toFixed(2)
                                break;
                            }
                        }
                    }

                    // 新商品则添加记录
                    if (sign === 1 || carGoods.length === 0) {
                        var attr = JSON.parse(quick_list[i].goods[i2].attr);
                        carGoods.push({
                            'goods_id': parseInt(quick_list[i].goods[i2].id),
                            'attr': attr[0]['attr_list'],
                            'goods_name': quick_list[i].goods[i2].name,
                            'goods_price': gPrice,
                            'num': 1,
                            'price': gPrice,
                        })
                    }
                }
            }
        }

        self.setData({
            carGoods: carGoods,
            quick_list: quick_list,
        })
        this.carStatistics(self);
        this.quickHotStatistics();
        this.updateGoodNum();
    },

    /**
     * -购物车
     */
    jian: function (e) {
        var self = this.currentPage;
        var current_good = e.currentTarget.dataset;
        var quick_list = self.data.quick_list;
        for (var i in quick_list) {
            for (var i2 in quick_list[i].goods) {
                var qGoods = quick_list[i].goods[i2];
                if (parseInt(qGoods.id) === parseInt(current_good.id)) {
                    //记录商品减少数量
                    var num = qGoods.num > 0 ? qGoods.num - 1 : qGoods.num
                    qGoods.num = num

                    //商品基本数据
                    var carGoods = self.data.carGoods;
                    for (var j in carGoods) {
                        var gPrice = current_good.price ? current_good.price : qGoods.price;
                        if (parseInt(carGoods[j].goods_id) === parseInt(qGoods.id) && JSON.parse(qGoods.attr).length === 1) {
                            // 购物车列表 单规格商品减少
                            carGoods[j].num = num;
                            carGoods[j].goods_price = (carGoods[j].num * carGoods[j].price).toFixed(2);
                            break;
                        } else {
                            // 购物车列表 多规格商品减少
                            var carIndex = current_good.index;
                            if (carGoods[carIndex] && carGoods[carIndex].num > 0) {
                                carGoods[carIndex].num = carGoods[carIndex].num - 1;
                                carGoods[carIndex].goods_price = (carGoods[carIndex].num * carGoods[carIndex].price).toFixed(2);
                                break;
                            }
                        }
                    }
                }
            }
        }

        self.setData({
            carGoods: carGoods,
            quick_list: quick_list,
        })

        this.carStatistics(self);
        this.quickHotStatistics();
        this.updateGoodNum();
    },

    /**
     * 用户手动输入商品数量
     */
    goodNumChange: function (e) {
        var self = this.currentPage;
        var currentGoodNum = parseInt(e.detail.value) ? parseInt(e.detail.value) : 0;
        var goodId = e.target.dataset.id ? parseInt(e.target.dataset.id) : self.data.currentGood.id;
        var carGoods = self.data.carGoods;
        var quickList = self.data.quick_list;
        var quickHotList = self.data.quick_hot_goods_lists;

        var goodNum = currentGoodNum;
        var currentGoodUseAttr = 0;
        var cGood = '';
        for (var j in quickList) {
            for (var j2 in quickList[j].goods) {
                var useAttr = parseInt(quickList[j].goods[j2].use_attr);
                var id = parseInt(quickList[j].goods[j2].id);
                // 单规格修改
                if (id === goodId && useAttr === 0) {
                    var num = parseInt(quickList[j].goods[j2].goods_num)
                    if (num < currentGoodNum) {
                        wx.showToast({
                            title: "商品库存不足",
                            image: "/images/icon-warning.png",
                        });

                        goodNum = num;
                    }
                    quickList[j].goods[j2].num = goodNum;
                    currentGoodUseAttr = useAttr;
                }
                // 多规格修改
                if (id === goodId && useAttr === 1) {
                    var temporaryGood = self.data.temporaryGood;

                    if (temporaryGood.num < currentGoodNum) {
                        wx.showToast({
                            title: "商品库存不足",
                            image: "/images/icon-warning.png",
                        });

                        goodNum = temporaryGood.num;
                    }
                    currentGoodUseAttr = useAttr;
                    cGood = quickList[j].goods[j2];
                    self.setData({
                        check_goods_price: (goodNum * temporaryGood.price).toFixed(2),
                    })
                }
            }
        }

        var goodNumCount = 0;
        for (var i in carGoods) {
            //currentGoodUseAttr 区分是单规格商品还是多规格商品
            var id = parseInt(carGoods[i].goods_id);
            if (id === goodId && currentGoodUseAttr === 0) {
                carGoods[i].num = goodNum;
                carGoods[i].goods_price = (goodNum * carGoods[i].price).toFixed(2);
            }

            if (id === goodId && currentGoodUseAttr === 1) {
                var checkedAttr = self.data.checked_attr;
                var attrs = carGoods[i].attr;

                var arr = [];
                for (var j in attrs) {
                    arr.push([
                        attrs[j].attr_id,
                        goodId
                    ])
                }
                // 这里需要指定某个规格组合的商品
                if (arr.sort().join() === checkedAttr.sort().join()) {
                    carGoods[i].num = goodNum;
                    carGoods[i].goods_price = (goodNum * carGoods[i].price).toFixed(2);
                }
            }

            if (id === goodId) {
                goodNumCount += carGoods[i].num;
            }

        }

        // 多规格显示的总数是 根据不同的规格组合数量 相加
        if (currentGoodUseAttr === 1) {
            cGood.num = goodNumCount;
        }

        for (var n in quickHotList) {
            var id = parseInt(quickHotList[n].id);
            if (id === goodId && currentGoodUseAttr === 0) {
                quickHotList[n].num = goodNum;
            }

            if (id === goodId && currentGoodUseAttr === 1) {
                quickHotList[n].num = goodNumCount;
            }
        }

        self.setData({
            carGoods: carGoods,
            quick_list: quickList,
            quick_hot_goods_lists: quickHotList,
            // goods_num: goodNumCount,//与快速购买页有所不同
        })

        this.carStatistics(self);
    },

    /**
     * 快速购买商品列表的商品数量同步到 热销商品列表
     */
    quickHotStatistics: function () {
        var self = this.currentPage;
        var quickHot = self.data.quick_hot_goods_lists;
        var quickList = self.data.quick_list;
        for (var i in quickHot) {
            for (var j in quickList) {
                for (var j2 in quickList[j].goods) {
                    //根据商品ID进行判断
                    if (parseInt(quickList[j].goods[j2].id) === parseInt(quickHot[i].id)) {
                        quickHot[i].num = quickList[j].goods[j2].num;
                    }
                }
            }
        }
        self.setData({
            quick_hot_goods_lists: quickHot
        })
    },

    /**
     * 更新单规格和多规格商品购买总数
     * 与快速购买页面有所不同，所以单独写个方法，用于从快速购买打开的商品详情页
     */
    updateGoodNum: function () {
        var self = this.currentPage;
        var quickList = self.data.quick_list;
        var currentGood = self.data.goods;
        if (quickList && currentGood) {
            for (var i in quickList) {
                for (var i2 in quickList[i].goods) {
                    if (parseInt(quickList[i].goods[i2].id) === parseInt(currentGood.id)) {
                        var goodNumCount = quickList[i].goods[i2].num;
                        var goods_num = quickList[i].goods[i2].num;

                        self.setData({
                            goods_num: goods_num,
                            goodNumCount: goodNumCount
                        })
                        break;
                    }
                }
            }
        }
    },

    buynow: function (e) {
        var self = this.currentPage;
        var carGoods = self.data.carGoods;
        var goodsModel = self.data.goodsModel;
        self.setData({
            goodsModel: false
        });

        var length = carGoods.length;
        var cart_list = [];
        var cart_list_goods = [];
        for (var a = 0; a < length; a++) {
            if (carGoods[a].num != 0) {
                cart_list_goods = {
                    'goods_id': carGoods[a].goods_id,
                    'num': carGoods[a].num,
                    'attr': carGoods[a].attr
                }
                cart_list.push(cart_list_goods)
            }
        }

        var mch_list = [];
        mch_list.push({
            mch_id: 0,
            goods_list: cart_list
        });
        getApp().core.navigateTo({
            url: '/pages/new-order-submit/new-order-submit?mch_list=' + JSON.stringify(mch_list),
        });

        this.clearShoppingCart();
    },
}