if (typeof wx === 'undefined') var wx = getApp().core;
// bargain/activity/activity.js
var time = require('../commons/time.js');
var app = getApp();
var api = getApp().api;
var setIntval = null;
var is_loading = false;
var is_no_more = true;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        show_more: true,
        p: 1,
        show_modal: false,
        show: false,
        show_more_btn: true,
        animationData: null,
        show_modal_a: false
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        var self = this;
        self.setData({
            order_id: options.order_id
        })
        self.joinBargain();
        time.init(self);
    },

    joinBargain: function() {
        var self = this;
        getApp().request({
            url: getApp().api.bargain.bargain,
            data: {
                order_id: self.data.order_id,
            },
            success: function(res) {
                if (res.code == 0) {
                    self.getOrderInfo();
                    self.setData(res.data);
                } else {
                    self.showToast({
                        title: res.msg
                    });
                    getApp().core.hideLoading();
                }
            }
        });
    },

    getOrderInfo: function() {
        var self = this;
        getApp().request({
            url: getApp().api.bargain.activity,
            data: {
                order_id: self.data.order_id,
                page: 1
            },
            success: function(res) {
                if (res.code == 0) {
                    self.setData(res.data);
                    self.setData({
                        time_list: self.setTimeList(res.data.reset_time),
                        show: true
                    });
                    if (self.data.bargain_status) {
                        self.setData({
                            show_modal: true,
                        });
                    }
                    self.setTimeOver();
                    is_no_more = false;
                    self.animationCr();
                } else {
                    self.showToast({
                        title: res.msg
                    });
                }
            },
            complete: function(res) {
                getApp().core.hideLoading();
            }
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
        clearInterval(setIntval);
        setIntval = null;
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        var self = this;
        var res = {
            path: "/bargain/activity/activity?order_id=" + self.data.order_id + "&user_id=" + self.data.__user_info.id,
            success: function (e) { },
            title: self.data.share_title || null,
        };
        return res;
    },

    loadData: function() {
        var self = this;
        getApp().core.showLoading({
            title: '加载中',
        });
        if (is_loading) {
            return;
        }
        is_loading = true;
        getApp().core.showNavigationBarLoading();
        var p = self.data.p + 1;
        getApp().request({
            url: getApp().api.bargain.activity,
            data: {
                order_id: self.data.order_id,
                page: p
            },
            success: function(res) {
                if (res.code == 0) {
                    var bargain_info = self.data.bargain_info;
                    bargain_info = bargain_info.concat(res.data.bargain_info);
                    self.setData({
                        bargain_info: bargain_info,
                        p: p,
                        price: res.data.price,
                        money_per: res.data.money_per,
                        money_per_t: res.data.money_per_t
                    });
                    if (res.data.bargain_info.length == 0) {
                        is_no_more = true;
                        p = p - 1;
                        self.setData({
                            show_more_btn: false,
                            show_more: true,
                            p: p
                        });
                    }
                } else {
                    self.showToast({
                        title: res.msg
                    });
                }
            },
            complete: function(res) {
                getApp().core.hideLoading();
                getApp().core.hideNavigationBarLoading();
                is_loading = false;
            }
        });
    },

    showMore: function(res) {
        var self = this;
        if (self.data.show_more_btn) {
            is_no_more = false;
        }
        if (is_no_more) {
            return;
        }
        self.loadData();
    },

    hideMore: function() {
        var self = this;
        self.setData({
            show_more_btn: true,
            show_more: false
        });
    },

    orderSubmit: function() {
        var self = this;
        getApp().core.showLoading({
            title: '加载中',
        })
        getApp().core.redirectTo({
            url: '/bargain/goods/goods?goods_id=' + self.data.goods_id,
        })
        return ;
        getApp().request({
            url: getApp().api.bargain.bargain_submit,
            method: "POST",
            data: {
                goods_id: self.data.goods_id,
            },
            success: function(res) {
                if (res.code == 0) {
                    getApp().core.redirectTo({
                        url: '/bargain/activity/activity?order_id=' + res.data.order_id,
                    })
                } else {
                    self.showToast({
                        title: res.msg
                    });
                }
            },
            complete: function(res) {
                getApp().core.hideLoading();
            }
        });
    },

    close: function() {
        this.setData({
            show_modal: false
        });
    },

    buyNow: function() {
        var self = this;
        var mch_list = [];
        var goods_list = [];
        goods_list.push({
            bargain_order_id: self.data.order_id
        });
        mch_list.push({
            mch_id: 0,
            goods_list: goods_list
        });
        getApp().core.showModal({
            title: '提示',
            content: '是否确认购买？',
            success: function(e) {
                if (e.confirm) {
                    getApp().core.redirectTo({
                        url: "/pages/new-order-submit/new-order-submit?mch_list=" + JSON.stringify(mch_list),
                    });
                }
            }
        });
    },

    goToList: function() {
        getApp().core.redirectTo({
            url: '/bargain/list/list',
        })
    },

    animationCr: function() {
        var self = this;
        self.animationT();
        setTimeout(function() {
            self.setData({
                show_modal_a: true
            });
            self.animationBig();
            self.animationS();
        }, 800);
    },

    animationBig: function() {
        var animation = getApp().core.createAnimation({
            duration: 500,
            transformOrigin: '50% 50%',
        });
        var self = this;

        var circleCount = 0;
        setInterval(function() {
            if (circleCount % 2 == 0) {
                animation.scale(0.9).step();
            } else {
                animation.scale(1.0).step();
            }

            self.setData({
                animationData: animation.export()
            });

            circleCount++;
            if (circleCount == 500) {
                circleCount = 0;
            }
        }, 500)
    },

    animationS: function() {
        var animation = getApp().core.createAnimation({
            duration: 500,
        });
        var self = this;
        animation.width('512rpx').height('264rpx').step();
        animation.rotate(-2).step();
        animation.rotate(4).step();
        animation.rotate(-2).step();
        animation.rotate(0).step();
        self.setData({
            animationDataHead: animation.export()
        })
    },

    animationT: function() {
        var animation = getApp().core.createAnimation({
            duration: 200,
        });
        var self = this;
        animation.width('500rpx').height('500rpx').step();
        self.setData({
            animationDataT: animation.export()
        })
    }
})