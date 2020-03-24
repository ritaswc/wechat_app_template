if (typeof wx === 'undefined') var wx = getApp().core;
var app = getApp();
var api = getApp().api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        show_edit_modal: false,
        order_sub_price: '',
        order_sub_price_mode: true,
        order_add_price: '',
        order_add_price_mode: false,
        show_send_modal: false,
        send_type: 'express',
        order: null,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        getApp().core.showLoading({
            title: '正在加载',
            mask: true,
        });
        getApp().request({
            url: getApp().api.mch.order.detail,
            data: {
                'id': options.id,
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        order: res.data.order,
                    });
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function (e) {
                            if (e.confirm) {
                                getApp().core.navigateBack();
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

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {
        getApp().page.onReady(this);
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        getApp().page.onShow(this);
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function () {
        getApp().page.onHide(this);
    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function () {
        getApp().page.onUnload(this);
    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {

    },

    //复制收货人信息到剪切板
    copyUserAddress: function () {
        var self = this;
        getApp().core.setClipboardData({
            data: '收货人:' + self.data.order.username + ',联系电话:' + self.data.order.mobile + ',收货地址:' + self.data.order.address,
            success: function (res) {
                getApp().core.getClipboardData({
                    success: function (res) {
                        self.showToast({
                            title: '已复制收货信息'
                        });
                    }
                });
            }
        });
    },

    //弹出修改价格窗
    showEditModal: function (e) {
        var self = this;
        self.setData({
            show_edit_modal: true,
            order_sub_price: '',
            order_add_price: '',
            order_sub_price_mode: true,
            order_add_price_mode: false,
        });
    },

    //关闭修改价格窗
    hideEditModal: function (e) {
        var self = this;
        self.setData({
            show_edit_modal: false,
        });
    },

    tabSwitch: function (e) {
        var self = this;
        var tab = e.currentTarget.dataset.tab;
        if (tab == 'order_sub_price_mode') {
            self.setData({
                order_sub_price_mode: true,
                order_add_price_mode: false,
            });
        }
        if (tab == 'order_add_price_mode') {
            self.setData({
                order_sub_price_mode: false,
                order_add_price_mode: true,
            });
        }
    },

    //优惠价格输入框输入事件
    subPriceInput: function (e) {
        var self = this;
        self.setData({
            order_sub_price: e.detail.value,
        });
    },

    //优惠价格输入框完成事件
    subPriceBlur: function (e) {
        var self = this;
        var val = parseFloat(e.detail.value);
        if (isNaN(val)) {
            val = '';
        } else {
            if (val <= 0) {
                val = '';
            } else {
                val = val.toFixed(2);
            }
        }
        self.setData({
            order_sub_price: val,
        });
    },

    //加价价格输入框输入事件
    addPriceInput: function (e) {
        var self = this;
        self.setData({
            order_add_price: e.detail.value,
        });
    },

    //加价价格输入框完成事件
    addPriceBlur: function (e) {
        var self = this;
        var val = parseFloat(e.detail.value);
        if (isNaN(val)) {
            val = '';
        } else {
            if (val <= 0) {
                val = '';
            } else {
                val = val.toFixed(2);
            }
        }
        self.setData({
            order_add_price: val,
        });
    },

    //修改价格提交
    editPriceSubmit: function () {
        var self = this;
        var type = self.data.order_sub_price_mode ? 'sub' : 'add';
        getApp().core.showLoading({
            mask: true,
            title: '正在处理'
        });
        getApp().request({
            url: getApp().api.mch.order.edit_price,
            method: 'post',
            data: {
                order_id: self.data.order.id,
                type: type,
                price: (type == 'sub') ? self.data.order_sub_price : self.data.order_add_price,
            },
            success: function (res) {
                getApp().core.showModal({
                    title: '提示',
                    content: res.msg,
                    showCancel: false,
                    success: function (e) {
                        if (e.confirm) {
                            if (res.code == 0)
                                getApp().core.redirectTo({
                                    url: '/mch/m/order-detail/order-detail?id=' + self.data.order.id,
                                });
                        }
                    }
                });
            },
            complete: function () {
                getApp().core.hideLoading();
            },
        });
    },

    showSendModal: function () {
        var self = this;
        self.setData({
            show_send_modal: true,
            send_type: 'express',
        });
    },

    hideSendModal: function () {
        var self = this;
        self.setData({
            show_send_modal: false,
        });
    },

    switchSendType: function (e) {
        var self = this;
        var type = e.currentTarget.dataset.type;
        self.setData({
            send_type: type,
        });
    },

    sendSubmit: function () {
        var self = this;
        if (self.data.send_type == 'express') {
            self.hideSendModal();
            getApp().core.navigateTo({
                url: '/mch/m/order-send/order-send?id=' + self.data.order.id,
            });
            return;
        }
        getApp().core.showModal({
            title: '提示',
            content: '无需物流方式订单将直接标记成已发货，确认操作？',
            success: function (e) {
                if (e.confirm) {
                    getApp().core.showLoading({
                        title: '正在提交',
                        mask: true,
                    });
                    getApp().request({
                        url: getApp().api.mch.order.send,
                        method: 'post',
                        data: {
                            send_type: 'none',
                            order_id: self.data.order.id,
                        },
                        success: function (res) {
                            getApp().core.showModal({
                                title: '提示',
                                content: res.msg,
                                success: function (e) {
                                    if (e.confirm) {
                                        if (res.code == 0) {
                                            getApp().core.redirectTo({
                                                url: '/mch/m/order-detail/order-detail?id=' + self.data.order.id,
                                            });
                                        }
                                    }
                                }
                            });
                        },
                        complete: function () {
                            getApp().core.hideLoading({
                                title: '正在提交',
                                mask: true,
                            });
                        }
                    });
                }
            }
        });
    },


});