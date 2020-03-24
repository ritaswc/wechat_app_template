module.exports = {
    currentPage: null,
    init: function(e) {
        var r = this;
        void 0 === (r.currentPage = e).viewImage && (e.viewImage = function(e) {
            r.viewImage(e);
        }), void 0 === e.copyinfo && (e.copyinfo = function(e) {
            r.copyinfo(e);
        }), void 0 === e.bindExpressPickerChange && (e.bindExpressPickerChange = function(e) {
            r.bindExpressPickerChange(e);
        }), void 0 === e.sendFormSubmit && (e.sendFormSubmit = function(e) {
            r.sendFormSubmit(e);
        });
    },
    viewImage: function(e) {
        var r = this.currentPage, t = e.currentTarget.dataset.index;
        getApp().core.previewImage({
            current: r.data.order_refund.refund_pic_list[t],
            urls: r.data.order_refund.refund_pic_list
        });
    },
    copyinfo: function(e) {
        this.currentPage;
        getApp().core.setClipboardData({
            data: e.target.dataset.info,
            success: function(e) {
                getApp().core.showToast({
                    title: "复制成功！",
                    icon: "success",
                    duration: 2e3,
                    mask: !0
                });
            }
        });
    },
    bindExpressPickerChange: function(e) {
        this.currentPage.setData({
            express_index: e.detail.value
        });
    },
    sendFormSubmit: function(e) {
        var r = this.currentPage, t = e.detail.formId, i = r.data.order_refund, d = r.data.express_index, n = e.detail.value.express_no, o = r.data.pageType;
        getApp().core.showLoading({
            title: "正在提交",
            mask: !0
        });
        var a = "";
        if ("STORE" === o) a = "/pages/order-refund-detail/order-refund-detail?id=" + i.order_refund_id; else if ("MIAOSHA" === o) a = "/pages/miaosha/order-refund-detail/order-refund-detail?id=" + i.order_refund_id; else {
            if ("PINTUAN" !== o) return void getApp().core.showModal({
                title: "提示",
                content: "pageType变量未定义或变量值不是预期的"
            });
            a = "/pages/pt/order-refund-detail/order-refund-detail?id=" + i.order_refund_id;
        }
        getApp().request({
            url: getApp().api.order.refund_send,
            method: "POST",
            data: {
                order_refund_id: i.order_refund_id,
                express: null !== d ? i.express_list[d].name : "",
                express_no: n,
                form_id: t,
                orderType: o
            },
            success: function(r) {
                getApp().core.showModal({
                    title: "提示",
                    content: r.msg,
                    showCancel: !1,
                    success: function(e) {
                        0 == r.code && getApp().core.redirectTo({
                            url: a
                        });
                    }
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    }
};