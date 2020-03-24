if (typeof wx === 'undefined') var wx = getApp().core;
module.exports = {
    currentPage: null,
    /**
     * 注意！注意！！注意！！！
     * 由于组件的通用，部分变量名称需统一，在各自引用的xxx.js文件需定义，并给对应变量赋相应的值
     * 以下变量必须定义并赋值
     * 
     * hide 视频组件隐藏
     * goods.service_list 商品服务列表
     * sales_volume 销量
     * min_price    商品价格
     * 持续补充...
     */
    init: function (self) {
        var _this = this;
        _this.currentPage = self;

        if (typeof self.showShareModal === 'undefined') {
            self.showShareModal = function (e) {
                _this.showShareModal(e);
            }
        }
        if (typeof self.shareModalClose === 'undefined') {
            self.shareModalClose = function (e) {
                _this.shareModalClose(e);
            }
        }
        if (typeof self.getGoodsQrcode === 'undefined') {
            self.getGoodsQrcode = function (e) {
                _this.getGoodsQrcode(e);
            }
        }
        if (typeof self.goodsQrcodeClose === 'undefined') {
            self.goodsQrcodeClose = function (e) {
                _this.goodsQrcodeClose(e);
            }
        }
        if (typeof self.saveGoodsQrcode === 'undefined') {
            self.saveGoodsQrcode = function (e) {
                _this.saveGoodsQrcode(e);
            }
        }
        if (typeof self.goodsQrcodeClick === 'undefined') {
            self.goodsQrcodeClick = function (e) {
                _this.goodsQrcodeClick(e);
            }
        }
        
    },

    showShareModal: function () {
        var self = this.currentPage;
        self.setData({
            share_modal_active: "active",
            no_scroll: true,
        });
    },
    shareModalClose: function () {
        var self = this.currentPage;
        self.setData({
            share_modal_active: "",
            no_scroll: false,
        });
    },
    getGoodsQrcode: function () {
        var self = this.currentPage;
        self.setData({
            goods_qrcode_active: "active",
            share_modal_active: "",
        });
        if (self.data.goods_qrcode) {
            return true;
        }
        var httpUrl = '';
        var pageType = self.data.pageType;
        if (pageType === 'PINTUAN') {
            httpUrl = getApp().api.group.goods_qrcode;
        } else if (pageType === 'BOOK') {
            httpUrl = getApp().api.book.goods_qrcode;
        } else if (pageType === 'STORE') {
            httpUrl = getApp().api.default.goods_qrcode;
        } else if (pageType === 'MIAOSHA') {
            httpUrl = getApp().api.miaosha.goods_qrcode;
        } else {
            getApp().core.showModal({
                title: '提示',
                content: 'pageType未定义或组件js未进行判断',
            });
            return;
        }
        getApp().request({
            url: httpUrl,
            data: {
                goods_id: self.data.id,
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        goods_qrcode: res.data.pic_url,
                    });
                }
                if (res.code == 1) {
                    self.goodsQrcodeClose();
                    getApp().core.showModal({
                        title: "提示",
                        content: res.msg,
                        showCancel: false,
                        success: function (res) {
                            if (res.confirm) {

                            }
                        }
                    });
                }
            },
        });
    },
    goodsQrcodeClose: function () {
        var self = this.currentPage;
        self.setData({
            goods_qrcode_active: "",
            no_scroll: false,
        });
    },

    saveGoodsQrcode: function () {
        var self = this.currentPage;
        if (!getApp().core.saveImageToPhotosAlbum) {
            // 如果希望用户在最新版本的客户端上体验您的小程序，可以这样子提示
            getApp().core.showModal({
                title: '提示',
                content: '当前版本过低，无法使用该功能，请升级到最新版本后重试。',
                showCancel: false,
            });
            return;
        }

        getApp().core.showLoading({
            title: "正在保存图片",
            mask: false,
        });

        getApp().core.downloadFile({
            url: self.data.goods_qrcode,
            success: function (e) {
                getApp().core.showLoading({
                    title: "正在保存图片",
                    mask: false,
                });
                getApp().core.saveImageToPhotosAlbum({
                    filePath: e.tempFilePath,
                    success: function () {
                        getApp().core.showModal({
                            title: '提示',
                            content: '商品海报保存成功',
                            showCancel: false,
                        });
                    },
                    fail: function (e) {
                        getApp().core.showModal({
                            title: '图片保存失败',
                            content: e.errMsg,
                            showCancel: false,
                        });
                    },
                    complete: function (e) {
                        getApp().core.hideLoading();
                    }
                });
            },
            fail: function (e) {
                getApp().core.showModal({
                    title: '图片下载失败',
                    content: e.errMsg + ";" + self.data.goods_qrcode,
                    showCancel: false,
                });
            },
            complete: function (e) {
                getApp().core.hideLoading();
            }
        });

    },

    goodsQrcodeClick: function (e) {
        var src = e.currentTarget.dataset.src;
        getApp().core.previewImage({
            urls: [src],
        });
    },
}