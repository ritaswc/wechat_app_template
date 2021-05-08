Page({
    data: {
        address_list: null
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
    },
    onShow: function() {
        getApp().page.onShow(this);
        var t = this;
        getApp().core.showNavigationBarLoading(), getApp().request({
            url: getApp().api.user.address_list,
            success: function(e) {
                getApp().core.hideNavigationBarLoading(), 0 == e.code && t.setData({
                    address_list: e.data.list
                });
            }
        });
    },
    pickAddress: function(e) {
        var t = e.currentTarget.dataset.index, a = this.data.address_list[t];
        getApp().core.setStorageSync(getApp().const.PICKER_ADDRESS, a), getApp().core.navigateBack();
    },
    getWechatAddress: function(e) {
        getApp().core.chooseAddress({
            success: function(e) {
                "chooseAddress:ok" == e.errMsg && (getApp().core.showLoading(), getApp().request({
                    url: getApp().api.user.add_wechat_address,
                    method: "post",
                    data: {
                        national_code: e.nationalCode,
                        name: e.userName,
                        mobile: e.telNumber,
                        detail: e.detailInfo,
                        province_name: e.provinceName,
                        city_name: e.cityName,
                        county_name: e.countyName
                    },
                    success: function(e) {
                        1 != e.code ? 0 == e.code && (getApp().core.setStorageSync(getApp().const.PICKER_ADDRESS, e.data), 
                        getApp().core.navigateBack()) : getApp().core.showModal({
                            title: "提示",
                            content: e.msg,
                            showCancel: !1
                        });
                    },
                    complete: function() {
                        getApp().core.hideLoading();
                    }
                }));
            }
        });
    }
});