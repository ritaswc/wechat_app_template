if (typeof wx === 'undefined') var wx = getApp().core;
var utils = require('../../../utils/helper.js');
var WxParse = require('../../../wxParse/wxParse.js');
var gSpecificationsModel = require('../../../components/goods/specifications_model.js'); //商城多规格选择
var goodsBanner = require('../../../components/goods/goods_banner.js');
var goodsInfo = require('../../../components/goods/goods_info.js');
var goodsBuy = require('../../../components/goods/goods_buy.js');

Page({
    /**
     * 页面的初始数据
     */
    data: {
        pageType: "PINTUAN",
        hide: "hide",
        form: {
            number: 1,
            pt_detail: false,
        },
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        var parent_id = 0;
        var user_id = options.user_id;
        var scene = decodeURIComponent(options.scene);
        if (typeof user_id !== 'undefined') {
            parent_id = user_id;
        } else if (typeof scene !== 'undefined') {
            var scene_obj = utils.scene_decode(scene);
            if (scene_obj.uid && scene_obj.gid) {
                parent_id = scene_obj.uid;
                options.gid = scene_obj.gid;
            } else {
                parent_id = scene;
            }
        } else {
            if (typeof my !== 'undefined') {
                if (getApp().query !== null) {
                    var query = getApp().query;
                    getApp().query = null;
                    options.id = query.gid;
                }
            }
        }

        this.setData({
            id: options.gid,
            oid: options.oid ? options.oid : 0,
            group_checked: options.group_id ? options.group_id : 0
        });
        this.getGoodsInfo(options);
        var store = getApp().core.getStorageSync(getApp().const.STORE);
        this.setData({
            store: store,
        });
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
        gSpecificationsModel.init(this);
        goodsBanner.init(this);
        goodsInfo.init(this);
        goodsBuy.init(this);
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function() {
        getApp().page.onHide(this);
    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function() {
        getApp().page.onUnload(this);
        getApp().core.removeStorageSync(getApp().const.PT_GROUP_DETAIL);
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
    onReachBottom: function() {
        getApp().page.onReachBottom(this);
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        var self = this;
        var user_info = getApp().core.getStorageSync(getApp().const.USER_INFO);
        var path = '/pages/pt/details/details?gid=' + self.data.goods.id + '&user_id=' + user_info.id;
        return {
            title: self.data.goods.name,
            path: path,
            imageUrl: self.data.goods.cover_pic,
            success: function(res) {}
        }
    },
    /**
     * 获取商品详情
     */
    getGoodsInfo: function(e) {
        var gid = e.gid;
        var self = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().core.showNavigationBarLoading();
        getApp().request({
            url: getApp().api.group.details,
            method: "get",
            data: {
                gid: gid
            },
            success: function(res) {
                if (res.code == 0) {
                    self.countDownRun(res.data.info.limit_time_ms);
                    var detail = res.data.info.detail;
                    WxParse.wxParse("detail", "html", detail, self);
                    getApp().core.setNavigationBarTitle({
                        title: res.data.info.name,
                    })
                    getApp().core.hideNavigationBarLoading();
                    var reduce_price = (res.data.info.original_price - res.data.info.price).toFixed(2);

                    var goods = res.data.info;
                    goods.service_list = res.data.info.service;

                    self.setData({
                        group_checked: self.data.group_checked ? self.data.group_checked : 0,
                        goods: goods,
                        attr_group_list: res.data.attr_group_list,
                        attr_group_num: res.data.attr_group_num,
                        limit_time: res.data.limit_time_res,
                        group_list: res.data.groupList,
                        group_num: res.data.groupList.length,
                        group_rule_id: res.data.groupRuleId,
                        comment: res.data.comment,
                        comment_num: res.data.commentNum,
                        reduce_price: reduce_price < 0 ? 0 : reduce_price,
                    });
                    self.countDown();
                    self.selectDefaultAttr();
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function(res) {
                            if (res.confirm) {
                                getApp().core.redirectTo({
                                    url: '/pages/pt/index/index'
                                });
                            }
                        }
                    });
                }
            },
            complete: function(res) {
                getApp().core.hideLoading();
            }
        });
    },

    more: function() {
        this.setData({
            'pt_detail': true
        })
    },
    end_more: function() {
        this.setData({
            'pt_detail': false
        })
    },
    previewImage: function(e) {
        var urls = e.currentTarget.dataset.url;
        getApp().core.previewImage({
            urls: [urls]
        });
    },
    selectDefaultAttr: function() {
        var self = this;
        if (!self.data.goods || self.data.goods.use_attr === '0')
            for (var i in self.data.attr_group_list) {

                for (var j in self.data.attr_group_list[i].attr_list) {

                    if (i == 0 && j == 0) {
                        self.data.attr_group_list[i].attr_list[j]['checked'] = true;
                    }
                }
            }

        self.setData({
            attr_group_list: self.data.attr_group_list,
        });
    },

    /**
     * 执行倒计时
     */
    countDownRun: function(limit_time_ms) {
        var self = this;
        setInterval(function() {
            var leftTime = (new Date(limit_time_ms[0], limit_time_ms[1] - 1, limit_time_ms[2], limit_time_ms[3], limit_time_ms[4], limit_time_ms[5])) - (new Date()); //计算剩余的毫秒数 
            var days = parseInt(leftTime / 1000 / 60 / 60 / 24, 10); //计算剩余的天数 
            var hours = parseInt(leftTime / 1000 / 60 / 60 % 24, 10); //计算剩余的小时 
            var minutes = parseInt(leftTime / 1000 / 60 % 60, 10); //计算剩余的分钟 
            var seconds = parseInt(leftTime / 1000 % 60, 10); //计算剩余的秒数 

            days = self.checkTime(days);
            hours = self.checkTime(hours);
            minutes = self.checkTime(minutes);
            seconds = self.checkTime(seconds);
            self.setData({
                limit_time: {
                    days: days < 0 ? '00' : days,
                    hours: hours < 0 ? '00' : hours,
                    mins: minutes < 0 ? '00' : minutes,
                    secs: seconds < 0 ? '00' : seconds,
                },
            });
        }, 1000);
    },
    /**
     * 时间补0
     */
    checkTime: function(i) { //将0-9的数字前面加上0，例1变为01
        if (i < 0) {
            return '00';
        }
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    },

    /**
     * 去参团
     */
    goToGroup: function(e) {
        getApp().core.navigateTo({
            url: '/pages/pt/group/details?oid=' + e.target.dataset.id,
        })
    },
    /**
     * 评论列表页
     */
    goToComment: function(e) {
        getApp().core.navigateTo({
            url: '/pages/pt/comment/comment?id=' + this.data.goods.id,
        })
    },
    /**
     * 拼团规则
     */
    goArticle: function(e) {
        if (this.data.group_rule_id) {
            getApp().core.navigateTo({
                url: '/pages/article-detail/article-detail?id=' + this.data.group_rule_id,
            })
        }
    },


    /**
     * 团购
     */
    buyNow: function() {
        this.submit('GROUP_BUY', this.data.group_checked);
    },
    /**
     * 单独购买
     */
    onlyBuy: function() {
        var self = this;
        this.submit('ONLY_BUY', 0);
    },
    /**
     * 订单提交
     */
    submit: function(type, group_id) {
        var self = this;
        var groupNum = type == 'GROUP_BUY';
        if (!self.data.show_attr_picker || groupNum != self.data.groupNum) {
            self.setData({
                show_attr_picker: true,
                groupNum: groupNum,
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
                    attr_group_name: attr_group_list[i].attr_group_name,
                    attr_id: attr.attr_id,
                    attr_name: attr.attr_name,
                });
            }
        }

        self.setData({
            show_attr_picker: false,
        });
        var parent_id = 0;
        if (self.data.oid) {
            type = "GROUP_BUY_C";
            parent_id = self.data.oid;
        }
        getApp().core.redirectTo({
            url: "/pages/pt/order-submit/order-submit?goods_info=" + JSON.stringify({
                goods_id: self.data.goods.id,
                attr: checked_attr_list,
                num: self.data.form.number,
                type: type,
                deliver_type: self.data.goods.type,
                group_id: group_id,
                parent_id: parent_id
            }),
        });

    },

    /**
     * 拼团倒计时
     */
    countDown: function() {
        var self = this;
        setInterval(function() {
            var group_list = self.data.group_list;
            for (var i in group_list) {
                var leftTime = (new Date(group_list[i]['limit_time_ms'][0], group_list[i]['limit_time_ms'][1] - 1, group_list[i]['limit_time_ms'][2], group_list[i]['limit_time_ms'][3], group_list[i]['limit_time_ms'][4], group_list[i]['limit_time_ms'][5])) - (new Date()); //计算剩余的毫秒数  
                var days = parseInt(leftTime / 1000 / 60 / 60 / 24, 10); //计算剩余的天数 
                var hours = parseInt(leftTime / 1000 / 60 / 60 % 24, 10); //计算剩余的小时
                var minutes = parseInt(leftTime / 1000 / 60 % 60, 10); //计算剩余的分钟
                var seconds = parseInt(leftTime / 1000 % 60, 10); //计算剩余的秒数

                days = self.checkTime(days);
                hours = self.checkTime(hours);
                minutes = self.checkTime(minutes);
                seconds = self.checkTime(seconds);
                group_list[i].limit_time = {
                    days: days,
                    hours: hours > 0 ? hours : '00',
                    mins: minutes > 0 ? minutes : '00',
                    secs: seconds > 0 ? seconds : '00',
                };
                self.setData({
                    group_list: group_list,
                });
            }
        }, 1000);
    },
    /**
     * 图片放大
     */
    bigToImage: function(e) {
        var urls = this.data.comment[e.target.dataset.index]['pic_list'];
        getApp().core.previewImage({
            current: e.target.dataset.url, // 当前显示图片的http链接
            urls: urls // 需要预览的图片http链接列表
        })
    },

    groupCheck: function () {
        var self = this;
        var attr_group_num = self.data.attr_group_num;
        var attr_list = self.data.attr_group_num.attr_list;
        for (var i in attr_list) {
            attr_list[i].checked = false;
        }
        attr_group_num.attr_list = attr_list;

        var goods = self.data.goods;
        self.setData({
            group_checked: 0,
            attr_group_num: attr_group_num,
        });

        var attr_group_list = self.data.attr_group_list;
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
            url: getApp().api.group.goods_attr_info,
            data: {
                goods_id: self.data.goods.id,
                group_id: self.data.group_checked,
                attr_list: JSON.stringify(check_attr_list),
            },
            success: function (res) {
                getApp().core.hideLoading();
                if (res.code == 0) {
                    var goods = self.data.goods;
                    goods.price = res.data.price;
                    goods.num = res.data.num;
                    goods.attr_pic = res.data.pic;
                    // goods.original_price = res.data.single;
                    goods.single_price = res.data.single_price ? res.data.single_price : 0;
                    goods.group_price = res.data.price;
                    goods.is_member_price = res.data.is_member_price;

                    self.setData({
                        goods: goods,
                    });
                }
            }
        });
    },

    attrNumClick: function (e) {
        var self = this;
        var attr_id = e.target.dataset.id;

        var attr_group_num = self.data.attr_group_num;
        var attr_list = attr_group_num.attr_list;

        for (var i in attr_list) {
            if (attr_list[i].id == attr_id) {
                attr_list[i].checked = true;
            } else {
                attr_list[i].checked = false;
            }
        }
        attr_group_num.attr_list = attr_list;

        self.setData({
            attr_group_num: attr_group_num,
            group_checked: attr_id,
        });

        var attr_group_list = self.data.attr_group_list;
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
            url: getApp().api.group.goods_attr_info,
            data: {
                goods_id: self.data.goods.id,
                group_id: self.data.group_checked,
                attr_list: JSON.stringify(check_attr_list),
            },
            success: function (res) {
                getApp().core.hideLoading();
                if (res.code == 0) {
                    var goods = self.data.goods;
                    goods.price = res.data.price;
                    goods.num = res.data.num;
                    goods.attr_pic = res.data.pic;
                    // goods.original_price = res.data.single;
                    goods.single_price = res.data.single_price ? res.data.single_price : 0;
                    goods.group_price = res.data.price
                    goods.is_member_price = res.data.is_member_price;
                    self.setData({
                        goods: goods,
                    });
                }
            }
        });

    },
})