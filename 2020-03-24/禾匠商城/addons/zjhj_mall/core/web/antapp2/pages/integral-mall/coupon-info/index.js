if (typeof wx === 'undefined') var wx = getApp().core;
// pages/integral-mall/coupon-info/index.js


Page({

    /**
     * 页面的初始数据
     */
    data: {
        showModel: false,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) { getApp().page.onLoad(this, options);
        if (options.coupon_id) {
            var id = options.coupon_id
            var self = this;
            getApp().request({
                url: getApp().api.integral.coupon_info,
                data: {
                    coupon_id: id
                },
                success: function (res) {
                    if (res.code == 0) {
                        self.setData({
                            coupon: res.data.coupon,
                            info: res.data.info,
                        });
                    }
                },
            });
        }
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

    exchangeCoupon: function (e) {
        var self = this;
        var coupon = self.data.coupon;
        var integral = self.data.__user_info.integral;
        if (parseInt(coupon.integral) > parseInt(integral)) {
            self.setData({
                showModel: true,
                content: '当前积分不足',
                status: 1,
            });
        } else {
            if (parseFloat(coupon.price) > 0) {
                var content = '需要' + coupon.integral + '积分' + '+￥' + parseFloat(coupon.price)
            } else {
                var content = '需要' + coupon.integral + '积分'
            }
            if (parseInt(coupon.total_num) <= 0) {
                self.setData({
                    showModel: true,
                    content: '已领完,来晚一步',
                    status: 1,
                });
                return
            }
            if (parseInt(coupon.num) >= parseInt(coupon.user_num)) {
                coupon.type = 1;
                self.setData({
                    showModel: true,
                    content: '兑换次数已达上限',
                    status: 1,
                });
                return
            }
            getApp().core.showModal({
                title: '确认兑换',
                content: content,
                success: function (e) {
                    if (e.confirm) {
                        if (parseFloat(coupon.price) > 0) {
                            getApp().core.showLoading({
                                title: '提交中',
                            });
                            getApp().request({
                                url: getApp().api.integral.exchange_coupon,
                                data: {
                                    id: coupon.id,
                                    type: 2
                                },
                                success: function (res) {
                                    if (res.code == 0) {
                                        getApp().core.requestPayment({
                                            _res: res,
                                            timeStamp: res.data.timeStamp,
                                            nonceStr: res.data.nonceStr,
                                            package: res.data.package,
                                            signType: res.data.signType,
                                            paySign: res.data.paySign,
                                            complete: function (e) {
                                                if (e.errMsg == "requestPayment:fail" || e.errMsg == "requestPayment:fail cancel") {
                                                    getApp().core.showModal({
                                                        title: "提示",
                                                        content: "订单尚未支付",
                                                        showCancel: false,
                                                        confirmText: "确认",
                                                    });
                                                    return;
                                                }
                                                if (e.errMsg == "requestPayment:ok") {
                                                    coupon.num = parseInt(coupon.num)
                                                    coupon.num += 1;
                                                    coupon.total_num = parseInt(coupon.total_num)
                                                    coupon.total_num -= 1;
                                                    integral = parseInt(integral)
                                                    integral -= parseInt(coupon.integral)
                                                    self.setData({
                                                        showModel: true,
                                                        status: 4,
                                                        content: res.msg,
                                                        coupon: coupon,
                                                    });
                                                }
                                            },
                                        });
                                    }else{
                                        self.setData({
                                            showModel: true,
                                            content: res.msg,
                                            status: 1,
                                        });
                                    }
                                },
                                complete: function () {
                                    getApp().core.hideLoading();
                                }
                            });
                        } else {
                            getApp().core.showLoading({
                                title: '提交中',
                            });
                            getApp().request({
                                url: getApp().api.integral.exchange_coupon,
                                data: {
                                    id: coupon.id,
                                    type: 1
                                },
                                success: function (res) {
                                    if (res.code == 0) {
                                        coupon.num = parseInt(coupon.num)
                                        coupon.num += 1;
                                        coupon.total_num = parseInt(coupon.total_num)
                                        coupon.total_num -= 1;
                                        integral = parseInt(integral)
                                        integral -= parseInt(coupon.integral)
                                        self.setData({
                                            showModel: true,
                                            status: 4,
                                            content: res.msg,
                                            coupon: coupon,
                                        });
                                    }else{
                                        self.setData({
                                            showModel: true,
                                            content: res.msg,
                                            status: 1,
                                        });
                                    }
                                },
                                complete: function () {
                                    getApp().core.hideLoading();
                                }
                            });
                        }
                    }
                }
            })
        }
    },
    hideModal: function () {
        this.setData({
            showModel: false
        });
    },
})