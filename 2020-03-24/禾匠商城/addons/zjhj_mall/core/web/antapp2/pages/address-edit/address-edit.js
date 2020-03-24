if (typeof wx === 'undefined') var wx = getApp().core;
var area_picker = require('./../../components/area-picker/area-picker.js');
Page({
    data: {
        name: "",
        mobile: "",
        detail: "",
        district: null,
    },
    onLoad: function (options) {
        getApp().page.onLoad(this, options);

        var self = this;
        self.getDistrictData(function (data) {
            area_picker.init({
                page: self,
                data: data,
            });
        });

        self.setData({
            address_id: options.id,
        });
        if (options.id) {
            getApp().core.showLoading({
                title: "正在加载",
                mask: true,
            });
            getApp().request({
                url: getApp().api.user.address_detail,
                data: {
                    id: options.id,
                },
                success: function (res) {
                    getApp().core.hideLoading();
                    if (res.code == 0) {
                        self.setData(res.data);
                    }
                }
            });
        }
    },

    getDistrictData: function (cb) {
        var district = getApp().core.getStorageSync(getApp().const.DISTRICT);
        if (!district) {
            getApp().core.showLoading({
                title: "正在加载",
                mask: true,
            });
            getApp().request({
                url: getApp().api.default.district,
                success: function (res) {
                    getApp().core.hideLoading();
                    if (res.code == 0) {
                        district = res.data;
                        getApp().core.setStorageSync(getApp().const.DISTRICT,district);
                        cb(district);
                    }
                }
            });
            return;
        }
        cb(district);
    },

    onAreaPickerConfirm: function (e) {
        var self = this;
        self.setData({
            district: {
                province: {
                    id: e[0].id,
                    name: e[0].name,
                },
                city: {
                    id: e[1].id,
                    name: e[1].name,
                },
                district: {
                    id: e[2].id,
                    name: e[2].name,
                },
            }
        });
    },

    saveAddress: function () {
        var self = this;
        // var myreg = /^([0-9]{6,12})$/;
        // var myreg2 = /^(\d{3,4}-\d{6,9})$/;
        // var myreg3 = /^\+?\d[\d -]{8,12}\d/;
        // if (!myreg.test(self.data.mobile) && !myreg2.test(self.data.mobile) && !myreg3.test(self.data.mobile)) {
        //     self.showToast({
        //         title: "联系电话格式不正确",
        //     });
        //     return false;
        // }
        getApp().core.showLoading({
            title: "正在保存",
            mask: true,
        });
        var district = self.data.district;
        if (!district) {
            district = {
                province: {
                    id: ""
                },
                city: {
                    id: ""
                },
                district: {
                    id: ""
                }
            };
        }
        getApp().request({
            url: getApp().api.user.address_save,
            method: "post",
            data: {
                address_id: self.data.address_id || "",
                name: self.data.name,
                mobile: self.data.mobile,
                province_id: district.province.id,
                city_id: district.city.id,
                district_id: district.district.id,
                detail: self.data.detail,
            },
            success: function (res) {
                getApp().core.hideLoading();
                if (res.code == 0) {
                    getApp().core.showModal({
                        title: "提示",
                        content: res.msg,
                        showCancel: false,
                        success: function (res) {
                            if (res.confirm) {
                                getApp().core.navigateBack();
                            }
                        }
                    });
                }
                if (res.code == 1) {
                    self.showToast({
                        title: res.msg,
                    });
                }
            }
        });
    },

    inputBlur: function (e) {
        var name = e.currentTarget.dataset.name;
        var value = e.detail.value;
        var data = '{"' + name + '":"' + value + '"}';
        this.setData(JSON.parse(data));
    },

    getWechatAddress: function (e) {
        var self = this;
        getApp().core.chooseAddress({
            success: function (e) {
                if (e.errMsg != 'chooseAddress:ok')
                    return;
                getApp().core.showLoading();
                getApp().request({
                    url: getApp().api.user.wechat_district,
                    data: {
                        national_code: e.nationalCode,
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
                        }
                        self.setData({
                            name: e.userName || "",
                            mobile: e.telNumber || "",
                            detail: e.detailInfo || "",
                            district: res.data.district,
                        });
                    },
                    complete: function () {
                        getApp().core.hideLoading();
                    }
                });
            }
        });
    },

    onShow: function () {
        getApp().page.onShow(this);
    },
});