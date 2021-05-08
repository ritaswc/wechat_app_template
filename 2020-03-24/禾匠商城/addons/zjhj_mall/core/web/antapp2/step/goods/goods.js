if (typeof wx === 'undefined') var wx = getApp().core;
var WxParse = require('../../wxParse/wxParse.js');
var shoppingCart = require('../../components/shopping_cart/shopping_cart.js');
var specificationsModel = require('../../components/specifications_model/specifications_model.js'); //快速购买多规格
var gSpecificationsModel = require('../../components/goods/specifications_model.js'); //商城多规格选择
var goodsBanner = require('../../components/goods/goods_banner.js');
var goodsInfo = require('../../components/goods/goods_info.js');
var goodsBuy = require('../../components/goods/goods_buy.js');
var quickNavigation = require('../../components/quick-navigation/quick-navigation.js');
var p = 1;
var is_loading_comment = false;
var is_more_comment = true;
var share_count = 0;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        pageType: 'STEP', //模块页面标识
        id: null,
        goods: {},
        show_attr_picker: false,
        form: {
            number: 1,
        },
        tab_detail: "active",
        tab_comment: "",
        comment_list: [],
        comment_count: {
            score_all: 0,
            score_3: 0,
            score_2: 0,
            score_1: 0,
        },
        autoplay: false,
        hide: "hide",
        show: false,
        x: getApp().core.getSystemInfoSync().windowWidth,
        y: getApp().core.getSystemInfoSync().windowHeight - 20,
        page: 1,
        drop: false,
        goodsModel: false,
        goods_num: 0,
        temporaryGood: {
            price: 0.00, // 对应规格的价格
            num: 0,
            use_attr: 1
        },
        goodNumCount: 0,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);

        var self = this;
        share_count = 0;
        p = 1;
        is_loading_comment = false;
        is_more_comment = true;
        var quick = options.quick;
        if (quick) {
            var item = getApp().core.getStorageSync(getApp().const.ITEM);
            if (item) {
                var total = item.total;
                var carGoods = item.carGoods;
            } else {
                var total = {
                    total_price: 0.00,
                    total_num: 0
                }
                var carGoods = [];
            }
            self.setData({
                quick: quick,
                quick_list: item.quick_list,
                total: total,
                carGoods: carGoods,
                quick_hot_goods_lists: item.quick_hot_goods_lists,
            });
        }
        if (typeof my === 'undefined') {
            var scene = decodeURIComponent(options.scene);
            if (typeof scene !== 'undefined') {
                var scene_obj = getApp().helper.scene_decode(scene);
                if (scene_obj.uid && scene_obj.gid) {
                    options.id = scene_obj.gid;
                }
            }
        } else {
            if (getApp().query !== null) {
                var query = app.query;
                getApp().query = null;
                options.id = query.gid;
            }
        }


        self.setData({
            id: options.goods_id,
            user_id: options.user_id
        });
        self.getGoods();
    },
    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function() {
        getApp().page.onReady(this);
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function() {
        getApp().page.onShow(this);
        shoppingCart.init(this);
        specificationsModel.init(this, shoppingCart);
        gSpecificationsModel.init(this);
        goodsBanner.init(this);
        goodsInfo.init(this);
        goodsBuy.init(this);
        quickNavigation.init(this);

        var self = this;
        var item = getApp().core.getStorageSync(getApp().const.ITEM);
        if (item) {
            var total = item.total;
            var carGoods = item.carGoods;
            var goods_num = self.data.goods_num;
        } else {
            var total = {
                total_price: 0.00,
                total_num: 0
            }
            var carGoods = [];
            var goods_num = 0;
        }
        self.setData({
            total: total,
            carGoods: carGoods,
            goods_num: goods_num
        });
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function() {
        getApp().page.onHide(this);
        shoppingCart.saveItemData(this);
    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function() {
        getApp().page.onUnload(this);
        shoppingCart.saveItemData(this);
    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function() {
        getApp().page.onPullDownRefresh(this);
    },

    /**
     * 页面上拉触底事件的处理函数
     */

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        var self = this;
        var user_info = getApp().getUser(); 
        var res = {
            path: "/step/goods/goods?goods_id=" + this.data.id + "&user_id=" + user_info.id,
            success: function(e) {
                share_count++;
                if (share_count == 1)
                    self.shareSendCoupon(self);
            },
            title: self.data.goods.name,
            imageUrl: self.data.goods.pic_list[0],
        };
        return res;
    },
    play: function(e) {
        var url = e.target.dataset.url; //获取视频链接
        this.setData({
            url: url,
            hide: '',
            show: true,
        });
        var videoContext = getApp().core.createVideoContext('video');
        videoContext.play();
    },

    close: function(e) {
        if (e.target.id == 'video') {
            return true;
        }
        this.setData({
            hide: "hide",
            show: false
        });
        var videoContext = getApp().core.createVideoContext('video');
        videoContext.pause();
    },


    closeCouponBox: function(e) {
        this.setData({
            get_coupon_list: ""
        });
    },

    to_dial: function(e) {
        var contact_tel = this.data.store.contact_tel;
        getApp().core.makePhoneCall({
            phoneNumber: contact_tel
        })
    },

    getGoods: function() {
        var self = this;
        var quick = self.data.quick;
        if (quick) {
            var carGoods = self.data.carGoods;
            if (carGoods) {
                var length = carGoods.length;
                var goods_num = 0;
                for (var i = 0; i < length; i++) {
                    if (carGoods[i].goods_id == self.data.id) {
                        goods_num += parseInt(carGoods[i].num);
                    }
                }
                self.setData({
                    goods_num: goods_num
                });
            }
        }
        getApp().request({
            url: getApp().api.step.goods,
            data: {
                goods_id: self.data.id,
                user_id: self.data.user_id
            },
            success: function(res) {
                if (res.code == 0) {
                    var detail = res.data.goods.detail;
                    WxParse.wxParse("detail", "html", detail, self);
                    var goods = res.data.goods;
                    goods.attr_pic = res.data.goods.attr_pic;
                    goods.cover_pic = res.data.goods.pic_list[0].pic_url;

                    var goodsList = goods.pic_list;
                    var pic_list = [];
                    for (var i in goodsList) {
                        pic_list.push(goodsList[i].pic_url);
                    }
                    goods.pic_list = pic_list;

                    self.setData({
                        goods: goods,
                        attr_group_list: res.data.goods.attr_group_list,
                        btn: true
                    });
                    self.selectDefaultAttr();
                }
                if (res.code == 1) {
                    getApp().core.showModal({
                        title: "提示",
                        content: res.msg,
                        showCancel: false,
                        success: function(res) {
                            if (res.confirm) {
                                getApp().core.switchTab({
                                    url: "/pages/index/index"
                                });
                            }
                        }
                    });
                }
            }
        });
    },
    tabSwitch: function(e) {
        var self = this;
        var tab = e.currentTarget.dataset.tab;
        if (tab == "detail") {
            self.setData({
                tab_detail: "active",
                tab_comment: "",
            });
        } else {
            self.setData({
                tab_detail: "",
                tab_comment: "active",
            });
        }
    },
    commentPicView: function(e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var pic_index = e.currentTarget.dataset.picIndex;
        getApp().core.previewImage({
            current: self.data.comment_list[index].pic_list[pic_index],
            urls: self.data.comment_list[index].pic_list,
        });
    },
    exchangeGoods: function() {
        var self = this;
        if (!self.data.show_attr_picker) {
            self.setData({
                show_attr_picker: true,
            });
            return true;
        }
        var attr_group_list = self.data.attr_group_list;
        var checked_attr_list = [];
        for (var i in attr_group_list) {
            var attr = false;
            for (var j in attr_group_list[i].attr_list) {

                if (attr_group_list[i].attr_list[j].checked) {
                    attr = ({
                        attr_id: attr_group_list[i].attr_list[j].attr_id,
                        attr_name: attr_group_list[i].attr_list[j].attr_name,
                    });
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
                    attr_group_name: attr_group_list[i].attr_group_name,
                    attr_id: attr.attr_id,
                    attr_name: attr.attr_name,
                });
            }
        }
        var attr_num = self.data.form.number;
        var goods = self.data.goods;
        if (attr_num <= 0 || attr_num > goods.num) {
            getApp().core.showToast({
                title: "商品库存不足!",
                image: "/images/icon-warning.png",
            });
            return true;
        }

        self.setData({
            show_attr_picker: false,
        });
        getApp().core.navigateTo({
            url: "/pages/order-submit/order-submit?step_id=" + goods.id + "&goods_info=" + JSON.stringify({
                goods_id: goods.id,
                attr: checked_attr_list,
                num: attr_num
            }),
        });
    },
});