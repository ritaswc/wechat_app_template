if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        address_list: [],
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        getApp().page.onShow(this);

        var self = this;
        getApp().core.showNavigationBarLoading();
        getApp().request({
            url: getApp().api.user.address_list,
            success: function (res) {
                getApp().core.hideNavigationBarLoading();
                if (res.code == 0) {
                    self.setData({
                        address_list: res.data.list,
                    });
                }
                self.setData({
                    show_no_data_tip: (self.data.address_list.length == 0),
                });
            }
        });
    },

    setDefaultAddress: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var address = self.data.address_list[index];
        getApp().core.showLoading({
            title: "正在保存",
            mask: true,
        });
        getApp().request({
            url: getApp().api.user.address_set_default,
            data: {
                address_id: address.id,
            },
            success: function (res) {
                getApp().core.hideLoading();
                if (res.code == 0) {
                    var address_list = self.data.address_list;
                    for (var i in address_list) {
                        if (i == index) {
                            address_list[i].is_default = 1;
                        } else {
                            address_list[i].is_default = 0;
                        }
                    }
                    self.setData({
                        address_list: address_list,
                    });
                }
            }
        });
    },

    deleteAddress: function (e) {
        var self = this;
        var address_id = e.currentTarget.dataset.id;
        var index = e.currentTarget.dataset.index;
        getApp().core.showModal({
            title: "提示",
            content: "确认删除改收货地址？",
            success: function (res) {
                if (res.confirm) {
                    getApp().core.showLoading({
                        title: "正在删除",
                        mask: true,
                    });
                    getApp().request({
                        url: getApp().api.user.address_delete,
                        data: {
                            address_id: address_id,
                        },
                        success: function (res) {
                            if (res.code == 0) {
                                getApp().core.redirectTo({
                                    url: "/pages/address/address",
                                });
                            }
                            if (res.code == 1) {
                                getApp().core.hideLoading();
                                getApp().core.showToast({
                                    title: res.msg,
                                });
                            }
                        }
                    });
                }
            }
        });
    },

});