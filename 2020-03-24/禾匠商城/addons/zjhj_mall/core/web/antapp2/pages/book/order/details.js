if (typeof wx === 'undefined') var wx = getApp().core;
var utils = require('../../../utils/helper.js');
Page({

    /**
     * 页面的初始数据
     */
    data: {
        hide: 1,
        qrcode: ""
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) { getApp().page.onLoad(this, options);
        this.setData({
            options: options
        });
        this.getOrderDetails(options);
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

    /**
     * 订单详情
     */
    getOrderDetails:function(e){
        var oid = e.oid;
        var self = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        // getApp().core.showNavigationBarLoading();
        getApp().request({
            url: getApp().api.book.order_details,
            method: "get",
            data: { id: oid },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        attr:JSON.parse(res.data.attr),
                        goods: res.data,
                    });
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function (res) {
                            if (res.confirm) {
                                getApp().core.redirectTo({
                                    url: '/pages/book/order/order?status=1'
                                });
                            }
                        }
                    });
                }
            },
            complete: function (res) {
                setTimeout(function () {
                    // 延长一秒取消加载动画
                    getApp().core.hideLoading();
                }, 1000);
            }
        });
    },
    /**
     * 跳转至商品详情
     */
    goToGoodsDetails:function(e){
        getApp().core.redirectTo({
            url: '/pages/book/details/details?id=' + this.data.goods.goods_id,
        })
    },
    /**
 * 点击取消
 */
    orderCancel: function (e) {
        var self = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        var id = e.currentTarget.dataset.id;
        getApp().request({
            url: getApp().api.book.order_cancel,
            data: {
                id: id,
            },
            success: function (res) {
                if (res.code == 0) {
                    getApp().core.redirectTo({
                        url: '/pages/book/order/order?status=0'
                    })
                }
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },
    /**
     * 订单列表点击支付
     */
    GoToPay(e) {
        getApp().core.showLoading({
            title: "正在提交",
            mask: true,
        });
        getApp().request({
            url: getApp().api.book.order_pay,
            data: {
                id: e.currentTarget.dataset.id,
            },
            complete: function () {
                getApp().core.hideLoading();
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
                        success: function (e) {
                        },
                        fail: function (e) {
                        },
                        complete: function (e) {

                            if (e.errMsg == "requestPayment:fail" || e.errMsg == "requestPayment:fail cancel") {//支付失败转到待支付订单列表
                                getApp().core.showModal({
                                    title: "提示",
                                    content: "订单尚未支付",
                                    showCancel: false,
                                    confirmText: "确认",
                                    success: function (res) {
                                        if (res.confirm) {
                                            getApp().core.redirectTo({
                                                url: "/pages/book/order/order?status=0",
                                            });
                                        }
                                    }
                                });
                                return;
                            }
                            getApp().core.redirectTo({
                                url: "/pages/book/order/order?status=1",
                            });
                        },
                    });
                }
                if (res.code == 1) {
                    getApp().core.showToast({
                        title: res.msg,
                        image: "/images/icon-warning.png",
                    });
                }
            }
        });
    },
    /**
     * 门店列表
     */
    goToShopList:function(e)
    {
        getApp().core.redirectTo({
            url: '/pages/book/shop/shop?ids=' + this.data.goods.shop_id,
        })
    },
    /**
     * 核销码
     */
    orderQrcode: function (e) {
        var self = this;
        var index = e.target.dataset.index;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        if (self.data.goods.offline_qrcode) {
            self.setData({
                hide: 0,
                qrcode: self.data.goods.offline_qrcode
            });
            getApp().core.hideLoading();
        } else {
            getApp().request({
                url: getApp().api.book.get_qrcode,
                data: {
                    order_no: self.data.goods.order_no
                },
                success: function (res) {
                    if (res.code == 0) {
                        self.setData({
                            hide: 0,
                            qrcode: res.data.url
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
        }
    },
    hide: function (e) {
        this.setData({
            hide: 1
        });
    },
    /**
     * 前往评价
     */
    comment: function (e) {
        getApp().core.navigateTo({
            url: '/pages/book/order-comment/order-comment?id=' + e.target.dataset.id,
            success: function (res) { },
            fail: function (res) { },
            complete: function (res) { },
        })
    },

    /**
     * 申请退款
     */
    applyRefund: function (e) {
        var self = this;
        var id = self.data.options.oid
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.book.apply_refund,
            data: {
                order_id: id
            },
            success: function (res) {
                if (res.code == 0) {
                    getApp().core.showModal({
                        title: '提示',
                        content: '申请退款成功',
                        showCancel: false,
                        success: function (res) {
                            if (res.confirm) {
                                getApp().core.redirectTo({
                                    url: "/pages/book/order/order?status=3",
                                });
                            }
                        }
                    })
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
});