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
    getOrderDetails: function (e) {
        var oid = "";
        if (typeof my === 'undefined') {
            oid = e.scene;
        } else {
            if (getApp().query !== null) {
                var query = getApp().query;
                getApp().query = null;
                oid = query.order_id;
            }
        }
        var self = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        // getApp().core.showNavigationBarLoading();
        getApp().request({
            url: getApp().api.book.clerk_order_details,
            method: "get",
            data: { id: oid },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
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
                                    url: '/pages/user/user'
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
    goToGoodsDetails: function (e) {
        getApp().core.redirectTo({
            url: '/pages/book/details/details?id=' + this.data.goods.goods_id,
        })
    },
    /**
     * 确认核销
     */
    nowWriteOff:function(e)
    {
        var self = this;
        getApp().core.showModal({
            title: '提示',
            content: '是否确认核销？',
            success: function (res) {
                if (res.confirm) {
                    getApp().core.showLoading({
                        title: "正在加载",
                    });
                    getApp().request({
                        url: getApp().api.book.clerk,
                        data: {
                            order_id: self.data.goods.id
                        },
                        success: function (res) {
                            if (res.code == 0) {
                                getApp().core.redirectTo({
                                    url: '/pages/user/user',
                                })
                            } else {
                                getApp().core.showModal({
                                    title: '警告！',
                                    showCancel: false,
                                    content: res.msg,
                                    confirmText: '确认',
                                    success: function (res) {
                                        if (res.confirm) {
                                            getApp().core.redirectTo({
                                                url: '/pages/index/index',
                                            })
                                        }
                                    }
                                });
                            }
                        },
                        complete: function () {
                            getApp().core.hideLoading();
                        }
                    });
                } else if (res.cancel) {
                }
            }
        })
    }
})