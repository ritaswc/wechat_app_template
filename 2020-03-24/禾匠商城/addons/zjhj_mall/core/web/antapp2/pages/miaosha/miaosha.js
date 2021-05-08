if (typeof wx === 'undefined') var wx = getApp().core;
//秒转成时分秒的时间
function secondToTimeStr(second) {
    if (second < 60) {
        var _s = second;
        return "00:00:" + (_s < 10 ? "0" + _s : _s);
    }
    if (second < 3600) {
        var _m = parseInt(second / 60);
        var _s = second % 60;
        return "00:" + (_m < 10 ? "0" + _m : _m) + ":" + (_s < 10 ? "0" + _s : _s);
    }
    if (second >= 3600) {
        var _h = parseInt(second / 3600);
        var _m = parseInt((second % 3600) / 60);
        var _s = second % 60;
        return (_h < 10 ? "0" + _h : _h) + ":" + (_m < 10 ? "0" + _m : _m) + ":" + (_s < 10 ? "0" + _s : _s);
    }
}

Page({

    /**
     * 页面的初始数据
     */
    data: {
        time_list: null,
        goods_list: null,
        page: 1,
        loading_more: false,
        status: true,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        this.loadData(options);
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
    //加载秒杀时间段
    loadData: function(options) {
        var self = this;
        getApp().request({
            url: getApp().api.miaosha.list,
            success: function(res) {
                if (res.code == 0) {
                    if (res.data.list.length == 0) {
                        if (res.data.next_list.length == 0) {
                            getApp().core.showModal({
                                content: "暂无秒杀活动",
                                showCancel: false,
                                confirmText: "返回首页",
                                success: function(e) {
                                    if (e.confirm) {
                                        getApp().core.navigateBack({
                                            url: "/pages/index/index",
                                        });
                                    }
                                }
                            });
                            return;
                        }
                        self.setData({
                            goods_list: res.data.next_list.list,
                            ms_active: true,
                            time_list: res.data.list,
                            next_list: res.data.next_list.list,
                            next_time: res.data.next_list.time,
                        })
                    } else {
                        self.setData({
                            time_list: res.data.list,
                            next_list: res.data.next_list == '' ? [] : res.data.next_list.list,
                            next_time: res.data.next_list == '' ? [] : res.data.next_list.time,
                            ms_active: false,
                        });

                        self.topBarScrollCenter();
                        self.setTimeOver();
                        self.loadGoodsList(false);
                    }


                }
                if (res.code == 1) {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        success: function() {
                            getApp().core.navigateBack({
                                url: '/pages/index/index',
                            });
                        },
                        showCancel: false,
                    });
                }
            }
        });
    },

    //设置倒计时
    setTimeOver: function() {
        var self = this;

        function _init() {
            for (var i in self.data.time_list) {
                var begin_time_over = self.data.time_list[i].begin_time - self.data.time_list[i].now_time;
                var end_time_over = self.data.time_list[i].end_time - self.data.time_list[i].now_time;
                begin_time_over = begin_time_over > 0 ? begin_time_over : 0;
                end_time_over = end_time_over > 0 ? end_time_over : 0;

                self.data.time_list[i]['begin_time_over'] = secondToTimeStr(begin_time_over);
                self.data.time_list[i]['end_time_over'] = secondToTimeStr(end_time_over);
                self.data.time_list[i].now_time = self.data.time_list[i].now_time + 1;
            }
            self.setData({
                time_list: self.data.time_list,
            });
        }

        _init();
        setInterval(function() {
            _init();
        }, 1000);
    },
    miaosha_next: function() {
        var self = this;
        var time_list = self.data.time_list;
        time_list.forEach(function(item, index, array) {
            time_list[index]['active'] = false;
        });
        self.setData({
            goods_list: null,
            ms_active: true,
            time_list: time_list,
        });
        setTimeout(function() {
            self.setData({
                goods_list: self.data.next_list,
            })
        }, 500);
    },
    //顶部滚动条自动滚到当前时间段
    topBarScrollCenter: function() {
        var self = this;
        var index = 0;
        for (var i in self.data.time_list) {
            if (self.data.time_list[i].active) {
                index = i;
                break;
            }
        }
        self.setData({
            top_bar_scroll: index >= 2 ? index - 2 : 0,
        });
    },

    //顶部秒杀时间段点击
    topBarItemClick: function(e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        for (var i in self.data.time_list) {
            if (index == i) {
                self.data.time_list[i].active = true;
            } else {
                self.data.time_list[i].active = false;
            }
        }
        self.setData({
            time_list: self.data.time_list,
            loading_more: false,
            page: 1,
            ms_active: false,
        });
        self.topBarScrollCenter();
        self.loadGoodsList(false);
    },

    loadGoodsList: function(load_more) {
        var self = this;
        var time = false;
        for (var i in self.data.time_list) {
            if (self.data.time_list[i].active) {
                time = self.data.time_list[i].start_time;
                break;
            }
            if (self.data.time_list.length == parseInt(i) + 1 && time == false) {
                time = self.data.time_list[0].start_time;
                self.data.time_list[0].active = true;
            }
        }
        if (!load_more) {
            self.setData({
                goods_list: null,
            });
        } else {
            self.setData({
                loading_more: true,
            });
        }
        getApp().request({
            url: getApp().api.miaosha.goods_list,
            data: {
                time: time,
                page: self.data.page,
            },
            success: function(res) {
                if (res.code == 0) {
                    if (load_more) {
                        self.data.goods_list = self.data.goods_list.concat(res.data.list)
                    } else {
                        self.data.goods_list = res.data.list;
                    }
                    self.setData({
                        loading_more: false,
                        goods_list: self.data.goods_list,
                        page: (!res.data.list || res.data.list.length == 0) ? -1 : (self.data.page + 1),
                    });
                }
            }
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
        if (self.data.page == -1)
            return;
        self.loadGoodsList(true);
    },
    
    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {
        getApp().page.onShareAppMessage(this);
        var self = this;
        var res = {
            path: "/pages/miaosha/miaosha?user_id=" + self.data.__user_info.id,
            success: function (e) { },
        };
        return res;
    },
});