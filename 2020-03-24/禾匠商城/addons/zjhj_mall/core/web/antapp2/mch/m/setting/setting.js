if (typeof wx === 'undefined') var wx = getApp().core;
var area_picker = require('./../../../components/area-picker/area-picker.js');
var app = getApp();
var api = getApp().api;
Page({

    /**
     * 页面的初始数据
     */
    data: {},

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        self.getDistrictData(function (data) {
            area_picker.init({
                page: self,
                data: data,
            });
        });
        getApp().core.showLoading({
            title: "正在加载"
        });
        getApp().request({
            url: getApp().api.mch.user.setting,
            success: function (res) {
                getApp().core.hideLoading();
                self.setData(res.data);
            }
        });
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
                        getApp().core.setStorageSync(getApp().const.DISTRICT, district);
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
            edit_district: {
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
                }
            },
        });
    },

    mchCommonCatChange: function (e) {
        var self = this;
        self.setData({
            mch_common_cat_index: e.detail.value
        });
    },

    formSubmit: function (e) {
        var self = this;
        getApp().core.showLoading({
            title: '正在提交',
            mask: true,
        });
        e.detail.value.form_id = e.detail.formId;
        e.detail.value.mch_common_cat_id = self.data.mch_common_cat_index ? (self.data.mch_common_cat_list[self.data.mch_common_cat_index].id) : ((self.data.mch && self.data.mch.mch_common_cat_id) ? self.data.mch.mch_common_cat_id : '');
        getApp().request({
            url: getApp().api.mch.user.setting_submit,
            method: 'post',
            data: e.detail.value,
            success: function (res) {
                getApp().core.hideLoading();
                if (res.code == 0) {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function (e) {
                            if (e.confirm) {
                                getApp().core.navigateBack({delta: 1});
                            }
                        }
                    });
                } else {
                    self.showToast({
                        title: res.msg,
                    });
                }
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

    uploadLogo: function () {
        var self = this;
        getApp().uploader.upload({
            start: function (e) {
                getApp().core.showLoading({
                    title: '正在上传',
                    mask: true,
                });
            },
            success: function (res) {
                if (res.code == 0) {
                    self.data.mch.logo = res.data.url;
                    self.setData({
                        mch: self.data.mch,
                    });
                } else {
                    self.showToast({
                        title: res.msg,
                    });
                }
            },
            error: function (e) {
                self.showToast({
                    title: e,
                });
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },

    uploadHeaderBg: function () {
        var self = this;
        getApp().uploader.upload({
            start: function (e) {
                getApp().core.showLoading({
                    title: '正在上传',
                    mask: true,
                });
            },
            success: function (res) {
                if (res.code == 0) {
                    self.data.mch.header_bg = res.data.url;
                    self.setData({
                        mch: self.data.mch,
                    });
                } else {
                    self.showToast({
                        title: res.msg,
                    });
                }
            },
            error: function (e) {
                self.showToast({
                    title: e,
                });
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },

});