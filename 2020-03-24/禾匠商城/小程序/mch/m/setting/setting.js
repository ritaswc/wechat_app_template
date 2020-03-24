var area_picker = require("./../../../components/area-picker/area-picker.js"), app = getApp(), api = getApp().api;

Page({
    data: {},
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var e = this;
        e.getDistrictData(function(t) {
            area_picker.init({
                page: e,
                data: t
            });
        }), getApp().core.showLoading({
            title: "正在加载"
        }), getApp().request({
            url: getApp().api.mch.user.setting,
            success: function(t) {
                getApp().core.hideLoading(), e.setData(t.data);
            }
        });
    },
    getDistrictData: function(e) {
        var a = getApp().core.getStorageSync(getApp().const.DISTRICT);
        if (!a) return getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), void getApp().request({
            url: getApp().api.default.district,
            success: function(t) {
                getApp().core.hideLoading(), 0 == t.code && (a = t.data, getApp().core.setStorageSync(getApp().const.DISTRICT, a), 
                e(a));
            }
        });
        e(a);
    },
    onAreaPickerConfirm: function(t) {
        this.setData({
            edit_district: {
                province: {
                    id: t[0].id,
                    name: t[0].name
                },
                city: {
                    id: t[1].id,
                    name: t[1].name
                },
                district: {
                    id: t[2].id,
                    name: t[2].name
                }
            }
        });
    },
    mchCommonCatChange: function(t) {
        this.setData({
            mch_common_cat_index: t.detail.value
        });
    },
    formSubmit: function(t) {
        var e = this;
        getApp().core.showLoading({
            title: "正在提交",
            mask: !0
        }), t.detail.value.form_id = t.detail.formId, t.detail.value.mch_common_cat_id = e.data.mch_common_cat_index ? e.data.mch_common_cat_list[e.data.mch_common_cat_index].id : e.data.mch && e.data.mch.mch_common_cat_id ? e.data.mch.mch_common_cat_id : "", 
        getApp().request({
            url: getApp().api.mch.user.setting_submit,
            method: "post",
            data: t.detail.value,
            success: function(t) {
                getApp().core.hideLoading(), 0 == t.code ? getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    success: function(t) {
                        t.confirm && getApp().core.navigateBack({
                            delta: 1
                        });
                    }
                }) : e.showToast({
                    title: t.msg
                });
            }
        });
    },
    onReady: function() {
        getApp().page.onReady(this);
    },
    onShow: function() {
        getApp().page.onShow(this);
    },
    onHide: function() {
        getApp().page.onHide(this);
    },
    onUnload: function() {
        getApp().page.onUnload(this);
    },
    uploadLogo: function() {
        var e = this;
        getApp().uploader.upload({
            start: function(t) {
                getApp().core.showLoading({
                    title: "正在上传",
                    mask: !0
                });
            },
            success: function(t) {
                0 == t.code ? (e.data.mch.logo = t.data.url, e.setData({
                    mch: e.data.mch
                })) : e.showToast({
                    title: t.msg
                });
            },
            error: function(t) {
                e.showToast({
                    title: t
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    uploadHeaderBg: function() {
        var e = this;
        getApp().uploader.upload({
            start: function(t) {
                getApp().core.showLoading({
                    title: "正在上传",
                    mask: !0
                });
            },
            success: function(t) {
                0 == t.code ? (e.data.mch.header_bg = t.data.url, e.setData({
                    mch: e.data.mch
                })) : e.showToast({
                    title: t.msg
                });
            },
            error: function(t) {
                e.showToast({
                    title: t
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    }
});