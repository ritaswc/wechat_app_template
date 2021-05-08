if (typeof wx === 'undefined') var wx = getApp().core;
module.exports = {
    currentPage: null,
    /**
     * 注意！注意！！注意！！！
     * 由于组件的通用，部分变量名称需统一，在各自引用的xxx.js文件需定义，并给对应变量赋相应的值
     * 以下变量必须定义并赋值
     * 
     * pageType 页面标识，从哪个页面引用
     * order_detail_id  订单ID
     * 持续补充...
     */
    init: function (self) {
        var _this = this;
        _this.currentPage = self;

        if (typeof self.switchTab === 'undefined') {
            self.switchTab = function (e) {
                _this.switchTab(e);
            }
        }
        if (typeof self.descInput === 'undefined') {
            self.descInput = function (e) {
                _this.descInput(e);
            }
        }
        if (typeof self.chooseImage === 'undefined') {
            self.chooseImage = function (e) {
                _this.chooseImage(e);
            }
        }
        if (typeof self.deleteImage === 'undefined') {
            self.deleteImage = function (e) {
                _this.deleteImage(e);
            }
        }
        if (typeof self.refundSubmit === 'undefined') {
            self.refundSubmit = function (e) {
                _this.refundSubmit(e);
            }
        }
    },
    switchTab: function (e) {
        var self = this.currentPage;
        var id = e.currentTarget.dataset.id;
        if (id == 1) {
            self.setData({
                switch_tab_1: "active",
                switch_tab_2: "",
            });
        } else {
            self.setData({
                switch_tab_1: "",
                switch_tab_2: "active",
            });
        }
    },
    descInput: function (e) {
        var self = this.currentPage;
        var type = e.currentTarget.dataset.type;
        var value = e.detail.value;
        if (type == 1) {
            var refund_data_1 = self.data.refund_data_1;
            refund_data_1.desc = value;
            self.setData({
                refund_data_1: refund_data_1,
            });
        }
        if (type == 2) {
            var refund_data_2 = self.data.refund_data_2;
            refund_data_2.desc = value;
            self.setData({
                refund_data_2: refund_data_2,
            });
        }
    },

    chooseImage: function (e) {
        var self = this.currentPage;
        var type = e.currentTarget.dataset.type;
        var max_pic_num = 6;
        if (type == 1) {
            var refund_data_1 = self.data.refund_data_1;
            var pic_num = 0;
            if (refund_data_1.pic_list)
                pic_num = refund_data_1.pic_list.length || 0;
            var _count = max_pic_num - pic_num;
            getApp().core.chooseImage({
                count: _count,
                success: function (res) {
                    if (!refund_data_1.pic_list)
                        refund_data_1.pic_list = [];
                    refund_data_1.pic_list = refund_data_1.pic_list.concat(res.tempFilePaths);
                    self.setData({
                        refund_data_1: refund_data_1
                    });
                }
            });
        }
        if (type == 2) {
            var refund_data_2 = self.data.refund_data_2;
            var pic_num = 0;
            if (refund_data_2.pic_list)
                pic_num = refund_data_2.pic_list.length || 0;
            var _count = max_pic_num - pic_num;
            getApp().core.chooseImage({
                count: _count,
                success: function (res) {
                    if (!refund_data_2.pic_list)
                        refund_data_2.pic_list = [];
                    refund_data_2.pic_list = refund_data_2.pic_list.concat(res.tempFilePaths);
                    self.setData({
                        refund_data_2: refund_data_2
                    });
                }
            });
        }
    },
    deleteImage: function (e) {
        var self = this.currentPage;
        var type = e.currentTarget.dataset.type;
        var index = e.currentTarget.dataset.index;
        if (type == 1) {
            var refund_data_1 = self.data.refund_data_1;
            refund_data_1.pic_list.splice(index, 1);
            self.setData({
                refund_data_1: refund_data_1
            });
        }
        if (type == 2) {
            var refund_data_2 = self.data.refund_data_2;
            refund_data_2.pic_list.splice(index, 1);
            self.setData({
                refund_data_2: refund_data_2
            });
        }
    },
    refundSubmit: function (e) {
        var self = this.currentPage;
        var type = e.currentTarget.dataset.type;
        var form_id = e.detail.formId;

        var pic_url_list = [];
        var pic_complete_count = 0;
        var pageType = self.data.pageType;
        var httpUrl = getApp().api.order.refund;
        var navigateToUrl = '';
        var orderType = '';

        if (pageType === 'STORE') {
            navigateToUrl = '/pages/order/order?status=4';
            orderType = 'STORE';

        } else if (pageType === 'PINTUAN') {
            navigateToUrl = '/pages/pt/order/order?status=4';
            orderType = 'PINTUAN';

        } else if (pageType === 'MIAOSHA') {
            navigateToUrl = '/pages/miaosha/order/order?status=4';
            orderType = 'MIAOSHA';

        } else {
            getApp().core.showModal({
                title: '提示',
                content: 'pageType变量未定义或变量值不是预期的',
            });
            return;

        }

        /*--退货退款开始--*/
        if (type == 1) {//退货退款
            var desc = self.data.refund_data_1.desc || "";
            if (desc.length == 0) {
                getApp().core.showToast({
                    title: "请填写退款原因",
                    image: "/images/icon-warning.png",
                });
                return;
            }

            //如果有图片先上传图片
            if (self.data.refund_data_1.pic_list && self.data.refund_data_1.pic_list.length > 0) {
                getApp().core.showLoading({
                    title: "正在上传图片",
                    mask: true,
                });
                for (var i in self.data.refund_data_1.pic_list) {
                    (function (i) {
                        getApp().core.uploadFile({
                            url: getApp().api.default.upload_image,
                            filePath: self.data.refund_data_1.pic_list[i],
                            name: "image",
                            success: function (res) {
                            },
                            complete: function (res) {
                                pic_complete_count++;
                                if (res.statusCode == 200) {
                                    res = JSON.parse(res.data);
                                    if (res.code == 0) {
                                        pic_url_list[i] = res.data.url;
                                    }
                                }
                                if (pic_complete_count == self.data.refund_data_1.pic_list.length) {
                                    getApp().core.hideLoading();
                                    _submit();
                                }
                            }
                        });
                    })(i);
                }
            } else {
                _submit();
            }

            function _submit() {
                getApp().core.showLoading({
                    title: "正在提交",
                    mask: true,
                });
                getApp().request({
                    url: httpUrl,
                    method: "post",
                    data: {
                        type: 1,
                        order_detail_id: self.data.goods.order_detail_id,
                        desc: desc,
                        pic_list: JSON.stringify(pic_url_list),
                        form_id: form_id,
                        orderType: orderType
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
                                        getApp().core.redirectTo({
                                            url: navigateToUrl
                                        });
                                    }
                                }
                            });
                        }
                        if (res.code == 1) {
                            getApp().core.showModal({
                                title: "提示",
                                content: res.msg,
                                showCancel: false,
                                success: function (res) {
                                    if (res.confirm) {
                                        getApp().core.navigateBack({
                                            delta: 2,
                                        });
                                    }
                                }
                            });
                        }
                    }
                });
            }
        }
        /*--退货退款结束--*/


        /*--换货开始--*/
        if (type == 2) {//换货
            var desc = self.data.refund_data_2.desc || "";
            if (desc.length == 0) {
                getApp().core.showToast({
                    title: "请填写换货说明",
                    image: "/images/icon-warning.png",
                });
                return;
            }
            var pic_url_list = [];
            var pic_complete_count = 0;

            //如果有图片先上传图片
            if (self.data.refund_data_2.pic_list && self.data.refund_data_2.pic_list.length > 0) {
                getApp().core.showLoading({
                    title: "正在上传图片",
                    mask: true,
                });
                for (var i in self.data.refund_data_2.pic_list) {
                    (function (i) {
                        getApp().core.uploadFile({
                            url: getApp().api.default.upload_image,
                            filePath: self.data.refund_data_2.pic_list[i],
                            name: "image",
                            success: function (res) {
                            },
                            complete: function (res) {
                                pic_complete_count++;
                                if (res.statusCode == 200) {
                                    res = JSON.parse(res.data);
                                    if (res.code == 0) {
                                        pic_url_list[i] = res.data.url;
                                    }
                                }
                                if (pic_complete_count == self.data.refund_data_2.pic_list.length) {
                                    getApp().core.hideLoading();
                                    _submit();
                                }
                            }
                        });
                    })(i);
                }
            } else {
                _submit();
            }

            function _submit() {
                getApp().core.showLoading({
                    title: "正在提交",
                    mask: true,
                });
                
                getApp().request({
                    url: httpUrl,
                    method: "post",
                    data: {
                        type: 2,
                        orderType: orderType,
                        order_detail_id: self.data.goods.order_detail_id,
                        desc: desc,
                        pic_list: JSON.stringify(pic_url_list),
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
                                        getApp().core.redirectTo({
                                            url: navigateToUrl
                                        });
                                    }
                                }
                            });
                        }
                        if (res.code == 1) {
                            getApp().core.showModal({
                                title: "提示",
                                content: res.msg,
                                showCancel: false,
                                success: function (res) {
                                    if (res.confirm) {
                                        getApp().core.navigateBack({
                                            delta: 2,
                                        });
                                    }
                                }
                            });
                        }
                    }
                });
            }
        }
        /*--换货结束--*/
    }
}