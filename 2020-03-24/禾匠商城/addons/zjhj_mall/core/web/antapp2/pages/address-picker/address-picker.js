if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        address_list: null,
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
            }
        });
    },

    pickAddress: function (e) {
        var index = e.currentTarget.dataset.index;
        var address = this.data.address_list[index];
        getApp().core.setStorageSync(getApp().const.PICKER_ADDRESS,address);
        getApp().core.navigateBack();
    },

    getWechatAddress: function (e) {
        getApp().core.chooseAddress({
            success: function (e) {
                if (e.errMsg != 'chooseAddress:ok')
                    return;
                getApp().core.showLoading();
                getApp().request({
                    url: getApp().api.user.add_wechat_address,
                    method: "post",
                    data: {
                        national_code: e.nationalCode,
                        name: e.userName,
                        mobile: e.telNumber,
                        detail: e.detailInfo,
                        province_name: e.provinceName,
                        city_name: e.cityName,
                        county_name: e.countyName,
                    },
                    success: function (res) {
                        if (res.code == 1) {
                            getApp().core.showModal({
                                title: '提示',
                                content: res.msg,
                                showCancel: false,
                            });
                            return;
                        }
                        if (res.code == 0) {
                            getApp().core.setStorageSync(getApp().const.PICKER_ADDRESS,res.data);
                            getApp().core.navigateBack();
                        }
                    },
                    complete: function () {
                        getApp().core.hideLoading();
                    }
                });
            }
        });
    },
});