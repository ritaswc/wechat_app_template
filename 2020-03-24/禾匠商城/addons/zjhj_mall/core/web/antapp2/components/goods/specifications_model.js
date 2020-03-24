if (typeof wx === 'undefined') var wx = getApp().core;
module.exports = {
    currentPage: null,
    /**
     * 注意！注意！！注意！！！
     * 由于组件的通用，部分变量名称需统一，在各自引用的xxx.js文件需定义，并给对应变量赋相应的值
     * 以下变量必须定义并赋值
     * 
     * pageType     标识从哪个模块引用的
     * goods         商品信息
     * goods.attr_pic    规格相应图片
     * goods.cover_pic   没有规格图片展示商品默认图片
     * goods.price      商品价格
     * goods.num        商品库存
     * attr_group_list  商品规格、包含规格组、规格
     * 持续补充...
     */
    init: function(self) {
        var _this = this;
        _this.currentPage = self;

        if (typeof self.previewImage === 'undefined') {
            self.previewImage = function(e) {
                _this.previewImage(e);
            }
        }
        if (typeof self.showAttrPicker === 'undefined') {
            self.showAttrPicker = function(e) {
                _this.showAttrPicker(e);
            }
        }
        if (typeof self.hideAttrPicker === 'undefined') {
            self.hideAttrPicker = function(e) {
                _this.hideAttrPicker(e);
            }
        }
        if (typeof self.storeAttrClick === 'undefined') {
            self.storeAttrClick = function(e) {
                _this.storeAttrClick(e);
            }
        }
        if (typeof self.numberAdd === 'undefined') {
            self.numberAdd = function(e) {
                _this.numberAdd(e);
            }
        }
        if (typeof self.numberSub === 'undefined') {
            self.numberSub = function(e) {
                _this.numberSub(e);
            }
        }
        if (typeof self.numberBlur === 'undefined') {
            self.numberBlur = function(e) {
                _this.numberBlur(e);
            }
        }
        if (typeof self.selectDefaultAttr === 'undefined') {
            self.selectDefaultAttr = function (e) {
                _this.selectDefaultAttr(e);
            }
        }
    },

    previewImage: function(e) {
        // TODO 商城的路径不是这个
        var urls = e.currentTarget.dataset.url;
        getApp().core.previewImage({
            urls: [urls]
        });
    },

    /**
     * 隐藏规格选择框
     */
    hideAttrPicker: function() {
        var self = this.currentPage;
        self.setData({
            show_attr_picker: false,
        });
    },
    /**
     * 显示规格选择框
     */
    showAttrPicker: function() {
        var self = this.currentPage;
        self.setData({
            show_attr_picker: true,
        });
    },

    /**
     * 选择规格
     */
    storeAttrClick: function(e) {
        var self = this.currentPage;
        var _this = this;
        var attr_group_id = e.target.dataset.groupId;
        var attr_id = parseInt(e.target.dataset.id);
        var attr_group_list = JSON.parse(JSON.stringify(self.data.attr_group_list));
        var attrs = self.data.goods.attr;
        var checkedAttr = [];
        if (typeof attrs == 'string') {
            attrs = JSON.parse(attrs);
        }

        for (var i in attr_group_list) {
            if (attr_group_list[i].attr_group_id != attr_group_id) {
                continue;
            }

            // TODO 重新写个for循环是为了解决bug,如有更好方案再修改
            for (var j in attr_group_list[i].attr_list) {
                var aGList = attr_group_list[i].attr_list[j];

                if (parseInt(aGList.attr_id) === attr_id && aGList.checked) {
                    aGList.checked = false;
                } else {
                    aGList.checked = parseInt(aGList.attr_id) === attr_id;
                }
            }
        }


        for (var i in attr_group_list) {
            for (var j in attr_group_list[i].attr_list) {
                if (attr_group_list[i].attr_list[j].checked) {
                    checkedAttr.push(attr_group_list[i].attr_list[j].attr_id)
                }
            }
        }
        for (var i in attr_group_list) {
            // 如果库存为0 则不往下执行
            for (var j in attr_group_list[i].attr_list) {
                var aGList = attr_group_list[i].attr_list[j];
                if (aGList.attr_id === attr_id && aGList.attr_num_0 === true) {
                    return;
                }
            }
        }

        // 无库存规格样式 start
        var attrNum_0 = [];
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
        for (var i in attr_group_list) {
            for (var j in attr_group_list[i].attr_list) {
                var cAttr = attr_group_list[i].attr_list[j];
                if (getApp().helper.inArray(cAttr.attr_id, newAttrNum_0) && !getApp().helper.inArray(cAttr.attr_id, checkedAttr)) {
                    cAttr.attr_num_0 = true;
                } else {
                    cAttr.attr_num_0 = false;
                }
            }
        }
        self.setData({
            attr_group_list: attr_group_list,
        });


        var check_attr_list = [];
        var check_all = true;
        for (var i in attr_group_list) {
            var group_checked = false;
            for (var j in attr_group_list[i].attr_list) {
                if (attr_group_list[i].attr_list[j].checked) {
                    //积分商城
                    if (self.data.pageType === 'INTEGRAL') {
                        var attrs = {
                            'attr_id': attr_group_list[i].attr_list[j].attr_id,
                            'attr_name': attr_group_list[i].attr_list[j].attr_name
                        }
                        check_attr_list.push(attrs);

                    } else {
                        check_attr_list.push(attr_group_list[i].attr_list[j].attr_id);
                        group_checked = true;
                        break;

                    }
                }
            }
            // TODO ..
            if (self.data.pageType !== 'INTEGRAL' && !group_checked) {
                check_all = false;
                break;
            }
        }

        // TODO ..
        if (self.data.pageType !== 'INTEGRAL' && !check_all) {
            return;
        }

        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });

        //不同模块页面请求接口不同
        var pageType = self.data.pageType;
        var group_checked = 0;
        if (pageType === 'STORE') {
            var httpUrl = getApp().api.default.goods_attr_info;

        } else if (pageType === 'PINTUAN') {
            var httpUrl = getApp().api.group.goods_attr_info;
            group_checked = self.data.group_checked

        } else if (pageType === 'INTEGRAL') {
            getApp().core.hideLoading();
            _this.integralMallAttrClick(check_attr_list);
            return;

        } else if (pageType === 'BOOK') {
            // getApp().core.hideLoading();
            var httpUrl = getApp().api.book.goods_attr_info;
            // _this.bookAttrGoodsClick(check_attr_list);
            // return;
        } else if (pageType === 'STEP') {
          var httpUrl = getApp().api.default.goods_attr_info;          
        } else if (pageType === 'MIAOSHA') {
            var httpUrl = getApp().api.default.goods_attr_info;
        } else {
            getApp().core.showModal({
                title: '提示',
                content: 'pageType变量未定义或变量值不是预期的',
            });
            getApp().core.hideLoading();
            return;

        }

        getApp().request({
            url: httpUrl,
            data: {
                goods_id: pageType === 'MIAOSHA' ? self.data.id : self.data.goods.id,
                group_id: self.data.group_checked, // TODO 商城没有该字段
                attr_list: JSON.stringify(check_attr_list),
                type: pageType === 'MIAOSHA' ? 'ms' : '',
                group_checked: group_checked, // 区分拼团和阶梯团，用于计算佣金
            },
            success: function(res) {
                getApp().core.hideLoading();
                if (res.code == 0) {
                    var goods = self.data.goods;
                    goods.price = res.data.price;
                    goods.num = res.data.num;
                    goods.attr_pic = res.data.pic;
                    goods.is_member_price = res.data.is_member_price;
                    goods.single_price = res.data.single_price ? res.data.single_price : 0; // 拼团使用
                    goods.group_price = res.data.price
                    
                    if (pageType === 'MIAOSHA') {
                        var miaosha = res.data.miaosha;
                        goods.price = miaosha.price;
                        goods.num = miaosha.miaosha_num;
                        goods.is_member_price = miaosha.is_member_price;
                        goods.attr_pic = miaosha.pic;
                        self.setData({
                            miaosha_data: miaosha,
                        });
                    }

                    if (pageType === 'BOOK') {
                        goods.price = goods.price > 0 ? goods.price : '免费预约';
                    }

                    self.setData({
                        goods: goods,
                    });

                }
            }
        });
    },

    attrClick: function(e) {
        var self = this;
        var attr_group_id = e.target.dataset.groupId;
        var attr_id = e.target.dataset.id;
        var attr_group_list = self.data.attr_group_list;
        for (var i in attr_group_list) {
            if (attr_group_list[i].attr_group_id != attr_group_id)
                continue;
            for (var j in attr_group_list[i].attr_list) {
                if (attr_group_list[i].attr_list[j].attr_id == attr_id) {
                    attr_group_list[i].attr_list[j].checked = true;
                } else {
                    attr_group_list[i].attr_list[j].checked = false;
                }
            }
        }
        self.setData({
            attr_group_list: attr_group_list,
        });

        var check_attr_list = [];
        var check_all = true;
        for (var i in attr_group_list) {
            var group_checked = false;
            for (var j in attr_group_list[i].attr_list) {
                if (attr_group_list[i].attr_list[j].checked) {
                    check_attr_list.push(attr_group_list[i].attr_list[j].attr_id);
                    group_checked = true;
                    break;
                }
            }
            if (!group_checked) {
                check_all = false;
                break;
            }
        }
        if (!check_all)
            return;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.default.goods_attr_info,
            data: {
                goods_id: self.data.id,
                attr_list: JSON.stringify(check_attr_list),
                type: 'ms'
            },
            success: function(res) {
                getApp().core.hideLoading();
                if (res.code == 0) {
                    var goods = self.data.goods;
                    goods.price = res.data.price;
                    goods.num = res.data.num;
                    goods.attr_pic = res.data.pic;
                    self.setData({
                        goods: goods,
                        miaosha_data: res.data.miaosha,
                    });
                }
            }
        });

    },

    /**
     * TODO 积分商城规格选择,需要合并优化
     */
    integralMallAttrClick: function(checkAttrList) {
        var self = this.currentPage;
        var goods = self.data.goods;
        var inattr = goods.attr;
        var inattr_id = [];
        var price = 0;
        var integral = 0;
        for (var x in inattr) {
            if (JSON.stringify(inattr[x].attr_list) == JSON.stringify(checkAttrList)) {
                if (parseFloat(inattr[x].price) > 0) {
                    price = inattr[x].price;
                } else {
                    price = goods.price;
                }
                if (parseInt(inattr[x].integral) > 0) {
                    integral = inattr[x].integral
                } else {
                    integral = goods.integral
                }
                goods.attr_pic = inattr[x].pic;
                goods.num = inattr[x].num;
                self.setData({
                    attr_integral: integral,
                    attr_num: inattr[x].num,
                    attr_price: price,
                    status: 'attr',
                    goods: goods
                });
            }
        }
    },

    /**
     * 商品数量减少
     */
    numberSub: function() {
        var self = this.currentPage;
        var num = self.data.form.number;
        if (num <= 1)
            return true;
        num--;
        self.setData({
            form: {
                number: num,
            }
        });
    },

    /**
     * 商品数量添加
     */
    numberAdd: function() {
        var self = this.currentPage;
        var num = self.data.form.number;
        var pageType = self.data.pageType;
        num++;
        // TODO 商城商品详情没有以下判断
        if (num > self.data.goods.one_buy_limit && self.data.goods.one_buy_limit != 0) {
            getApp().core.showModal({
                title: '提示',
                content: '数量超过最大限购数',
                showCancel: false,
                success: function(res) {}
            })
            return;
        }

        if (pageType === 'MIAOSHA') {
            if (num > self.data.goods.miaosha.buy_max && self.data.goods.miaosha.buy_max != 0) {
                getApp().core.showToast({
                    title: "一单限购" + self.data.goods.miaosha.buy_max,
                    image: "/images/icon-warning.png",
                });
                return true;
            }
        }

        self.setData({
            form: {
                number: num,
            }
        });
    },

    /**
     * 手动输入商品数量
     */
    numberBlur: function(e) {
        var self = this.currentPage;
        var num = e.detail.value;
        var pageType = self.data.pageType;
        num = parseInt(num);
        if (isNaN(num)) {
            num = 1;
        }
        if (num <= 0) {
            num = 1;
        }
        // TODO 商城商品详情没有以下判断   
        if (num > self.data.goods.one_buy_limit && self.data.goods.one_buy_limit != 0) {
            getApp().core.showModal({
                title: '提示',
                content: '数量超过最大限购数',
                showCancel: false,
                success: function(res) {}
            });
            num = self.data.goods.one_buy_limit;
        }

        if (pageType === 'MIAOSHA') {
            if (num > self.data.goods.miaosha.buy_max && self.data.goods.miaosha.buy_max != 0) {
                getApp().core.showToast({
                    title: "一单限购" + self.data.goods.miaosha.buy_max,
                    image: "/images/icon-warning.png",
                });
                return true;
            }
        }

        self.setData({
            form: {
                number: num,
            }
        });
    },

    /**
     * 无规格、默认选中
     */
    selectDefaultAttr: function () {
        var self = this.currentPage;
        if (!self.data.goods || self.data.goods.use_attr != 0)
            return;
        for (var i in self.data.attr_group_list) {
            for (var j in self.data.attr_group_list[i].attr_list) {
                if (i == 0 && j == 0)
                    self.data.attr_group_list[i].attr_list[j]['checked'] = true;
            }
        }
        self.setData({
            attr_group_list: self.data.attr_group_list,
        });
    },
}