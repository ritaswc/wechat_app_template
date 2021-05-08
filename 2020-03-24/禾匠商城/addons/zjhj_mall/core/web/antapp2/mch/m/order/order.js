if (typeof wx === 'undefined') var wx = getApp().core;
var app = getApp();
var api = getApp().api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        status: 1,
        show_menu: false,
        order_list: [],
        no_orders: false,
        no_more_orders: false,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        self.setData({
            status: parseInt(options.status || 1),
            loading_more: true,
        });
        self.loadOrderList(function () {
            self.setData({
                loading_more: false,
            });
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

    showMenu: function (e) {
        var self = this;
        self.setData({
            show_menu: self.data.show_menu ? false : true,
        });
    },

    loadOrderList: function (callback) {
        var self = this;
        var status = self.data.status;
        var current_page = self.data.current_page || 0;
        var target_page = current_page + 1;
        var keyword = self.data.keyword || '';
        getApp().request({
            url: getApp().api.mch.order.list,
            data: {
                status: status,
                page: target_page,
                keyword: keyword,
            },
            success: function (res) {
                if (res.code == 0) {
                    if (target_page == 1 && (!res.data.list || !res.data.list.length)) {
                        self.setData({
                            no_orders: true,
                        });
                    }
                    if (!res.data.list || !res.data.list.length) {
                        self.setData({
                            no_more_orders: true,
                        });
                    } else {
                        self.data.order_list = self.data.order_list || [];
                        self.data.order_list = self.data.order_list.concat(res.data.list);
                        self.setData({
                            order_list: self.data.order_list,
                            current_page: target_page,
                        });
                    }
                }
            },
            complete: function () {
                if (typeof callback == 'function')
                    callback();
            },
        });
    },

    showSendModal: function (e) {
        var self = this;
        self.setData({
            show_send_modal: true,
            send_type: 'express',
            order_index: e.currentTarget.dataset.index,
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
                url: '/mch/m/order-send/order-send?id=' + self.data.order_list[self.data.order_index].id,
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
                            order_id: self.data.order_list[self.data.order_index].id,
                        },
                        success: function (res) {
                            getApp().core.showModal({
                                title: '提示',
                                content: res.msg,
                                success: function (e) {
                                    if (e.confirm) {
                                        if (res.code == 0) {
                                            getApp().core.redirectTo({
                                                url: '/mch/m/order/order?status=2',
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

    showPicList: function (e) {
        var self = this;
        getApp().core.previewImage({
            urls: self.data.order_list[e.currentTarget.dataset.index].pic_list,
            current: self.data.order_list[e.currentTarget.dataset.index].pic_list[e.currentTarget.dataset.pindex],
        });
    },

    refundPass: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var id = self.data.order_list[index].id;
        var type = self.data.order_list[index].type;
        getApp().core.showModal({
            title: '提示',
            content: '确认同意' + (type == 1 ? '退款？资金将原路返回！' : '换货？'),
            success: function (e) {
                if (e.confirm) {
                    getApp().core.showLoading({
                        title: '正在处理',
                        mask: true,
                    });
                    getApp().request({
                        url: getApp().api.mch.order.refund,
                        method: 'post',
                        data: {
                            id: id,
                            action: 'pass',
                        },
                        success: function (res) {
                            getApp().core.showModal({
                                title: '提示',
                                content: res.msg,
                                showCancel: false,
                                success: function (e) {
                                    getApp().core.redirectTo({
                                        url: '/' + self.route + '?' + getApp().helper.objectToUrlParams(self.options),
                                    });
                                }
                            });
                        },
                        complete: function () {
                            getApp().core.hideLoading();
                        },
                    });
                }
            }
        });
    },

    refundDeny: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var id = self.data.order_list[index].id;
        var type = self.data.order_list[index].type;
        getApp().core.showModal({
            title: '提示',
            content: '确认拒绝？',
            success: function (e) {
                if (e.confirm) {
                    getApp().core.showLoading({
                        title: '正在处理',
                        mask: true,
                    });
                    getApp().request({
                        url: getApp().api.mch.order.refund,
                        method: 'post',
                        data: {
                            id: id,
                            action: 'deny',
                        },
                        success: function (res) {
                            getApp().core.showModal({
                                title: '提示',
                                content: res.msg,
                                showCancel: false,
                                success: function (e) {
                                    getApp().core.redirectTo({
                                        url: '/' + self.route + '?' + getApp().helper.objectToUrlParams(self.options),
                                    });
                                }
                            });
                        },
                        complete: function () {
                            getApp().core.hideLoading();
                        },
                    });
                }
            }
        });
    },

    searchSubmit: function (e) {
        var self = this;
        var keyword = e.detail.value;
        self.setData({
            keyword: keyword,
            loading_more: true,
            order_list: [],
            current_page: 0,
        });
        self.loadOrderList(function () {
            self.setData({
                loading_more: false,
            });
        });

    },

});