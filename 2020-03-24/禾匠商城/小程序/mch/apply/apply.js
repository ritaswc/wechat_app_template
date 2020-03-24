var area_picker = require("./../../components/area-picker/area-picker.js"), app = getApp(), api = getApp().api;

Page({
    data: {
        is_form_show: !1
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var t = this;
        t.getDistrictData(function(e) {
            area_picker.init({
                page: t,
                data: e
            });
        }), getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.mch.apply,
            success: function(e) {
                getApp().core.hideLoading(), 0 == e.code && (e.data.apply && (e.data.show_result = !0), 
                t.setData(e.data), e.data.apply || t.setData({
                    is_form_show: !0
                }));
            }
        });
    },
    getDistrictData: function(t) {
        var a = getApp().core.getStorageSync(getApp().const.DISTRICT);
        if (!a) return getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), void getApp().request({
            url: getApp().api.default.district,
            success: function(e) {
                getApp().core.hideLoading(), 0 == e.code && (a = e.data, getApp().core.setStorageSync(getApp().const.DISTRICT, a), 
                t(a));
            }
        });
        t(a);
    },
    onAreaPickerConfirm: function(e) {
        this.setData({
            edit_district: {
                province: {
                    id: e[0].id,
                    name: e[0].name
                },
                city: {
                    id: e[1].id,
                    name: e[1].name
                },
                district: {
                    id: e[2].id,
                    name: e[2].name
                }
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
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    mchCommonCatChange: function(e) {
        this.setData({
            mch_common_cat_index: e.detail.value
        });
    },
    applySubmit: function(e) {
        var t = this;
        !t.data.entry_rules || t.data.agree_entry_rules ? (getApp().core.showLoading({
            title: "正在提交",
            mask: !0
        }), 0 === t.data.mch_common_cat_index && (t.data.mch_common_cat_index = "0"), getApp().request({
            url: getApp().api.mch.apply_submit,
            method: "post",
            data: {
                realname: e.detail.value.realname,
                tel: e.detail.value.tel,
                name: e.detail.value.name,
                province_id: e.detail.value.province_id,
                city_id: e.detail.value.city_id,
                district_id: e.detail.value.district_id,
                address: e.detail.value.address,
                mch_common_cat_id: t.data.mch_common_cat_index ? t.data.mch_common_cat_list[t.data.mch_common_cat_index].id : t.data.apply && t.data.apply.mch_common_cat_id ? t.data.apply.mch_common_cat_id : "",
                service_tel: e.detail.value.service_tel,
                form_id: e.detail.formId,
                wechat_name: e.detail.value.wechat_name
            },
            success: function(e) {
                getApp().core.hideLoading(), 0 == e.code && getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1,
                    success: function(e) {
                        e.confirm && getApp().core.redirectTo({
                            url: "/mch/apply/apply"
                        });
                    }
                }), 1 == e.code && t.showToast({
                    title: e.msg
                });
            }
        })) : getApp().core.showModal({
            title: "提示",
            content: "请先阅读并同意《入驻协议》。",
            showCancel: !1
        });
    },
    hideApplyResult: function() {
        this.setData({
            show_result: !1,
            is_form_show: !0
        });
    },
    showApplyResult: function() {
        this.setData({
            show_result: !0
        });
    },
    showEntryRules: function() {
        this.setData({
            show_entry_rules: !0
        });
    },
    disagreeEntryRules: function() {
        this.setData({
            agree_entry_rules: !1,
            show_entry_rules: !1
        });
    },
    agreeEntryRules: function() {
        this.setData({
            agree_entry_rules: !0,
            show_entry_rules: !1
        });
    }
});