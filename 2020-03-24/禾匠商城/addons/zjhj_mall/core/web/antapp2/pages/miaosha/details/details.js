if (typeof wx === 'undefined') var wx = getApp().core;
var utils = require('../../../utils/helper.js');
var WxParse = require('../../../wxParse/wxParse.js');
var goodsBanner = require('../../../components/goods/goods_banner.js');
var gSpecificationsModel = require('../../../components/goods/specifications_model.js');//商城多规格选择
var goodsInfo = require('../../../components/goods/goods_info.js');
var goodsBuy = require('../../../components/goods/goods_buy.js');
var p = 1;
var is_loading_comment = false;
var is_more_comment = true;
var share_count = 0;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        pageType: "MIAOSHA",
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
        miaosha_end_time_over: {
            h: "--",
            m: "--",
            s: "--",
            type: 0
        },
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        share_count = 0;
        p = 1;
        is_loading_comment = false;
        is_more_comment = true;
        var parent_id = 0;
        var user_id = options.user_id;
        var scene = decodeURIComponent(options.scene);
        var scene_type = 0;
        if (typeof user_id !== 'undefined') {
            parent_id = user_id;
        } else {
            if (typeof my === 'undefined') {
                if (typeof options.scene !== 'undefined') {
                    scene_type = 1;
                    var scene = decodeURIComponent(options.scene);
                    var scene_obj = utils.scene_decode(scene);
                    if (scene_obj.uid && scene_obj.gid) {
                        parent_id = scene_obj.uid;
                        options.id = scene_obj.gid;
                    } else {
                        parent_id = scene;
                    }
                }
            } else {
                if (getApp().query !== null) {
                    scene_type = 1;
                    var query = getApp().query;
                    getApp().query = null;
                    options.id = query.gid;
                }
            }
        }
        var self = this;
        self.setData({
            id: options.id,
            scene_type: scene_type,
        });
        self.getGoods();
        self.getCommentList();
    },
    getGoods: function() {
        var self = this;
        var data = {};
        if(self.data.id){
            data.id = self.data.id;
        }
        if(self.data.goods_id){
            data.goods_id = self.datat.goods_id
        }
        data.scene_type = self.data.scene_type;
        getApp().request({
            url: getApp().api.miaosha.details,
            data: data,
            success: function(res) {
                if (res.code == 0) {
                    var detail = res.data.detail;
                    WxParse.wxParse("detail", "html", detail, self);

                    var goods = res.data;
                    var miaoshaGoods = res.data.miaosha;
                    var pic_list = [];
                    for (var i in goods.pic_list) {
                        pic_list.push(goods.pic_list[i].pic_url);
                    }
                    goods.pic_list = pic_list;
                    goods.min_price = miaoshaGoods.new_small_price;
                    goods.sales_volume = miaoshaGoods.sell_num;
                    self.setData({
                        goods: goods,
                        attr_group_list: res.data.attr_group_list,
                        miaosha_data: res.data.miaosha.miaosha_data
                    });
                    if (self.data.scene_type == 1) {
                        self.setData({
                            id: res.data.miaosha.miaosha_goods_id
                        });
                    }

                    if (self.data.goods.miaosha)
                        self.setMiaoshaTimeOver();
                    self.selectDefaultAttr();
                }
                if (res.code == 1) {
                    getApp().core.showModal({
                        title: "提示",
                        content: res.msg,
                        showCancel: false,
                        success: function(res) {
                            if (res.confirm) {
                                getApp().core.redirectTo({
                                    url: "/pages/index/index"
                                });
                            }
                        }
                    });
                }
            }
        });
    },
    selectDefaultAttr: function() {
        var self = this;
        if (!self.data.goods || self.data.goods.use_attr !== 0)
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
    getCommentList: function(more) {
        var self = this;
        if (more && self.data.tab_comment != "active")
            return;
        if (is_loading_comment)
            return;
        if (!is_more_comment)
            return;
        is_loading_comment = true;
        getApp().request({
            url: getApp().api.miaosha.comment_list,
            data: {
                goods_id: self.data.id,
                page: p,
            },
            success: function(res) {
                if (res.code != 0)
                    return;
                is_loading_comment = false;
                p++;
                self.setData({
                    comment_count: res.data.comment_count,
                    comment_list: more ? self.data.comment_list.concat(res.data.list) : res.data.list,
                });
                if (res.data.list.length == 0)
                    is_more_comment = false;
            }
        });
    },
    
    addCart: function() {
        this.submit('ADD_CART');
    },

    buyNow: function() {
        if (!this.data.goods.miaosha) {
            getApp().core.showModal({
                title: "提示",
                content: '秒杀商品当前时间暂无活动',
                showCancel: false,
                success: function(res) {}
            });
            return;
        }
        this.submit('BUY_NOW');
    },

    submit: function(type) {
        var self = this;
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

        if (this.data.goods.miaosha['begin_time'] * 1000 > Date.parse(new Date())) {
            getApp().core.showToast({
                title: "活动未开始",
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
                    goods_id: self.data.id,
                    attr: JSON.stringify(checked_attr_list),
                    num: self.data.form.number,
                },
                success: function(res) {
                    getApp().core.showToast({
                        title: res.msg,
                        duration: 1500
                    });
                    getApp().core.hideLoading();
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
            getApp().core.redirectTo({
                url: "/pages/miaosha/order-submit/order-submit?goods_info=" + JSON.stringify({
                    goods_id: self.data.id,
                    attr: checked_attr_list,
                    num: self.data.form.number,
                }),
            });
        }

    },

    favoriteAdd: function() {
        var self = this;
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

    favoriteRemove: function() {
        var self = this;
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
        goodsBanner.init(this);
        gSpecificationsModel.init(this);
        goodsInfo.init(this);
        goodsBuy.init(this);
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function(options) {
        getApp().page.onHide(this);

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function(options) {
        getApp().page.onUnload(this);

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
        var self = this;
        self.getCommentList(true);
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function(options) {
        getApp().page.onShareAppMessage(this);
        var self = this;
        var user_info = getApp().getUser();
        var res = {
            path: "/pages/miaosha/details/details?id=" + this.data.id + "&user_id=" + user_info.id,
            success: function(e) {
                share_count++;
                if (share_count == 1)
                    getApp().shareSendCoupon(self);
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

    hide: function(e) {
        if (e.detail.current == 0) {
            this.setData({
                img_hide: ""
            });
        } else {
            this.setData({
                img_hide: "hide"
            });
        }
    },

    closeCouponBox: function(e) {
        this.setData({
            get_coupon_list: ""
        });
    },

    setMiaoshaTimeOver: function() {
        var self = this;

        function _init() {
            var time_over = self.data.goods.miaosha.end_time - self.data.goods.miaosha.now_time;
            time_over = time_over < 0 ? 0 : time_over;
            self.data.goods.miaosha.now_time++;
            self.setData({
                goods: self.data.goods,
                miaosha_end_time_over: secondToTime(time_over),
            });
        }

        _init();
        setInterval(function() {
            _init();
        }, 1000);

        function secondToTime(second) {
            var _h = parseInt(second / 3600);
            var _m = parseInt((second % 3600) / 60);
            var _s = second % 60;
            var type = 0;
            if (_h >= 1) {
                _h -= 1,
                    type = 1
            }
            return {
                h: _h < 10 ? ("0" + _h) : ("" + _h),
                m: _m < 10 ? ("0" + _m) : ("" + _m),
                s: _s < 10 ? ("0" + _s) : ("" + _s),
                type: type
            };
        }
    },
    to_dial: function(e) {
        var contact_tel = this.data.store.contact_tel;
        getApp().core.makePhoneCall({
            phoneNumber: contact_tel
        })
    },

});