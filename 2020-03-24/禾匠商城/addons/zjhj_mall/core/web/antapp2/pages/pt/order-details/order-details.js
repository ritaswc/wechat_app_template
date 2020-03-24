if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    options:'',
    data: {
        hide: 1,
        qrcode: ""
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) { getApp().page.onLoad(this, options);
        this.options = options;
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function (options) { getApp().page.onReady(this);

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function (options) { getApp().page.onShow(this);
        var self = this;
        self.loadOrderDetails();
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function (options) { getApp().page.onHide(this);

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function (options) { getApp().page.onUnload(this);

    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function (options) { getApp().page.onPullDownRefresh(this);

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function (options) { getApp().page.onReachBottom(this);

    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function (options) { getApp().page.onShareAppMessage(this);
        var self = this;
        var path = '/pages/pt/group/details?oid=' + self.data.order_info.order_id
        return {
            title: self.data.order_info.goods_list[0].name,
            path: path,
            imageUrl: self.data.order_info.goods_list[0].goods_pic,
            success: function (res) {
            }
        }
    },
    /**
     * 订单详情数据加载
     */
    loadOrderDetails: function () {
        var self = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.group.order.detail,
            data: {
                order_id: self.options.id
            },
            success: function (res) {
                if (res.code == 0) {
                    if (res.data.status != 3) {
                        self.countDownRun(res.data.limit_time_ms);
                    }
                    self.setData({
                        order_info: res.data,
                        limit_time: res.data.limit_time,
                    });
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function (res) {
                            if (res.confirm) {
                                getApp().core.redirectTo({
                                    url: '/pages/pt/order/order'
                                })
                            }
                        }
                    })
                }
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },
    /**
     * 复制
     */
    copyText: function (e) {
        var self = this;
        var text = e.currentTarget.dataset.text;
        getApp().core.setClipboardData({
            data: text,
            success: function () {
                getApp().core.showToast({
                    title: "已复制"
                });
            }
        });
    },
    /**
     * 执行倒计时
     */
    countDownRun: function (limit_time_ms) {
        var self = this;
        setInterval(function () {
            var leftTime = (new Date(limit_time_ms[0], limit_time_ms[1] - 1, limit_time_ms[2], limit_time_ms[3], limit_time_ms[4], limit_time_ms[5])) - (new Date()); //计算剩余的毫秒数 
            var hours = parseInt(leftTime / 1000 / 60 / 60 % 24, 10); //计算剩余的小时 
            var minutes = parseInt(leftTime / 1000 / 60 % 60, 10);//计算剩余的分钟 
            var seconds = parseInt(leftTime / 1000 % 60, 10);//计算剩余的秒数 

            hours = self.checkTime(hours);
            minutes = self.checkTime(minutes);
            seconds = self.checkTime(seconds);
            self.setData({
                limit_time: {
                    hours: hours > 0 ? hours : 0,
                    mins: minutes > 0 ? minutes : 0,
                    secs: seconds > 0 ? seconds : 0,
                },
            });
        }, 1000);
    },
    /**
     * 时间补0
     */
    checkTime: function (i) { //将0-9的数字前面加上0，例1变为01 
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    },
    /**
     * 确认收货
     */
    toConfirm: function (e) {
        var self = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.group.order.confirm,
            data: {
                order_id: self.data.order_info.order_id
            },
            success: function (res) {
                if (res.code == 0) {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function (res) {
                            if (res.confirm) {
                                getApp().core.redirectTo({
                                    url: '/pages/pt/order-details/order-details?id=' + self.data.order_info.order_id
                                })
                            }
                        }
                    });
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function (res) {
                            if (res.confirm) {
                                getApp().core.redirectTo({
                                    url: '/pages/pt/order-details/order-details?id=' + self.data.order_info.order_id
                                })
                            }
                        }
                    });
                };
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },
    /**
     * 前往参团页
     */
    goToGroup: function (e) {
        getApp().core.redirectTo({
            url: '/pages/pt/group/details?oid=' + this.data.order_info.order_id,
            success: function (res) { },
            fail: function (res) { },
            complete: function (res) { },
        })
    },
    /**
     * 导航到店
     */
    location: function () {
        var self = this;
        var shop = self.data.order_info.shop;
        getApp().core.openLocation({
            latitude: parseFloat(shop.latitude),
            longitude: parseFloat(shop.longitude),
            address: shop.address,
            name: shop.name
        });
    },
    /**
     * 到店拿货获取核销二维码
     */
    getOfflineQrcode: function (e) {
        var self = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.group.order.get_qrcode,
            data: {
                order_no: e.currentTarget.dataset.id,
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        hide: 0,
                        qrcode: res.data.url,
                    });
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                    })
                }
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },
    hide: function (e) {
        this.setData({
            hide: 1
        });
    },
    orderRevoke: function () {
        var self = this;
        getApp().core.showModal({
            title: '提示',
            content: '是否取消该订单？',
            cancelText: '否',
            confirmtext: '是',
            success: function (e) {
                if(e.confirm){
                    getApp().core.showLoading({
                        title: '操作中',
                    });
                    getApp().request({
                        url: getApp().api.group.order.revoke,
                        data:{
                            order_id:self.data.order_info.order_id
                        },
                        success: function (res) {
                            getApp().core.hideLoading();
                            getApp().core.showModal({
                                title: "提示",
                                content: res.msg,
                                showCancel: false,
                                success: function (res) {
                                    if (res.confirm) {
                                        self.loadOrderDetails();
                                    }
                                }
                            });
                        }
                    });
                }
            }
        })
    }
});