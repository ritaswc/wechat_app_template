if (typeof wx === 'undefined') var wx = getApp().core;
var WxParse = require('./../../wxParse/wxParse.js');
Page({

    /**
     * 页面的初始数据
     */
    data: {

    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;

        if (typeof my === 'undefined') {
            var scene = decodeURIComponent(options.scene);
            if (typeof scene !== 'undefined') {
                var scene_obj = getApp().helper.scene_decode(scene);
                if (scene_obj.uid && scene_obj.gid) {
                    options.id = scene_obj.gid;
                }
            }
        } else {
            if (getApp().query !== null) {
                var query = app.query;
                getApp().query = null;
                options.id = query.gid;
            }
        }

        getApp().request({
            url: getApp().api.default.topic,
            data: {
                id: options.id,
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData(res.data);
                    WxParse.wxParse("content", "html", res.data.content, self);
                } else {
                    getApp().core.showModal({
                        title: "提示",
                        content: res.msg,
                        showCancel: false,
                        success: function (e) {
                            if (e.confirm) {
                                getApp().core.redirectTo({
                                    url: "/pages/index/index"
                                });
                            }
                        }
                    });
                }
            }
        });

    },

    wxParseTagATap: function (e) {
        if (e.currentTarget.dataset.goods) {
            var src = e.currentTarget.dataset.src || false;
            if (!src) return;
            getApp().core.navigateTo({
                url: src,
            });
        }
    },

    quickNavigation: function(){
        this.setData({
            quick_icon:!this.data.quick_icon
        })  
        let animationPlus = getApp().core.createAnimation({
            duration: 300,
            timingFunction: 'ease-out',
        });

        var x = -55;
        if (this.data.quick_icon) {
            animationPlus.opacity(0).step();
        } else {
            animationPlus.translateY(x).opacity(1).step();
        }
        this.setData({
           animationPlus: animationPlus.export(),
        });
    },

    favoriteClick: function (e) {
        var self = this;
        var action = e.currentTarget.dataset.action;

        getApp().request({
            url: getApp().api.user.topic_favorite,
            data: {
                topic_id: self.data.id,
                action: action,
            },
            success: function (res) {
                getApp().core.showToast({
                    title: res.msg,
                });
                if (res.code == 0) {
                    self.setData({
                        is_favorite: action,
                    });
                }
            }
        });
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        getApp().page.onShow(this);
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {
        getApp().page.onShareAppMessage(this);

        var user_info = getApp().getUser();
        var id = this.data.id;
        var res = {
            title: this.data.title,
            path: "/pages/topic/topic?id=" + id + "&user_id=" + user_info.id,
        };
        return res;
    },


    showShareModal:function(){
        this.setData({
            share_modal_active: "active",
        });
    },

    shareModalClose:function(){
        this.setData({
            share_modal_active:'',
        })
    },

    /**
    *  海报
    */
    getGoodsQrcode: function() {
        var self = this;
        self.setData({
            qrcode_active: "active",
            share_modal_active: "",
        });
        if (self.data.goods_qrcode) return true
        getApp().request({
            url: getApp().api.default.topic_qrcode,
            data: {
                goods_id: self.data.id,
            },
            success: function(res) {
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
                        success: function(res) {
                            if (res.confirm) {
                            }
                        }
                    });
                }
            },
        });
    },  


    qrcodeClick: function(e) {
        var src = e.currentTarget.dataset.src;
        getApp().core.previewImage({
            urls: [src],
        });
    },
    qrcodeClose: function() {
        var self = this; 
        self.setData({
            qrcode_active: "",
        });
    },

    goodsQrcodeClose: function() {
        var self = this;
        self.setData({
            goods_qrcode_active: "",
            no_scroll: false,
        });
    },

    saveQrcode: function() {
        var self = this;
        if (!getApp().core.saveImageToPhotosAlbum) {
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
            success: function(e) {
                getApp().core.showLoading({
                    title: "正在保存图片",
                    mask: false,
                });
                getApp().core.saveImageToPhotosAlbum({
                    filePath: e.tempFilePath,
                    success: function() {
                        getApp().core.showModal({
                            title: '提示',
                            content: '专题海报保存成功',
                            showCancel: false,
                        });
                    },
                    fail: function(e) {
                        getApp().core.showModal({
                            title: '图片保存失败',
                            content: e.errMsg,
                            showCancel: false,
                        });
                    },
                    complete: function(e) {
                        getApp().core.hideLoading();
                    }
                });
            },
            fail: function(e) {
                getApp().core.showModal({
                    title: '图片下载失败',
                    content: e.errMsg + ";" + self.data.goods_qrcode,
                    showCancel: false,
                });
            },
            complete: function(e) {
                getApp().core.hideLoading();
            }
        });
    },



});