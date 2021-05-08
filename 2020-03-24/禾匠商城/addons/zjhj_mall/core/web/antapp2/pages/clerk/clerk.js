if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
    * 页面的初始数据
    */
    data: {
        order: null,
        getGoodsTotalPrice: function () {
            return this.data.order.total_price;
        }
    },

    /**
    * 生命周期函数--监听页面加载
    */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);

        var self = this;
        var order_no = '';
        if (typeof my === 'undefined'){
            order_no = options.scene;
        } else {
            if (getApp().query !== null) {
                var query = getApp().query;
                getApp().query = null;
                order_no = query.order_no;
            }
        }
        self.setData({
            store: getApp().core.getStorageSync(getApp().const.STORE),
            user_info: getApp().getUser()
        });
        getApp().core.showLoading({
            title: "正在加载",
        });
        getApp().request({
            url: getApp().api.order.clerk_detail,
            data: {
                order_no: order_no,
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        order: res.data,
                    });
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
    },
    clerk: function (e) {
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
                        url: getApp().api.order.clerk,
                        data: {
                            order_no: self.data.order.order_no
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
                }
            }
        })
    }

});