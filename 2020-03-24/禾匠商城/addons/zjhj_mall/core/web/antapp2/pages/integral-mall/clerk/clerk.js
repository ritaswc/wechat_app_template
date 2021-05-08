if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {

    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        var self = this;
        if (options.scene) {
            var oid = options.scene;
            self.setData({
                type: ''
            });
        } else if (options.type) {
            self.setData({
                type: options.type,
                status: 1
            });
            var oid = options.id
        } else {
            var oid = options.id;
            self.setData({
                status: 1,
                type: ''
            });
        }
        if (typeof my === 'undefined') {
        } else {
            self.setData({
                type: ''
            });
            if (getApp().query !== null) {
                var query = getApp().query;
                getApp().query = null;
                var oid = query.order_no;
            }
        }
        if (oid) {
            self.setData({
                order_id: oid,
            });
            getApp().core.showLoading({
                title: "正在加载",
                mask: true,
            });
            getApp().request({
                url: getApp().api.integral.clerk_order_details,
                data: {
                    id: oid,
                    type: self.data.type
                },
                success: function(res) {
                    if (res.code == 0) {
                        self.setData({
                            order_info: res.data,
                        });
                    } else {
                        getApp().core.showModal({
                            title: '提示',
                            content: res.msg,
                            showCancel: false,
                            success: function(res) {
                                if (res.confirm) {
                                    getApp().core.redirectTo({
                                        url: '/pages/integral-mall/order/order?status=2'
                                    })
                                }
                            }
                        })
                    }
                },
                complete: function() {
                    getApp().core.hideLoading();
                }
            });
        }
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

    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function(options) {
        getApp().page.onShareAppMessage(this);
        var self = this;
        var path = '/pages/pt/group/details?oid=' + self.data.order_info.order_id
        return {
            title: self.data.order_info.goods_list[0].name,
            path: path,
            imageUrl: self.data.order_info.goods_list[0].goods_pic,
            success: function(res) {}
        }
    },
    /**
     * 核销订单
     */
    clerkOrder: function(e) {
        var self = this;
        getApp().core.showModal({
            title: '提示',
            content: '是否确认核销？',
            success: function(res) {
                if (res.confirm) {
                    getApp().core.showLoading({
                        title: "正在加载",
                    });
                    getApp().request({
                        url: getApp().api.integral.clerk,
                        data: {
                            order_id: self.data.order_id
                        },
                        success: function(res) {
                            if (res.code == 0) {
                                getApp().core.showModal({
                                    showCancel: false,
                                    content: res.msg,
                                    confirmText: '确认',
                                    success: function(res) {
                                        if (res.confirm) {
                                            getApp().core.redirectTo({
                                                url: '/pages/index/index',
                                            })
                                        }
                                    }
                                });
                            } else {
                                getApp().core.showModal({
                                    title: '警告！',
                                    showCancel: false,
                                    content: res.msg,
                                    confirmText: '确认',
                                    success: function(res) {
                                        if (res.confirm) {
                                            getApp().core.redirectTo({
                                                url: '/pages/index/index',
                                            })
                                        }
                                    }
                                });
                            }
                        },
                        complete: function() {
                            getApp().core.hideLoading();
                        }
                    });
                } else if (res.cancel) {}
            }
        })
    },

    /**
     * 导航到店
     */
    location: function() {
        var self = this;
        var shop = self.data.order_info.shop;
        getApp().core.openLocation({
            latitude: parseFloat(shop.latitude),
            longitude: parseFloat(shop.longitude),
            address: shop.address,
            name: shop.name
        });
    },
    copyText: function(e) {
        var self = this;
        var text = e.currentTarget.dataset.text;
        getApp().core.setClipboardData({
            data: text,
            success: function() {
                getApp().core.showToast({
                    title: "已复制"
                });
            }
        });
    },
});