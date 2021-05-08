if (typeof wx === 'undefined') var wx = getApp().core;
module.exports = {
    currentPage: null,
    shoppingCart: null,

    init: function (self, shoppingCart) {
        var _this = this;
        _this.currentPage = self;
        _this.shoppingCart = shoppingCart;

        if (typeof self.showDialogBtn === 'undefined') {
            self.showDialogBtn = function (e) {
                _this.showDialogBtn(e);
            }
        }
        if (typeof self.attrClick === 'undefined') {
            self.attrClick = function (e) {
                _this.attrClick(e);
            }
        }
        if (typeof self.onConfirm === 'undefined') {
            self.onConfirm = function (e) {
                _this.onConfirm(e);
            }
        }
        if (typeof self.guigejian === 'undefined') {
            self.guigejian = function (e) {
                _this.guigejian(e);
            }
        }
        if (typeof self.close_box === 'undefined') {
            self.close_box = function (e) {
                _this.close_box(e);
            }
        }
        if (typeof self.hideModal === 'undefined') {
            self.hideModal = function (e) {
                _this.hideModal(e);
            }
        }
    },

    /**
     * 多规格弹框
     */
    showDialogBtn: function (e) {
        var self = this.currentPage;
        var _this = this;
        var current_good = e.currentTarget.dataset;
        getApp().request({
            url: getApp().api.default.goods,
            data: {
                id: current_good.id
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        currentGood: res.data,
                        goods_name: res.data.name,
                        attr_group_list: res.data.attr_group_list,
                        showModal: true,
                    });

                    _this.resetData();
                    _this.updateData();
                    _this.checkAttrNum();
                }
            }
        });
    },

    /**
     * 重置临时存储数据，用于多规格弹框数据回显
     */
    resetData: function () {
        var self = this.currentPage;
        self.setData({
            checked_attr: [],
            check_num: 0,
            check_goods_price: 0,
            temporaryGood: {
                price: '0.00',
                num: 0
            }
        })
    },

    // 多规格弹框数据回显，如有多条则回显第一条数据
    updateData: function () {
        var self = this.currentPage;
        var currentGood = self.data.currentGood; //当前商品信息
        var carGoods = self.data.carGoods; //购物车数据
        var attr = JSON.parse(currentGood.attr); //商品规格信息
        var attrGroups = currentGood.attr_group_list;

        for (var i in attr) {
            var arr = []
            for (var i2 in attr[i].attr_list) {
                arr.push([
                    attr[i].attr_list[i2].attr_id,
                    currentGood.id
                ]);
            }

            for (var j in carGoods) {
                var arr2 = [];
                for (var j2 in carGoods[j].attr) {
                    arr2.push([
                        carGoods[j].attr[j2].attr_id,
                        carGoods[j].goods_id
                    ]);
                }
                if (arr.sort().join() === arr2.sort().join()) {
                    for (var k in attrGroups) {
                        for (var k2 in attrGroups[k].attr_list) {
                            for (var n in arr) {
                                if (parseInt(attrGroups[k].attr_list[k2].attr_id) === parseInt(arr[n])) {
                                    attrGroups[k].attr_list[k2].checked = true
                                    break;
                                } else {
                                    attrGroups[k].attr_list[k2].checked = false
                                }
                            }
                        }
                    }

                    var temporaryGood = {
                        price: carGoods[j].price,
                    }

                    self.setData({
                        attr_group_list: attrGroups,
                        check_num: carGoods[j].num,
                        check_goods_price: carGoods[j].goods_price,
                        checked_attr: arr,
                        temporaryGood: temporaryGood
                    })
                    return
                }
            }
        }
    },

    /**
     * 多规格规格切换时 数据回显
     */
    checkUpdateData: function (checked_attr) {
        var self = this.currentPage;
        var carGoods = self.data.carGoods; //购物车数据

        for (var j in carGoods) {
            var arr = [];
            for (var j2 in carGoods[j].attr) {
                arr.push([
                    carGoods[j].attr[j2].attr_id,
                    carGoods[j].goods_id
                ]);
            }
            // 根据商品ID 和规格ID进行判断
            if (arr.sort().join() === checked_attr.sort().join()) {
                self.setData({
                    check_num: carGoods[j].num,
                    check_goods_price: carGoods[j].goods_price
                })
            }
        }
    },

    /**
     * 选择规格
     */
    attrClick: function (e) {
        var self = this.currentPage;
        var _this = this;
        var attr_group_id = parseInt(e.target.dataset.groupId);
        var attr_id = parseInt(e.target.dataset.id);
        var attr_group_list = self.data.attr_group_list;
        var currentGood = self.data.currentGood
        var attrs = JSON.parse(currentGood.attr);
        var checkedAttr = [];

        // 添加选择按钮样式
        for (var i in attr_group_list) {
            if (attr_group_list[i].attr_group_id != attr_group_id) {
                continue;
            }
            for (var j in attr_group_list[i].attr_list) {
                var cAttr = attr_group_list[i].attr_list[j];
                if (cAttr.attr_id == attr_id && cAttr.checked !== true) {
                    cAttr.checked = true;
                } else {
                    cAttr.checked = false;
                }
            }
        }

        //获取被选中的规格
        var checked_attr = [];
        for (var i in attr_group_list) {
            for (var j in attr_group_list[i].attr_list) {
                var cAttr = attr_group_list[i].attr_list[j];
                if (cAttr.checked === true) {
                    checked_attr.push([
                        cAttr.attr_id,
                        currentGood.id
                    ])
                    checkedAttr.push(cAttr.attr_id)
                }
            }
        }

        var attr = JSON.parse(currentGood.attr);
        var temporaryGood = self.data.temporaryGood;
        for (var k in attr) {
            var arr = [];
            for (var k2 in attr[k].attr_list) {
                arr.push([
                    attr[k].attr_list[k2].attr_id,
                    currentGood.id
                ])
            }

            // 根据当前选择的规格，获取对应规格商品价格
            if (arr.sort().join() === checked_attr.sort().join()) {

                // 判断商品库存为0 则不继续执行
                if (parseInt(attr[k].num) === 0) {
                    return;
                }

                // 如果规格价格为0 则用商品自身价格
                if (!parseFloat(attr[k].price)) {
                    temporaryGood = {
                        price: (parseFloat(currentGood.price)).toFixed(2),
                        num: attr[k].num
                    }
                } else {
                    temporaryGood = {
                        price: (parseFloat(attr[k].price)).toFixed(2),
                        num: attr[k].num
                    }
                }
            }
        }

        // 无库存规格样式 start
        var attrNum_0 = [];
        console.log(checkedAttr)
        for (var i in attrs) {
            var arr = [];
            var sign = 0;
            for (var j in attrs[i].attr_list) {
                if (!getApp().helper.inArray(attrs[i].attr_list[j].attr_id, checkedAttr)) {
                    sign += 1;
                }

                arr.push(attrs[i].attr_list[j].attr_id);
            }

            if (attrs[i].num === 0 && sign <= 1) {
                attrNum_0.push(arr)
            }
        }


        var checkedAttrLength = checkedAttr.length;
        var attrGroupListLength = attr_group_list.length;

        var newAttrNum_0 = [];
        if (attrGroupListLength - checkedAttrLength <= 1) {
            for (var i in checkedAttr) {
                for (var j in attrNum_0) {
                    if (getApp().helper.inArray(checkedAttr[i], attrNum_0[j])) {
                        for (var k in attrNum_0[j]) {
                            if (attrNum_0[j][k] !== checkedAttr[i]) {
                                newAttrNum_0.push(attrNum_0[j][k])
                            }
                        }
                    }
                }
            }
        }

        //库存为0的规格添加标识
        console.log(newAttrNum_0);
        console.log(checkedAttr)
        for (var i in attr_group_list) {
            for (var j in attr_group_list[i].attr_list) {
                var cAttr = attr_group_list[i].attr_list[j];
                if (getApp().helper.inArray(cAttr.attr_id, newAttrNum_0) && !getApp().helper.inArray(cAttr.attr_id, checkedAttr)) {
                    cAttr.is_attr_num = true;
                } else {
                    cAttr.is_attr_num = false;
                }
            }
        }


        _this.resetData()
        _this.checkUpdateData(checked_attr)
        self.setData({
            attr_group_list: attr_group_list,
            temporaryGood: temporaryGood,
            checked_attr: checked_attr,
        })
        // _this.checkAttrNum();
    },

    /**
     * 多规格库存检测，库存为0的按钮无法点击
     */
    checkAttrNum: function () {
        var self = this.currentPage;
        var attrGroupList = self.data.attr_group_list
        var attrGroups = JSON.parse(self.data.currentGood.attr);
        var checkedAttr = self.data.checked_attr;

        var newCheckedAttr = [];
        for (var i in checkedAttr) {
            newCheckedAttr.push(checkedAttr[i][0]);
        }

        // var newAttrArr = [];
        // for (var n in checkedAttr) {
        //     for (var i in attrGroups) {
        //         var attrArr = [];
        //         var sign = 0;
        //         for (var i2 in attrGroups[i].attr_list) {
        //             attrArr.push(attrGroups[i].attr_list[i2].attr_id)
        //         }
        //         var inArray = getApp().helper.inArray(checkedAttr[n][0], attrArr)
        //         if (inArray && attrGroups[i].num === 0) {
        //             for (var j in attrArr) {
        //                 if (attrArr[j] !== checkedAttr[n][0]) {
        //                     newAttrArr.push(attrArr[j]);
        //                 }
        //             }
        //         }
        //     }
        // }

        for (var i in attrGroups) {
            var attrArr = [];
            var sign = 0;
            for (var i2 in attrGroups[i].attr_list) {
                var attrId = attrGroups[i].attr_list[i2].attr_id
                if (getApp().helper.inArray(attrId, newCheckedAttr)) {
                    sign += 1;
                } else {
                    attrArr.push(attrId);
                }
            }

            if (attrGroupList.length - sign == 1 && attrGroups[i].num == 0) {
                for (var w in attrGroupList) {
                    for (var w2 in attrGroupList[w].attr_list) {
                        var attr = attrGroupList[w].attr_list[w2];
                        var inArray = getApp().helper.inArray(attr.attr_id, attrArr)
                        if (inArray) {
                            attr.is_attr_num = true
                        }
                    }
                }
            }
        }

        // 检测多规格是否还有库存，并修改按钮样式
        // for (var w in attrGroupList) {
        //     for (var w2 in attrGroupList[w].attr_list) {
        //         var attr = attrGroupList[w].attr_list[w2];
        //         attr.is_attr_num = false;
        //         var inArray = getApp().helper.inArray(attr.attr_id, newAttrArr)
        //         if (inArray) {
        //             attr.is_attr_num = true
        //         }
        //     }
        // }

        self.setData({
            attr_group_list: attrGroupList
        })
    },

    /**
     * 规格加购物车
     */
    onConfirm: function (e) {
        var self = this.currentPage;
        var attrGroupList = self.data.attr_group_list;
        var checked_attr = self.data.checked_attr;
        var current_good = self.data.currentGood;
        if (checked_attr.length !== attrGroupList.length) {
            wx.showToast({
                title: "请选择规格",
                image: "/images/icon-warning.png",
            });
            return
        }

        var check_num = self.data.check_num ? self.data.check_num + 1 : 1;
        var attrs = JSON.parse(current_good.attr);
        for (var i in attrs) {
            var arr = [];
            for (var i2 in attrs[i].attr_list) {
                arr.push([
                    attrs[i].attr_list[i2].attr_id,
                    current_good.id
                ])

                if (arr.sort().join() === checked_attr.sort().join()) {
                    // 当前规格商品价格，没有则拿当前商品价格
                    var gPrice = attrs[i].price ? attrs[i].price : current_good.price;
                    var attr = attrs[i].attr_list;
                    if (check_num > attrs[i].num) {
                        wx.showToast({
                            title: "商品库存不足",
                            image: "/images/icon-warning.png",
                        });
                        return
                    }
                }
            }
        }
        //购物车商品基本数据
        var carGoods = self.data.carGoods;
        var sign = 1;
        var goodsPrice = (parseFloat(gPrice * check_num)).toFixed(2)
        for (var j in carGoods) {
            var cArr = [];
            for (var j2 in carGoods[j].attr) {
                cArr.push([
                    carGoods[j].attr[j2].attr_id,
                    carGoods[j].goods_id
                ]);
            }
            // 如果商品已存在则不需要重复添加
            if (cArr.sort().join() === checked_attr.sort().join()) {
                sign = 0;
                carGoods[j].num = carGoods[j].num + 1;
                carGoods[j].goods_price = (parseFloat(gPrice * carGoods[j].num)).toFixed(2);
                break;
            }
        }
        // 新商品则添加记录
        if (sign === 1 || carGoods.length === 0) {
            carGoods.push({
                'goods_id': current_good.id,
                'attr': attr,
                'goods_name': current_good.name,
                'goods_price': gPrice,
                'num': 1,
                'price': gPrice,
            })
        }

        self.setData({
            carGoods: carGoods,
            check_goods_price: goodsPrice,
            check_num: check_num,
        })

        this.shoppingCart.carStatistics(self);
        this.attrGoodStatistics();
        this.shoppingCart.updateGoodNum();
    },

    /**
     * 多规格减
     */
    guigejian: function (e) {
        var self = this.currentPage;
        var checked_attr = self.data.checked_attr;
        var carGoods = self.data.carGoods;
        var check_num = self.data.check_num ? --self.data.check_num : 1;
        var current_good = self.data.currentGood;

        for (var i in carGoods) {
            var arr = [];
            for (var i2 in carGoods[i].attr) {
                arr.push([
                    carGoods[i].attr[i2].attr_id,
                    carGoods[i].goods_id
                ]);
            }

            if (arr.sort().join() === checked_attr.sort().join()) {
                if (carGoods[i].num > 0) {
                    carGoods[i].num -= 1;
                    carGoods[i].goods_price = (parseFloat(carGoods[i].num * carGoods[i].price)).toFixed(2);
                }
                self.setData({
                    carGoods: carGoods,
                    check_goods_price: carGoods[i].goods_price,
                    check_num: check_num,
                })

                this.shoppingCart.carStatistics(self);
                this.attrGoodStatistics();
                this.shoppingCart.updateGoodNum();
                return
            }
        }
    },

    /**
     * 多规格商品总数统计
     */
    attrGoodStatistics: function () {
        var self = this.currentPage;
        var currentGood = self.data.currentGood; //当前点击的商品
        var carGoods = self.data.carGoods;
        var quickList = self.data.quick_list;
        var quickHot = self.data.quick_hot_goods_lists;

        var num = 0;
        for (var i in carGoods) {
            if (carGoods[i].goods_id === currentGood.id) {
                num += carGoods[i].num;
            }
        }
        // 普通商品总数统计
        for (var i in quickList) {
            for (var i2 in quickList[i].goods) {
                if (parseInt(quickList[i].goods[i2].id) === currentGood.id) {
                    quickList[i].goods[i2].num = num
                }
            }
        }

        // 热销商品总数统计
        for (var i in quickHot) {
            if (parseInt(quickHot[i].id) === currentGood.id) {
                quickHot[i].num = num
            }
        }
        self.setData({
            quick_list: quickList,
            quick_hot_goods_lists: quickHot
        })
    },

    close_box: function (e) {
        var self = this.currentPage;
        self.setData({
            showModal: false,
        });
    },
    hideModal: function () {
        var self = this.currentPage;
        self.setData({
            showModal: false
        });
    },
}