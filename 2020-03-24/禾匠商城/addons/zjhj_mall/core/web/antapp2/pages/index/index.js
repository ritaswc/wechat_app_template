if (typeof wx === 'undefined') var wx = getApp().core;
var interval = 0;
var page_first_init = true;
var timer = 1;
var fullScreen = false;
var page_first = [];
Page({
    data: {
        WindowWidth: getApp().core.getSystemInfoSync().windowWidth,
        WindowHeight: getApp().core.getSystemInfoSync().windowHeight,
        left: 0,
        show_notice: -1,
        animationData: {},
        play: -1,
        time: 0,
        buy: false,
        opendate: false,
        goods: '',
        form: {
            number: 1,
        },
        time_all: []
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        if (!options.page_id) {
            options.page_id = -1;
        }
        this.setData({
            options: options
        });
        this.loadData(options);
    },

    /**
     * 购买记录
     */
    suspension: function() {
        var self = this;

        interval = setInterval(function() {
            getApp().request({
                url: getApp().api.default.buy_data,
                data: {
                    'time': self.data.time
                },
                method: 'POST',
                success: function(res) {
                    if (res.code == 0) {
                        var inArray = false;
                        var msgHistory = self.data.msgHistory;
                        if (msgHistory == res.md5) {
                            inArray = true;
                        }
                        var cha_time = '';
                        var s = res.cha_time;
                        var m = Math.floor(s / 60 - Math.floor(s / 3600) * 60);
                        if (m == 0) {
                            cha_time = s % 60 + '秒';
                        } else {
                            cha_time = m + '分' + s % 60 + '秒';
                        }

                        if (!inArray && res.cha_time <= 300) {
                            self.setData({
                                buy: {
                                    time: cha_time,
                                    type: res.data.type,
                                    url: res.data.url,
                                    user: (res.data.user.length >= 5) ? res.data.user.slice(0, 4) + "..." : res.data.user,
                                    avatar_url: res.data.avatar_url,
                                    address: (res.data.address.length >= 8) ? res.data.address.slice(0, 7) + "..." : res.data.address,
                                    content: res.data.content,
                                },
                                msgHistory: res.md5
                            });
                        } else {
                            self.setData({
                                buy: false
                            });
                        }

                    }
                },
                noHandlerFail: true,
            });
        }, 10000);
    },

    /**
     * 加载页面数据
     */
    loadData: function() {
        var self = this;
        var data = {};
        var options = self.data.options
        if (options.page_id != -1) {
            data.page_id = options.page_id;
        } else {
            data.page_id = -1;
            var pages_index_index = getApp().core.getStorageSync(getApp().const.PAGE_INDEX_INDEX);
            if (pages_index_index) {
                pages_index_index.act_modal_list = [];
                self.setData(pages_index_index);
            }
        }
        getApp().request({
            url: getApp().api.default.index,
            data: data,
            success: function(res) {
                if (res.code == 0) {
                    if (res.data.status == 'diy') {
                        var act_modal_list = res.data.act_modal_list;
                        if (options.page_id != -1) {
                            getApp().core.setNavigationBarTitle({
                                title: res.data.info,
                            });
                            self.setData({
                                title: res.data.info
                            });
                        }

                        for (var i = act_modal_list.length - 1; i >= 0; i--) {
                            if (((typeof act_modal_list[i].status == 'undefined' || act_modal_list[i].status == 0) && getApp().helper.inArray(act_modal_list[i].page_id, page_first) && !self.data.user_info_show) || act_modal_list[i].show == 0) {
                                act_modal_list.splice(i, 1);
                            } else {
                                page_first.push(act_modal_list[i].page_id);
                            }
                        }

                        self.setData({
                            template: res.data.template,
                            act_modal_list: act_modal_list,
                            time_all: res.data.time_all
                        });
                        self.setTime();
                    } else {
                        if (!page_first_init) {
                            res.data.act_modal_list = [];
                        } else {
                            if (!self.data.user_info_show) {
                                page_first_init = false;
                            }
                        }

                        self.setData(res.data);
                        self.miaoshaTimer();
                    }
                    if (options.page_id == -1) {
                        getApp().core.setStorageSync(getApp().const.PAGE_INDEX_INDEX, res.data);
                    }
                }
            },
            complete: function() {
                getApp().core.stopPullDownRefresh();
            }
        });

    },
    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function() {
        var self = this;
        getApp().page.onShow(this);
        var diy = require('./../../components/diy/diy.js');
        diy.init(this);
        getApp().getConfig(function(config) {
            let store = config.store;
            if (store && store.name && self.data.options.page_id == -1) {
                getApp().core.setNavigationBarTitle({
                    title: store.name,
                });
            }
            if (store && store.purchase_frame === 1) {
                self.suspension(self.data.time);
            } else {
                self.setData({
                    buy_user: '',
                })
            }
        });
        getApp().query = null;
    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function() {
        getApp().getStoreData();
        clearInterval(timer);
        this.loadData();
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function(options) {
        getApp().page.onShareAppMessage(this);
        var self = this;
        var user_info = getApp().getUser();
        var res = {};
        if (self.data.options.page_id != -1) {
            res = {
                path: "/pages/index/index?user_id=" + user_info.id + '&page_id=' + self.data.options.page_id,
                title: self.data.title
            }
        } else {
            res = {
                path: "/pages/index/index?user_id=" + user_info.id,
                title: self.data.store.name
            }
        }
        return res;
    },

    showshop: function(e) {

        var self = this;
        var goods_id = e.currentTarget.dataset.id;
        var data = e.currentTarget.dataset;
        getApp().request({
            url: getApp().api.default.goods,
            data: {
                id: goods_id
            },
            success: function(res) {
                if (res.code == 0) {
                    self.setData({
                        data: data,
                        attr_group_list: res.data.attr_group_list,
                        goods: res.data,
                        showModal: true
                    });
                }
            }
        });
    },

    miaoshaTimer: function() {
        var self = this;
        if (!self.data.miaosha) {
            return;
        }
        if (self.data.miaosha.rest_time == 0) {
            return;
        }
        if (self.data.miaosha.ms_next) {} else {
            timer = setInterval(function() {
                if (self.data.miaosha.rest_time > 0) {
                    self.data.miaosha.rest_time = self.data.miaosha.rest_time - 1;
                } else {
                    clearInterval(timer);
                    return;
                }
                self.data.miaosha.times = self.setTimeList(self.data.miaosha.rest_time);
                self.setData({
                    miaosha: self.data.miaosha,
                });
            }, 1000);
        }
    },

    onHide: function() {
        getApp().page.onHide(this);
        this.setData({
            play: -1
        });
        clearInterval(interval);
    },
    onUnload: function() {
        getApp().page.onUnload(this);
        this.setData({
            play: -1
        });
        clearInterval(timer);
        clearInterval(interval);
    },
    showNotice: function(e) {
        console.log(e);
        this.setData({
            show_notice: e.currentTarget.dataset.index
        });
    },
    closeNotice: function() {
        this.setData({
            show_notice: -1
        });
    },

    to_dial: function() {
        var contact_tel = this.data.store.contact_tel;
        getApp().core.makePhoneCall({
            phoneNumber: contact_tel
        })
    },

    closeActModal: function() {
        var self = this;
        var act_modal_list = self.data.act_modal_list;
        for (var i in act_modal_list) {
            var index = parseInt(i);
            if (act_modal_list[index].show) {
                act_modal_list[index].show = false;
            }
            break;
        }
        self.setData({
            act_modal_list: act_modal_list,
        });

        setTimeout(function() {
            for (var i in act_modal_list) {
                if (act_modal_list[i].show) {
                    var one = act_modal_list.splice(i, 1);
                    act_modal_list = one.concat(act_modal_list);
                    break;
                }
            }
            self.setData({
                act_modal_list: act_modal_list
            });
        }, 500);
    },
    naveClick: function(e) {
        var self = this;
        getApp().navigatorClick(e, self);
    },
    onPageScroll: function(e) {
        var self = this;
        if (fullScreen) {
            return;
        }
        if (self.data.play != -1) {
            var max = getApp().core.getSystemInfoSync().windowHeight;
            if (typeof my === 'undefined') {
                getApp().core.createSelectorQuery().select('.video').fields({
                    rect: true
                }, function(res) {
                    if (res.top <= -200 || res.top >= max - 57) {
                        self.setData({
                            play: -1
                        });
                    }
                }).exec();
            } else {
                getApp().core.createSelectorQuery().select('.video').boundingClientRect().scrollOffset().exec((res) => {
                    if (res[0].top <= -200 || res[0].top >= max - 57) {
                        self.setData({
                            play: -1
                        });
                    }
                });
            }
        }
    },
    fullscreenchange: function(e) {
        if (e.detail.fullScreen) {
            fullScreen = true;
        } else {
            fullScreen = false;
        }
    },
});