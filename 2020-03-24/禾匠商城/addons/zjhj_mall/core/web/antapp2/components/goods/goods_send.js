if (typeof wx === 'undefined') var wx = getApp().core;
module.exports = {
    currentPage: null,
    /**
     * 注意！注意！！注意！！！
     * 由于组件的通用，部分变量名称需统一，在各自引用的xxx.js文件需定义，并给对应变量赋相应的值
     * 以下变量必须定义并赋值
     * 
     * pageType 页面标识
     * 持续补充...
     */
    init: function(self) {
        var _this = this;
        _this.currentPage = self;

        if (typeof self.viewImage === 'undefined') {
            self.viewImage = function(e) {
                _this.viewImage(e);
            }
        }
        if (typeof self.copyinfo === 'undefined') {
            self.copyinfo = function(e) {
                _this.copyinfo(e);
            }
        }
        if (typeof self.bindExpressPickerChange === 'undefined') {
            self.bindExpressPickerChange = function(e) {
                _this.bindExpressPickerChange(e);
            }
        }
        if (typeof self.sendFormSubmit === 'undefined') {
            self.sendFormSubmit = function(e) {
                _this.sendFormSubmit(e);
            }
        }
    },

    viewImage: function(e) {
        var self = this.currentPage;
        var index = e.currentTarget.dataset.index;
        getApp().core.previewImage({
            current: self.data.order_refund.refund_pic_list[index],
            urls: self.data.order_refund.refund_pic_list,
        });
    },
    copyinfo: function(e) {
        var that = this.currentPage;
        getApp().core.setClipboardData({
            data: e.target.dataset.info,
            success: function(res) {
                getApp().core.showToast({
                    title: '复制成功！',
                    icon: 'success',
                    duration: 2000,
                    mask: true
                })
            }
        });
    },
    bindExpressPickerChange: function(e) {
        var self = this.currentPage;
        self.setData({
            express_index: e.detail.value,
        });
    },

    sendFormSubmit: function(e) {
        var self = this.currentPage;
        var formId = e.detail.formId;
        var orderRefund = self.data.order_refund;
        var expressIndex = self.data.express_index;
        var expressNo = e.detail.value.express_no;
        var pageType = self.data.pageType;

        getApp().core.showLoading({
            title: '正在提交',
            mask: true,
        });

        var redirectToUrl = '';
        if (pageType === 'STORE') {
            redirectToUrl = '/pages/order-refund-detail/order-refund-detail?id=' + orderRefund.order_refund_id;

        } else if (pageType === 'MIAOSHA') {
            redirectToUrl = '/pages/miaosha/order-refund-detail/order-refund-detail?id=' + orderRefund.order_refund_id;

        } else if (pageType === 'PINTUAN') {
            redirectToUrl = '/pages/pt/order-refund-detail/order-refund-detail?id=' + orderRefund.order_refund_id;

        } else {
            getApp().core.showModal({
                title: '提示',
                content: 'pageType变量未定义或变量值不是预期的',
            });
            return;
            
        }

        getApp().request({
            url: getApp().api.order.refund_send,
            method: 'POST',
            data: {
                order_refund_id: orderRefund.order_refund_id,
                express: expressIndex !== null ? (orderRefund.express_list[expressIndex].name) : '',
                express_no: expressNo,
                form_id: formId,
                orderType: pageType
            },
            success: function(res) {
                getApp().core.showModal({
                    title: '提示',
                    content: res.msg,
                    showCancel: false,
                    success: function(e) {
                        if (res.code == 0) {
                            getApp().core.redirectTo({
                                url: redirectToUrl,
                            });
                        }
                    },
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
}