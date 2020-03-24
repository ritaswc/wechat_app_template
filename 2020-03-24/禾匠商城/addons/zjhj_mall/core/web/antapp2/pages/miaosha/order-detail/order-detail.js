if (typeof wx === 'undefined') var wx = getApp().core;
// pages/miaosha/order-detail/order-detail.js


Page({

    /**
     * 页面的初始数据
     */
    data: {
        order: null,
        getGoodsTotalPrice: function() {
            return this.data.order.total_price;
        }
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        var self = this;
        getApp().core.showLoading({
            title: "正在加载",
        });
        getApp().request({
            url: getApp().api.miaosha.order_details,
            data: {
                order_id: options.id,
            },
            success: function(res) {
                if (res.code == 0) {
                    self.setData({
                        order: res.data,
                    });
                }
            },
            complete: function() {
                getApp().core.hideLoading();
            }
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
    location: function() {
        var self = this;
        var shop = self.data.order.shop;
        getApp().core.openLocation({
            latitude: parseFloat(shop.latitude),
            longitude: parseFloat(shop.longitude),
            address: shop.address,
            name: shop.name
        })
    },

    orderRevoke: function(e) {
        var self = this;
        getApp().core.showModal({
            title: "提示",
            content: "是否退款该订单？",
            cancelText: "否",
            confirmText: "是",
            success: function(res) {
                if (res.cancel)
                    return true;
                if (res.confirm) {
                    getApp().core.showLoading({
                        title: "操作中",
                    });
                    getApp().request({
                        url: getApp().api.miaosha.order_revoke,
                        data: {
                            order_id: e.currentTarget.dataset.id,
                        },
                        success: function(res) {
                            getApp().core.hideLoading();
                            getApp().core.showModal({
                                title: "提示",
                                content: res.msg,
                                showCancel: false,
                                success: function(res) {
                                    if (res.confirm) {
                                        self.onLoad({id:self.data.order.order_id});
                                    }
                                }
                            });
                        }
                    });
                }
            }
        });
    },
});