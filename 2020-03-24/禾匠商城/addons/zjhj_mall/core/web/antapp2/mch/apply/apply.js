if (typeof wx === 'undefined') var wx = getApp().core;
var area_picker = require('./../../components/area-picker/area-picker.js');
var app = getApp();
var api = getApp().api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        is_form_show: false
    },

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
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.mch.apply,
            success: function (res) {
                getApp().core.hideLoading();
                if (res.code == 0) {
                    if (res.data.apply) {
                        res.data.show_result = true;
                    }
                    self.setData(res.data);
                    if (!res.data.apply) {
                        self.setData({
                            is_form_show: true
                        })
                    }
                }
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

    mchCommonCatChange: function (e) {
        var self = this;
        self.setData({
            mch_common_cat_index: e.detail.value
        });
    },


    applySubmit: function (e) {
        var self = this;
        if (self.data.entry_rules && !self.data.agree_entry_rules) {
            getApp().core.showModal({
                title: '提示',
                content: '请先阅读并同意《入驻协议》。',
                showCancel: false,
            });
            return;
        }
        getApp().core.showLoading({
            title: "正在提交",
            mask: true,
        });
        if(self.data.mch_common_cat_index===0){
            self.data.mch_common_cat_index = '0';
        }
        getApp().request({
            url: getApp().api.mch.apply_submit,
            method: 'post',
            data: {
                realname: e.detail.value.realname,
                tel: e.detail.value.tel,
                name: e.detail.value.name,
                province_id: e.detail.value.province_id,
                city_id: e.detail.value.city_id,
                district_id: e.detail.value.district_id,
                address: e.detail.value.address,
                mch_common_cat_id: self.data.mch_common_cat_index ? (self.data.mch_common_cat_list[self.data.mch_common_cat_index].id) : ((self.data.apply && self.data.apply.mch_common_cat_id) ? self.data.apply.mch_common_cat_id : ''),
                service_tel: e.detail.value.service_tel,
                form_id: e.detail.formId,
                wechat_name: e.detail.value.wechat_name,
            },
            success: function (res) {
                getApp().core.hideLoading();
                if (res.code == 0) {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function (e) {
                            if (e.confirm) {
                                getApp().core.redirectTo({
                                    url: '/mch/apply/apply'
                                });
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

    hideApplyResult: function () {
        var self = this;
        self.setData({
            show_result: false,
            is_form_show: true
        });
    },

    showApplyResult: function () {
        var self = this;
        self.setData({
            show_result: true,
        });
    },

    showEntryRules: function () {
        this.setData({
            show_entry_rules: true,
        });
    },

    disagreeEntryRules: function () {
        this.setData({
            agree_entry_rules: false,
            show_entry_rules: false,
        });
    },

    agreeEntryRules: function () {
        this.setData({
            agree_entry_rules: true,
            show_entry_rules: false,
        });
    },

});