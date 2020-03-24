if (typeof wx === 'undefined') var wx = getApp().core;
var utils = getApp().helper;
var app = getApp();
var api = getApp().api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        tab: 1,
        sort: 1,
        coupon_list: [],
        copy: false,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);

        var self = this;
        if (typeof my === 'undefined') {
            if (options.scene) {
                var scene = decodeURIComponent(options.scene);
                if (scene) {
                    scene = utils.scene_decode(scene);
                    if (scene.mch_id) {
                        options.mch_id = scene.mch_id;
                    }
                }
            }
        } else {
            if (getApp().query !== null) {
                var query = getApp().query;
                getApp().query = null;
                options.mch_id = query.mch_id;
            }
        }

        self.setData({
            tab: options.tab || 1,
            sort: options.sort || 1,
            mch_id: options.mch_id || false,
            cat_id: options.cat_id || '',
        });
        if (!self.data.mch_id) {
            getApp().core.showModal({
                title: '提示',
                content: '店铺不存在！店铺id为空'
            });
        }
        setInterval(function() {
            self.onScroll();
        }, 40);
        this.getShopData();
    },
    quickNavigation: function() {
        var status = 0;
        this.setData({
            quick_icon: !this.data.quick_icon
        })
        var store = this.data.store;
        var animationPlus = getApp().core.createAnimation({
            duration: 300,
            timingFunction: 'ease-out',
        });

        var x = -55;
        if (!this.data.quick_icon) {
            animationPlus.translateY(x).opacity(1).step();
        } else {
            animationPlus.opacity(0).step();
        }
        this.setData({
            animationPlus: animationPlus.export(),
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
    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function() {

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function() {
        var self = this;
        self.getGoodsList();
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {
        getApp().page.onShareAppMessage(this);
        var self = this;
        var user_info = getApp().getUser();
        return {
            path: "/mch/shop/shop?user_id=" + user_info.id + '&mch_id=' + self.data.mch_id,
            title: self.data.shop ? self.data.shop.name : '商城首页',
        };
    },
    kfuStart: function() {
        this.setData({
            copy: true,
        })
    },
    kfuEnd: function() {
        this.setData({
            copy: false,
        })
    },
    copyinfo: function(e) {
        getApp().core.setClipboardData({
            data: e.target.dataset.info,
            success: function(res) {
                getApp().core.showToast({
                    title: '复制成功！',
                    icon: 'success',
                    duration: 2000,
                    mask: true
                })
            }
        });
    },
    callPhone: function(e) {
        getApp().core.makePhoneCall({
            phoneNumber: e.target.dataset.info
        })
    },
    onScroll: function(e) {
        var self = this;
        getApp().core.createSelectorQuery().selectViewport('.after-navber').scrollOffset(function(res) {
            var limit = self.data.tab == 2 ? 136.5333 : 85.3333;
            if (res.scrollTop >= limit) {
                self.setData({
                    fixed: true,
                });
            } else {
                self.setData({
                    fixed: false,
                });
            }
        }).exec();
    },

    getShopData: function() {
        var self = this;
        var current_page = self.data.current_page || 0;
        var target_page = current_page + 1;
        var cache_key = 'shop_data_mch_id_' + self.data.mch_id;
        var cache_data = getApp().core.getStorageSync(cache_key);
        if (cache_data) {
            self.setData({
                shop: cache_data.shop,
            });
        }
        getApp().core.showNavigationBarLoading();
        self.setData({
            loading: true,
        });
        getApp().request({
            url: getApp().api.mch.shop,
            data: {
                mch_id: self.data.mch_id,
                tab: self.data.tab,
                sort: self.data.sort,
                page: target_page,
                cat_id: self.data.cat_id,
            },
            success: function(res) {
                if (res.code == 1) {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function(e) {
                            if (e.confirm) {
                                getApp().core.redirectTo({
                                    url: '/pages/index/index',
                                });
                            }
                        }
                    });
                    return;
                }
                if (res.code == 0) {
                    self.setData({
                        shop: res.data.shop,
                        coupon_list: res.data.coupon_list,
                        hot_list: res.data.goods_list,
                        goods_list: res.data.goods_list,
                        new_list: res.data.new_list,
                        current_page: target_page,
                        cs_icon: res.data.shop.cs_icon,
                    });
                    getApp().core.setStorageSync(cache_key, res.data);
                }
            },
            complete: function() {
                getApp().core.hideNavigationBarLoading();
                self.setData({
                    loading: false,
                });
            }
        });
    },

    getGoodsList: function() {
        var self = this;
        if (self.data.tab == 3) {
            return;
        }
        if (self.data.loading) {
            return;
        }
        if (self.data.no_more) {
            return;
        }
        self.setData({
            loading: true,
        });
        var current_page = self.data.current_page || 0;
        var target_page = current_page + 1;
        getApp().request({
            url: getApp().api.mch.shop,
            data: {
                mch_id: self.data.mch_id,
                tab: self.data.tab,
                sort: self.data.sort,
                page: target_page,
                cat_id: self.data.cat_id,
            },
            success: function(res) {
                if (res.code == 0) {
                    if (self.data.tab == 1) {
                        if (res.data.goods_list && res.data.goods_list.length) {
                            self.data.hot_list = self.data.hot_list.concat(res.data.goods_list);
                            self.setData({
                                hot_list: self.data.hot_list,
                                current_page: target_page,
                            });
                        } else {
                            self.setData({
                                no_more: true,
                            });
                        }
                    }
                    if (self.data.tab == 2) {
                        if (res.data.goods_list && res.data.goods_list.length) {
                            self.data.goods_list = self.data.goods_list.concat(res.data.goods_list);
                            self.setData({
                                goods_list: self.data.goods_list,
                                current_page: target_page,
                            });
                        } else {
                            self.setData({
                                no_more: true,
                            });
                        }
                    }
                }
            },
            complete: function() {
                self.setData({
                    loading: false,
                });
            }
        });
    },


});